<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['pack/scan'] = array(
	'scanner' => 'opbp_scanner',
	'scan_datetime' => 'opbp_scan_datetime',
);
$config['pack/upload/bom'] = array(
	'材料' => 'b_material',
	'颜色' => 'b_color',
	'类别' => 'b_cubicle_name',
	'柜体位置' => 'b_cubicle_num',
	'名称' => 'b_board_name',
	'序号' => 'b_board_num',
	'长度' => 'b_length',
	'宽度' => 'b_width',
	'厚度' => 'b_thick',
	'封边' => 'b_fengbian',
	'备注' => 'b_remark',
	'打孔分类' => 'b_punch',
	'开槽信息' => 'b_slot',
	'数量' => 'b_num',
	'条形码' => 'b_qrcode',
);
$config['pack/upload/order'] = array(
	'经销商名称' => 'o_dealer_name',
	'联系人' => 'o_dealer_linker',
	'联系方式' => 'o_dealer_phone',
	'客户地址' => 'o_dealer_address',
	'业主' => 'o_owner',
	'订单号' => 'o_num',
);