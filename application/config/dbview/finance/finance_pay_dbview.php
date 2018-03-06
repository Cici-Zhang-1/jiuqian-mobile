<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_pay_model/select'] = array(
	'fp_id' => 'fpid',
	'a.fa_id' => 'faid',
	'a.fa_name' => 'name',
	'b.fa_id' => 'in_faid',
	'b.fa_name' => 'in_name',
	'fp_type' => 'type',
	'fp_amount' => 'amount',
	'fp_fee' => 'fee',
	'fp_bank_date' => 'bank_date',
	'fp_supplier' => 'supplier',
	'fp_supplier_id' => 'supplier_id',
	'u_truename' => 'creator',
	'fp_create_datetime' => 'create_datetime',
	'fp_remark' => 'remark',
);
$config['finance/finance_pay_model/is_valid_finance_pay'] = array(
	'fp_id' => 'fpid',
	'fa_id' => 'faid',
	'fa_name' => 'name',
	'fp_in_finance_account_id' => 'in_faid',
	'fp_amount' => 'amount',
	'fp_fee' => 'fee',
	'fp_remark' => 'remark',
	'fp_status' => 'status',
	'fp_type' => 'type',
);