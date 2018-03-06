<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月16日
 * @author Administrator
 * @version
 * @des
 * 电子锯
 */
class Table_saw extends CWDMS_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item ;
    private $_Cookie;
    private $_Classify;

    private $Search = array(
        'status' => '3',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_classify_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';

        log_message('debug', 'Controller Order/Talble_saw Start!');
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

    public function read(){
        $Cookie = $this->_Cookie.__FUNCTION__;
        $this->Search = $this->get_page_conditions($Cookie, $this->Search);
        $Data = array();
        if(!empty($this->Search)){
            if(!!($Data = $this->order_product_classify_model->select($this->Search))){
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要报价的订单';
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }


    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Selected = $this->input->post('selected', true);
            $this->load->library('workflow/workflow');
            foreach ($Selected as $key => $value){
                $this->workflow->initialize('order_product_classify', $value);
                $this->workflow->table_saw();
            }
        }else{
            $this->Failue = validation_errors();
        }
        $this->_return();
    }
}