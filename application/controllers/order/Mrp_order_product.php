<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mrp order product Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Mrp_order_product extends MY_Controller {
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Mrp_order_product __construct Start!');
        $this->load->model('order/mrp_model');
        $this->load->model('order/order_product_classify_model');
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
        $V = $this->input->get('v');
        $V = intval(trim($V));
        $Data = array();
        if (empty($V)) {
            $this->Code = EXIT_ERROR;
            $this->Message = '请选择批次号后查看订单!';
        } else {
            if (!!($Mrp = $this->mrp_model->is_status_and_brothers($V, array(M_ELECTRONIC_SAW, M_SHEAR, M_SHEARED, M_ELECTRONIC_SAWED)))) {
                foreach ($Mrp as $Key => $Value) {
                    $Mrp[$Key] = $Value['v'];
                }
                if(!($Data = $this->order_product_classify_model->select_by_mrp_id($Mrp))){
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                    $this->Code = EXIT_ERROR;
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '没有找到对应的批次号!';
            }
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
            if(!!($NewId = $this->mrp_order_product_model->insert($Post))) {
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
            if(!!($this->mrp_order_product_model->update($Post, $Where))){
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
            if ($this->mrp_order_product_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
