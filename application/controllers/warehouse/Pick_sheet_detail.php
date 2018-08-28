<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pick sheet detail Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Pick_sheet_detail extends MY_Controller {
    private $__Search = array(
        'paging' => 0,
        'v' => 0
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller warehouse/Pick_sheet_detail __construct Start!');
        $this->load->model('order/order_product_model');
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
        if(!($Data = $this->order_product_model->select_pick_sheet_detail($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $this->load->helper('json_helper');
            foreach ($Data['content'] as $Key => $Value) {
                $Data['content'][$Key]['pack_detail'] = discode_pack($Value['pack_detail']);
            }
            $this->load->model('warehouse/unqrcode_model');
            if (!!($Unqrcode = $this->unqrcode_model->select_pick_sheet_detail($this->_Search))) {
                foreach ($Unqrcode['content'] as $Key => $Value) {
                    $Value['pack_detail'] = discode_pack($Value['pack_detail']);
                    $Value['warehouse_v'] = discode_warehouse_v($Value['warehouse_v']);
                    array_push($Data['content'], $Value);
                }
                $Data['num'] += $Unqrcode['num'];
            }
        }
        $Data['query']['stock_outted_v'] = $this->_Search['v'];
        $this->_ajax_return($Data);
    }

    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if(!!($NewId = $this->pick_sheet_detail_model->insert($Post))) {
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
            if(!!($this->pick_sheet_detail_model->update($Post, $Where))){
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
            if ($this->pick_sheet_detail_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
