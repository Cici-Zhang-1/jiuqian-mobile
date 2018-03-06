<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-23
 * @author ZhangCC
 * @version
 * @description  
 */
class Menu extends MY_Controller {
	private $_Module;
    private $_Controller;
    private $_Item ;
	public function __construct(){
		log_message('debug', 'Controller permission/Menu Start!');
		parent::__construct();
		$this->load->model('permission/menu_model');
		$this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
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
	    $Parent = $this->input->get('parent', true);
	    $Parent = trim($Parent);
	    if(preg_match('/\d{1,10}/', $Parent)){
	        $Pid = $Parent;
	    }elseif(is_string($Parent) && !empty($Parent)){
	        if(!($Pid = $this->menu_model->select_menu_id(gh_mysql_string($Parent)))){
	            $this->Failue = '您要查找的菜单不存在';
	        }
	    }else{
	        $Pid = 0;
	    }
	    $Return = array();
	    if(empty($this->Failue)){
	        if(!!($Query = $this->menu_model->select_menu())){
	            foreach ($Query as $key => $value){
	                $Data[$value['mid']] = $value;
	                $Child[$value['parent']][] = $value['mid'];
	            }
	            ksort($Child);
	            $Child = gh_infinity_category($Child, $Pid);
	            while(list($key, $value) = each($Child)){
	                $Return['content'][] = $Data[$value];
	            }
	        }else{
	            $this->Failue = '没有菜单内容';
	        }
	    }
	    $this->_ajax_return($Return);
	}

	public function add(){
	    $Item = $this->_Item.__FUNCTION__;
		if($this->form_validation->run($Item)){
		    $Post = gh_escape($_POST);
		    $Post['img'] = $this->input->post('img');
			if(!!($Mid = $this->menu_model->insert($Post))){
			    $this->load->model('permission/role_menu_model');
			    $Pid = $this->role_menu_model->insert(array('role_id' => SUPER_NO, 'menu_id' => $Mid)); // 新建菜单都是关联超级管理员
			    $this->Success .= '菜单新增成功, 刷新后生效!';
			}else{
				$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'菜单新增失败!';
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
		    $Post['img'] = $this->input->post('img');
		    $Where = $Post['selected'];
		    unset($Post['selected']);
			if(!!($this->menu_model->update($Post, $Where))){
				$this->Success .= '菜单信息修改成功, 刷新后生效!';
			}else{
				$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'菜单信息修改失败!';
			}
		}else{
			$this->Failue .= validation_errors();
		}
		$this->_return();
	}
	
	public function remove(){
		$Item = $this->_Item.__FUNCTION__;
		if($this->form_validation->run($Item)){
			$Where = $this->input->post('selected', true);
			if($Where !== false){
			    $this->load->model('permission/role_menu_model');
				if (!!($this->menu_model->delete($Where)) && !!($this->role_menu_model->delete_by_mid($Where))) {
					$this->load->model('permission/func_model');
					$this->func_model->delete_by_mid($Where);
					$this->load->model('permission/card_model');
					$this->card_model->delete_by_mid($Where);
					$this->Success .= '菜单信息删除成功，刷新后生效！';
				}else {
					$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'菜单信息删除失败';
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
