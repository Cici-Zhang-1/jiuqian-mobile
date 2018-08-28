<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/12
 * Time: 16:09
 */

$config['warehouse/pick_sheet_print/edit'] = array(
    array (
        'field' => 'v',
        'label' => '拣货单V',
        'rules' => 'trim|required|numeric|max_length[10]'
    )
);