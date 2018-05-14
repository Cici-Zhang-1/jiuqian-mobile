<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*Program: JIUQIAN
*============================
* 
*============================
*Author: Zhangcc
*Date:2015-4-26
**/
class Logistics extends MY_Controller{
	private $_Module;
	private $_Controller;
	private $_Item;
	
    public function __construct(){
		parent::__construct();
		$this->load->model('data/logistics_model');
		$this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
		log_message('debug', 'Controller Data/Logistics Start!');
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
        if(!!($Data = $this->logistics_model->select())){
            $this->Success = '获取物流信息成功';
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合搜索条件的物流信息';
        }
        $this->_return($Data);
	}
	
	public function add(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Post = gh_escape($_POST);
	        if(!!($Mid = $this->logistics_model->insert($Post))){
	            $this->Success .= '物流新增成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'物流新增失败!';
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
	        if(!!($this->logistics_model->update($Post, $Where))){
	            $this->Success .= '物流修改成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'物流修改失败!';
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
        $Item = $this->_Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false && is_array($Where) && count($Where) > 0){
                if($this->logistics_model->delete($Where)){
                    $this->Success .= '物流信息删除成功, 刷新后生效!';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'物流信息删除失败';
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
