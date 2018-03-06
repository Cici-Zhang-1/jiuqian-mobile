<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月6日
 * @author Zhangcc
 * @version
 * @des
 * 包装详情
*/
class Lack_detail extends CWDMS_Controller{
	private $_Module = 'order';
    private $_Controller;
    private $_Item;
    private $_Cookie;
	public function __construct(){
		parent::__construct();
		$this->load->model('order/order_product_board_plate_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
        
        log_message('debug', 'Controller Pack/Lack_detail Start!');
	}

	public function index(){
		$View = $this->uri->segment(4, false);
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
	    $Id = $this->input->get('id', true);
	    $Id = intval(trim($Id));
	    if($Id > 0){
	        $Data = array('Id' => $Id);
	        if(!!($Data['Plate'] = $this->order_product_board_plate_model->select_scan_lack_detail($Id))){
	            $this->Success = '获取差板块列表成功';
	            $this->load->view($this->_Item.__FUNCTION__, $Data);
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'该订单还未进入生产...';
	            show_error($this->Failue);
	        }
	    }else{
	        show_error('您要访问的内容不存在!');
	    }
	}
}