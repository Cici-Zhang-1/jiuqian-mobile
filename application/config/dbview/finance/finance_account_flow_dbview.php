<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_account_flow_model/_select_finance_account_income'] = array(
    1 => 'type',
    'fi_id' => 'v',
    'fi_finance_account' => 'finance_account',
    'fi_income_pay' => 'income_pay',
    'fi_balance' => 'balance',
    'fi_amount' => 'amount',
    'fi_fee' => 'fee',
    'fi_dealer' => 'ob',
    'fi_bank_date' => 'bank_date',
    'fi_remark' => 'remark',
    'u_truename' => 'creator',
    'fi_flow_num' => 'flow_num'
);
$config['finance/finance_account_flow_model/_select_finance_account_pay'] = array(
    0 => 'type',
    'fa_id' => 'v',
    'fp_finance_account' => 'finance_account',
    'fp_income_pay' => 'income_pay',
    'fp_balance' => 'balance',
    'fp_amount' => 'amount',
    'fp_fee' => 'fee',
    'fp_supplier' => 'ob',
    'fp_bank_date' => 'bank_date',
    'fp_remark' => 'remark',
    'u_truename' => 'creator',
    'fp_flow_num' => 'flow_num'
);
