<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Finance income Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Finance_income_intime extends MY_Controller {
    private $__Search = array(
        'intime' => YES,
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
    private $_FinanceIncomeId = 0;
    private $_DealerAccountBookId;
    private $_Category;
    private $_OrderNums = array();

    private $_Announce = NO;
    private $_AnnounceMsg = '';
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
                $Post['inned'] = YES;
                if (isset($Post['order_num'])) {
                    $this->_OrderNums =  $Post['order_num'];
                    unset($Post['order_num']);
                    $Post['remark'] .= ',' . implode(',', $this->_OrderNums);
                }

                if (($Post['amount'] == ZERO && $Post['corresponding'] > ZERO) || ($Post['amount'] < $Post['corresponding'])) {
                    $this->_Announce = true;
                    $this->_AnnounceMsg = $this->session->userdata('truename') . '登记了一笔财务账户进账为￥' . $Post['amount'] . '，客户' . $Post['dealer'] . '进账为￥' . $Post['corresponding'] . '的及时进账';
                }
            }
        } elseif ($Post['income_pay'] == '银行结息') {
            $Post['inned'] = YES;
        } else {
            $Post['inned'] = NO;
        }
        if (!!($this->_read_finance_account($Post['finance_account_id']))) {
            $Post['finance_account'] = $this->_FinanceAccount['name'];
        } else {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'进账账户不存在!';
            $this->Code = EXIT_ERROR;
            return false;
        }
        list($U, $S) = explode(' ', microtime());
        $Post['flow_num'] = number_format($S + $U, TEN, '.', '');
        $this->_Remark = $Post['remark'];
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
                if (!empty($Post['dealer_id'])) {
                    $this->_set_category('', $Post['income_pay']);
                    $this->_edit_dealer($Post, $Post['dealer_id']);
                }
                $Post['remark'] = $this->_Remark;
                if (!!($this->_FinanceIncomeId = $this->finance_income_model->insert($Post))) {
                    $this->_edit_finance_account($Post);
                    if (!empty($this->_OrderNums)) {
                        $this->_edit_order();
                    }
                    if (!empty($this->_DealerAccountBookId)) {
                        $this->_edit_dealer_account_book();
                    }
                } else {
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                    $this->Code = EXIT_ERROR;
                }
                $this->_trans_end();
            }
        }
        $this->_ajax_return();
    }

    /**
     * 读取财务账户信息
     * @param $V
     * @return array
     */
    private function _read_finance_account($V) {
        $this->load->model('finance/finance_account_model');
        $this->_FinanceAccount = $this->finance_account_model->is_exist($V);
        return $this->_FinanceAccount;
    }
    /**
     * 编辑财务账户的金额
     * @param $Post
     * @return bool
     */
    private function _edit_finance_account ($Post) {
        if (empty($this->_FinanceAccount) || $Post['finance_account_id'] != $this->_FinanceAccount['v']) {
            $this->_read_finance_account($Post['finance_account_id']);
        }
        $Data = array(
            'balance' => bcadd($Post['amount'], $this->_FinanceAccount['balance'], 2),
            'in' => bcadd($Post['amount'], $this->_FinanceAccount['in'], 2),
            'in_fee' => bcadd($Post['fee'], $this->_FinanceAccount['in_fee'], 2)
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
    * 编辑财务进账
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
                    if ($Query['finance_account_id'] != $Post['finance_account_id']) {
                        $Query['amount'] = -1 * $Query['amount'];
                        $Query['fee'] = -1 * $Query['fee'];
                        $this->_edit_finance_account($Post);
                        $this->_edit_finance_account($Query);
                    } else {
                        $Post['amount'] = bcsub($Post['amount'], $Query['amount'], 2);
                        $Post['fee'] = bcsub($Post['fee'], $Query['fee'], 2);
                        $this->_edit_finance_account($Post);
                    }
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
                        $P['inned'] = YES;
                    } else {
                        if (!empty($Query['dealer_id'])) { // 如果先前指定客户，现在没有指定客户
                            $Query['corresponding'] = -1 * $Query['corresponding'];
                            $this->_Category = $this->config->item('dabc_remove');
                            $this->_edit_dealer($Query, $Query['dealer_id'], false);
                        }
                    }
                    if ($P['income_pay'] == '银行结息') {
                        $P['inned'] = YES;
                    }
                    if ($this->Code == EXIT_SUCCESS) {
                        $Post = $P;
                        $Post['remark'] = $this->_Remark;
                        if(!!($this->finance_income_model->update($Post, $this->_FinanceIncomeId))){
                            $this->Message = '进账修改成功!';
                        } else {
                            $this->Code = EXIT_ERROR;
                            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'进账修改失败';
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
     * 删除已经登记的财务进账
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
                    $Value['amount'] = -1 * $Value['amount'];
                    $Value['fee'] = -1 * $Value['fee'];
                    $Value['corresponding'] = -1 * $Value['corresponding'];
                    $this->_edit_finance_account($Value);
                    if (!empty($Value['dealer_id'])) {
                        $this->_FinanceIncomeId = $Value['v'];
                        $this->_edit_dealer($Value, $Value['dealer_id']);
                    }
                    array_push($Where, $Value['v']);
                }
                if ($this->finance_income_model->delete($Where)) {
                    $this->Message = '进账删除成功!';
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '进账删除失败';
                }
                $this->_trans_end();
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要删除的财务进账不存在!';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 编辑客户财务状况
     * @param $Post
     * @param $Where
     * @param bool $Remark
     * @return bool
     */
    private function _edit_dealer ($Post, $Where, $Remark = true) {
        if ($Post['corresponding'] == 0) {
            return true;
        }
        if (empty($this->_Dealer) || $Where != $this->_Dealer['v']) {
            $this->_read_dealer($Where);
        }
        $Data = array(
            'balance' => bcadd($this->_Dealer['balance'], $Post['corresponding'], 2),
            'received' => bcadd($this->_Dealer['received'], $Post['corresponding'], 2),
            'virtual_balance' => bcadd($this->_Dealer['virtual_balance'], $Post['corresponding'], 2),
            'virtual_received' => bcadd($this->_Dealer['virtual_received'], $Post['corresponding'], 2)
        );
        $this->load->model('dealer/dealer_model');
        if (!!($this->dealer_model->update($Data, $Where))) {
            if ($Remark) {
                $this->_Remark .= '[截止本次付款，账户余额为 ' . $Data['balance'] . ' 元]';
            }
            return $this->_add_dealer_account_book($Post['corresponding'], $Data['balance'], $Data['virtual_balance']);
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '客户进账金额修改失败';
        }
    }

    /**
     * 获取客户信息
     * @param $V
     * @return array
     */
    private function _read_dealer ($V) {
        $this->load->model('dealer/dealer_model');
        $this->_Dealer = $this->dealer_model->is_exist($V);
        return $this->_Dealer;
    }

    /**
     * 解析认领表单
     * @return array|bool|string
     */
    private function _parse_input_claim () {
        $Post = gh_escape($_POST);
        $this->_read_dealer($Post['dealer_id']);
        $Post['dealer'] = $this->_Dealer['unique_name'];
        $Post['inned'] = YES;
        if (isset($Post['order_num'])) {
            $this->_OrderNums =  $Post['order_num'];
            unset($Post['order_num']);
            $Post['remark'] .= ',' . implode(',', $this->_OrderNums);
        }
        if (($Post['amount'] == ZERO && $Post['corresponding'] > ZERO) || ($Post['amount'] < $Post['corresponding'])) {
            $this->_Announce = true;
            $this->_AnnounceMsg = $this->session->userdata('truename') . '认领了一笔财务账户进账为￥' . $Post['amount'] . '，客户' . $Post['dealer'] . '进账为￥' . $Post['corresponding'] . '的及时进账';
        }
        if (!($this->finance_income_model->is_un_claimed($Post['v']))) {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要认领的财务进账不存在!';
            $this->Code = EXIT_ERROR;
            return false;
        }
        $this->_Remark = $Post['remark'];
        return $Post;
    }
    /**
     * 认领金额
     */
    public function claim () {
        if ($this->_do_form_validation()) {
            if (!!($Post = $this->_parse_input_claim())) {
                $V = $Post['v'];
                unset($Post['v']);
                $this->_trans_start();
                if (isset($Post['dealer_id'])) {
                    $this->_FinanceIncomeId = $V;
                    $this->_set_category('', $Post['income_pay']);
                    $this->_edit_dealer($Post, $Post['dealer_id']);
                }
                if ($this->Code == EXIT_SUCCESS) {
                    $Post['remark'] = $this->_Remark;
                    if ($this->finance_income_model->update($Post, $V)) {
                        if (!empty($this->_OrderNums)) {
                            $this->_edit_order();
                        }
                        $this->Message = '认领成功!';
                    } else {
                        $this->Code = EXIT_ERROR;
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'认领失败!';
                    }
                }
                $this->_trans_end();
            }
        }
        $this->_ajax_return();
    }

    /**
     * 添加客户财务流水
     * @param $Amount
     * @param $Balance
     * @param $VirtualBalance
     * @param string $Remark
     * @return bool
     */
    private function _add_dealer_account_book ($Amount, $Balance, $VirtualBalance, $Remark = '') {
        $this->load->model('dealer/dealer_account_book_model');
        if (!empty($this->_OrderNums)) {
            $Remark = $Remark . implode(',', $this->_OrderNums);
        }
        $Data = array(
            'flow_num' => date('YmdHis' . join('', explode('.', microtime(true)))),
            'dealer_id' => $this->_Dealer['v'],
            'in' => $Amount > 0,
            'amount' => $Amount,
            'title' => '进账',
            'category' => $this->_Category,
            'source_id' => $this->_FinanceIncomeId,
            'balance' => $Balance,
            'remark' => $Remark,
            'virtual_amount' => $Amount,
            'virtual_balance' => $VirtualBalance,
            'inside' => NO,
            'source_status' => O_MINUS
        );
        if (!!($this->_DealerAccountBookId = $this->dealer_account_book_model->insert($Data))) {
            return true;
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '新增客户流水失败!';
            return false;
        }
    }

    /**
     * 编辑客户账单流水
     * @return bool
     */
    private function _edit_dealer_account_book () {
        $Data = array(
            'source_id' => $this->_FinanceIncomeId,
        );
        if (!!($this->dealer_account_book_model->update($Data, $this->_DealerAccountBookId))) {
            return true;
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '编辑客户流水失败!';
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
            } elseif ($IncomePay == '平账') {
                $this->_Category = $this->config->item('dabc_discount');
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
                if ($this->_Announce) {
                    $this->_announce_to_finance();
                }
            } else {
                $this->finance_income_model->trans_rollback();
            }
        }
    }

    /**
     * 向财务发送钉钉消息
     * @return bool|string
     */
    private function _announce_to_finance () {
        if (!!($User = $this->_finance())) {
            require_once APPPATH . 'third_party/eapp/send_finance.php';
            return send(array("msgtype" => 'text', 'text' => array(
                'content' => $this->_AnnounceMsg
            )), $User);
        } else {
            return '没有财务账户可以发送';
        }
    }
    /**
     * 获取财务人员信息
     * @return array|bool
     */
    private function _finance() {
        $this->load->model('permission/usergroup_model');
        $this->load->model('manage/user_model');
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('财务管理员'))) {
            $this->get_page_search();
            $this->_Search['usergroup_v'] = $UsergroupV;
            $Data = array();
            if(!($Data = $this->user_model->select($this->_Search))){
                log_message('debug', 'dddddddd' . '没有user');
                return false;
            } else {
                $User = array();
                foreach ($Data['content'] as $Key => $Value) {
                    if (!empty($Value['user_id'])) {
                        array_push($User, $Value['user_id']);
                    }
                }
                if (!empty($User)) {
                    return $User;
                }
                log_message('debug', 'dddddddd' . '没有user_id');
                return false;
            }
        } else {
            log_message('debug', 'dddddddd' . '没有usergroup');
            return false;
        }
    }

    /**
     * 编辑订单支付状态
     * @return bool
     */
    private function _edit_order () {
        $this->load->model('order/order_datetime_model');
        $this->_OrderNums = gh_escape($this->_OrderNums);
        return  $this->order_datetime_model->update_order_payed($this->_OrderNums);
    }
}
