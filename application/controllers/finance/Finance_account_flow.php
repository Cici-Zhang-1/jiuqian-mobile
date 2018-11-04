<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/1
 * Time: 13:10
 * Des: 财务账户流水
 */
class Finance_account_flow extends MY_Controller {
    private $__Search = array(
        'finance_account_id' => ZERO,
        'start_date' => '',
        'end_date' => ''
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller Finance/Finance_account_flow __construct Start!');
        $this->load->model('finance/finance_account_flow_model');
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['finance_account_id'])) {
            $this->_Search['finance_account_id'] = $this->input->get('v', true);
            $this->_Search['finance_account_id'] = intval(trim($this->_Search['finance_account_id']));
        }
        $Data = array();
        if (!empty($this->_Search['finance_account_id'])) {
            if(!($Data = $this->finance_account_flow_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            } else {
                foreach ($Data['content'] as $Key => $Value) {
                    $Data['content'][$Key]['type'] = $Data['content'][$Key]['type'] == YES ? '<i class="fa fa-plus text-success"></i>' : '<i class="fa fa-minus"></i>';
                }
            }
        } else {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'请选择账户后查看账户流水';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
}