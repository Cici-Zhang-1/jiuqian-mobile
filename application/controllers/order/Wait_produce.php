<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月23日
 * @author Zhangcc
 * @version
 * @des
 * 等待生产
 */
class Wait_produce extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item ;
    private $_Cookie;
    private $Count;
    private $InsertId;
    private $Search = array(
        'status' => '',
        'keyword' => ''
    );
    public function __construct(){
        log_message('debug', 'Controller Order/Wait_produce Start!');
        parent::__construct();
        $this->load->model('order/order_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_'.$this->session->userdata('uid').'_';
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $this->data['action'] = site_url($Item);
            $this->load->view($Item, $this->data);
        }
    }

    public function read(){
        $Cookie = $this->_Cookie.__FUNCTION__;
        $this->Search = $this->get_page_conditions($Cookie, $this->Search);
        $Data = array();
        if(!empty($this->Search)){
            if(!!($Data = $this->order_model->select_order_wait_produce($this->Search))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要核价的订单';;
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
        /* $Cookie = $this->_Cookie.__FUNCTION__;
        $this->Search['status'] = $this->input->get('status', true);
        $this->Search['pagesize'] = $this->input->get('pagesize', true);
        $this->Search['keyword'] = $this->input->get('keyword', true);
        if($this->Search['status'] !== false
            ||$this->Search['pagesize'] !== false ||$this->Search['keyword'] !== false){

            if(!empty($this->Search['status'])){
                log_message('debug', 'Search Wait_produce From Wait_produce Status on '.$this->Search['status']);
                $this->Search['status'] = trim($this->Search['status']);
            }
             
            $this->Search['pagesize'] = intval(trim($this->Search['pagesize']));
            if($this->Search['pagesize'] < 100 || $this->Search['pagesize'] > 1000){
                $this->Search['pagesize'] = 100;
            }
            if(!empty($this->Search['keyword'])){
                log_message('debug', 'Search Wait_produce From Wait_produce Keyword on '.$this->Search['keyword']);
                $this->Search['keyword'] = trim($this->Search['keyword']);
            }
            $this->Search['p'] = $this->input->get('p', true);
            if($this->Search['p'] !== false){
                $this->Search['p'] = $this->Search['p'];
            }else{
                $this->Search['p'] = 1;
            }
            $this->Search['pn'] = 0;
            $New = true;
        }else{
            $P = $this->input->get('p', true);
            $Page = $this->input->get('page', true);
            $Table = $this->input->get('table', true);
            if($P != false){
                $P = intval(trim($P));
                if((!!($Cookies = $this->input->cookie($Cookie, true)) 
                    && !!($Condition = json_decode($Cookies, true)))
		            || ($Page != false && !!($Cookies = $this->input->cookie($Page, true)) 
		            && !!($Cookies = json_decode($Cookies, true)) 
		            && isset($Cookies[$Table]) 
	                && !!($Condition = $Cookies[$Table]))){
                    unset($Cookies);
                    $this->Search = array_merge($this->Search, $Condition);
                    $this->Search['p'] = $P;
                    if($this->Search['p'] < 1){
                        $this->Search['p'] = 1;
                    }elseif (!empty($this->Search['pn']) && $this->Search['p'] > $this->Search['pn']){
                        $this->Search['p'] = $this->Search['pn'];
                    }
                    if($this->Search['pagesize'] < 100 || $this->Search['pagesize'] > 1000){
                        $this->Search['pagesize'] = 100;
                    }
                    $New = false;
                }else{
                    $this->Search = array(
                        'status' => 1,
                        'product' => 1,
                        'pagesize' => 100,
                        'p' => 1,
                        'pn' => 0
                    );
                    $New = true;
                }
            }else{
                $this->Failue = '对不起, 您访问的内容不存在!';
            }
        }
        $Data = array();
        if(empty($this->Failue)){
            if(!!($Data = $this->order_model->select_order_wait_produce($this->Search))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要核价的订单';;
            }
        }
        $this->_return($Data); */
    }

    /**
     * 废止
     */
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false && is_array($Where) && count($Where) > 0){
                $this->workflow->action($Where, 'order', 2);
                $this->Success = '作废成功';
            }else{
                $this->Failue .= '没有可作废项!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
