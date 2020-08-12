<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Finance account Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Finance_account extends MY_Controller {
    private $__Search = array(
        'paging' => NO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller finance/Finance_account __construct Start!');
        $this->load->model('finance/finance_account_model');
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
        if(!($Data = $this->finance_account_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $Statistics = array(
                'v' => '0',
                'name' => '统计',
                'host' => '',
                'account' => '',
                'balance' => 0,
                'in' => 0,
                'in_fee' => 0,
                'out' => 0,
                'out_fee' => 0,
                'fee' => 0,
                'fee_max' => 0,
                'intime' => 0
            );
            foreach ($Data['content'] as $key => $value){
                $Statistics['balance'] = bcadd($Statistics['balance'], $value['balance'],2 );
                $Statistics['in'] = bcadd($Statistics['in'], $value['in'],2 );
                $Statistics['in_fee'] = bcadd($Statistics['in_fee'], $value['in_fee'],2 );
                $Statistics['out'] = bcadd($Statistics['out'], $value['out'],2 );
                $Statistics['out_fee'] = bcadd($Statistics['out_fee'], $value['out_fee'],2 );
                /*$Statistics['balance'] += $value['balance'];
                $Statistics['in'] += $value['in'];
                $Statistics['in_fee'] += $value['in_fee'];
                $Statistics['out'] += $value['out'];
                $Statistics['out_fee'] += $value['out_fee'];*/
            }
            array_push($Data['content'], $Statistics);
            $Data['num'] += ONE;
            unset($Statistics);
        }
        $this->_ajax_return($Data);
    }

    private function _read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->finance_account_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    public function intime () {
        $this->__Search['intime'] = YES;
        $this->_read();
    }

    public function outtime () {
        $this->__Search['intime'] = NO;
        $this->_read();
    }

    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if(!!($NewId = $this->finance_account_model->insert($Post))) {
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    /**
    *
    * @return void
    */
    public function edit() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->finance_account_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
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
            if ($this->finance_account_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
