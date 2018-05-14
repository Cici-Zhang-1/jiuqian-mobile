<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author zhangcc
 * @version
 * @des
 * 板件扫描
 */
class Scan extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;

    private $_Search = array(
        'keyword' => ''
    );

    public function __construct(){
        parent::__construct();
        $this->load->model('pack/scan_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        
        log_message('debug', 'Controller Order/scan Start!');
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
        $Qrcode = trim($this->input->get('qrcode', TRUE));
        $Data = array();
        $Return = array();
        if($Qrcode && preg_match('/^[\w\-\s]{3,128}$/',$Qrcode)){
            log_message('debug', '$Qrcode is'.$Qrcode);
            $this->load->model('order/order_product_board_plate_model');
            if(!!($Opbp = $this->order_product_board_plate_model->select_scan($Qrcode))){
                $Data['order'] = $Opbp;
                if(!!($Data['content'] = $this->order_product_board_plate_model->select_scan_list($Data['order']['opid']))){
                    $this->Success = '获取待扫描列表成功';
                }else{
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'该订单还未进入生产...';
                }
            }
        }else{
            $this->Failue = '二维码格式不正确!';
        }
        $this->_return($Data);
    }

    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Selected = $this->input->post('selected');
            if(is_array($Selected)){
                $Bid = $Selected[array_rand($Selected,1)];
            }else{
                $Bid = $Selected;
            }
            $this->load->model('order/order_product_board_plate_model');
            $this->load->model('order/order_product_model');
            if(!!($this->order_product_board_plate_model->update_scan($Selected))
                && $this->order_product_model->update_scan($Bid)){
                $this->Success .= '扫描保存成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'扫描保存失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
