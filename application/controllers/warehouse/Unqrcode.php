<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Unqrcode Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Unqrcode extends MY_Controller {
    private $__Search = array(
        'order_id' => ZERO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller warehouse/Unqrcode __construct Start!');
        $this->load->model('warehouse/unqrcode_model');
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
        if (empty($this->_Search['order_id'])) {
            $OrderId = $this->input->get('v', true);
            $this->_Search['order_id'] = intval($OrderId);
        }
        if (!empty($this->_Search['order_id'])) {
            $this->_Search['p'] = ONE;
            $this->_Search['pagesize'] = ALL_PAGESIZE;
        }
        $Data = array();
        if(!($Data = $this->unqrcode_model->select($this->_Search))){
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
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Post['order_product_num'] = strtoupper($Post['order_product_num']);
            $this->load->model('order/order_model');
            if (!!($Query = $this->order_model->is_exist_order_num($Post['order_product_num']))) {
                if ($Query['status'] > O_DELIVERED) {
                    $this->Message = '订单已经出厂, 不能再增加无码入库, 请直接和成品库沟通!';
                    $this->Code = EXIT_ERROR;
                } else {
                    $Post['order_id'] = $Query['v'];
                    if (!!($Query = $this->unqrcode_model->has_brother($Post['order_id']))) {
                        if (preg_match('/[X|B][\d]{10,10}-[\w]([\d]+)/', $Query['num'], $Matches)) {
                            $Matches[1]++;
                            $Post['num'] = $Post['order_product_num'] . '-A' . $Matches[1];
                        } else {
                            $Post['num'] = $Post['order_product_num'] . '-A1';
                        }
                    } else {
                        $Post['num'] = $Post['order_product_num'] . '-A1';
                    }
                    $Post['pack_detail'] = json_encode(array('total' => $Post['pack'])); // 为和订单产品保持一致
                    if(!!($NewId = $this->unqrcode_model->insert($Post))) {
                        $this->Message = '新建成功, 刷新后生效!';
                    }else{
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                        $this->Code = EXIT_ERROR;
                    }
                }
            } else {
                $this->Message = '订单编号不存在!';
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
            if(!!($this->unqrcode_model->update($Post, $Where))){
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
            if ($this->unqrcode_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
