<?php


namespace App\Traits;


use Illuminate\Support\Facades\Http;

trait ManagesBanks
{
    public function getAvailableBankList()
    {
        $banks = Http::get(config('transactions.paystack_url').'/bank');
        if($banks->json()) {
            return $banks->json()['data'];
        }
        return null;
    }

    public function getBankById($id)
    {
        $banks = $this->getAvailableBankList();
        $filtered = collect($banks)->filter(function( $item ) use ($id) {
            return $item['id'] == $id;
        });

        $array = reset($filtered);
        return $banks[key($array)];
    }

    public function validateBankCard($cardBin)
    {
        $stripped = str_replace(' ', '', $cardBin);
        $bin = substr($stripped, 0, 6);

        $validateCard = Http::withHeaders([
            'Authorization' => 'Bearer '.config('transaction.paystack_secret')
        ])->get(config('transactions.paystack_url').'/decision/bin/'.$bin);

        if ($validateCard->json()) {
            return $validateCard->json()['data'];
        }
        return null;
    }

    public function resolveAccountNumber($account_number, $bank_code)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('transaction.paystack_secret')
        ])->get(config('transactions.paystack_url').'/bank/resolve?account_number='.$account_number.'&bank_code='.$bank_code);

        if ($response->json()) {
            return $response->json();
        }
        return null;
    }

    public function isCardDetailsMatched($card, $bankId)
    {
        $bank = $this->getBankById($bankId);
        $card = $this->validateBankCard($card);
        $matchBank = strtolower($card['bank']) == strtolower($bank['name']);

        if ($card['card_type'] == 'DEBIT' && $card['country_code'] == 'NG' && $matchBank) {
            return true;
        }
        return false;
    }

}
