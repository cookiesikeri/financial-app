<?php


namespace App\Traits;


use App\Mail\DebitEmail;
use App\Mail\TransactionMail;
use App\Models\AccountNumber;
use App\Models\Loan;
use App\Models\LoanKyc;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

trait ManagesLoans
{
    /**
     * create a loan account.
     * @param array $data
     * @return |null | collection
     */
    public function createLoanAccount(array $data)
    {
        //check if account exist
        $found = LoanKyc::on('mysql::read')->where('user_id', $data['user_id'])->exists();
        if ($found) {
            return null;
        }
        return LoanKyc::create($data);
    }

    /**
     * update a loan account
     * @param array $data
     * @param $id
     * @return |null
     */
    public function updateLoanAccount(array $data, $id)
    {
        $loanAccount = LoanKyc::on('mysql::write')->find($id);
        if ($loanAccount) {
            $response = $loanAccount->fill($data)->save();
            if ($response) {
                return LoanKyc::find($id);
            }
        }
        return null;
    }

    /**
     * get details of single loan account
     * @param $id
     * @return |null
     */
    public function getLoanAccountDetails($id)
    {
        $account = LoanKyc::select('loan_accounts.*', 'users.email', 'users.bvn', 'users.phone', 'users.sex')
            ->join('users', 'loan_accounts.user_id', '=', 'users.id')
            ->orderBy('loan_accounts.created_at', 'desc')->where('loan_accounts.id', $id)->first();
        if($account) {
            $account['educational_qualification'] = $account->educationalqualification;
            $account['state'] = $account->state;
            $account['lga'] = $account->lga;
            $account['residential_status'] = $account->residentialstatus;
            $account['employment_status'] = $account->employmentstatus;
            $account['monthly_income'] = $account->monthlyincome;
            $account['loans'] = $account->loans;
            return $account;
        }
        return null;
    }

    /**
     * calculate loan amount
     * @param $request_amount
     * @param $loan_account_id
     * @return float
     */
    public function calculateLoanAmount($request_amount, $loan_account_id)
    {
        $amount = floatval($request_amount);
        $salary = floatval($this->getBorrowerIncome($loan_account_id));
        if ($salary < 20000) {
            if ($amount > 0.3 * $salary) {
                return 0.3 * $salary;
            }else
                return $amount;
        }else {
            $isFirstBorrower = $this->hasNotBorrowedBefore($loan_account_id);
            if ($isFirstBorrower) {
                return 20000;
            }else {
                if ($amount > 0.3 * $salary) {
                    return 0.3 * $salary;
                }else
                    return $amount;
            }
        }
    }

    /**
     * calculate loan interest and service charge
     * @param $amount
     * @param $duration
     * @return \Illuminate\Support\Collection
     */
    public function calculateLoanInterest($amount, $duration)
    {
        $result['charge'] = 0.015 * floatval($amount) * floatval($duration);
        $result['interest'] = 0.010 * floatval($amount) * floatval($duration);

        return $result;
    }

    /**
     * check for first laon applicants
     * @param $loanAccountId
     * @return mixed
     */
    public function hasNotBorrowedBefore ($loanAccountId)
    {
        return (bool)Loan::on('mysql::read')->where('loan_account_id', $loanAccountId)->doesntExist();
    }

    public function initialLoanBalance ($amount, $duration)
    {
        $charges = $this->calculateLoanInterest($amount, $duration);
        return floatval($amount) + $charges['interest'] + $charges['charge'];
    }

    /**
     * get all pending loans
     * @param $loan_account_id
     * @return mixed
     */
    public function getPendingBorrowersLoans($loan_account_id)
    {
        return Loan::on('mysql::read')->where('loan_account_id', $loan_account_id)->where('balance', '>', 0)->get();
    }

    /**
     * check if borrower has paid loan
     * @param $loan_id
     * @return bool
     */
    public function hasPaidLoanBalance($loan_id)
    {
        return (bool)Loan::on('mysql::read')->whereId($loan_id)->where('balance', 0.00)->orWhere('balance', '<=', 0)->first();
    }

    /**
     * get borrowers income
     * @param $loan_account_id
     * @return mixed
     */
    public function getBorrowerIncome($loan_account_id) {
        $account = LoanKyc::on('mysql::read')->find($loan_account_id);
        return $account->monthlyincome->max;
    }

    /**
     * get loan account data
     * @param $request_amount
     * @param $loan_account_id
     * @return mixed
     */
    public function getLoanData($request_amount, $loan_account_id)
    {
        $charges = $this->calculateLoanInterest($request_amount, config('vfd.loan_duration'));
        $data['amount'] = $this->calculateLoanAmount($request_amount, $loan_account_id);
        $data['originating_fee'] = $charges['charge'];
        $data['interest'] = $charges['interest'];
        $data['duration'] =  config('vfd.loan_duration');
        $input['balance'] = $this->initialLoanBalance($request_amount, config('vfd.loan_duration'));
        $input['expiry_date'] = Carbon::now()->addDays((int)config('vfd.loan_duration'));

        return $data;
    }

    public function isWalletBalanceGreaterThanAmount($loan_id, $amount)
    {
        $loanAccount = Loan::on('mysql::read')->find($loan_id)->loanaccount;
        $balance = $loanAccount->user->wallet->balance;
        return floatval($balance) > floatval($amount);
    }

    public function walletToWalletTransfer(array $data)
    {
        $receiverAccount = AccountNumber::on('mysql::write')->where('account_number', $data['account_number'])->first();
        $receiverWallet = $receiverAccount->wallet;
        $senderLoanAccount = Loan::on('mysql::write')->find($data['loan_id'])->loanaccount;
        $senderWallet = $senderLoanAccount->user->wallet;

        $sender_new_balance = floatval($senderWallet->balance) - floatval($data['amount']);
        $senderWallet->update(['balance' => $sender_new_balance]);
        $receiver_new_balance = floatval($receiverWallet->balance) + floatval($data['amount']);
        $receiverWallet->update(['balance' => $receiver_new_balance]);

        $senderAccount = AccountNumber::on('mysql::read')->where('wallet_id', $senderWallet->id)->first();

        $senderTransactions = WalletTransaction::on('mysql::write')->create([
            'wallet_id' => $senderWallet->id,
            'amount' => $data['amount'],
            'type' => 'Debit',
            'sender_account_number'=> $senderAccount->account_number,
            'sender_name'=>$senderLoanAccount->user->name,
            'receiver_name'=>$receiverWallet->user->name,
            'receiver_account_number'=>$data['account_number'],
            'description'=>$data['description'],
            'transfer'=>true,
        ]);
        $receiverTransactions = WalletTransaction::on('mysql::write')->create([
            'wallet_id' => $receiverWallet->id,
            'amount' => $data['amount'],
            'type' => 'Credit',
            'sender_account_number'=> $senderAccount->account_number,
            'sender_name'=>$senderLoanAccount->user->name,
            'receiver_name'=>$receiverWallet->user->name,
            'receiver_account_number'=>$data['account_number'],
            'description'=>$data['description'],
            'transfer'=>true,
        ]);
        Mail::to($senderLoanAccount->user->email)->send(new DebitEmail($senderLoanAccount->user->name,$data['amount'], $senderTransactions, $senderWallet));
        Mail::to($receiverWallet->user->email)->send(new TransactionMail($receiverWallet->user->name,$data['amount']));

        $response['sender'] = $senderTransactions;
        $response['receiver'] = $receiverTransactions;
        return $response;
    }

    public function isPinValid($pin, $user)
    {
        return Hash::check($pin, $user->transaction_pin);
    }

}
