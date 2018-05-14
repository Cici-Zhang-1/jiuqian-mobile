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
    private $_Module;
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $_InsertId;

    public function __construct(){
        parent::__construct();
        $this->load->model('dealer/dealer_model');
        $this->load->model('dealer/dealer_linker_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

        log_message('debug', 'Controller Dealer/dealer Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view($Item, $Data);
        }
    }

    private function _read(){
        if(!!($Data = $this->dealer_model->select_dealer_money())){
            $this->load->view($this->_Item.__FUNCTION__, $Data);
        }else{
            show_error('获取经销商欠款失败!');
        }
    }
}
