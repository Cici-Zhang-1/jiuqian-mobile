<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月24日
 * @author Administrator
 * @version
 * @des
 * 
 * 下单排行榜
 */
class Order_sorter extends CWDMS_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $Count;
    private $Insert;
    private $Search = array(
        'start_date' => '',
        'end_date' => '',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

        log_message('debug', 'Controller Order_sorter/Order_sorter __construct Start!');
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
            if(empty($this->Search['start_date']) && empty($this->Search['end_date'])){
                $this->Search['start_date'] = date('Y-m-d', time()-MONTHS);
            }elseif (empty($this->Search['start_date']) && !empty($this->Search['end_date'])){
                $this->Search['start_date'] = date('Y-m-d', gh_to_sec($this->Search['end_date']) - MONTHS);
            }
            if(!!($Return = $this->order_model->select_order_sorter($this->Search))){
                $Tmp = array();
                $K = 1;
                foreach ($Return as $key => $value){
                    if($value['sum'] > 0 && !empty($value['sum_detail'])){
                        $value = array_merge($value, json_decode($value['sum_detail'], true));
                        unset($value['sum_detail']);
                        if(empty($Tmp[$value['did']])){
                            $Tmp[$value['did']] = $value;
                            $Tmp[$value['did']]['key'] = $K++;
                            $Tmp[$value['did']]['amount'] = 1;
                        }else{
                            $Tmp[$value['did']]['amount'] += 1;
                            $Tmp[$value['did']]['sum'] += $value['sum'];
                            $Tmp[$value['did']]['cabinet'] += $value['cabinet'];
                            $Tmp[$value['did']]['wardrobe'] += $value['wardrobe'];
                            $Tmp[$value['did']]['door'] += $value['door'];
                            $Tmp[$value['did']]['kuang'] += $value['kuang'];
                            $Tmp[$value['did']]['fitting'] += $value['fitting'];
                            $Tmp[$value['did']]['other'] += $value['other'];
                            $Tmp[$value['did']]['server'] += $value['server'];
                        }
                    }
                }
                $Data['content'] = $Tmp;
                unset($Tmp);
                unset($Return);
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的订单';;
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }
}