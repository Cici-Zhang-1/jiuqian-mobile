<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月19日
 * @author Administrator
 * @version
 * @des
 */
class Ining_detail extends CWDMS_Controller{
    private $_Module = 'order';
    private $_Controller ;
    private $_Item ;
    private $_Cookie ;
    private $Count;
    private $_Id;
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
        
        log_message('debug', 'Controller Order/Ining_detail Start!');
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

    private function _read(){
        if(empty($this->_Id)){
            $Id = $this->input->get('id');
            $this->_Id = intval(trim($Id));
        }
        if($this->_Id > 0){
            $Data = array();
            if(!!($Data['Detail'] = $this->order_product_model->select_by_oid($this->_Id, $this->_Item.__FUNCTION__))){
                $this->load->view($this->_Item.__FUNCTION__, $Data);
            }else{
                $this->Failue .= isset($GLOBALS['error'])?(is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']):'没有正在入库的订单';
                $this->close_tab($this->Failue);
            }
        }else{
            show_error('您要访问的订单不存在!');
        }
    }
}