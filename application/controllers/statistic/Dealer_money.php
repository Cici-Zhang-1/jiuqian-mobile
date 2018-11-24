<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月24日
 * @author Zhangcc
 * @version
 * @des
 * 经销商欠款
 */
class Dealer_money extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('dealer/dealer_model');
        log_message('debug', 'Controller Dealer/Dealer_money Start!');
    }

    public function read(){
        $this->get_page_search();
        $Data = array();
        if (!($Data = $this->dealer_model->select_dealer_money($this->_Search))) {
            $this->Code = EXIT_ERROR;
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
        } else {
            $Sum = 0;
            $VirtualSum = 0;
            foreach ($Data['content'] as $Key => $Value) {
                $Sum += $Value['balance'];
                $VirtualSum += $Value['virtual_balance'];
            }
            array_unshift($Data['content'], array(
                'v' => ZERO,
                'dealer_id' => ZERO,
                'dealer' => '---',
                'balance' => $Sum,
                'virtual_balance' => $VirtualSum,
                'owner' => '总计'
            ));
            $Data['num']++;
        }
        $this->_ajax_return($Data);
    }
}