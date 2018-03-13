<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月13日
 * @author Zhangcc
 * @version
 * @des
 */
class Wait_dismantle extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    private $_Cookie;
    private $Count;
    private $InsertId;
    private $Search = array(
        'status' => '1',
        'product' => '1,2,3,4,5,6,7',
        'public' => '0',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_'.$this->session->userdata('uid').'_';
        log_message('debug', 'Controller Order/Wait_dismantle Start!');
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
            if(!!($Data = $this->order_product_model->select_wait_dismantle($this->Search))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要拆单的订单';
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }
    
    /**
     * 确认拆单
     */
    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            $this->load->library('workflow/workflow');
            if(!!($Return = $this->order_product_model->is_dismantled($Where))){
                $Disposed = array();  /*防止有些没有拆单就点击确认*/
                foreach ($Return as $key => $value){
                    if(!in_array($value['opid'], $Disposed)){
                        if(!!($this->workflow->initialize('order_product',$value['opid']))){
                            $this->workflow->dismantled();
                            $Disposed[] = $value['opid'];
                        }else{
                            $this->Failue = $this->workflow->get_failue();
                            break;
                        }
                    }
                }
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要确认拆单的订单产品';
            }
            if(empty($this->Failue)){
                $this->Success .= '订单产品确认拆单成功!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }

    /**
     * 废置拆单
     */
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false && is_array($Where) && count($Where) > 0){
                $this->load->library('workflow/workflow');
                if(!!($this->workflow->initialize('order_product',$Where))){
                    $this->workflow->remove_order_product();
                    $this->Success .= '订单产品作废成功!';
                }else{
                    $this->Failue = $this->workflow->get_failue();
                }
            }else{
                $this->Failue .= '没有可作废项!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
