<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-27
 * @author ZhangCC
 * @version
 * @description  
 */
class Order_type extends CWDMS_Controller{
	private $_Module;
	private $_Controller;
	private $_Item;
	
    public function __construct(){
		parent::__construct();
		$this->load->model('data/order_type_model');
		$this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
		
		log_message('debug', 'Controller Data/Order_type Start!');
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
	    if(!!($Data = $this->order_type_model->select())){
	        $this->Success = '获取订单类型信息成功';
	    }else{
	        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合搜索条件的订单类型信息';;
	    }
	    $this->_return($Data);
	}
	public function add(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Post = gh_escape($_POST);
	        if(!!($Mid = $this->order_type_model->insert($Post))){
	            $this->Success .= '订单类型新增成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单类型新增失败!';
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
	        if(!!($this->order_type_model->update($Post, $Where))){
	            $this->Success .= '订单类型修改成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单类型修改失败!';
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
                if($this->order_type_model->delete($Where)){
                    $this->Success .= '订单类型信息删除成功, 刷新后生效!';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单类型信息删除失败';
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