<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dealer track Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Dealer_track extends MY_Controller {
    private $__Search = array(
        'dealer_id' => 0,
        'shop_id' => 0
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller dealer/Dealer_track __construct Start!');
        $this->load->model('dealer/dealer_track_model');
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
        $V = $this->input->get('v');
        $V = intval($V);
        if (!empty($this->_Search['dealer_id'])) {
            if (!empty($V)) {
                $this->_Search['shop_id'] = $V;
            }
        } else {
            if (!empty($V)) {
                $this->_Search['dealer_id'] = $V;
            }
        }
        $Data = array();
        if(!($Data = $this->dealer_track_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $Data['query'] = array(
            'dealer_id' => $this->_Search['dealer_id'],
            'shop_id' => $this->_Search['shop_id']
        );
        $this->_ajax_return($Data);
    }

    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if(!!($NewId = $this->dealer_track_model->insert($Post))) {
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }
}
