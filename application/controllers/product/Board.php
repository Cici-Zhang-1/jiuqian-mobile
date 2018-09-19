<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Board extends MY_Controller {
    private $__Search = array(
        'status' => YES
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller product/Board __construct Start!');
        $this->load->model('product/board_model');
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
        if(!($Data = $this->board_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    /**
     *
     * @return void
     */
    public function add() {
        $this->_format();
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $this->load->model('supplier/supplier_model');
            if (!!($Supplier = $this->supplier_model->is_exist($Post['supplier_id']))) {
                if ($Post['name'] != '') {
                    $Post['color'] = array_pop($Post['color']);
                    $Post['thick'] = array_pop($Post['thick']);
                    $Post['nature'] = array_pop($Post['nature']);
                    if(!!($NewId = $this->board_model->insert($Post))) {
                        $this->Message = '新建成功, 刷新后生效!';
                    }else{
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                        $this->Code = EXIT_ERROR;
                    }
                } else {
                    $Color = $Post['color'];
                    $Thick = $Post['thick'];
                    $Nature = $Post['nature'];
                    $Set = array();
                    foreach ($Thick as $Key => $Value) {
                        $Post['thick'] = $Value;
                        foreach ($Nature as $Ikey => $Ivalue) {
                            $Post['nature'] = $Ivalue;
                            foreach ($Color as $IIkey => $IIvalue) {
                                $Post['color'] = $IIvalue;
                                $Post['name'] = $Value . $Ivalue . $IIvalue . $Supplier['code'];
                                array_push($Set, $Post);
                            }
                        }
                    }
                    if(!!($this->board_model->insert_batch($Set))) {
                        $this->Message = '新建成功, 刷新后生效!';
                    }else{
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                        $this->Code = EXIT_ERROR;
                    }
                }
            } else {
                $this->Message = '供应商不存在!';
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
            if(!!($this->board_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
    }

    private function _format () {
        $Thick = $this->input->post('thick', true);
        if (!is_array($Thick)) {
            $_POST['thick'] = explode(',', $Thick);
        }
        $Nature = $this->input->post('nature', true);
        if (!is_array($Nature)) {
            $_POST['nature'] = explode(',', $Nature);
        }
        $Color = $this->input->post('color', true);
        if (!is_array($Color)) {
            $_POST['color'] = explode(',', $Color);
        }
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
            if ($this->board_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
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
            if ($this->board_model->update(array('status' => YES), $Where)) {
                $this->Message = '起售成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'起售失败!';
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
            if ($this->board_model->update(array('status' => NO), $Where)) {
                $this->Message = '停售成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error'] : '停售失败!';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 定采购价
     */
    public function purchase () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->board_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
    }
}
