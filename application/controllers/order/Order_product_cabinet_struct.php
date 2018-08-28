<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order product cabinet struct Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Order_product_cabinet_struct extends MY_Controller {
    private $__Search = array(
        'order_product_id' => ZERO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Order_product_cabinet_struct __construct Start!');
        $this->load->model('order/order_product_cabinet_struct_model');
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
        if (empty($this->_Search['order_product_id'])) {
            $OrderProductId = $this->input->get('v', true);
            $OrderProductId = intval($OrderProductId);
            if (!empty($OrderProductId)) {
                $this->_Search['order_product_id'] = $OrderProductId;
            }
        }
        $Data = array();
        if (!empty($this->_Search['order_product_id'])) {
            if(!($Data = $this->order_product_cabinet_struct_model->select_one($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            }
        } else {
            $this->Message = '请选择需要查看结构的订单产品';
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
            if(!!($NewId = $this->order_product_cabinet_struct_model->insert($Post))) {
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
            if(!!($this->order_product_cabinet_struct_model->update($Post, $Where))){
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
            if ($this->order_product_cabinet_struct_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
