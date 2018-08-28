<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Workflow procedure Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Workflow_procedure extends MY_Controller {
    private $__Search = array(
        'paging' => 0
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller workflow/Workflow_procedure __construct Start!');
        $this->load->model('workflow/workflow_procedure_model');
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
        $Data = array();
        if(!($Data = $this->workflow_procedure_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
    private function _read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->workflow_procedure_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        return $Data;
    }

    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if($this->workflow_procedure_model->insert($Post) !== false) {
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
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->workflow_procedure_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
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
            if ($this->workflow_procedure_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }

    public function edge () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if ($Value['v'] == WP_EDGE || $Value['v'] == WP_EDGING || $Value['v'] == WP_EDGED) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
        }
        $this->_ajax_return($Data);
    }
    public function punch () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if ($Value['v'] == WP_PUNCH || $Value['v'] == WP_PUNCHING || $Value['v'] == WP_PUNCHED) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
        }
        $this->_ajax_return($Data);
    }
    public function scan () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if ($Value['v'] == WP_SCAN || $Value['v'] == WP_SCANNING || $Value['v'] == WP_SCANNED) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
        }
        $this->_ajax_return($Data);
    }
    public function pack () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if ($Value['v'] == WP_PACK || $Value['v'] == WP_PACKING || $Value['v'] == WP_PACKED) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
        }
        $this->_ajax_return($Data);
    }
}
