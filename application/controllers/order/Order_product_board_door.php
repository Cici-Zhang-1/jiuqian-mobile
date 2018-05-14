<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月14日
 * @author Zhangcc
 * @version
 * @des
 * 门板清单
 */
class Order_product_board_door extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;

    private $_Id;
    private $_Product;

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Order_product_board_door Start !');
        $this->load->model('order/order_product_board_door_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
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

    private function _read(){
        $this->_Product = $this->uri->segment(5, false);
        $this->_Product = trim($this->_Product);
        $this->_Id = $this->input->get('id', true);
        $this->_Id = intval(trim($this->_Id));

        if($this->_Id && $this->_Product){
            $Item = $this->_Item.__FUNCTION__.'_'.$this->_Product;
            $Data = array(
                'Id' => $this->_Id,
                'Product' => $this->_Product
            );
            $this->load->library('d_dismantle');
            $Data['BoardDoor'] = $this->d_dismantle->read_detail('board_door', $this->_Id);
            $this->load->view($Item, $Data);
        }else{
            $this->close_tab('您访问的内容不存在!');
        }
    }
}
