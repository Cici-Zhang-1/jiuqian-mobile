<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Saler goods price Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Saler_goods_price extends MY_Controller {
    private $__Search = array(
        'product_id' => ZERO,
        'supplier_id' => ZERO,
        'goods_speci_id' => ZERO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller product/Saler_goods_price __construct Start!');
        $this->load->model('product/saler_goods_price_model');
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
        if (empty($this->_Search['goods_speci_id'])) {
            $GoodsSpeciId = $this->input->get('v', true);
            $GoodsSpeciId  = intval($GoodsSpeciId);
            if (!empty($GoodsSpeciId)) {
                $this->_Search['goods_speci_id'] = $GoodsSpeciId;
            }
        }
        $Data = array();
        if(!($Data = $this->saler_goods_price_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $Data['query']['goods_speci_id'] = $this->_Search['goods_speci_id'];
        $this->_ajax_return($Data);
    }

    /**
     *
     * @return void
     */
    public function add() {
        $GoodsSpeci = $this->input->post('v', true);
        $_POST['goods_speci_id'] = explode(',', $GoodsSpeci);
        unset($_POST['v']);
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Data = array();
            foreach ($Post['goods_speci_id'] as $Key => $Value) {
                $Data[] = array(
                    'goods_speci_id' => $Value,
                    'unit_price' => $Post['unit_price']
                );
            }
            if(!!($NewId = $this->saler_goods_price_model->insert_batch_update($Data))) {
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
            if(!!($this->saler_goods_price_model->update($Post, $Where))){
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
            if ($this->saler_goods_price_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
