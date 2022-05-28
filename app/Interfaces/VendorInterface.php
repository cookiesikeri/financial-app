<?php

namespace App\Interfaces;

interface VendorInterface {
    public function get_sign_up_requests();
    public function get_all_sign_up_requests();
    public function get_approved_accounts();
    public function get_all_approved_accounts();
    public function get_rejected_accounts();
    public function get_all_rejected_accounts();
    public function get_vendors();
    public function get_all_vendors();
    public function is_vendor($id);
    public function create_user($data);
    public function create_vendor($data);
    public function create_vendor_wallet($vendor_id);
    public function get_vendor_wallet_balance($vendor_id);
    public function vendor_has_sufficient_wallet_balance($vendor_id, $amount);
    public function update_vendor_wallet_balance($vendor_id, $amount);
    public function log_vendor_wallet_transaction($wallet_id, $amount, $current_balance, $new_balance, $type, $description, $status, $reference);
    public function get_vendor_wallet_transaction_history($wallet_id, $chunk);
    public function debit_vendor_wallet($vendor_id, $amount);
    public function approve_vendor($vendor_id);
    public function reject_vendor($vendor_id);
    public function suspend_vendor($vendor_id);
    public function delete_vendor($vendor_id);
    public function get_vendor_power_transactions($vendor_id, $paginate, $status);
    public function get_vendor_all_power_transactions($vendor_id, $status);
    public function get_vendor_airtime_transactions($vendor_id, $paginate, $status);
    public function get_vendor_all_airtime_transactions($vendor_id, $status);
    public function get_vendor_data_transactions($vendor_id, $paginate, $status);
    public function get_vendor_all_data_transactions($vendor_id, $status);
    public function get_vendor_tv_transactions($vendordor_id, $paginate, $status);
    public function get_vendor_all_tv_transactions($vendor_id, $status);
    public function get_all_sign_up_requests_count();
    public function get_all_approved_accounts_count();
    public function get_all_rejected_accounts_count();
    public function get_all_vendors_count();
    public function get_vendor_all_power_transactions_count($vendor_id, $status);
    public function get_vendor_all_airtime_transactions_count($vendor_id, $status);
    public function get_vendor_all_data_transactions_count($vendor_id, $status);
    public function get_vendor_all_tv_transactions_count($vendor_id, $status);
    public function update_vendor_status($vendor_id, $status, $userId);
    public function create_vendor_commissions($vendorId);
    public function update_vendor_profile(Array $data, $vendorId);
    public function findVendorById($id);
    public function findVendorByCode($code);
    public function findVendorPowerTransactions($vendor_id, $date, $status);
    public function findVendorAirtimeTransactions($vendor_id, $date, $status);
    public function findVendorDataTransactions($vendor_id, $date, $status);
    public function findVendorTvTransactions($vendor_id, $date, $status);
    public function findPowerTransactionById($id);
    public function findAirtimeTransactionById($id);
    public function findTVTransactionById($id);
    public function generate_transaction_reference();
    public function findVendorCommissionByDate($month, $id);
}
