<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Finance income Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Finance_income_outtime extends MY_Controller {
    private $__Search = array(
        'intime' => NO,
        'finance_account_id' => array(),
        'income_pay' => '',
        'start_date' => '',
        'end_date' => '',
        'inned' => array(
            NO
        )
    );
    private $_FinanceAccount = array();
    private $_Remark = '';
    private $_Dealer = array();
    private $_FinanceIncomeId;
    private $_Category;
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller finance/Finance_income __construct Start!');
        $this->load->model('finance/finance_income_model');
        $this->config->load('defaults/dealer_account_book_category');
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
        if(!($Data = $this->finance_income_model->select($this->_Search))){
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
        if ($Post['corresponding'] === '') {
            $Post['corresponding'] = $Post['amount']; // 默认对应客户进账的金额就是账号进账金额
        }
        if (!empty($Post['dealer_id'])) {
            if (empty($Post['income_pay'])) {
                $this->Code = EXIT_ERROR;
                $this->Message = '请选择进账类型!';
            } else {
                $this->_read_dealer($Post['dealer_id']);
                $Post['dealer'] = $this->_Dealer['unique_name'];
            }
        } elseif ($Post['income_pay'] == '银行结息') {
            $Post['inned'] = YES;
        }
        if (!!($this->_read_finance_account($Post['finance_account_id']))) {
            $Post['finance_account'] = $this->_FinanceAccount['name'];
        } else {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'非及时进账账户不存在!';
            $this->Code = EXIT_ERROR;
        }
        return $Post;
    }
    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            if (!!($Post = $this->_parse_input())) {
                $this->_trans_start();
                if (!!($this->_FinanceIncomeId = $this->finance_income_model->insert($Post))) {
                    if (!empty($Post['dealer_id'])) {
                        $this->_set_category('', $Post['income_pay']);
                        $this->_edit_dealer($Post, $Post['dealer_id']);
                    }
                    if ($Post['inned']) { // 如果是已经到账则更改财务账户信息
                        $this->_edit_finance_account($Post);
                    }
                    $this->Message = '新建成功, 刷新后生效!';
                } else {
                    $this->Message = isset($GLOBALS['error']) ? is_array($GLOBALS['error']) ? implode(',', $GLOBALS['error']) : $GLOBALS['error'] : '新建失败!';
                    $this->Code = EXIT_ERROR;
                }
                $this->_trans_end();
            }
        }
        $this->_ajax_return();
    }
    /**
     * 获取财务账户
     * @param $V
     * @return array
     */
    private function _read_finance_account($V) {
        $this->load->model('finance/finance_account_model');
        $this->_FinanceAccount = $this->finance_account_model->is_exist($V);
        return $this->_FinanceAccount;
    }

    /**
     * 编辑财务账户
     * @param $Post
     * @return bool
     */
    private function _edit_finance_account ($Post) {
        if (empty($this->_FinanceAccount) || $Post['finance_account_id'] != $this->_FinanceAccount['v']) {
            $this->_read_finance_account($Post['finance_account_id']);
        }
        $Data = array(
            'balance' => $Post['amount'] + $this->_FinanceAccount['balance'],
            'in' => $Post['amount'] + $this->_FinanceAccount['in'],
            'in_fee' => $Post['fee'] + $this->_FinanceAccount['in_fee']
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

    /**
    *
    * @return void
    */
    public function edit() {
        if ($this->_do_form_validation()) {
            if (!!($Post = $this->_parse_input())) {
                $this->_FinanceIncomeId = $Post['v'];
                unset($Post['v']);
                if (!!($Query = $this->finance_income_model->is_valid($this->_FinanceIncomeId))) {
                    $this->_trans_start();
                    $P = $Post;
                    if (!empty($Post['dealer_id'])) {
                        if (!empty($Query['dealer_id']) && $Query['dealer_id'] != $Post['dealer_id']) { // 如果先前指定了客户，但是与现在的不同
                            $this->_Category = $this->config->item('dabc_remove');
                            $Query['corresponding'] = -1 * $Query['corresponding'];
                            $this->_edit_dealer($Query, $Query['dealer_id'], false);
                            $this->_set_category('', $Post['income_pay']);
                        } elseif ($Query['dealer_id'] == $Post['dealer_id']) { // 先前指定了客户与 现在指定的相同
                            $this->_Category = $this->config->item('dabc_edit');
                            $Post['corresponding'] = $Post['corresponding'] - $Query['corresponding'];
                        }
                        $this->_edit_dealer($Post, $Post['dealer_id']);
                    } else {
                        if (!empty($Query['dealer_id'])) { // 如果先前指定了客户，现在没有指定了客户
                            $this->_Category = $this->config->item('dabc_remove');
                            $Query['corresponding'] = -1 * $Query['corresponding'];
                            $this->_edit_dealer($Query, $Query['dealer_id'], false);
                        }
                    }
                    if ($Post['inned'] == YES) {
                        if ($Query['inned'] != $Post['inned']) { // 先前没有到账，当前到账
                            $this->_edit_finance_account($Post);
                        } else { // 先前和现在都已到账
                            if ($Query['finance_account_id'] != $Post['finance_account_id']) {
                                $Query['amount'] = -1 * $Query['amount'];
                                $Query['fee'] = -1 * $Query['fee'];
                                $this->_edit_finance_account($Post);
                                $this->_edit_finance_account($Query);
                            } else {
                                $Post['amount'] = $Post['amount'] - $Query['amount'];
                                $Post['fee'] = $Post['fee'] - $Query['fee'];
                                $this->_edit_finance_account($Post);
                            }
                        }
                    } else {
                        if ($Query['inned'] != $Post['inned']) { // 先前到账，当前没有到账
                            $Query['amount'] = -1 * $Query['amount'];
                            $Query['fee'] = -1 * $Query['fee'];
                            $this->_edit_finance_account($Query);
                        }
                    }

                    if ($this->Code == EXIT_SUCCESS) {
                        $Post = $P;
                        $Post['remark'] .= $this->_Remark;
                        if(!!($this->finance_income_model->update($Post, $this->_FinanceIncomeId))){
                            $this->Message = '非及时进账修改成功!';
                        } else {
                            $this->Code = EXIT_ERROR;
                            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'非及时进账修改失败';
                        }
                    }
                    $this->_trans_end();
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要修改的内容不存在';
                }
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
            if (!!($Query = $this->finance_income_model->are_valid($Where))) {
                $Where = array();
                $this->_trans_start();
                $this->_Category = $this->config->item('dabc_remove'); // 客户流水账类别
                foreach ($Query as $Key => $Value) {
                    if ($Value['inned']) { // 如果已经进账，则需要删除
                        $Value['amount'] = -1 * $Value['amount'];
                        $Value['fee'] = -1 * $Value['fee'];
                        $Value['corresponding'] = -1 * $Value['corresponding'];
                        $this->_edit_finance_account($Value);
                    } else {
                        $Value['corresponding'] = -1 * $Value['corresponding'];
                    }
                    if (!empty($Value['dealer_id'])) { // 如果已经挂到某个客户，则要清除
                        $this->_FinanceIncomeId = $Value['v'];
                        $this->_edit_dealer($Value, $Value['dealer_id']);
                    }
                    array_push($Where, $Value['v']);
                }
                $this->finance_income_model->delete($Where);
                $this->Message = '进账删除成功!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要删除的财务进账不存在!';
            }
        }
        $this->_ajax_return();
    }

    private function _edit_dealer ($Post, $Where, $Remark = true) {
        if ($Post['corresponding'] == 0) {
            return true;
        }
        if (empty($this->_Dealer) || $Where != $this->_Dealer['v']) {
            $this->_read_dealer($Where);
        }
        $Data = array(
            'balance' => $this->_Dealer['balance'] + $Post['corresponding'],
            'received' => $this->_Dealer['received'] + $Post['corresponding'],
            'virtual_balance' => $this->_Dealer['virtual_balance'] + $Post['corresponding'],
            'virtual_received' => $this->_Dealer['virtual_received'] + $Post['corresponding']
        );
        $this->load->model('dealer/dealer_model');
        if (!!($this->dealer_model->update($Data, $Where))) {
            if ($Remark) {
                $this->_Remark = '[截止本次付款，账户余额为 ' . $Data['balance'] . ' 元]';
            }
            return $this->_add_dealer_account_book($Post['corresponding'], $Data['balance']);
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '客户进账金额修改失败';
        }
    }

    private function _read_dealer ($V) {
        $this->load->model('dealer/dealer_model');
        $this->_Dealer = $this->dealer_model->is_exist($V);
        return $this->_Dealer;
    }

    public function inned () {
        if ($this->_do_form_validation()) {
            $V = $this->input->post('v', true);
            if (!!($Query = $this->finance_income_model->is_un_inned($V))) {
                $Post = gh_escape($_POST);
                unset($Post['v']);
                if ($Post['fee'] != '') {
                    $Query['fee'] = $Post['fee'] - $Query['fee'];
                }
                $this->_trans_start();
                $this->_edit_finance_account($Query);
                if ($this->Code == EXIT_SUCCESS) {
                    $Post['inned'] = YES;
                    if ($this->finance_income_model->update($Post, $V)) {
                        $this->Message = '到账确认成功!';
                    } else {
                        $this->Code = EXIT_ERROR;
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'到账确认失败!';
                    }
                }
                $this->_trans_end();
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要确认到账的财务进账不存在!';
            }
        }
        $this->_ajax_return();
    }

    private function _add_dealer_account_book ($Amount, $Balance, $Remark = '') {
        $this->load->model('dealer/dealer_account_book_model');
        $Data = array(
            'flow_num' => date('YmdHis' . microtime(true)),
            'dealer_id' => $this->_Dealer['v'],
            'in' => $Amount > 0,
            'amount' => $Amount,
            'title' => '进账',
            'category' => $this->_Category,
            'source_id' => $this->_FinanceIncomeId,
            'balance' => $Balance,
            'remark' => $Remark,
        );
        if ($this->dealer_account_book_model->insert($Data)) {
            return true;
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '新增客户流水失败!';
            return false;
        }
    }

    private function _set_category ($Key = '', $IncomePay = '') {
        if ($Key != '') {
            $this->_Category = $this->config->item($Key);
        } elseif ($IncomePay != '') {
            if ($IncomePay == '货款收入') {
                $this->_Category = $this->config->item('dabc_goods');
            } elseif ($IncomePay == '客户返款') {
                $this->_Category = $this->config->item('dabc_back');
            } else {
                $this->_Category = $this->config->item('dabc_other');
            }
        } else {
            $this->_Category = $this->config->item('dabc_other');
        }
    }
    private function _trans_start () {
        $this->finance_income_model->trans_begin();
    }

    private function _trans_end () {
        if ($this->finance_income_model->trans_status() === FALSE){
            $this->finance_income_model->trans_rollback();
            $this->Message = '执行当前操作时出错';
            $this->Code = EXIT_ERROR;
        } else {
            if ($this->Code == EXIT_SUCCESS) {
                $this->finance_income_model->trans_commit();
            } else {
                $this->finance_income_model->trans_rollback();
            }
        }
    }
}
