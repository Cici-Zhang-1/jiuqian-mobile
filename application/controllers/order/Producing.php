<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 16/7/3
 * Time: 11:07
 */

class Producing extends CWDMS_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $Search = array(
        'product' => '1,2',     /*橱柜、衣柜*/
        'status' => 4,        /*正在生产*/
        //'o_status' => 12,
        'keyword' => ''
    );

    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';

        log_message('debug', 'Controller Order/Producing Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view($Item, $Data);
        }
    }

    public function read(){
        $Cookie = $this->_Cookie.__FUNCTION__;
        $this->Search = $this->get_page_conditions($Cookie, $this->Search);
        $Data = array();
        if(!empty($this->Search)){
            if(!!($Data = $this->order_product_model->select_producing($this->Search))){
                $this->Success = '获取预处理订单成功!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要预处理的订单';
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
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