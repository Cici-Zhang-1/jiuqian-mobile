<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Finance pay Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Finance_pay extends MY_Controller {
    private $__Search = array(
        'start_date' => '',
        'end_date' => '',
        'finance_account_id' => ZERO,
        'status' => YES
    );
    private $_FinanceAccount = array();
    private $_InFinanceAccount = array();
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller finance/Finance_pay __construct Start!');
        $this->load->model('finance/finance_pay_model');
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
        if(!($Data = $this->finance_pay_model->select($this->_Search))) {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    private function _parse_input () {
        $Post = gh_escape($_POST);
        if(empty($Post['bank_date'])){
            $Post['bank_date'] = date('Y-m-d');
        }
        if (!($this->_FinanceAccount = $this->_read_finance_account($Post['finance_account_id']))) {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'支付账户不存在!';
            $this->Code = EXIT_ERROR;
        } else {
            $Post['finance_account'] = $this->_FinanceAccount['name'];
        }
        if (!empty($Post['in_finance_account_id'])) {
            if ($Post['finance_account_id'] == $Post['in_finance_account_id']) {
                $this->Message = '支付与转入账号不能相同!';
                $this->Code = EXIT_ERROR;
                return false;
            } elseif (!($this->_InFinanceAccount = $this->_read_finance_account($Post['in_finance_account_id']))) {
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'转入账户不存在!';
                $this->Code = EXIT_ERROR;
                return false;
            } else {
                $Post['in_finance_account'] = $this->_InFinanceAccount['name'];
            }
        }
        return $Post;
    }
    private function _read_finance_account($V) {
        $this->load->model('finance/finance_account_model');
        return $this->finance_account_model->is_exist($V);
    }
    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            if (!!($Post = $this->_parse_input())) {
                $this->_trans_start();
                $Post['status'] = YES;
                if(!!($NewId = $this->finance_pay_model->insert($Post))) {
                    $this->_edit_finance_account($Post);
                    if(!empty($Post['in_finance_account_id'])) {
                        $this->_edit_finance_account_in($Post);
                    }
                    $this->Message = '新建成功, 刷新后生效!';
                }else{
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                    $this->Code = EXIT_ERROR;
                }
                $this->_trans_end();
            }
        }
        $this->_ajax_return();
    }

    private function _edit_finance_account ($Post) {
        if (empty($this->_FinanceAccount) || $Post['finance_account_id'] != $this->_FinanceAccount['v']) {
            $this->_FinanceAccount = $this->_read_finance_account($Post['finance_account_id']);
        }
        $Data = array(
            'balance' => $Post['amount'] - $this->_FinanceAccount['balance'],
            'out' => $Post['amount'] + $this->_FinanceAccount['out'],
            'out_fee' => $Post['fee'] + $this->_FinanceAccount['out_fee']
        );
        $Data['balance'] -= $Post['fee'];
        $this->load->model('finance/finance_account_model');
        if (!!($this->finance_account_model->update($Data, $Post['finance_account_id']))) {
            return true;
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '修改账号金额失败';
        }
    }

    private function _edit_finance_account_in ($Post) {
        if (empty($this->_InFinanceAccount) || $Post['in_finance_account_id'] != $this->_InFinanceAccount['v']) {
            $this->_InFinanceAccount = $this->_read_finance_account($Post['in_finance_account_id']);
        }
        $Data = array(
            'balance' => $Post['amount'] + $this->_InFinanceAccount['balance'] - $Post['fee'],
            'in' => $Post['amount'] + $this->_InFinanceAccount['in'] - $Post['fee']
        );
        $this->load->model('finance/finance_account_model');
        if (!!($this->finance_account_model->update($Data, $Post['in_finance_account_id']))) {
            return true;
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '修改账号金额失败';
        }
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
            if(!!($this->finance_pay_model->update($Post, $Where))){
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
            if (!!($Query = $this->finance_pay_model->are_valid($Where))) {
                $Where = array();
                $this->_trans_start();
                foreach ($Query as $Key => $Value) {
                    $Value['amount'] = -1 * $Value['amount'];
                    $Value['fee'] = -1 * $Value['fee'];
                    $this->_edit_finance_account($Value);
                    if (!empty($Value['in_finance_account_id'])) {
                        $this->_edit_finance_account_in($Value);
                    }
                    array_push($Where, $Value['v']);
                }
                if ($this->finance_pay_model->delete($Where)) {
                    $this->Message = '支出删除成功!';
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '支出删除失败';
                }
                $this->_trans_end();
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要删除的财务支出不存在!';
            }
        }
        $this->_ajax_return();
    }
    private function _trans_start () {
        $this->finance_pay_model->trans_begin();
    }

    private function _trans_end () {
        if ($this->finance_pay_model->trans_status() === FALSE){
            $this->finance_pay_model->trans_rollback();
            $this->Message = '执行当前操作时出错';
            $this->Code = EXIT_ERROR;
        } else {
            if ($this->Code == EXIT_SUCCESS) {
                $this->finance_pay_model->trans_commit();
            } else {
                $this->finance_pay_model->trans_rollback();
            }
        }
    }
}
