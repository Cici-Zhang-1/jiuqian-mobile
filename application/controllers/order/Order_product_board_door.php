<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order product board door Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Order_product_board_door extends MY_Controller {
    private $__Search = array(
        'order_product_id' => ZERO
    );
    private $_Statistic = array(
        'total_open_hole' => ZERO,
        'total_invisibility' => ZERO,
        'total_area' => ZERO,
        'total_amount' => ZERO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Order_product_board_door __construct Start!');
        $this->load->model('order/order_product_board_door_model');
    }

    /**
    *
    * @return void
    */
    public function index() {
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read () {
        $this->_page_search();
        $Data = array();
        if (!empty($this->_Search['order_product_id'])) {
            if(!($Data = $this->order_product_board_door_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            }
            $Data['query']['order_product_id'] = $this->_Search['order_product_id'];
        } else {
            $this->Message = '请选择订单产品后查看门板信息';
            $this->Code = EXIT_ERROR;
        }

        $this->_ajax_return($Data);
    }

    private function _page_search () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['order_product_id'])) {
            $OrderProductId = $this->input->get('order_product_id', true);
            if (!empty($OrderProductId)) {
                $this->_Search['order_product_id'] = $OrderProductId;
            }
        }
        return $this->_Search;
    }

    public function prints () {
        $this->_page_search();
        $Data = array();
        if (!empty($this->_Search['order_product_id'])) {
            if(!($Data = $this->order_product_board_door_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            } else {
                $Data['content'] = $this->_combine($Data['content']);
                $Data['statistic'] = $this->_Statistic;
                $Data['struct'] = $this->_get_door_struct();
            }
            $Data['query']['order_product_id'] = $this->_Search['order_product_id'];
        } else {
            $this->Message = '请选择订单产品后打印板块信息';
            $this->Code = EXIT_ERROR;
        }

        $this->_ajax_return($Data);
    }

    private function _combine($BoardDoor) {
        $List = array();
        foreach ($BoardDoor as $key => $value){
            $value['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
            $value['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
            $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
            if ($value['area'] < MIN_M_AREA) {
                $value['area'] = MIN_M_AREA;
            }

            $Tmp2 = implode('^', array($value['board'], $value['width'], $value['length'],
                $value['punch'], $value['handle'],
                $value['open_hole'], $value['invisibility'], $value['remark']));

            if(isset($List[$Tmp2])){
                $List[$Tmp2]['area'] += $value['area'];
                $List[$Tmp2]['open_hole'] += $value['open_hole'];
                $List[$Tmp2]['invisibility'] += $value['invisibility'];
                $List[$Tmp2]['amount'] += 1;
            }else{
                $List[$Tmp2] = $value;
            }
            $this->_Statistic['total_area'] += $value['area'];
            $this->_Statistic['total_open_hole'] += $value['open_hole'];
            $this->_Statistic['total_invisibility'] += $value['invisibility'];
        }
        $this->_Statistic['total_amount'] = count($BoardDoor);
        ksort($List);
        $List = array_values($List);
        $List = $this->_divide_line($List);
        return $List;
    }

    private function _divide_line ($BoardPlate) {
        $Board = '';
        $List = array();
        if (is_array($BoardPlate)) {
            foreach ($BoardPlate as $Key => $Value) {
                if ($Board != $Value['board']) {
                    array_push($List, array('board' => $Value['board'], 'divide' => true));
                    $Board = $Value['board'];
                }
                $Value['key'] = $Key + 1;
                array_push($List, $Value);
            }
            return $List;
        } else {
            return $BoardPlate;
        }
    }

    private function _get_door_struct () {
        $this->load->model('order/order_product_door_model');
        return $this->order_product_door_model->select_one(array('order_product_id' => $this->_Search['order_product_id']));
    }
    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if(!!($NewId = $this->order_product_board_door_model->insert($Post))) {
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    /**
    *
    * @return void
    */
    public function edit() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->order_product_board_door_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
    }

    /**
     *
     * @param  int $id
     * @return void
     */
    public function remove() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->order_product_board_door_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
