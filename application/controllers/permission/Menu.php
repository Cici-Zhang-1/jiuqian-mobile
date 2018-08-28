<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-23
 * @author ZhangCC
 * @version
 * @description  
 */
class Menu extends MY_Controller {
    private $__Search = array(
        'paging' => 1,
        'usergroup_v' => 0
    );
	public function __construct(){
		parent::__construct();
        log_message('debug', 'Controller permission/Menu Start!');
		$this->load->model('permission/menu_model');
	}

    /**
     * @param int $V Menu Id
     */
    public function read() {
        $Data = array();
        if(!($Menu = $this->menu_model->select_by_usergroup_v($this->session->userdata('ugid')))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $TmpSource = array();
            $TmpDes = array();
            foreach ($Menu as $key => $value){
                $value = $this->__menu_format($value);
                $TmpSource[$value['v']] = $value;
                $Child[$value['parent']][] = $value['v'];
            }
            ksort($Child);
            $Child = gh_infinity_category($Child);
            while(list($key, $value) = each($Child)){
                $TmpDes[] = $TmpSource[$value];
            }
            $Data = array(
                'content' => $TmpDes,
                'num' => count($TmpDes),
                'p' => ONE,
                'pn' => ONE
            );
        }
        $this->_ajax_return($Data);
    }

	private function __menu_format($Menu) {
        $Menu['class_alien'] = '|';
	    for($I = 0; $I < $Menu['class']; $I++) {
            $Menu['class_alien'] .= '---';
        }
        if (preg_match('/^<i\s+class=\"(.*)\"><\/i>$/', $Menu['img'], $Matched)) {
            $Menu['img'] = $Matched[1];
        }
        $Menu['funcs'] = $this->_func_format($Menu['v']);
        if ($Menu['page_type_name'] == 'form') {
            $Menu['form_pages'] = $this->_form_page_format($Menu['v']);
            // $Menu['form_pages'] = $this->_page_form_format($Menu['v']);
        } else {
            $Menu['page_search'] = $this->_page_search_format($Menu['v']);
        }
        $Menu['cards'] = $this->_card_format($Menu['v']);

        return $Menu;
    }

    private function _form_page_format($Mid) {
        $this->load->model('permission/form_page_model');
        $Return = array();
        if (!!($Query = $this->form_page_model->select_allowed($GLOBALS['ugid'], $Mid))) {
            foreach ($Query as $Key => $Value) {
                $Value['page_forms'] = $this->_page_form_format($Mid, $Value['v']);
                $Return[$Key] = $Value;
            }
        }
        return $Return;
    }

    private function _page_form_format($Mid, $Pfid) {
        $this->load->model('permission/page_form_model');
        $Return = array();
        if (!!($Query = $this->page_form_model->select_allowed($GLOBALS['ugid'], $Mid, $Pfid))) {
            foreach ($Query as $Key => $Value) {
                foreach ($Query as $Key => $Value) {
                    // $Value['dv'] = '';
                    $Return[$Value['name']] = $Value;
                }
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
                $Value['forms'] = $this->_form_format($Mid, $Value['v']);
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
                $Value['elements'] = $this->_element_format($Mid, $Value['v']);
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

	public function add(){
        $Parent = $this->input->post('parent');
        $_POST['parent'] = $Parent ? $Parent : 0;
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if ($Post['parent'] == 0) {
                $Post['class'] = 0;
            } elseif ($Parent = $this->menu_model->is_exist($Post['parent'])) {
                $Post['class'] = $Parent['class'] + 1;
            } else {
                $Post['class'] = 0;
            }
            if(!!($Fid = $this->menu_model->insert($Post))) {
                $this->load->model('permission/role_menu_model');
                $this->role_menu_model->insert(array('role_v' => SUPER_NO, 'menu_v' => $Fid));
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
	}
	
	public function edit(){
        $Parent = $this->input->post('parent');
        $_POST['parent'] = $Parent ? $Parent : 0;
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if ($Post['parent'] == 0) {
                $Post['class'] = 0;
            } elseif ($Parent = $this->menu_model->is_exist($Post['parent'])) {
                $Post['class'] = $Parent['class'] + 1;
            } else {
                $Post['class'] = 0;
            }
            $Post['img'] = $this->input->post('img');
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->menu_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
	}
	
	public function remove(){
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->menu_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
	}
}
