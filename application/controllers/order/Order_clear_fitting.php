<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月27日
 * @author Zhangcc
 * @version
 * @des
 * 清理配件
 */
class Order_clear_fitting extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $Count;
    private $Insert;
    private $Search = array(
        'start_date' => '',
        'end_date' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

        log_message('debug', 'Controller Order_clear_fitting/Order_clear_fitting __construct Start!');
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
            $this->load->model('order/order_product_model');
            $this->Search['end_date'] = date('Y-m-d', gh_to_sec($this->Search['start_date'])+DAYS);
            if(!!($Data = $this->order_product_model->select_clear_fitting($this->Search))){
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
                $this->Success = '获取要打印配件订单成功!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的配件订单!';
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }
    
    private function _edit(){
        $Id = $this->input->get('id', true);
        $Ids = explode(',', $Id);
        foreach ($Ids as $key => $value){
            $Ids[$key] = intval(trim($value));
            if($Ids[$key] <= 0){
                unset($Ids[$key]);
            }
        }
        if(count($Ids) > 0){
            $Cookie = $this->_Cookie.'read';
            $this->Search = $this->input->cookie($Cookie);
            $this->Search = json_decode($this->Search, TRUE);
            $Data['StartDate'] = $this->Search['start_date'];
            $this->load->model('order/order_product_fitting_model');
            if(!!($Return = $this->order_product_fitting_model->select_order_product_fitting_by_opid($Ids))){
                $Tmp = array();
                foreach ($Return as $key => $value){
                    if(!isset($Tmp[$value['order_product_num']])){
                        $Tmp[$value['order_product_num']] = array(
                            'dealer' => $value['dealer'],
                            'child' => array()
                        );
                    }
                    $Tmp[$value['order_product_num']]['child'][] = array(
                        'name' => $value['name'],
                        'amount' => $value['amount'],
                        'unit' => $value['unit'],
                        'remark' => $value['remark']
                    );
                }
                unset($Return);
                $Data['List'] = $Tmp;
                $this->load->view('header2');
                $this->load->view($this->_Item.__FUNCTION__, $Data);
            }else{
                show_error('您要打印的配件订单不存在!');
            }
        }else{
            show_error('请先选择要打印的配件订单!');
        }
        
    }
}
