<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-27
 * @author ZhangCC
 * @version
 * @description  
 * 支付条款
 */
class Payterms extends MY_Controller{
	private $_Module;
	private $_Controller;
	private $_Item;
	public function __construct(){
		parent::__construct();
		$this->load->model('dealer/payterms_model');
		$this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
		
		log_message('debug', 'Controller Dealer/Payterms Start!');
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
	    $Data = array();
	    if(!!($Data = $this->payterms_model->select())){
	        $this->Success = '获取经销商支付条款成功';
	    }else{
	        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有经销商支付条款';;
	    }
	    $this->_return($Data);
	}
	
	public function add(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Post = gh_escape($_POST);
	        if(!!($Mid = $this->payterms_model->insert($Post))){
	            $this->Success .= '经销商支付条款新增成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商支付条款新增失败!';
	        }
	    }else{
	        $this->Failue .= validation_errors();
	    }
	    $this->_return();
	}
	
	public function edit(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Post = gh_escape($_POST);
	        $Where = $Post['selected'];
	        unset($Post['selected']);
	        if(!!($this->payterms_model->update($Post, $Where))){
	            $this->Success .= '经销商支付条款修改成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商支付条款修改失败!';
	        }
	    }else{
	        $this->Failue .= validation_errors();
	    }
	    $this->_return();
	}
}
