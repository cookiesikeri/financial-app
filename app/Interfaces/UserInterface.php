<?php

namespace App\Interfaces;

interface UserInterface {
    public function is_user($user_id);
    public function create_user_wallet($user_id);
    public function get_user_wallet_balance($user_id);
    public function get_user_loan_balance($user_id);
    public function user_has_sufficient_wallet_balance($user_id, $amount);
    public function update_user_wallet_balance($user_id, $amount);
    public function debit_user_wallet($user_id, $amount);
    public function get_user_power_transactions($user_id, $paginate, $status);
    public function get_user_all_power_transactions($user_id, $status);
    public function get_user_airtime_transactions($user_id, $paginate, $status);
    public function get_user_all_airtime_transactions($user_id, $status);
    public function get_user_data_transactions($user_id, $paginate, $status);
    public function get_user_all_data_transactions($user_id, $st);
    public function get_user_tv_transactions($user_id, $paginate, $status);
    public function get_user_all_tv_transactions($user_id, $status);
    public function log_wallet_transaction($user, $amount_entered, $new_balance, $transaction_type, $description, $transaction_status, $transaction_reference);
    public function generate_transaction_reference();
    public function verify_wallet_funding_payment($reference, $mode);
}
