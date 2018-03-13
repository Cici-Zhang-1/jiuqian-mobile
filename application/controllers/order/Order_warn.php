<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月7日
 * @author Administrator
 * @version
 * @des
 */
class Order_warn extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $Count;
    private $Insert;
    private $Search = array(
        'area' => '',
        'days' => 3,
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

        log_message('debug', 'Controller Order/Order __construct Start!');
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
            $this->Search['days'] = intval($this->Search['days']);
            if($this->Search['days'] <= 0){
                $this->Search['days'] = 3;
            }
            $this->Search['end_date'] = date('Y-m-d', strtotime(sprintf('+%d days', $this->Search['days'])));
            
            if(!!($Data = $this->order_model->select_order_warn($this->Search))){
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
                $this->Success = '获得订单预警成功';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的预警订单';
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的预警订单!';
        }
        $this->_return($Data);
    }
}
