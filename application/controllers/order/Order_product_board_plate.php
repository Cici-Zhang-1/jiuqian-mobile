<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月4日
 * @author Zhangcc
 * @version
 * @des
 * 板块
 */
class Order_product_board_plate extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    
    private $_Id;
    private $_Product;

    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_board_plate_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        
        log_message('debug', 'Controller Order/Order_product_board_plate Start !');
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
            switch ($this->_Product){
                case 'w':
                    $Data['CabinetStruct'] = $this->d_dismantle->read_detail('cabinet_struct', $this->_Id);
                    $Data['BoardPlate'] = $this->d_dismantle->read_detail('board_plate', $this->_Id);
                    break;
                case 'y':
                    $Data['WardrobeStruct'] = $this->d_dismantle->read_detail('wardrobe_struct', $this->_Id);
                    $Data['BoardPlate'] = $this->d_dismantle->read_detail('board_plate', $this->_Id);
                    break;
            }
            $this->load->view($Item, $Data);
        }else{
            $this->close_tab('您访问的内容不存在!');
        }
    }

    private function _label() {
        $Item = $this->_Item.__FUNCTION__;
        $this->load->view('header2');
        $this->load->view($Item);
    }
    /**
     * 打印板块标签
     */
    public function label() {
        $OrderProductNum = $this->input->get('order_product_num', true);
        $OrderProductNum = trim($OrderProductNum);
        $OrderProductNum = strtoupper($OrderProductNum);

        $Data = array();
        $Length = ORDER_PREFIX + ORDER_SUFFIX;
        if(preg_match("/^(X|B)[\d]{{$Length},{$Length}}\-[A-Z][\d]{1,10}$/", $OrderProductNum)){
            $Item = $this->_Item.__FUNCTION__;
            $this->load->model('order/order_product_board_plate_model');
            if(!!($Query = $this->order_product_board_plate_model->select_label($OrderProductNum))){
                foreach ($Query as $key => $value){
                    $Query[$key]['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                    $Query[$key]['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                }
                $Data['Data'] = $Query;
                unset($Query);
                $this->load->view('header2');
                $this->load->view($Item,$Data);
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要打印的板块分类订单';
                gh_alert_back($this->Failue);
            }
        }else{
            gh_alert_back('您选择分类和填写正确的订单编号!');
        }
    }
}
