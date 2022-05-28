<?php

namespace App\Repositories;
use App\Models\Vendor;
use App\Interfaces\VendorInterface;

class VendorRepository implements VendorInterface {

    public function get_sign_up_requests() {
        return \App\Models\Vendor::on('mysql::read')->where('status', 2)->paginate(20);
    }

    public function get_all_sign_up_requests() {
        return \App\Models\Vendor::on('mysql::read')->where('status', 2)->get();
    }

    public function get_approved_accounts() {
        return \App\Models\Vendor::on('mysql::read')->where('status', 1)->paginate(20);
    }

    public function get_all_approved_accounts() {
        return \App\Models\Vendor::on('mysql::read')->where('status', 1)->get();
    }

    public function get_rejected_accounts() {
        return \App\Models\Vendor::on('mysql::read')->where('status', 3)->paginate(20);
    }

    public function get_all_rejected_accounts() {
        return \App\Models\Vendor::on('mysql::read')->where('status', 3)->get();
    }

    public function get_vendors() {
        return \App\Models\User::on('mysql::read')->where('role_id', 2)->paginate(20);
    }

    public function get_all_vendors() {
        return \App\Models\User::on('mysql::read')->where('role_id', 3)->get();
    }

    public function is_vendor($id) {
        $vendor = \App\Models\Vendor::on('mysql::write')->find($id);
        if(!$vendor) {
            $vendor = 404;
        }
        return $vendor;
    }

    public function create_user($data) {
        return \App\Models\User::on('mysql::write')->create($data);
    }

    public function create_vendor($data) {
        return \App\Models\Vendor::on('mysql::write')->create($data);
    }

    public function create_vendor_wallet($vendor_id) {
        $wallet_created = 0;
        $vendor = $this->is_vendor($vendor_id);
        if(!is_int($vendor)) {
            $vendor->user->wallet()->create(['balance' => 0.00]);
            $wallet_created = !$wallet_created;
        }
        return $wallet_created;
    }

    public function get_vendor_wallet_balance($vendor_id) {
        $wallet_balance = 0.00;
        $vendor = $this->is_vendor($vendor_id);
        if(!is_int($vendor)) {
            $wallet_balance = $vendor->user->wallet->balance;
        }
        return $wallet_balance;
    }

    public function vendor_has_sufficient_wallet_balance($vendor_id, $amount) {
        $has_sufficient_balance = false;
        $wallet_balance = $this->get_vendor_wallet_balance($vendor_id);
        if($wallet_balance > 0.00 && $wallet_balance > $amount) {
            $has_sufficient_balance = !$has_sufficient_balance;
        }
        return $has_sufficient_balance;
    }

    public function update_vendor_wallet_balance($vendor_id, $amount) {
        $update = false;
        $vendor = $this->is_vendor($vendor_id);
        if(!is_int($vendor)) {
            $vendor->user->wallet()->update(['balance' => $amount]);
            $update = !$update;
        }
        return $update;
    }

    public function log_vendor_wallet_transaction($wallet_id, $amount, $current_balance, $new_balance, $type, $description, $status, $reference) {
        \App\Models\WalletTransaction::on('mysql::write')->create([
            'wallet_id'                 => $wallet_id,
            'transaction_amount'        => $amount,
            'current_balance'           => $current_balance,
            'new_balance'               => $new_balance,
            'transaction_type'          => $type,
            'transaction_description'   => $description,
            'status'                    => $status,
            'transaction_reference'     => $reference
        ]);
        return 'ok';
    }

    public function get_vendor_wallet_transaction_history($wallet_id, $chunk) {
        $wallet_transactions = null;
        if($chunk == 'all') {
            $wallet_transactions = \App\Models\WalletTransaction::on('mysql::read')->where('wallet_id', $wallet_id)->all();
        } else {
            $wallet_transactions = \App\Models\WalletTransaction::on('mysql::read')->where('wallet_id', $wallet_id)->paginate($chunk);
        }
        return $wallet_transactions;
    }

