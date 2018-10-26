<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Warehouse Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Warehouse extends MY_Controller {
    private $__Search = array(
        'paging' => 0,
        'status' => ''
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller warehouse/Warehouse __construct Start!');
        $this->load->model('warehouse/warehouse_model');
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
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->warehouse_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    /**
     * 库位推荐
     */
    public function recommend () {
        $Recommend = $this->input->get('recommend');
        $Recommend = strtoupper($Recommend);
        $Data = array();
        $this->load->model('order/order_product_model');
        if (preg_match(REG_RECOMMEND, $Recommend, $Matches)
            && !!($OrderProduct = $this->order_product_model->is_exist($Matches[1]))) { // 同一订单编号下的库位
            $this->load->model('order/order_product_board_model');
            if (!($Area = $this->order_product_board_model->select_area_by_order_v($OrderProduct['order_v']))) {   // 获取板材面积
                $Area = $this->order_product_board_model->select_area_by_order_v($OrderProduct['order_v'], false);
            }
            if ($Area) {
                if (empty($OrderProduct['warehouse_v'])) { // 如果这个订单之前没有入库，则直接推荐库位
                    $Data['content'] = $this->_compute_warehouse($Area);
                    $Data['num'] = count($Data['content']);
                } else {
                    $OrderProduct['warehouse_v'] = json_decode($OrderProduct['warehouse_v'], true);
                    $WarehouseV = array();
                    foreach ($OrderProduct['warehouse_v'] as $Key => $Value) {
                        $OrderProduct['warehouse_v'][$Key]['used'] = true;
                        array_push($WarehouseV, $Value['v']);
                    }
                    $MaxArea = $this->warehouse_model->select_sum_max_area($WarehouseV);
                    if ($MaxArea < $Area) {
                        $OrderProduct['warehouse_v'] = array_merge($OrderProduct['warehouse_v'], $this->_compute_warehouse($Area - $MaxArea));
                    }
                    $Data['content'] = $OrderProduct['warehouse_v'];
                    $Data['num'] = count($Data['content']);
                }
                $Data['p'] = $Data['pn'] = ONE;
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '这个订单缺少板材面积信息，无法推荐库位';
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '信息不准确，无法推荐';
        }
        $this->_ajax_return($Data);
    }

    /**
     * 计算需要的库位
     * @param $Area
     * @return array
     */
    private function _compute_warehouse ($Area) {
        $WarehouseV = array();
        $Area = ceil($Area);
        $Num = floor($Area/100) + floor(($Area%80)/80);
        if ($Num > 0) {
            if (!!($Able = $this->warehouse_model->select_height(array(ONE), $Num))) { // 一层
                $WarehouseV = array_merge($WarehouseV, $Able) ;
                $M = count($Able);
                $Area = $Area - $M * 100;
            }
        }
        if ($Area > 0) {
            $Num = floor($Area/80) + floor(($Area%50)/50);
            if ($Num > 0) {
                if (!!($Able = $this->warehouse_model->select_height(array(TWO), $Num))) { // 二层
                    $WarehouseV = array_merge($WarehouseV, $Able) ;
                    $M = count($Able);
                    $Area = $Area - $M * 80;
                }
            }
        }
        if ($Area > 0) {
            $Num = floor($Area/50);
            if ($Area%50 != 0) {
                $Num++;
            }
            if ($Num > 0) {
                if (!!($Able = $this->warehouse_model->select_height(array(THREE, FOUR), $Num))) { // 三、四层
                    $WarehouseV = array_merge($WarehouseV, $Able) ;
                }
            }
        }
        return $WarehouseV;
    }
    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if(!!($Cid = $this->warehouse_model->insert($Post))) {
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
            if(!!($this->warehouse_model->update($Post, $Where))){
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
            if ($this->warehouse_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }

    /**
     *
     */
    public function enable() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->warehouse_model->update(array('status' => 1), $Where)) {
                $this->Message = '启用成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'启用失败!';
            }
        }
        $this->_ajax_return();
    }

    public function unable() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->warehouse_model->update(array('status' => 0), $Where)) {
                $this->Message = '停用成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'停用失败!';
            }
        }
        $this->_ajax_return();
    }

    public function occupy() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->warehouse_model->update(array('status' => 2), $Where)) {
                $this->Message = '占用成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'占用失败!';
            }
        }
        $this->_ajax_return();
    }
}
