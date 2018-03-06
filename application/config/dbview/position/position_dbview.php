<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['position/position_model/select_position'] = array(
	'p_id' => 'pid',
	'p_name' => 'name',
	'op_num' => 'order_product_num',
	'p_status' => 'status',
	'if(p_status = 0, "<i class=\"fa fa-circle fa-2x fa-color-success\"></i>", if(p_status=1, "<i class=\"fa fa-circle fa-2x fa-color-warning\"></i>", "<i class=\"fa fa-circle fa-2x fa-color-danger\"></i>"))' => 'icon',
);