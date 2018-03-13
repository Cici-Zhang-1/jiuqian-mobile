<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-25
 * @author ZhangCC
 * @version
 * @description  
 * 地区列表
 */
class Area extends MY_Controller{
	private $_Module;
	private $_Controller;
	private $_Item;
    private $_Cookie;
	private $Search = array(
	    'keyword' => ''
	);
	
	public function __construct(){
		parent::__construct();
		$this->load->model('data/area_model');
		$this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
		$this->_Cookie = str_replace('/', '_', $this->_Item);
		log_message('debug', 'Controller Data/User Start!');
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
	        if(!!($Data = $this->area_model->select($this->Search))){
	            $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合搜索条件的地区';;
	        }
	    }else{
	        $this->Failue = '对不起, 没有符合条件的内容!';
	    }
	    $this->_return($Data);
	}

	public function add(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Post = gh_escape($_POST);
	        if(!!($Mid = $this->area_model->insert($Post))){
	            $this->Success .= '地区新增成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'地区新增失败!';
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
	        if(!!($this->area_model->update($Post, $Where))){
	            $this->Success .= '地区修改成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'地区修改失败!';
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
	            if($this->area_model->delete($Where)){
	                $this->Success .= '地区删除成功, 刷新后生效!';
	            }else {
	                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'地区删除失败';
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
