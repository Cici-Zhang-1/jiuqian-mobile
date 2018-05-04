<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-23
 * @author ZhangCC
 * @version
 * @description  
 */
class Menu extends MY_Controller {
	public function __construct(){
		parent::__construct();
        log_message('debug', 'Controller permission/Menu Start!');
		$this->load->model('permission/menu_model');
	}

    /**
     * @param int $V Menu Id
     */
	public function read ($V = 0) {
	    $Pid = $this->input->get('parent', false);
	    $Full = $this->input->get('full', true); // 是否读取整个Menu数据

        $Return = array();
	    if ($Pid !== false) {
	        if (is_string($Pid) && !empty($Pid) && !preg_match('/[1-9]\d{0, 10}/', $Pid)) {
                if(!($Pid = $this->menu_model->select_menu_id(gh_mysql_string($Pid)))){
                    $this->Code = EXIT_ERROR;
                }
            }
        }
        if (!($Query = $this->menu_model->select_allowed_by_ugid($GLOBALS['ugid'], $GLOBALS['MOBILE']))){
            $this->Code = EXIT_ERROR;
        } else {
	        $Query = $this->_menu_format($Query);
	        if ($Full) {
	            $Query = $this->_menu_full($Query);
            }
            if ($Pid) {
                foreach ($Query as $key => $value){
                    $Data[$value['v']] = $value;
                    $Child[$value['parent']][] = $value['v'];
                }
                ksort($Child);
                $Child = gh_infinity_category($Child, $Pid);
                while(list($key, $value) = each($Child)){
                    $Return['content'][] = $Data[$value];
                }
            } else {
	            // $Return = $Query;
	            $Return = array(
	                'content' => $Query,
                    'p' => 1,
                    'pn' => 1,
                    'num' => count($Query),
                    'pagesize' => count($Query)
                );
            }
        }
        $this->_ajax_return($Return);
    }

    /**
     * 格式化menu
     * @param $Menu
     */
    private function _menu_format ($Menu) {
        foreach ($Menu as $Key => $Row){
            $Row['class_alien'] = '|';
            for ($I = 0; $I < $Row['class']; $I++) {
                $Row['class_alien'] .= '---';
            }
            $Menu[$Key] = $Row;
        }

        return $Menu;
    }
    private function _menu_full ($Menu) {
        $Return = array();
        foreach ($Menu as $Key => $Row){
            if (preg_match('/^<i\s+class=\"(.*)\"><\/i>$/', $Row['img'], $Matched)) {
                $Row['img'] = $Matched[1];
            }
            if ($Row['page_type_name'] == 'form') {
                $Row['page_forms'] = $this->_page_form_format($Row['v']);
            } else {
                $Row['funcs'] = $this->_func_format($Row['v']);
                $Row['page_search'] = $this->_page_search_format($Row['v']);
                $Row['cards'] = $this->_card_format($Row['v']);
            }
            $Return[$Key] = $Row;
            /*if ($Row['label'] != false) {
                $Return[$Row['label']] = $Row;
            } else {
                $Return[$Key] = $Row;
            }*/

        }

        return $Return;
    }

    private function _page_form_format($Mid) {
        $this->load->model('permission/page_form_model');
        $Return = array();
        if (!!($Query = $this->page_form_model->select_allowed($GLOBALS['ugid'], $Mid))) {
            foreach ($Query as $Key => $Value) {
                $Value['value'] = '';
                $Return[$Value['name']] = $Value;
            }
        }
        return $Return;
    }

    private function _func_format($Mid) {
        $this->load->model('permission/func_model');
        $Return = array();
        if (!!($Query = $this->func_model->select_allowed($GLOBALS['ugid'], $Mid))) {
            foreach ($Query as $Key => $Value) {
                if (preg_match('/^<i\s+class=\"(.*)\"><\/i>$/', $Value['img'], $Matched)) {
                    $Value['img'] = $Matched[1];
                }
                $Value['forms'] = $this->_form_format($Mid, $Value['fid']);
                $Return[$Key] = $Value;
            }
        }
        return $Return;
    }

    private function _form_format($Mid, $Fid) {
        $this->load->model('permission/form_model');
        $Return = array();
        if (!!($Query = $this->form_model->select_allowed($GLOBALS['ugid'], $Mid, $Fid))) {
            foreach ($Query as $Key => $Value) {
                foreach ($Query as $Key => $Value) {
                    $Return[$Value['name']] = $Value;
                }
            }
        }
        return $Return;
    }

    private function _card_format($Mid) {
        $this->load->model('permission/card_model');
        $Return = array();
        if (!!($Query = $this->card_model->select_allowed($GLOBALS['ugid'], $Mid))) {
            foreach ($Query as $Key => $Value) {
                $Value['data'] = array();
                $Value['elements'] = $this->_element_format($Mid, $Value['cid']);
                $Return[$Key] = $Value;
            }
        }
        return $Return;
    }

    private function _element_format($Mid, $Cid) {
        $this->load->model('permission/element_model');
        $Return = array();
        if (!!($Query = $this->element_model->select_allowed($GLOBALS['ugid'], $Mid, $Cid))) {
            foreach ($Query as $Key => $Value) {
                $Return[$Value['name']] = $Value;
            }
        }
        return $Return;
    }

    private function _page_search_format($Mid) {
        $this->load->model('permission/page_search_model');
        $Return = array();
        if (!!($Query = $this->page_search_model->select_allowed($GLOBALS['ugid'], $Mid))) {
            foreach ($Query as $Key => $Value) {
                $Value['value'] = '';
                $Return[$Value['name']] = $Value;
            }
        }
        return $Return;
    }
	
	/*public function read(){
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
	}*/

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
