<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer/add'] = array(
                        array (
            'field' => 'company_type',
            'label' => '企业类型',
            'rules' => 'trim|required|max_length[32]'
        ),
    array (
        'field' => 'num',
        'label' => '客户编号',
        'rules' => 'trim|required|max_length[64]|is_unique[dealer.d_num]'
    ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
    array (
        'field' => 'shop',
        'label' => '店面名称',
        'rules' => 'trim|max_length[128]'
    ),
                                array (
            'field' => 'area_id',
            'label' => '所在地区',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'address',
            'label' => '地址',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'discount',
            'label' => '折扣',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'credit',
            'label' => '信誉度',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'payterms',
            'label' => '支付条款',
            'rules' => 'trim|max_length[64]'
        ),
    array (
        'field' => 'down_payment',
        'label' => '首付比例',
        'rules' => 'trim|required|greater_than_equal_to[0]|less_than_equal_to[1]|max_length[5]'
    ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'balance',
            'label' => '账户余额',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[2]'
        ),
                                array (
            'field' => 'start_date',
            'label' => '期初开始日期',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'start',
            'label' => '期初金额',
            'rules' => 'trim|max_length[10]'
        ),
    array (
        'field' => 'dealer_delivery_area_id',
        'label' => '收货地区',
        'rules' => 'trim|numeric|max_length[10]'
    ),
    array (
        'field' => 'dealer_delivery_address',
        'label' => '收货地址',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'dealer_delivery_logistics',
        'label' => '物流',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'dealer_delivery_out_method',
        'label' => '要求出厂方式',
        'rules' => 'trim|required|max_length[64]'
    ),
    array (
        'field' => 'dealer_delivery_linker',
        'label' => '收货人',
        'rules' => 'trim|max_length[32]'
    ),
    array (
        'field' => 'dealer_delivery_phone',
        'label' => '联系电话',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'dealer_linker_name',
        'label' => '用户名',
        'rules' => 'trim|max_length[32]'
    ),
    array (
        'field' => 'dealer_linker_truename',
        'label' => '真实姓名',
        'rules' => 'trim|required|max_length[64]'
    ),
    array (
        'field' => 'dealer_linker_position',
        'label' => '职位',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'dealer_linker_mobilephone',
        'label' => '移动电话',
        'rules' => 'trim|required|min_length[10]|max_length[16]|is_unique[dealer_linker.dl_mobilephone]'
    ),
    array (
        'field' => 'dealer_linker_telephone',
        'label' => '座机',
        'rules' => 'trim|max_length[16]'
    ),
    array (
        'field' => 'dealer_linker_email',
        'label' => 'Email',
        'rules' => 'trim|max_length[16]'
    ),
    array (
        'field' => 'dealer_linker_qq',
        'label' => 'QQ',
        'rules' => 'trim|max_length[16]'
    ),
    array (
        'field' => 'dealer_linker_fax',
        'label' => 'Fax',
        'rules' => 'trim|max_length[16]'
    )
            );

$config['dealer/dealer/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
    array (
        'field' => 'num',
        'label' => '客户编号',
        'rules' => 'trim|required|max_length[64]'
    ),
                                array (
            'field' => 'company_type',
            'label' => '企业类型',
            'rules' => 'trim|required|max_length[32]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),

                                array (
            'field' => 'area_id',
            'label' => '所在地区',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'address',
            'label' => '地址',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'discount',
            'label' => '折扣',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'credit',
            'label' => '信誉度',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'payterms',
            'label' => '支付条款',
            'rules' => 'trim|required|max_length[64]'
        ),
    array (
        'field' => 'down_payment',
        'label' => '首付比例',
        'rules' => 'trim|required|greater_than_equal_to[0]|less_than_equal_to[1]|max_length[5]'
    ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        )
            );

$config['dealer/dealer/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                                                                );

$config['dealer/dealer/start'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);

$config['dealer/dealer/stop'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
