<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mrp Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Mrp extends MY_Controller {
    private $__Search = array(
        'status' => '',
        'keyword' => '',
        'distribution' => ''
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Mrp __construct Start!');
        $this->load->model('order/mrp_model');
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->mrp_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    /**
     * 分配
     */
    public function distribution() {
        $V = $this->input->post('v');
        $_POST['v'] = explode(',', $V);
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            $this->load->model('order/order_product_classify_model');
            if (!!($this->order_product_classify_model->are_status_by_mrp_id($Where, array(WP_SHEAR, WP_SHEARED, WP_ELECTRONIC_SAW), TRUE))) {
                $this->Code = EXIT_ERROR;
                $this->Message = '这个批次中有的订单准备工作还没有做，比如：打印清单...';
            } else {
                if (!!($Distribution = $this->mrp_model->is_status_and_brothers($Where, array(M_ELECTRONIC_SAW, M_SHEAR, M_SHEARED)))) {
                    $Where = array();
                    $WorkflowMessage = '';
                    foreach ($Distribution as $Key => $Value) {
                        $Where[] = $Value['v'];
                        $WorkflowMessage .= $Value['board'];
                    }
                    if(!!($this->mrp_model->update($Post, $Where))) {
                        $this->load->library('workflow/workflow');
                        $W = $this->workflow->initialize('mrp');
                        if(!!($W->initialize($Where))) {
                            $GLOBALS['workflow_msg'] = $WorkflowMessage;
                            $W->sheared();
                            $this->Message = '分配成功, 刷新后生效!';
                        } else {
                            $this->Code = EXIT_ERROR;
                            $this->Message = $this->workflow_mrp->get_failue();
                        }
                    } else {
                        $this->Code = EXIT_ERROR;
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'分配失败';
                    }
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '该批次已经分配和下料，不可重新分配!';
                }
            }
        }
        $this->_ajax_return();
    }

    /**
     * 删除
     */
    public function remove() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if (!!($Removable = $this->mrp_model->is_status_and_brothers($Where, array(M_ELECTRONIC_SAW, M_SHEAR, M_SHEARED)))) {
                $Where = array();
                foreach ($Removable as $Key => $Value) {
                    $Where[] = $Value['v'];
                }
                if (!($this->mrp_model->delete($Where))) {
                    $this->Message = '删除失败!';
                } else {
                    $this->Message = '删除成功!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '已经下料，不可删除!';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 计数统计
     */
    public function count () {
        $Data = array(
            'count' => ZERO
        );
        $Thick = $this->mrp_model->select_un_electronic_sawed();
        $Thin = $this->mrp_model->select_un_electronic_sawed(false);
        $Data['count'] = $Thick . '/' . $Thin;
        $this->_ajax_return($Data);
    }

    /**
     * 重新安排
     */
    public function re_shear () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if (!!($Distribution = $this->mrp_model->is_status_and_brothers($Where, array(M_ELECTRONIC_SAW)))) {
                $Where = array();
                $WorkflowMessage = '';
                foreach ($Distribution as $Key => $Value) {
                    $Where[] = $Value['v'];
                    $WorkflowMessage .= $Value['board'];
                }
                $this->load->library('workflow/workflow');
                $W = $this->workflow->initialize('mrp');
                if(!!($W->initialize($Where))){
                    $GLOBALS['workflow_msg'] = $WorkflowMessage;
                    $W->re_shear();
                    $this->Message = '重新分配成功, 刷新后生效!';
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '该批次已经分配和下料，不可重新分配!';
            }
        }
        $this->_ajax_return();
    }
}
