<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Print drawing Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Print_image extends MY_Controller {
    private $__Search = array(
        'activity_id' => ZERO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller drawing/Print_image __construct Start!');
        $this->load->model('activity/activity_model');
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['activity_id'])) {
            $this->_Search['activity_id'] = $this->input->get('v', true);
            $this->_Search['activity_id'] = intval($this->_Search['activity_id']);
        }
        $Data = array();
        if (empty($this->_Search['activity_id'])) {
            $this->Message = '请选择活动后查看图纸';
            $this->Code = EXIT_ERROR;
        } else {
            if(!($Data = $this->activity_model->select_image($this->_Search))) {
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            } else {
                foreach ($Data['content'] as $Key => $Value) {
                    $Value['url'] = drawing_url($Value['image']);
                    $Data['content'][$Key] = $Value;
                }
            }
            if (!empty($this->_Search['activity_id'])) {
                $Data['query']['activity_id'] = $this->_Search['activity_id'];
            }
        }
        $this->_ajax_return($Data);
    }
}
