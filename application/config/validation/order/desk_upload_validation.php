<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/25
 * Time: 9:31
 */

$config['order/desk_upload/saw'] = array(
    array(
        'field' => 'batch_num',
        'label' => '批次号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[64]'
    ),
    array(
        'field' => 'mat[]',
        'label' => '板材',
        'rules' => 'trim|required|min_length[1]|max_length[5096]'
    )
);