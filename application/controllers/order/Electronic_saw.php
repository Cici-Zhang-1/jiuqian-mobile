<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Electronic saw Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Electronic_saw extends MY_Controller {
    private $__Search = array(
        'distribution' => ZERO,
        'start_date' => '',
        'end_date' => '',
        'status' => M_ELECTRONIC_SAW
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Electronic_saw __construct Start!');
        $this->load->model('order/mrp_model');
    }

    /**
    *
    * @return void
    */
    public function index() {
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_' . $View)){
            $View = '_' . $View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['distribution'])) {
            if ($this->_is_electronic_saw()) {
                $this->_Search['distribution'] = $this->session->userdata('uid');
            }
        }
        $Data = array();
        if(!($Data = $this->mrp_model->select_electronic_saw($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    /**
    *
    * @return void
    */
    public function edit() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if (!!($ElectronicSaw = $this->mrp_model->is_status_and_brothers($Post['v'], M_ELECTRONIC_SAW))) {
                foreach ($ElectronicSaw as $Key => $Value) {
                    $ElectronicSaw[$Key] = $Value['v'];
                }
                $this->load->library('workflow/workflow');
                $W = $this->workflow->initialize('mrp');
                if(!!($W->initialize($ElectronicSaw))){
                    $W->electronic_sawed();
                    $this->Message = '确认下料成功, 刷新后生效!';
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您选择确认的已经确认下料，不能重复确认';
            }
        }
        $this->_ajax_return();
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
