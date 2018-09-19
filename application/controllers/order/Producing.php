<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 16/7/3
 * Time: 11:07
 */

class Producing extends MY_Controller{
    private $__Search = array(
        'product_id' => array(
            CABINET,
            WARDROBE,
            DOOR,
            WOOD,
            OTHER,
            FITTING
        ),     /*橱柜、衣柜*/
        'status' => array(
            OP_PRODUCING,
            OP_PACKING
        )        /*正在生产*/
    );

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Producing Start!');
        $this->load->model('order/order_product_model');
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->order_product_model->select($this->_Search))){
            $this->Code = EXIT_ERROR;
            $this->Message = '没有获取到生产中的订单';
        }
        $this->_ajax_return($Data);
    }

    /**
     * 重新安排生产
     */
    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Selected = $this->input->post('selected', true);

            $this->load->library('workflow/workflow');

            if($this->workflow->initialize('order_product', $Selected)){
                $this->workflow->re_produce();
            }else{
                $this->Failue = $this->workflow->get_failue();
            }
        }else{
            $this->Failue = validation_errors();
        }
        if(empty($this->Failue)){
            $this->Success = '重新安排生产成功!';
        }
        $this->_return();
    }
}
