<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月18日
 * @author Zhangcc
 * @version
 * @des
 */
class Classify_label extends CWDMS_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    private $_Cookie;
    private $_Type;
    private $_Id;
    private $_Code;

    public function __construct(){
        parent::__construct();
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';
        
        log_message('debug', 'Controller Order/Classify_label Start !');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view('header2');
            $this->load->view($Item, $Data);
        }
    }

    private function _print(){
        $OrderProductNum = $this->input->get('order_product_num', true);
	    $Sn = $this->input->get('sn', true);
	    $Sn = trim($Sn);
        $OrderProductNum = trim($OrderProductNum);
        $OrderProductNum = strtoupper($OrderProductNum);
        
        $Cid = $this->input->get('cid', true);
        $Cid = intval(trim($Cid));
        
        $Data = array();
        $Length = ORDER_PREFIX + ORDER_SUFFIX;
        if(preg_match("/^(X|B)[\d]{{$Length},{$Length}}\-[A-Z][\d]{1,10}$/", $OrderProductNum) && !empty($Cid)){
            $Item = $this->_Item.__FUNCTION__;
            $this->load->model('order/order_product_board_plate_model');
            if(!!($Query = $this->order_product_board_plate_model->select_classify_label($OrderProductNum, $Cid))){
                foreach ($Query as $key => $value){
                    $Query[$key]['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                    $Query[$key]['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                }
                $Data['Data'] = $Query;
		        $Data['Sn'] = $Sn;
                unset($Query);
                $this->load->view('header2');
                $this->load->view($Item,$Data);
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要打印的板块分类订单';
                gh_alert_back($this->Failue);
            }
        }else{
            gh_alert_back('您选择分类和填写正确的订单编号!');
        }
    }
}