<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Electronic saw Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Electronic_saw_statistic extends MY_Controller {
    private $__Search = array(
        'distribution' => 0,
        'start_date' => '',
        'end_date' => '',
        'status' => M_ELECTRONIC_SAWED
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Electronic_saw_statistic __construct Start!');
        $this->load->model('order/mrp_model');
    }

    public function read () {
        $this->__Search['paging'] = 0;
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['distribution'])) {
            if ($this->_is_electronic_saw()) {
                $this->_Search['distribution'] = $this->session->userdata('uid');
            }
        }
        if ($this->_Search['start_date'] == '') {
            $this->_Search['start_date'] = date('Y-m-01');
        }
        if ($this->_Search['status'] == M_ELECTRONIC_SAW) {
            $this->_Search['start_date'] = '';
            $this->_Search['end_date'] = '';
        }
        $Data = array();
        if(!($Data = $this->mrp_model->select_electronic_saw($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $BatchNum = array();
            $Num = 0;
            $Count = 1;
            foreach ($Data['content'] as $Key => $Value) {
                $Num += $Value['num'];
                if (!in_array($Value['batch_num'], $BatchNum)) {
                    array_push($BatchNum, $Value['batch_num']);
                    if ($Count++ > 5) {
                        array_push($BatchNum, '<br />');
                        $Count = 1;
                    }
                }
            }
            $Data['content'] = array(
                array(
                    'label' => '批次号',
                    'name' => implode(',', $BatchNum)
                ),
                array(
                    'label' => '板材数',
                    'name' => $Num
                )
            );
            $Data['num'] = count($Data['content']);
            $Data['p'] = ONE;
            $Data['pn'] = ONE;
            $Data['pagesize'] =ALL_PAGESIZE;
        }
        $this->_ajax_return($Data);
    }

    public function electronic_sawed () {
        $Days = $this->input->get('days', true);
        if (empty($Days)) {
            $Days = date('Y-m-d');
        } else {
            $Days = '-' . $Days . ' days';
            $Days = date('Y-m-d', strtotime($Days));
        }
        $Data = array('list' => array());
        if (!!($Query = $this->mrp_model->select_electronic_sawed($Days))) {
            $Tmp = array();
            foreach ($Query as $Key => $Value) {
                if (!isset($Tmp[$Value['saw']])) {
                    $Tmp[$Value['saw']] = array(
                        'thick' => 0,
                        'thin' => 0
                    );
                }
                if ($Value['thick'] > THICK) {
                    $Tmp[$Value['saw']]['thick'] += $Value['num'];
                } else {
                    $Tmp[$Value['saw']]['thin'] += $Value['num'];
                }
            }
            foreach ($Tmp as $Key => $Value) {
                $Data['list'][] = array(
                    'name' => $Key,
                    'value' => $Value['thick'] . '/' . $Value['thin']
                );
            }
        }
        $this->_ajax_return($Data);
    }

    /**
     * 判断此人是否是电子锯组成员
     * @return bool
     */
    private function _is_electronic_saw() {
        $this->load->model('permission/usergroup_model');
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('电子锯'))) {
            return $this->session->userdata('ugid') == $UsergroupV;
        } else {
            return false;
        }
    }
}
