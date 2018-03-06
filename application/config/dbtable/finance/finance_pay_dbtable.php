<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_pay_model/insert'] = array(
	'faid' => 'fp_finance_account_id',
	'in_faid' => 'fp_in_finance_account_id',
	'supplier_id' => 'fp_supplier_id',
	'supplier' => 'fp_supplier',
	'amount' => 'fp_amount',
	'type' => 'fp_type',
	'fee' => 'fp_fee',
	'remark' => 'fp_remark',
	'bank_date' => 'fp_bank_date',
	'creator' => 'fp_creator',
	'create_datetime' => 'fp_create_datetime',
);
$config['finance/finance_pay_model/insert_batch'] = array(
	'faid' => 'fp_finance_account_id',
	'amount' => 'fp_amount',
	'type' => 'fp_type',
	'fee' => 'fp_fee',
	'remark' => 'fp_remark',
	'bank_date' => 'fp_bank_date',
	'creator' => 'fp_creator',
	'create_datetime' => 'fp_create_datetime',
);
$config['finance/finance_pay_model/update'] = array(
	'faid' => 'fp_finance_account_id',
	'in_faid' => 'fp_in_finance_account_id',
	'supplier_id' => 'fp_supplier_id',
	'supplier' => 'fp_supplier',
	'amount' => 'fp_amount',
	'type' => 'fp_type',
	'fee' => 'fp_fee',
	'remark' => 'fp_remark',
	'bank_date' => 'fp_bank_date',
);