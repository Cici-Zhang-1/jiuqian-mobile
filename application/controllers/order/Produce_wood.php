<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月27日
 * @author Administrator
 * @version
 * @des
 * 橱柜柜体清单结算
 */
class Produce_wood extends MY_Controller{
    private $__Search = array(
        'start_date' => '',
        'end_date' => '',
        'status' => ''
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller order/Produce_wood __construct Start!');
        $this->load->model('order/order_product_board_model');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_' . $View)){
            $View = '_' . $View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $this->_Search['product'] = WOOD;
        $Data = array();
        if(!($Data = $this->order_product_board_model->select_produce_process($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_return($Data);
    }

    public function distribution () {
        $V = $this->input->post('v');
        $_POST['v'] = explode(',', $V);
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);

            if (!!($Distribution = $this->order_product_board_model->had_status_and_brothers($Where, $Post['distribution']))) {
                $this->Code = EXIT_ERROR;
                $this->Message = '您选中的之前已经分配，不可重新分配!';
            } else {
                $this->load->library('workflow/workflow_order_product_board/workflow_order_product_board');
                if(!!($this->workflow_order_product_board->initialize($Where))){
                    if (OPB_EDGE == $Post['distribution']) {
                        $this->workflow_order_product_board->edge();
                        $this->Message = '分配封边成功, 刷新后生效!';
                    } elseif (OPB_SCAN == $Post['distribution']) {
                        $this->workflow_order_product_board->sscan();
                        $this->Message = '分配扫描成功, 刷新后生效!';
                    } elseif (OPB_PACK == $Post['distribution']) {
                        $this->workflow_order_product_board->ppack();
                        $this->Message = '分配打包成功, 刷新后生效!';
                    } else {
                        $this->Code = EXIT_ERROR;
                        $this->Message = '您要分配到的状态不存在!';
                    }
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message = $this->workflow_mrp->get_failue();
                }
            }
        }
        $this->_ajax_return();
    }
}