    public function debit_vendor_wallet($vendor_id, $amount) {
        $debit = false;
        $wallet_balance = $this->get_vendor_wallet_balance($vendor_id);
        $new_wallet_balance = $wallet_balance - $amount;
        if($new_wallet_balance > 0.00) {
            $debit = $new_wallet_balance;
        }
        return $debit;
    }

    public function approve_vendor($vendor_id) {
        $approved = 0;
        $vendor = $this->is_vendor($vendor_id);
        if(!is_int($vendor)) {
            $vendor->update(['status' => 1]);
            $approved = !$approved;
        }
        return $approved;
    }

    public function reject_vendor($vendor_id) {
        $rejected = 0;
        $vendor = $this->is_vendor($vendor_id);
        if(!is_int($vendor)) {
            $vendor->update(['status' => 3]);
            $rejected = !$rejected;
        }
        return $rejected;
    }

    public function suspend_vendor($vendor_id) {
        $suspended = 0;
        $vendor = $this->is_vendor($vendor_id);
        if(!is_int($vendor)) {
            $vendor->update(['status' => 4]);
            $suspended = !$suspended;
        }
        return $suspended;
    }

    public function delete_vendor($vendor_id) {
        $deleted = 0;
        $vendor = $this->is_vendor($vendor_id);
        if(!is_int($vendor)) {
            $vendor->user->wallet()->delete();
            $vendor->user()->delete();
            $vendor->delete();
            $deleted = !$deleted;
        }
        return $deleted;
    }

