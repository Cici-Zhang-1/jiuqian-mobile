<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-25
 * @author ZhangCC
 * @version
 * @description  
 * 供应商
 */
class Supplier extends CWDMS_Controller{
	private $_Module;
	private $_Controller;
	private $_Item;
	private $_Cookie;
	
	private $Search = array(
	    'keyword' => ''
	);
	public function __construct(){
		parent::__construct();
		$this->load->model('supplier/supplier_model');
		$this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
		$this->_Cookie = str_replace('/', '_', $this->_Item).'_';
		log_message('debug', 'Controller Supplier/supplier Start!');
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

    public function read($All = ''){
        if('all' == $All){
            $this->_read_all();
        }else{
            $this->_read_part();
        }
	}
	
	private function _read_part(){
	    $Cookie = $this->_Cookie.__FUNCTION__;
	    $this->Search = $this->get_page_conditions($Cookie, $this->Search);
	    $Data = array();
	    if(!empty($this->Search)){
	        if(!!($Data = $this->supplier_model->select($this->Search))){
	            $this->Search['pn'] = $Data['pn'];
	            $this->Search['num'] = $Data['num'];
	            $this->Search['p'] = $Data['p'];
	            $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合搜索条件的供应商';
	        }
	    }else{
	        $this->Failue = '对不起, 没有符合条件的内容!';
	    }
	    $this->_return($Data);
	}
    
	private function _read_all(){
	    if(!!($Data = $this->supplier_model->select_all())){
	        $this->Success = '获取所有供应商信息成功!';
	    }else{
	        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的供应商';
	    }
	    $this->_return($Data);
	}
	
	public function add(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Post = gh_escape($_POST);
	        if(!!($this->supplier_model->insert($Post))){
	            $this->Success .= '供应商新增成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'供应商新增失败!';
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
	        if(!!($this->supplier_model->update($Post, $Where))){
	            $this->Success .= '供应商信息修改成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'供应商信息修改失败!';
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
	    $Item = $this->Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Where = $this->input->post('selected', true);
	        if($Where !== false && is_array($Where) && count($Where) > 0){
	            if($this->supplier_model->delete_supplier($Where)){
	                $this->Success .= '供应商信息删除成功, 刷新后生效!';
	            }else {
	                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'供应商信息删除失败';
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