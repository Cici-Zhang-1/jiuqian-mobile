<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月2日
 * @author Zhangcc
 * @version
 * @des
 */
class Dismantle_w extends MY_Controller{
    private $_EditParam;
    public function __construct(){
        log_message('debug', 'Controller Order/Dismantle_w eStart!');
        parent::__construct();
        $this->load->library('d_dismantle');
    }
    
    public function read(){
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        $Data = array(
            'struct' => array(), 'cabinet' => array(), 'plate_board' => array()
        );
        if($Id){
            if(!!($Data['struct'] = $this->d_dismantle->read_detail('cabinet_struct', $Id))){
                $Data['cabinet'] = $this->d_dismantle->read_detail('cabinet', $Data['struct']['opcsid']);
            }
            $Data['board_plate'] = $this->d_dismantle->read_detail('board_plate', $Id);
        }
        $this->_return($Data);
    }
    /* 
    private function _read_struct($Id){
        $this->load->model('order/order_product_cabinet_struct_model');
        return $this->order_product_cabinet_struct_model->select_order_product_cabinet_struct_by_opid($Id);
    }
    
    private function _read_cabinet($Opcsids){
        $this->load->model('order/order_product_cabinet_model');
        return $this->order_product_cabinet_model->select_order_product_cabinet_by_opcsid($Opcsids);
    }
    
    private function _read_board_plate($Id){
        $this->load->model('order/order_product_board_plate_model');
        return $this->order_product_board_plate_model->select_order_product_board_plate_by_opid($Id);
    } */
}
