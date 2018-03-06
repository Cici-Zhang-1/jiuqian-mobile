<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月23日
 * @author Zhangcc
 * @version
 * @des
 * 我的拆单(未拆的、正在拆的、已经拆单、已经删的)
 */
class My_dismantle extends CWDMS_Controller{
    private $Module = 'order';
    private $_Controller;
    private $_Cookie;
    private $Count;
    private $InsertId;
    private $Search = array(
        'status' => '1',
        'product' => '1,2,3,4,5,6,7',
        'keyword' => ''
    );
    public function __construct(){
        log_message('debug', 'Controller Order/My_dismantle Start!');
        parent::__construct();
        $this->load->model('order/order_product_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->Module.'_'.$this->_Controller.'_'.$this->session->userdata('uid').'_';
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->Module.'/'.strtolower(__CLASS__).'/'.$View;
            $this->data['action'] = site_url($Item);
            $this->load->view($Item, $this->data);
        }
    }

    public function read(){
        $Cookie = $this->_Cookie.__FUNCTION__;
        $this->Search = $this->get_page_conditions($Cookie, $this->Search);
        $Data = array();
        if(!empty($this->Search)){
            if(!!($Data = $this->order_product_model->select_order_product_my_dismantle($this->Search))){
                $this->config->load('status');
                $Status = $this->config->item('library/workflow/order_product');
                foreach ($Data['content'] as $key => $value){
                    $Tmp = json_decode($value['remark'], true);
                    if(isset($Tmp[$this->Module.'_'.__CLASS__])){
                        $Data['content'][$key]['remark'] = $Tmp[$this->Module.'_'.__CLASS__];
                    }
                    unset($Tmp);
                    $Data['content'][$key]['status'] = $Status[$value['status']]['text'];
                }
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要拆单的订单';;
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
        /* $this->Search['status'] = $this->input->get('status', true);
        $this->Search['product'] = $this->input->get('product', true);
        $this->Search['pagesize'] = $this->input->get('pagesize', true);
        $this->Search['keyword'] = $this->input->get('keyword', true);
        $Cookie = $this->_Cookie.__FUNCTION__;
        if($this->Search['status'] !== false || $this->Search['product'] !== false
            ||$this->Search['pagesize'] !== false ||$this->Search['keyword'] !== false){

            if(!empty($this->Search['status'])){
                log_message('debug', 'Search My_dismantle From My_dismantle Status on '.$this->Search['status']);
                $this->Search['status'] = trim($this->Search['status']);
            }
            
            if(!empty($this->Search['product'])){
                log_message('debug', 'Search My_dismantle From My_dismantle Status on '.$this->Search['product']);
                $this->Search['product'] = trim($this->Search['product']);
            }
            	
            $this->Search['pagesize'] = intval(trim($this->Search['pagesize']));
            if($this->Search['pagesize'] < 100 || $this->Search['pagesize'] > 1000){
                $this->Search['pagesize'] = 100;
            }
            if(!empty($this->Search['keyword'])){
                log_message('debug', 'Search My_dismantle From My_dismantle Keyword on '.$this->Search['keyword']);
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
            if(!!($Data = $this->order_product_model->select_order_product_my_dismantle($this->Search))){
                $this->config->load('status');
                $Status = $this->config->item('library/workflow/order_product');
                foreach ($Data['content'] as $key => $value){
                    $Tmp = json_decode($value['remark'], true);
                    if(isset($Tmp[$this->Module.'_'.__CLASS__])){
                        $Data['content'][$key]['remark'] = $Tmp[$this->Module.'_'.__CLASS__];
                    }
                    unset($Tmp);
                    $Data['content'][$key]['status'] = $Status[$value['status']]['text'];
                }
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要拆单的订单';;
            }
        }
        $this->_return($Data); */
    }
    public function edit_asure(){
        $Item = $this->Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false && is_array($Where) && count($Where) > 0){
                $this->workflow->action($Where, 'order_product', 1);
                $this->load->helper('file');
                delete_cache_files('(.*dismantle.*)');
                $this->Success = '确认成功';
            }else{
                $this->Failue .= '没有可确认项!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    
    public function remove(){
        $Item = $this->Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false && is_array($Where) && count($Where) > 0){
                $this->workflow->action($Where, 'order_product', 2);
                $this->load->helper('file');
                delete_cache_files('(.*dismantle.*)');
                $this->Success = '作废成功';
            }else{
                $this->Failue .= '没有可作废项!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }

    private function _default($name, $tmp=''){
        static $Did = false;
        switch ($name){
            case 'creator':
                $Return = $this->session->userdata('uid');
                break;
            case 'create_datetime':
                $Return = time();
                break;
            default:
                $Return = $tmp;
        }
        return $Return;
    }
}