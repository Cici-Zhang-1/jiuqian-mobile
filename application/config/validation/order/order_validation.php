<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order/add'] = array(
	array(
		'field' => 'order_type',
		'label' => '订单类型',
		'rules' => 'trim|required|min_length[1]|max_length[2]'
	),
    array(
        'field' => 'task_level',
        'label' => '任务等级',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
    ),
	array(
		'field' => 'dealer_id',
		'label' => '客户编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	),
    array(
        'field' => 'shop_id',
        'label' => '店面编号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
	array(
		'field' => 'dealer',
		'label' => '客户',
		'rules' => 'trim|required|min_length[1]|max_length[512]'
	),
	array(
		'field' => 'owner',
		'label' => '业主',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'checker',
		'label' => '对单人',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'checker_phone',
		'label' => '对单电话',
		'rules' => 'trim|max_length[16]'
	),
	array(
		'field' => 'payer',
		'label' => '支付人',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'payer_phone',
		'label' => '支付电话',
		'rules' => 'trim|max_length[16]'
	),
	array(
		'field' => 'payterms',
		'label' => '支付条款',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'logistics',
		'label' => '要求物流',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'delivery_area',
		'label' => '收货地区',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'delivery_address',
		'label' => '收货具体地址',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'delivery_linker',
		'label' => '收货人',
		'rules' => 'trim|max_length[16]'
	),
	array(
		'field' => 'delivery_phone',
		'label' => '收货人联系方式',
		'rules' => 'trim|max_length[16]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'dealer_remark',
		'label' => '客户备注',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'product[]',
		'label' => '产品',
		'rules' => 'trim|required|numeric|max_length[4]'
	),
	array(
		'field' => 'request_outdate',
		'label' => '要求出厂日期',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'out_method',
		'label' => '出厂方式',
		'rules' => 'trim|max_length[64]'
	)
);

$config['order/order/edit'] = array(
	array(
		'field' => 'v',
		'label' => '订单ID',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'owner',
		'label' => '业主',
		'rules' => 'trim|required|max_length[128]'
	),
	array(
		'field' => 'task_level',
		'label' => '任务等级',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'dealer_remark',
		'label' => '客户备注',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'request_outdate',
		'label' => '要求出厂日期',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'checker',
		'label' => '对单人',
		'rules' => 'trim|required|max_length[32]'
	),
	array(
		'field' => 'checker_phone',
		'label' => '对单电话',
		'rules' => 'trim|required|max_length[16]'
	),
	array(
		'field' => 'payer',
		'label' => '支付人',
		'rules' => 'trim|required|max_length[32]'
	),
	array(
		'field' => 'payer_phone',
		'label' => '支付电话',
		'rules' => 'trim|required|max_length[16]'
	),
	array(
		'field' => 'delivery_area',
		'label' => '收货地区',
		'rules' => 'trim|required|max_length[64]'
	),
	array(
		'field' => 'delivery_address',
		'label' => '收货具体地址',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'delivery_linker',
		'label' => '收货人',
		'rules' => 'trim|required|max_length[16]'
	),
	array(
		'field' => 'delivery_phone',
		'label' => '收货人联系方式',
		'rules' => 'trim|required|max_length[16]'
	),
	array(
		'field' => 'logistics',
		'label' => '要求物流',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'out_method',
		'label' => '出厂方式',
		'rules' => 'trim|required|max_length[64]'
	)
);

$config['order/order/remove'] = array(
    array(
        'field' => 'v[]',
        'label' => '订单编号',
        'rules' => 'required|numeric|min_length[1]|max_length[10]'
    )
);

$config['order/order/re_dismantle'] = array(
    array(
        'field' => 'v',
        'label' => '订单编号',
        'rules' => 'required|numeric|min_length[1]|max_length[10]'
    )
);
