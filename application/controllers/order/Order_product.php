<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 2017/10/17
 * Time: 11:06
 *
 * Desc:
 */
class Order_product extends MY_Controller {
    private $_Module;
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $Count;
    private $Insert;
    private $Search = array(
        'status' => '',
        'start_date' => '',
        'end_date' => '',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

        log_message('debug', 'Controller Order/Order_product __construct Start!');
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

    public function read_wait_position() {
        $this->_Item = $this->_Item.__FUNCTION__;

        $Data = array();
        if(!($Query = $this->order_product_model->select_wait_position())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有等待入库订单！';
        }else{
            $Data['content'] = $Query;
        }
        $this->_return($Data);
    }

}
