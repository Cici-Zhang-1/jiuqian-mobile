<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月22日
 * @author Zhangcc
 * @version
 * @des
 * 经销商跟踪
 */
class Dealer_trace extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;

    private $_Search = array(
        'id' => '',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('dealer/dealer_trace_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';

        log_message('debug', 'Controller Dealer/Dealer_trace Start!');
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
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        $Data = array('Id' => $Id);
        if($Id > 0){
            if(!($Data['Trace'] = $this->dealer_trace_model->select($Id))){
                $Data['Error'] = '您要访问的经销商没有跟踪信息';
            }
        }else{
            $Data['Error'] = '您要访问的经销商不存在';
        }
        $this->load->view('dealer/dealer_trace/_read', $Data);
    }

    public function add(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($Id = $this->dealer_trace_model->insert($Post))){
                $this->Success .= '经销商跟踪新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商跟踪新增失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
