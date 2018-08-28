<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classify Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Classify extends MY_Controller {
    private $__Search = array(
        'class' => '',
        'parent' => ZERO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller data/Classify __construct Start!');
        $this->load->model('data/classify_model');
    }

    /**
    *
    * @return void
    */
    public function index() {
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if(preg_match('/^\d{1,10}$/', $this->_Search['parent'])){
        }elseif(is_string($this->_Search['parent']) && !empty($this->_Search['parent'])){
            if(!($this->_Search['parent'] = $this->classify_model->select_classify_id(gh_mysql_string($this->_Search['parent'])))){
                $this->_Search['parent'] = ZERO;
            }
        }else{
            $this->_Search['parent'] = 0;
        }
        $Pid = $this->_Search['parent'];
        $Data = array();
        if(!($Data = $this->classify_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $Query = $Data['content'];
            $TmpOne = array();
            $TmpTwo = array();
            $Child = array();
            foreach ($Query as $key => $value){
                $value = $this->_format($value);
                $TmpOne[$value['v']] = $value;
                $Child[$value['parent']][] = $value['v'];
            }
            ksort($Child);
            $Child = gh_infinity_category($Child, $Pid);
            while(list($key, $value) = each($Child)){
                $TmpTwo[] = $TmpOne[$value];
            }
            $Data['content'] = $TmpTwo;
            unset($Query, $TmpTwo, $TmpOne, $Child);
        }
        $this->_ajax_return($Data);
    }

    private function _format($Data) {
        $Data['class_alien'] = '|';
        for($I = 0; $I < $Data['class']; $I++) {
            $Data['class_alien'] .=  '---';
        }
        return $Data;
    }
    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $this->_set_parent();
            $Post = gh_escape($_POST);
            if(!!($NewId = $this->classify_model->insert($Post))) {
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    /**
    *
    * @return void
    */
    public function edit() {
        if ($this->_do_form_validation()) {
            $this->_set_parent();
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->classify_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 根据父类信息设置flag 和class
     */
    private function _set_parent () {
        if ($_POST['parent'] == ZERO) {
            $_POST['class'] = ZERO;
        } elseif ($Parent = $this->classify_model->is_exist($_POST['parent'])) {
            if ($Parent['class'] == ONE) {
                $_POST['parent'] = $Parent['parent'];
                $_POST['flag'] = $Parent['flag'];
                $_POST['production_line'] = $Parent['production_line'];
            }
            $_POST['class'] = ONE;
        } else {
            $_POST['parent'] = ZERO;
            $_POST['class'] = ZERO;
        }
        return true;
    }
    /**
     *
     * @param  int $id
     * @return void
     */
    public function remove() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->classify_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }

    public function stop () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->classify_model->update(array('status' => NO), $Where)) {
                $this->Message = '停用成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error'] : '停用失败!';
            }
        }
        $this->_ajax_return();
    }
    public function start () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->classify_model->update(array('status' => YES), $Where)) {
                $this->Message = '起用成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'起用失败!';
            }
        }
        $this->_ajax_return();
    }
}