    public function get_vendor_power_transactions($vendor_id, $paginate = 20, $status = 'all') {
        $power_transactions = null;
        $vendor = $this->is_vendor($vendor_id);
        if($vendor instanceof Vendor) {
            switch($status) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                    $power_transactions = \App\Models\PowerTransaction::on('mysql::read')->where('status', $status)->where('user_id', $vendor->user->id)->paginate($paginate);
                    break;
                case 'all':
                    $power_transactions = \App\Models\PowerTransaction::on('mysql::read')->where('user_id', $vendor->user->id)->where('status', '<>', 0)->paginate($paginate);
                    break;
            }
        }
        return $power_transactions;
    }

    public function get_vendor_all_power_transactions($vendor_id, $status) {
        $power_transactions = null;
        $vendor = $this->is_vendor($vendor_id);
        if($vendor instanceof Vendor) {
            switch($status) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                    $power_transactions = \App\Models\PowerTransaction::on('mysql::read')->where('status', $status)->where('user_id', $vendor->user->id)->get();
                    break;
                case 'all':
                    $power_transactions = \App\Models\PowerTransaction::on('mysql::read')->where('user_id', $vendor->user->id)->where('status', '<>', 0)->get();
                    break;
            }
        }
        return $power_transactions;
    }

    public function get_vendor_airtime_transactions($vendor_id, $paginate = 20, $status = 'all') {
        $airtime_transactions = null;
        $vendor = $this->is_vendor($vendor_id);
        if($vendor instanceof Vendor) {
            switch($status) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                    $airtime_transactions = \App\Models\AirtimeTransaction::on('mysql::read')->where('status', $status)->where('user_id', $vendor->user->id)->paginate($paginate);
                    break;
                case 'all':
                    $airtime_transactions = \App\Models\AirtimeTransaction::on('mysql::read')->where('user_id', $vendor->user->id)->where('status', '<>', 0)->paginate($paginate);
                    break;
            }
        }
        return $airtime_transactions;
    }

    public function get_vendor_all_airtime_transactions($vendor_id, $status) {
        $airtime_transactions = null;
        $vendor = $this->is_vendor($vendor_id);
        if($vendor instanceof Vendor) {
            \Log::info('vendor profile found!');
            \Log::info($vendor);
            switch($status) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                    $airtime_transactions = \App\Models\AirtimeTransaction::on('mysql::read')->where('status', $status)->where('user_id', $vendor->user->id)->get();
                    break;
                case 'all':
                    $airtime_transactions = \App\Models\AirtimeTransaction::on('mysql::read')->where('user_id', $vendor->user->id)->where('status', '<>', 0)->get();
                    break;
            }
        } else {
            \Log::info('unable to locate vendor profile from repository');
        }
        return $airtime_transactions;
    }

    public function get_vendor_data_transactions($vendor_id, $paginate = 20, $status = 'all') {
        $data_transactions = null;
        $vendor = $this->is_vendor($vendor_id);
        if($vendor instanceof Vendor) {
            switch($status) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                    $data_transactions = \App\Models\DataTransaction::on('mysql::read')->where('status', $status)->where('user_id', $vendor->user->id)->paginate($paginate);
                    break;
                case 'all':
                    $data_transactions = \App\Models\DataTransaction::on('mysql::read')->where('user_id', $vendor->user->id)->where('status', '<>', 0)->paginate($paginate);
                    break;
            }
        }
        return $data_transactions;
    }

    public function get_vendor_all_data_transactions($vendor_id, $status) {
        $data_transactions = null;
        $vendor = $this->is_vendor($vendor_id);
        if($vendor instanceof Vendor) {
            switch($status) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                    $data_transactions = \App\Models\DataTransaction::on('mysql::read')->where('status', $status)->where('user_id', $vendor->user->id)->get();
                    break;
                case 'all':
                    $data_transactions = \App\Models\DataTransaction::on('mysql::read')->where('user_id', $vendor->user->id)->where('status', '<>', 0)->get();
                    break;
            }
        }
        return $data_transactions;
    }

    public function get_vendor_tv_transactions($vendor_id, $paginate = 20, $status = 'all') {
        $tv_transactions = null;
        $vendor = $this->is_vendor($vendor_id);
        if($vendor instanceof Vendor) {
            switch($status) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                    $tv_transactions = \App\Models\TVTransaction::on('mysql::read')->where('status', $status)->where('user_id', $vendor->user->id)->paginate($paginate);
                    break;
                case 'all':
                    $tv_transactions = \App\Models\TVTransaction::on('mysql::read')->where('user_id', $vendor->user->id)->where('status', '<>', 0)->paginate($paginate);
                    break;
            }
        }
        return $tv_transactions;
    }

    public function get_vendor_all_tv_transactions($vendor_id, $status) {
        $tv_transactions = null;
        $vendor = $this->is_vendor($vendor_id);
        if($vendor instanceof Vendor) {
            switch($status) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                    $tv_transactions = \App\Models\TVTransaction::on('mysql::read')->where('status', $status)->where('user_id', $vendor->user->id)->get();
                    break;
                case 'all':
                    $tv_transactions = \App\Models\TVTransaction::on('mysql::read')->where('user_id', $vendor->user->id)->where('status', '<>', 0)->get();
                    break;
            }
        }
        return $tv_transactions;
    }

    public function get_all_sign_up_requests_count() {
        return count($this->get_all_sign_up_requests());
    }

    public function get_all_approved_accounts_count() {
        return count($this->get_all_approved_accounts());
    }

    public function get_all_rejected_accounts_count() {
        return count($this->get_all_rejected_accounts());
    }

    public function get_all_vendors_count() {
        return count($this->get_all_vendors());
    }

    public function get_vendor_all_power_transactions_count($vendor_id, $status) {
        return count($this->get_vendor_all_power_transactions($vendor_id, $status));
    }

    public function get_vendor_all_airtime_transactions_count($vendor_id, $status) {
        return count($this->get_vendor_all_airtime_transactions($vendor_id, $status));
    }

    public function get_vendor_all_data_transactions_count($vendor_id, $status) {
        return count($this->get_vendor_all_data_transactions($vendor_id, $status));
    }

    public function get_vendor_all_tv_transactions_count($vendor_id, $status) {
        return count($this->get_vendor_all_tv_transactions($vendor_id, $status));
    }

    public function update_vendor_status($vendor_id, $status, $userId) {
        $vendor = $this->findVendorById($vendor_id);
        $vendor->update(['status' => $status, 'approved_by' => $userId]);
        return 1;
    }

    public function create_vendor_commissions($vendorId) {
        $vendor = $this->findVendorById($vendorId);
        $vendor->commissions()->create();
        return 1;
    }

    public function update_vendor_profile(Array $data, $vendorId) {
        $vendor = $this->findVendorById($vendorId);
        $vendor->update($data);
        return 1;
    }

    public function findVendorById($id) {
        return \App\Models\Vendor::on('mysql::write')->find($id);
    }

    public function findVendorByCode($code) {
        return \App\Models\Vendor::on('mysql::read')->where('code', $code)->first();
    }

    public function findVendorPowerTransactions($vendor_id, $date, $status) {
        $vendor = $this->is_vendor($vendor_id);
        if($status == '5') {
            return \App\Models\PowerTransaction::on('mysql::read')->where('user_id', $vendor->user_id)->where('date_created', 'LIKE', '%' . $date . '%')->paginate(20);
        } else {
            return \App\Models\PowerTransaction::on('mysql::read')->where('user_id', $vendor->user_id)->where('date_created', 'LIKE', '%' .  $date . '%')->where('status', $status)->paginate(20);
        }
    }

    public function findVendorAirtimeTransactions($vendor_id, $date, $status) {
        $vendor = $this->is_vendor($vendor_id);
        if($status == '5') {
            return \App\Models\AirtimeTransaction::on('mysql::read')->where('user_id', $vendor->user_id)->where('date_created', 'LIKE', '%' .  $date . '%')->paginate(20);
        } else {
            return \App\Models\AirtimeTransaction::on('mysql::read')->where('user_id', $vendor->user_id)->where('date_created', 'LIKE', '%' .  $date . '%')->where('status', $status)->paginate(20);
        }
    }

    public function findVendorDataTransactions($vendor_id, $date, $status) {
        $vendor = $this->is_vendor($vendor_id);
        if($status == '5') {
            return \App\Models\DataTransaction::on('mysql::read')->where('user_id', $vendor->user_id)->where('date_created', 'LIKE', '%' .  $date . '%')->paginate(20);
        } else {
            return \App\Models\DataTransaction::on('mysql::read')->where('user_id', $vendor->user_id)->where('date_created', 'LIKE', '%' .  $date . '%')->where('status', $status)->paginate(20);
        }
    }

    public function findVendorTvTransactions($vendor_id, $date, $status) {
        $vendor = $this->is_vendor($vendor_id);
        if($status == '5') {
            return \App\Models\TVTransaction::on('mysql::read')->where('user_id', $vendor->user_id)->where('date_created', 'LIKE', '%' .  $date . '%')->paginate(20);
        } else {
            return \App\Models\TVTransaction::on('mysql::read')->where('user_id', $vendor->user_id)->where('date_created', 'LIKE', '%' .  $date . '%')->where('status', $status)->paginate(20);
        }
    }

    public function findPowerTransactionById($id) {
        return \App\Models\PowerTransaction::on('mysql::read')->find($id);
    }

    public function findAirtimeTransactionById($id) {
        return \App\Models\AirtimeTransaction::on('mysql::read')->find($id);
    }

    public function findTVTransactionById($id) {
        return \App\Models\TVTransaction::on('mysql::read')->find($id);
    }

    public function generate_transaction_reference()
    {
        $random_string_length = 10;
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $random_string_length; $i++)
        {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }

    public function findVendorCommissionByDate($month, $id) {
        return \App\Models\VendorPayout::on('mysql::read')->where('vendor_id', $id)->where('date', $month)->first();
    }

    public function createVendorCommission($data) {
        return \App\Models\VendorPayout::on('mysql::write')->create($data);
    }
}
