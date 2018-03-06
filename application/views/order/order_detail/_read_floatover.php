<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月12日
 * @author Zhangcc
 * @version
 * @des
 * 订单详情的浮动窗口
 */

echo <<<END
<table class="table table-bordered table-condensed">
    <tbody>
END;
if(isset($Detail) && is_array($Detail) && count($Detail) > 0){
    foreach ($Detail as $key => $value){
        $Code = strtolower($value['code']);
        if(is_array($value['detail']) && count($value['detail']) > 0){
            $Product = '' != $value['product']?$value['product']:$value['name'];
            switch ($Code){
                case 'w':
                case 'y':
                case 'm':
                case 'k':
                    echo board_table_tr($value['detail']);
                    break;
                case 'p':
                    echo fitting_table_tr($value['detail']);
                    break;
                case 'g':
                    echo other_table_tr($value['detail']);
                    break;
                case 'f':
                    echo server_table_tr($value['detail']);
                    break;
            }
        }
    }
}