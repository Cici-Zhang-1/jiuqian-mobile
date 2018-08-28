<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Goods speci Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Goods_speci extends MY_Controller {
    private $__Search = array(
        'product_id' => ZERO,
        'supplier_id' => ZERO,
        'status' => YES,
        'goods_id' => ZERO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller product/Goods_speci __construct Start!');
        $this->load->model('product/goods_speci_model');
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
        if (empty($this->_Search['goods_id'])) {
            $GoodsId = $this->input->get('v', true);
            $GoodsId  = intval($GoodsId);
            if (!empty($GoodsId)) {
                $this->_Search['goods_id'] = $GoodsId;
            }
        }
        $Data = array();
        if(!($Data = $this->goods_speci_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $Data['query']['goods_id'] = $this->_Search['goods_id'];
        $this->_ajax_return($Data);
    }

    public function fitting () {
        $Data = array();
        if (!($Data = $this->goods_speci_model->select_by_product_code('P'))) {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'获取配件商品失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    public function other () {
        $Data = array();
        if (!($Data = $this->goods_speci_model->select_by_product_code('G'))) {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'获取外购商品失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    public function server () {
        $Data = array();
        if (!($Data = $this->goods_speci_model->select_by_product_code('F'))) {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'获取其它商品失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if(!!($NewId = $this->goods_speci_model->insert($Post))) {
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
            if(!!($this->goods_speci_model->update($Post, $Where))){
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
            if ($this->goods_speci_model->delete($Where)) {
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
            if ($this->goods_speci_model->update(array('status' => YES), $Where)) {
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
            if ($this->goods_speci_model->update(array('status' => NO), $Where)) {
                $this->Message = '停售成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error'] : '停售失败!';
            }
        }
        $this->_ajax_return();
    }
}
