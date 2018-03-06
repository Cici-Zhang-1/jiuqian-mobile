<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_model/insert'] = array(
	'des' => 'd_des',
	'shop' => 'd_shop',
	'aid' => 'd_area_id',
	'address' => 'd_address',
	'dcid' => 'd_company_type_id',
	'pid' => 'd_payterms_id',
	'name' => 'd_name',
	'password' => 'd_password',
	'remark' => 'd_remark',
	'creator' => 'd_creator_id',
	'create_datetime' => 'd_create_datetime',
);
$config['dealer/dealer_model/update'] = array(
	'des' => 'd_des',
	'shop' => 'd_shop',
	'aid' => 'd_area_id',
	'address' => 'd_address',
	'dcid' => 'd_company_type_id',
	'pid' => 'd_payterms_id',
	'remark' => 'd_remark',
	'password' => 'd_password',
);
$config['dealer/dealer_model/update_batch'] = array(
	'did' => 'd_id',
	'debt1' => 'd_debt1',
	'debt2' => 'd_debt2',
	'balance' => 'd_balance',
);
$config['dealer/dealer_model/update_batch_dealer_debt'] = array(
	'did' => 'd_id',
	'debt1' => 'd_debt1',
	'debt2' => 'd_debt2',
	'balance' => 'd_balance',
);
$config['dealer/dealer_model/update_batch_dealer_debt2'] = array(
	'did' => 'd_id',
	'debt2' => 'd_debt2',
);
$config['dealer/dealer_model/update_batch_dealer_balance'] = array(
	'did' => 'd_id',
	'balance' => 'd_balance',
);