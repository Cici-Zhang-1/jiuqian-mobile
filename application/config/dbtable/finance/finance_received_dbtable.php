<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_received_model/insert'] = array(
	'faid' => 'fr_finance_account_id',
	'amount' => 'fr_amount',
	'fee' => 'fr_fee',
	'cargo_no' => 'fr_cargo_no',
	'remark' => 'fr_remark',
	'bank_date' => 'fr_bank_date',
	'creator' => 'fr_creator',
	'create_datetime' => 'fr_create_datetime',
);
$config['finance/finance_received_model/insert_outtime'] = array(
	'faid' => 'fr_finance_account_id',
	'amount' => 'fr_amount',
	'fee' => 'fr_fee',
	'cargo_no' => 'fr_cargo_no',
	'remark' => 'fr_remark',
	'bank_date' => 'fr_bank_date',
	'creator' => 'fr_creator',
	'create_datetime' => 'fr_create_datetime',
	'type' => 'fr_type',
	'did' => 'fr_dealer_id',
	'dealer' => 'fr_dealer',
	'corresponding' => 'fr_Corresponding',
	'status' => 'fr_status',
);
$config['finance/finance_received_model/update'] = array(
	'faid' => 'fr_finance_account_id',
	'amount' => 'fr_amount',
	'fee' => 'fr_fee',
	'cargo_no' => 'fr_cargo_no',
	'remark' => 'fr_remark',
	'bank_date' => 'fr_bank_date',
);
$config['finance/finance_received_model/update_outtime'] = array(
	'fee' => 'fr_fee',
);
$config['finance/finance_received_model/update_finance_received_pointer'] = array(
	'type' => 'fr_type',
	'did' => 'fr_dealer_id',
	'dealer' => 'fr_dealer',
	'corresponding' => 'fr_corresponding',
	'remark' => 'fr_remark',
);