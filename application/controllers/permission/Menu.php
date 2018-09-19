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
    private $_FormPage = array();
    private $_PageForm = array();
    private $_Func = array();
    private $_Form = array();
    private $_Card = array();
    private $_Element = array();
    private $_PageSearch = array();
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
            $this->_read_form_page();
            $this->_read_page_form();
            $this->_read_func();
            $this->_read_form();
            $this->_read_card();
            $this->_read_element();
            $this->_read_page_search();
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
        $Menu['funcs'] = $this->_func_format($Menu['v']);
        if ($Menu['page_type_name'] == 'form') {
            $Menu['form_pages'] = $this->_form_page_format($Menu['v']);
        } else {
            $Menu['page_search'] = $this->_page_search_format($Menu['v']);
        }
        $Menu['cards'] = $this->_card_format($Menu['v']);

        return $Menu;
    }

    private function _read_form_page () {
        $this->load->model('permission/form_page_model');
        if (!!($Query = $this->form_page_model->select_allowed($GLOBALS['ugid']))) {
            foreach ($Query as $Key => $Value) {
                if (!isset($this->_FormPage[$Value['menu_id']])) {
                    $this->_FormPage[$Value['menu_id']] = array();
                }
                array_push($this->_FormPage[$Value['menu_id']], $Value);
            }
        }
    }
    private function _form_page_format($Mid) {
        $Return = array();
        if (isset($this->_FormPage[$Mid])) {
            $Return = $this->_FormPage[$Mid];
            foreach ($Return as $Key => $Value) {
                $Value['page_forms'] = $this->_page_form_format($Mid, $Value['v']);
                $Return[$Key] = $Value;
            }
        }
        return $Return;
        /*$this->load->model('permission/form_page_model');
        $Return = array();
        if (!!($Query = $this->form_page_model->select_allowed($GLOBALS['ugid'], $Mid))) {
            foreach ($Query as $Key => $Value) {
                $Value['page_forms'] = $this->_page_form_format($Mid, $Value['v']);
                $Return[$Key] = $Value;
            }
        }
        return $Return;*/
    }

    private function _read_page_form () {
        $this->load->model('permission/page_form_model');
        $Return = array();
        if (!!($Query = $this->page_form_model->select_allowed($GLOBALS['ugid']))) {
            foreach ($Query as $Key => $Value) {
                $Ikey = $Value['menu_id'] . $Value['form_page_id'];
                if (!isset($this->_PageForm[$Ikey])) {
                    $this->_PageForm[$Ikey] = array();
                }
                $this->_PageForm[$Ikey][$Value['name']] = $Value;
            }
        }
        return $Return;
    }
    private function _page_form_format($Mid, $Pfid) {
        $Return = array();
        $Key = $Mid  . $Pfid;
        if (isset($this->_PageForm[$Key])) {
            $Return = $this->_PageForm[$Key];
        }
        return $Return;
        /*$this->load->model('permission/page_form_model');
        $Return = array();
        if (!!($Query = $this->page_form_model->select_allowed($GLOBALS['ugid'], $Mid, $Pfid))) {
            foreach ($Query as $Key => $Value) {
                $Return[$Value['name']] = $Value;
            }
        }
        return $Return;*/
    }

    private function _read_func () {
        $this->load->model('permission/func_model');
        $Return = array();
        if (!!($Query = $this->func_model->select_allowed($GLOBALS['ugid']))) {
            foreach ($Query as $Key => $Value) {
                $Ikey = $Value['menu_id'];
                if (!isset($this->_Func[$Ikey])) {
                    $this->_Func[$Ikey] = array();
                }
                array_push($this->_Func[$Ikey], $Value);
            }
        }
        return $Return;
    }
    private function _func_format($Mid) {
        $Return = array();
        if (isset($this->_Func[$Mid])) {
            $Return = $this->_Func[$Mid];
            foreach ($Return as $Key => $Value) {
                $Value['forms'] = $this->_form_format($Mid, $Value['v']);
                $Return[$Key] = $Value;
            }
        }
        return $Return;
        /*$this->load->model('permission/func_model');
        $Return = array();
        if (!!($Query = $this->func_model->select_allowed($GLOBALS['ugid'], $Mid))) {
            foreach ($Query as $Key => $Value) {
                $Value['forms'] = $this->_form_format($Mid, $Value['v']);
                $Return[$Key] = $Value;
            }
        }
        return $Return;*/
    }

    private function _read_form () {
        $this->load->model('permission/form_model');
        $Return = array();
        if (!!($Query = $this->form_model->select_allowed($GLOBALS['ugid']))) {
            foreach ($Query as $Key => $Value) {
                $Ikey = $Value['menu_id'] . $Value['func_id'];
                if (!isset($this->_Form[$Ikey])) {
                    $this->_Form[$Ikey] = array();
                }
                $this->_Form[$Ikey][$Value['name']] = $Value;
            }
        }
        return $Return;
    }
    private function _form_format($Mid, $Fid) {
        $Return = array();
        $Key = $Mid  . $Fid;
        if (isset($this->_Form[$Key])) {
            $Return = $this->_Form[$Key];
        }
        return $Return;
        /*$this->load->model('permission/form_model');
        $Return = array();
        if (!!($Query = $this->form_model->select_allowed($GLOBALS['ugid'], $Mid, $Fid))) {
            foreach ($Query as $Key => $Value) {
                $Return[$Value['name']] = $Value;
            }
        }
        return $Return;*/
    }

    private function _read_card () {
        $this->load->model('permission/card_model');
        $Return = array();
        if (!!($Query = $this->card_model->select_allowed($GLOBALS['ugid']))) {
            foreach ($Query as $Key => $Value) {
                $Ikey = $Value['menu_id'];
                if (!isset($this->_Card[$Ikey])) {
                    $this->_Card[$Ikey] = array();
                }
                $Value['data'] = array();
                array_push($this->_Card[$Ikey], $Value);
            }
        }
        return $Return;
    }
    private function _card_format($Mid) {
        $Return = array();
        if (isset($this->_Card[$Mid])) {
            $Return = $this->_Card[$Mid];
            foreach ($Return as $Key => $Value) {
                $Value['elements'] = $this->_element_format($Mid, $Value['v']);
                $Return[$Key] = $Value;
            }
        }
        return $Return;
        /*$this->load->model('permission/card_model');
        $Return = array();
        if (!!($Query = $this->card_model->select_allowed($GLOBALS['ugid'], $Mid))) {
            foreach ($Query as $Key => $Value) {
                $Value['data'] = array();
                $Value['elements'] = $this->_element_format($Mid, $Value['v']);
                $Return[$Key] = $Value;
            }
        }
        return $Return;*/
    }

    private function _read_element () {
        $this->load->model('permission/element_model');
        if (!!($Query = $this->element_model->select_allowed($GLOBALS['ugid']))) {
            foreach ($Query as $Key => $Value) {
                $Ikey = $Value['menu_id'] . $Value['card_id'];
                if (!isset($this->_Element[$Ikey])) {
                    $this->_Element[$Ikey] = array();
                }
                $this->_Element[$Ikey][$Value['name']] = $Value;
            }
        }
    }
    private function _element_format($Mid, $Cid) {
        $Return = array();
        $Key = $Mid  . $Cid;
        if (isset($this->_Element[$Key])) {
            $Return = $this->_Element[$Key];
        }
        return $Return;
        /*$this->load->model('permission/element_model');
        $Return = array();
        if (!!($Query = $this->element_model->select_allowed($GLOBALS['ugid'], $Mid, $Cid))) {
            foreach ($Query as $Key => $Value) {
                $Return[$Value['name']] = $Value;
            }
        }
        return $Return;*/
    }

    private function _read_page_search () {
        $this->load->model('permission/page_search_model');
        if (!!($Query = $this->page_search_model->select_allowed($GLOBALS['ugid']))) {
            foreach ($Query as $Key => $Value) {
                $Ikey = $Value['menu_id'];
                if (!isset($this->_PageSearch[$Ikey])) {
                    $this->_PageSearch[$Ikey] = array();
                }
                $Value['value'] = '';
                $this->_PageSearch[$Ikey][$Value['name']] = $Value;
            }
        }
    }
    private function _page_search_format($Mid) {
        $Return = array();
        if (isset($this->_PageSearch[$Mid])) {
            $Return = $this->_PageSearch[$Mid];
        }
        return $Return;
        /*$this->load->model('permission/page_search_model');
        $Return = array();
        if (!!($Query = $this->page_search_model->select_allowed($GLOBALS['ugid'], $Mid))) {
            foreach ($Query as $Key => $Value) {
                $Value['value'] = '';
                $Return[$Value['name']] = $Value;
            }
        }
        return $Return;*/
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
