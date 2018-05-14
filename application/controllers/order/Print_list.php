<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author Zhangcc
 * @version
 * @des
 * 打印清单
 */
class Print_list extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item ;
    private $_Cookie;
    private $Search = array(
        'product' => '1,2,3,4',
        'print' => 'print',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
        
        log_message('debug', 'Controller Order/Print_list Start!');
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
            if(!!($Data = $this->order_product_model->select_print_list($this->Search))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要打印的清单';
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }
}
