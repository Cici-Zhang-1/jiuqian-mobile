<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-25
 * @author ZhangCC
 * @version
 * @description  
 * 基本异性
 */
class Abnormity extends CWDMS_Controller{
	private $_Module;
	private $_Controller;
	private $_Item;
	public function __construct(){
		parent::__construct();
		$this->load->model('data/abnormity_model');
		$this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
		
		log_message('debug', 'Controller Data/Abnormity Start!');
	}
	
	public function index(){
	    $View = $this->uri->segment(4, 'read');
	    if(method_exists(__CLASS__, '_'.$View)){
	        $View = '_'.$View;
	        $this->$View();
	    }else{
	        $Item = $this->_Item.$View;
	        $this->data['action'] = site_url($Item);
	        $this->load->view($Item, $this->data);
	    }
	}

	public function read(){
	    $this->Item = $this->_Item.__FUNCTION__;
	    $PrintList = $this->input->get('print_list');
	    if(false === $PrintList || is_null($PrintList)){
	        $PrintList = false;
	    }else{
	        $PrintList = intval(trim($PrintList));
	    }
	    $Scan = $this->input->get('scan');
	    if(false === $Scan || is_null($Scan)){
	        $Scan = false;
	    }else{
	        $Scan = intval(trim($Scan));
	    }
	    $Data = array();
	    if(!($Query = $this->abnormity_model->select_abnormity($PrintList, $Scan))){
	        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有异形名称';
	    }else{
	        $Data['content'] = $Query;
	    }
	    $this->_return($Data);
	}
	
	public function add(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Post = gh_escape($_POST);
	        if(!!($Id = $this->abnormity_model->insert_abnormity($Post))){
	            $this->Success .= '异形名称新增成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'异形名称新增失败';
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
	        $Where = $this->input->post('selected');
	        if(!!($this->abnormity_model->update_abnormity($Post, $Where))){
	            $this->Success .= '异形名称修改成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'异形名称修改失败';
	        }
	    }else{
	        $this->Failue .= validation_errors();
	    }
	    $this->_return();
	}
	/**
	 * 删除
	 */
	public function remove(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Where = $this->input->post('selected', true);
	        if($Where !== false && is_array($Where) && count($Where) > 0){
	            if($this->abnormity_model->delete_abnormity($Where)){
	                $this->Success .= '异形名称删除成功, 刷新后生效!';
	            }else {
	                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'异形名称删除失败';
	            }
	        }else{
	            $this->Failue .= '没有可删除项!';
	        }
	    }else{
	        $this->Failue .= validation_errors();
	    }
	    $this->_return();
	}
}
