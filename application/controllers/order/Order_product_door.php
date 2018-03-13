<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月2日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_product_door extends MY_Controller{
    private $Module = 'order';

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Order_product_door Start !');
        $this->load->model('order/order_product_door_model');
    }

    public function read(){
        $Oid = $this->input->get('id', true);
        $Oid = intval(trim($Oid));
        $Data = array();
        if($Oid){
            $Cache = $Oid.'_order_order_product_door';
            $this->e_cache->open_cache();
            $Return = array();
            if(!($Return = $this->cache->get($Cache))){
                if(!!($Query = $this->order_product_door_model->select_order_product_door($Oid))){
                    $this->config->load('dbview/order');
                    $Dbview = $this->config->item('order/order_product_door/read');
                    foreach ($Query as $key=>$value){
                        foreach ($Dbview as $ikey=>$ivalue){
                            $Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
                        }
                    }
                    $this->cache->save($Cache, $Return, HOURS);
                }else{
                    $this->Failue .= '该订单暂时没有门板信息';
                }
            }
            $Data['content'] = $Return;
            unset($Return);
        }
        $this->_return($Data);
    }
}
