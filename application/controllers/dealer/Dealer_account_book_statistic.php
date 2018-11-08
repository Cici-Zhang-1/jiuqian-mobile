<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dealer account book Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Dealer_account_book_statistic extends MY_Controller {
    private $__Search = array(
        'dealer_id' => ZERO,
        'start_date' => '',
        'end_date' => '',
        'unique' => NO,
        'paging' => NO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller dealer/Dealer_account_book_statistic __construct Start!');
        $this->load->model('dealer/dealer_account_book_model');
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
        if (empty($this->_Search['dealer_id'])) {
            $this->_Search['dealer_id'] = $this->input->get('v', true);
            $this->_Search['dealer_id'] = intval(trim($this->_Search['dealer_id']));
        }
        $Data = array();
        if (!empty($this->_Search['dealer_id'])) {
            if(!($Data = $this->dealer_account_book_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            } else {
                $In = 0;
                $Out = 0;
                $OutOrder = array();
                if ($this->_Search['unique']) {
                    $UniqueWaitSure = array();
                    $UniqueWaitDelivery = array();
                    foreach ($Data['content'] as $Key => $Value) {
                        if ($Value['inside'] == YES) { // 去掉内部加款的项目
                            // unset($Data['content'][$Key]);
                            continue;
                        }
                        if ($Value['in'] == NO) { // 扣款项
                            if ($Value['source_status'] == O_WAIT_SURE) {
                                if (in_array($Value['source_id'], $UniqueWaitSure)) {
                                    // unset($Data['content'][$Key]);
                                    continue;
                                } else {
                                    array_push($UniqueWaitSure, $Value['source_id']);
                                }
                            } elseif ($Value['source_status'] == O_WAIT_DELIVERY) {
                                if (in_array($Value['source_id'], $UniqueWaitDelivery)) {
                                    // unset($Data['content'][$Key]);
                                    continue;
                                } else {
                                    array_push($UniqueWaitDelivery, $Value['source_id']);
                                }
                            }
                            if (!in_array($Value['title'], $OutOrder)) {
                                array_push($OutOrder, $Value['title']);
                            }
                            $Out += $Value['amount'];
                        } else {
                            $In += $Value['amount'];
                        }
                    }
                } else {
                    foreach ($Data['content'] as $Key => $Value) {
                        if ($Value['in'] == NO) { // 扣款项
                            if (!in_array($Value['title'], $OutOrder)) {
                                array_push($OutOrder, $Value['title']);
                            }
                            $Out += $Value['amount'];
                        } else {
                            $In += $Value['amount'];
                        }
                    }
                }
                $Data['content'] = array();
                array_push($Data['content'], array(
                    'order' => implode('<br />', $OutOrder),
                    'out' => $Out,
                    'in' => $In
                ));
                $Data['num'] = ONE;
            }
        } else {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'请选择客户后查看客户账单';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
}
