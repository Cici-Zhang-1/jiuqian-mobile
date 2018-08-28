<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月4日
 * @author Zhangcc
 * @version
 * @des
 * 九千系统的配置
 */
/**
 * session中存储的内容
 */
$config['session_keys'] = 'uid name truename ugid usergroup';

/**
 * cookie中存储的内容
 */
$config['cookie_keys'] = 'uid truename';

/**
 * action对应的Controller
 */
$config['uris'] = array('default'=>'order', 'asure_produce'=>'order', 'order_deleted'=> 'order', 'asure_man_over'=>'manufacture', 'wait_delivery'=> 'delivery', 'delivered'=> 'delivery');
$config['page'] = array('delivery_history' => array(true,100));

$config['order_nums'] = 4; //生成订单编号的前缀是8位(20150101)还是4位(0101)

/**
 * 产品定义
 */
