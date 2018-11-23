<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/17
 * Time: 10:20
 */
class Overdue extends MY_Controller{
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Overdue Start!');
        $this->load->model('order/order_model');
    }

    public function read(){
        $this->get_page_search();
        $Data = array();
        if (!($Data = $this->order_model->select_overdue($this->_Search))) {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            foreach ($Data['content'] as $Key => $Value) {
                $Data['content'][$Key]['days'] = $this->_diff(date('Y-m-d'), $Value['inned_datetime']);
            }
        }
        $this->_ajax_return($Data);
    }

    private function _diff ($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ceil(($second1 - $second2) / 86400);
    }
}