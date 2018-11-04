<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Goods Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Goods extends MY_Controller {
    private $_Speci = array();
    private $_GoodsId = 0;
    private $__Search = array(
        'product_id' => 0,
        'supplier_id' => 0,
        'status' => YES
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller product/Goods __construct Start!');
        $this->load->model('product/goods_model');
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
        if (empty($this->_Search['product_id'])) {
            $ProductId = $this->input->get('v', true);
            $ProductId  = intval($ProductId);
            if (!empty($ProductId)) {
                $this->_Search['product_id'] = $ProductId;
            }
        }
        $Data = array();
        if(!($Data = $this->goods_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            foreach ($Data['content'] as $Key => $Value) {
                if ($Value['speci'] != '') {
                    $Data['content'][$Key]['speci'] = explode(',', $Value['speci']);
                }
            }
        }
        if (!empty($this->_Search['product_id'])) {
            $Data['query']['product_id'] = $this->_Search['product_id'];
        }
        $this->_ajax_return($Data);
    }

    /**
     *
     * @return void
     */
    public function add() {
        $Speci = $this->input->post('speci', true);
        if (!is_array($Speci)) {
            $_POST['speci'] = $Speci ? explode(',', $Speci) : [];
        }
        if (empty($_POST['purchase'])) {
            $_POST['purchase'] = 0;
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if (count($Post['speci']) > 0) {
                $this->_set_goods_speci();
            }
            $Post['speci'] = implode(',', $Post['speci']);
            $Post['status'] = YES;
            if(!!($this->_GoodsId = $this->goods_model->insert($Post))) {
                $this->_add_goods_speci();
                $this->Message .= '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    private function _add_goods_speci () {
        $Set = array();
        if ($this->_GoodsId && count($this->_Speci) > 0) {
            $this->load->helper('array');
            $Speci = combos($this->_Speci);
            foreach ($Speci as $Value) {
                $Set[] = array(
                    'goods_id' => $this->_GoodsId,
                    'speci' => implode('-', $Value),
                    'purchase' => $_POST['purchase'],
                    'unit_price' => $_POST['unit_price'],
                    'status' => YES
                );
            }
        } else {
            $Set[] = array(
                'goods_id' => $this->_GoodsId,
                'speci' => '-',
                'purchase' => $_POST['purchase'],
                'unit_price' => $_POST['unit_price'],
                'status' => YES
            );
        }
        $this->load->model('product/goods_speci_model');
        if (!!($this->goods_speci_model->insert_batch_update($Set))) {
            $this->Message .= '商品规格编辑成功, 刷新后生效!';
            return true;
        } else {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'商品规格编辑失败!';
            $this->Code = EXIT_ERROR;
        }
        return false;
    }
    private function _set_goods_speci () {
        $this->load->model('product/speci_model');
        if (!!($Query = $this->speci_model->is_exists($_POST['speci']))) {
            foreach ($Query as $Key => $Value) {
                if ($Value['class'] == ZERO) {
                    if (!isset($this->_Speci[$Value['v']])) {
                        $this->_Speci[$Value['v']] = array();
                    }
                } else {
                    if (!isset($this->_Speci[$Value['parent']])) {
                        $this->_Speci[$Value['parent']] = array(
                            $Value['name']
                        );
                    } elseif (!in_array($Value['v'], $this->_Speci[$Value['parent']])) {
                        array_push($this->_Speci[$Value['parent']], $Value['name']);
                    }
                }
            }
            $this->_Speci = array_values($this->_Speci);
            return true;
        }
        return false;
    }

    /**
    *
    * @return void
    */
    public function edit() {
        $Speci = $this->input->post('speci', true);
        if (!empty($Speci) && !is_array($Speci)) {
            $_POST['speci'] = explode(',', $Speci);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if (count($Post['speci']) > 0) {
                $this->_set_goods_speci();
            }
            $Post['speci'] = implode(',', $Post['speci']);
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->goods_model->update($Post, $Where))){
                $this->_GoodsId = $Where;
                $this->_add_goods_speci();
                $this->Message .= '内容修改成功, 刷新后生效!';
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
            if ($this->goods_model->delete($Where)) {
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
            if ($this->goods_model->update(array('status' => YES), $Where)) {
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
            if ($this->goods_model->update(array('status' => NO), $Where)) {
                $this->Message = '停售成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error'] : '停售失败!';
            }
        }
        $this->_ajax_return();
    }
/*
    public function import_other () {
        $this->load->model('product/other_model');
        if (!!($Others = $this->other_model->select_other())) {
            foreach ($Others as $Key => $Post) {
                $Post['speci'] = array();
                if (count($Post['speci']) > 0) {
                    $this->_set_goods_speci();
                }
                $Post['speci'] = implode(',', $Post['speci']);
                $Post['status'] = YES;
                $Post['purchase'] = 0;
                $_POST = array_merge($_POST, $Post);
                if(!!($this->_GoodsId = $this->goods_model->insert($Post))) {
                    $this->_add_goods_speci();
                    $this->Message .= '新建成功, 刷新后生效!';
                }else{
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                    $this->Code = EXIT_ERROR;
                    break;
                }
            }
        }
        $this->_ajax_return();
    }
    public function import_server () {
        $this->load->model('product/server_model');
        if (!!($Others = $this->server_model->select_server())) {
            foreach ($Others as $Key => $Post) {
                $Post['speci'] = array();
                if (count($Post['speci']) > 0) {
                    $this->_set_goods_speci();
                }
                $Post['speci'] = implode(',', $Post['speci']);
                $Post['status'] = YES;
                $Post['supplier_id'] = 16; // 九千
                $Post['purchase'] = 0;
                $_POST = array_merge($_POST, $Post);
                if(!!($this->_GoodsId = $this->goods_model->insert($Post))) {
                    $this->_add_goods_speci();
                    $this->Message .= '新建成功, 刷新后生效!';
                }else{
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                    $this->Code = EXIT_ERROR;
                    break;
                }
            }
        }
        $this->_ajax_return();
    }
    public function import_fitting () {
        $this->load->model('product/fitting_model');
        if (!!($Others = $this->fitting_model->select_fitting())) {
            foreach ($Others as $Key => $Post) {
                $Post['speci'] = array();
                if (count($Post['speci']) > 0) {
                    $this->_set_goods_speci();
                }
                $Post['speci'] = implode(',', $Post['speci']);
                $Post['status'] = YES;
                $Post['purchase'] = 0;
                $_POST = array_merge($_POST, $Post);
                if(!!($this->_GoodsId = $this->goods_model->insert($Post))) {
                    $this->_add_goods_speci();
                    $this->Message .= '新建成功, 刷新后生效!';
                }else{
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                    $this->Code = EXIT_ERROR;
                    break;
                }
            }
        }
        $this->_ajax_return();
    }*/
    public function purchase () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->goods_model->update($Post, $Where))){
                $this->_edit_goods_speci($Post, $Where);
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
    }

    public function unit_price () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->goods_model->update($Post, $Where))){
                $this->_edit_goods_speci($Post, $Where);
                $this->Message = '销售价修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'销售价修改失败';
            }
        }
        $this->_ajax_return();
    }
    private function _edit_goods_speci ($Data, $Where) {
        $this->load->model('product/goods_speci_model');
        if (!!($this->goods_speci_model->update_by_goods_id($Data, $Where))) {
            $this->Message .= '商品规格编辑成功, 刷新后生效!';
            return true;
        } else {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'商品规格编辑失败!';
            $this->Code = EXIT_ERROR;
        }
        return false;
    }
}
