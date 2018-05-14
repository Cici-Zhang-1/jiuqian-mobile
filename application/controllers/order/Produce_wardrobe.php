<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月27日
 * @author Administrator
 * @version
 * @des
 * 橱柜柜体清单结算
 */
class Produce_wardrobe extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;
    private $_Cookie;
    
    private $Search = array(
        'product' => 2,
        'start_date' => '',   /*都是以确认时间为标准*/
        'end_date' => '',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
		$this->_Cookie = str_replace('/', '_', $this->_Item).'_';
		
		$this->load->model('order/order_product_board_model');

        log_message('debug', 'Controller Order/Produce_wardrobe Start !');
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
            if(!!($Data = $this->order_product_board_model->select_produce($this->Search))){
                /* $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p']; */
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
