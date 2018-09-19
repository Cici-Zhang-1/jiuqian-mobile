<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pick sheet print Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Pick_sheet_print extends MY_Controller {
    private $__Search = array(
        'paging' => 0,
        'v' => 0
    );
    private $_StockOutted;
    private $_Nos = array(); // 每个订单的订单产品编号
    private $_Flag = array(); // 标志排在左列或右列
    private $_Scanned = 0; // 已扫描出库件数
    private $_Pack = 0; // 要求发货件数
    private $_Tmp = array();
    private $_Tables = array();
    private $_Page = 1;
    private $_MaxLineNum = 17;
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller warehouse/Pick_sheet_print __construct Start!');
        $this->load->model('order/order_product_model');
        $this->load->model('warehouse/warehouse_order_product_model');
        $this->load->model('warehouse/stock_outted_model');
        $this->config->load('defaults/stock_outted_status');
        $this->config->load('defaults/warehouse_status');
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
        if (empty($this->_Search['v'])) {
            $this->_Search['v'] = $this->input->get('stock_outted_id', true);
            $this->_Search['v'] = intval(trim($this->_Search['v']));
        }
        $Data = array();
        if (!empty($this->_Search['v'])) {
            if (!!($this->_StockOutted = $this->stock_outted_model->is_exist($this->_Search['v']))) {
                $this->_parse_stock_outted();
                if (!($Query = $this->order_product_model->select_pick_sheet_print($this->_Search))) {
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                    $this->Code = EXIT_ERROR;
                } else {
                    $this->load->helper('json_helper');
                    $this->_parse_order_product($Query);
                    $this->load->model('warehouse/unqrcode_model');
                    if (!!($Unqrcode = $this->unqrcode_model->select_pick_sheet_print($this->_Search))) {
                        $this->_parse_order_product($Unqrcode, true);
                    }
                    if (!empty($this->_Tmp)) {
                        foreach ($this->_Tmp as $Key => $Value) {
                            array_push($this->_Tables[$Key]['trs'], $Value);
                        }
                    }
                    $Spliceds = array();
                    foreach ($this->_Tables as $Key => $Value) {
                        $Spliced = array_chunk($Value['trs'], $this->_MaxLineNum);
                        if (count($Spliced) > 1) {
                            foreach ($Spliced as $Ikey => $Ivalue) {
                                $Value['trs'] = $Ivalue;
                                $Spliceds[] = $Value;
                            }
                        } else {
                            $Spliceds[] = $Value;
                        }
                    }
                    $Data['tables'] = $Spliceds;
                    $Data['scanned'] = $this->_Scanned;
                    $Data['pack'] = $this->_Pack;
                    $Data['num'] = count($Spliceds);
                    if ($this->_Scanned < $this->_Pack) {
                        $Data['message'] = '请注意: 扫描件数是' . $this->_Scanned . '，发货件数是' . $this->_Pack;
                    }
                    $Data['query']['stock_outted_id'] = $this->_Search['v'];
                }
            } else {
                $this->Message = '您选择的发货单不存在';
                $this->Code = EXIT_ERROR;
            }
        } else {
            $this->Message = '请选择要打印的发货单';
            $this->Code = EXIT_ERROR;
        }
        /*if(!($Query = $this->order_product_model->select_pick_sheet_print($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $this->load->model('warehouse/unqrcode_model');
            if (!!($Unqrcode = $this->unqrcode_model->select_pick_sheet_print($this->_Search))) {
                $Query = array_merge($Query, $Unqrcode);
            }
            $this->load->helper('json_helper');
            $No = 1;
            $Nos = array();
            $Flag = array();
            $Scanned = 0;
            $Pack = 0;
            $OrderV = 0;
            $Tmp = array();
            $Tables = array();
            $Page = 1;
            foreach ($Query as $Key => $Value) {
                $OrderV = $Value['order_v'];
                $Scanned += $Value['scanned'];  // 扫描件数
                $Pack += $Value['order_product_pack'];  // 包装件数
                if('物流代收' == $Value['payed']){
                    $Payed = $Value['sum'];
                }else{
                    $Payed = 0;
                }
                if (!isset($Tables[$Value['order_v']])) {
                    $Tables[$Value['order_v']] = array(
                        'p' => $Page++,
                        'dealer' => $Value['dealer'],
                        'delivery_area' => $Value['delivery_area'],
                        'delivery_address' => $Value['delivery_address'],
                        'delivery_linker' => $Value['delivery_linker'],
                        'delivery_phone' => $Value['delivery_phone'],
                        'logistics' => $Value['logistics'],
                        'owner' => $Value['owner'],
                        'end_datetime' => $Value['end_datetime'],
                        'truck' => $Value['truck'],
                        'train' => $Value['train'],
                        'pack' => $Value['pack'],
                        'page_pack' => $Value['order_product_pack'],
                        'collection' => $Value['collection'],
                        'page_collection' => $Payed,
                        'trs' => array()
                    );
                    $Flag[$Value['order_v']] = false;
                } else {
                    $Tables[$Value['order_v']]['page_pack'] += $Value['order_product_pack'];
                }
                $PackDetail = json_decode($Value['pack_detail'], true);
                if (!isset($Nos[$Value['order_v']])) {
                    $Nos[$Value['order_v']] = 1;
                }
                if ($Flag[$Value['order_v']] === false) { // 左列
                    $Tmp[$Value['order_v']] = array(
                        'one_no' => $Nos[$Value['order_v']]++,
                        'one_order_product_num' => $Value['order_product_num'],
                        'one_product' => $Value['product'],
                        'one_thick' => isset($PackDetail['thick']) && $PackDetail['thick'] > 0 ? $PackDetail['thick'] : 0,
                        'one_thin' => isset($PackDetail['thin']) && $PackDetail['thin'] > 0 ? $PackDetail['thin'] : 0,
                        'one_pack' => $Value['order_product_pack']
                    );
                    $Flag[$Value['order_v']] = true;
                } else {  // 右列
                    $Tmp[$Value['order_v']] = array_merge($Tmp[$Value['order_v']], array(
                        'two_no' => $Nos[$Value['order_v']]++,  // 右列
                        'two_order_product_num' => $Value['order_product_num'],
                        'two_product' => $Value['product'],
                        'two_thick' => isset($PackDetail['thick']) && $PackDetail['thick'] > 0 ? $PackDetail['thick'] : 0,
                        'two_thin' => isset($PackDetail['thin']) && $PackDetail['thin'] > 0 ? $PackDetail['thin'] : 0,
                        'two_pack' => $Value['order_product_pack']
                    ));
                    array_push($Tables[$Value['order_v']]['trs'], $Tmp[$Value['order_v']]);
                    unset($Tmp[$Value['order_v']]);
                    $Flag[$Value['order_v']] = false;
                }
            }
            if (!empty($Tmp)) {
                foreach ($Tmp as $Key => $Value) {
                    array_push($Tables[$Key]['trs'], $Value);
                }
            }
            $Spliceds = array();
            foreach ($Tables as $Key => $Value) {
                $Spliced = array_chunk($Value['trs'], $this->_MaxLineNum);
                if (count($Spliced) > 1) {
                    foreach ($Spliced as $Ikey => $Ivalue) {
                        $Value['trs'] = $Ivalue;
                        $Spliceds[] = $Value;
                    }
                } else {
                    $Spliceds[] = $Value;
                }
            }
            $Data['tables'] = $Spliceds;
            $Data['scanned'] = $Scanned;
            $Data['pack'] = $Pack;
            $Data['num'] = count($Spliceds);
            if ($Scanned < $Pack) {
                $Data['message'] = '请注意: 扫描件数是' . $Scanned . '，发货件数是' . $Pack;
            }
        }*/
        $this->_ajax_return($Data);
    }

    private function _parse_stock_outted () {
        $PackDetail = json_decode($this->_StockOutted['pack_detail'], true);
        $OrderProduct = array();
        foreach ($PackDetail as $Key => $Value) {
            $OrderProduct[$Value['v']] = $Value['pack'];
        }
        $this->_Search['order_product_id'] = array_keys($OrderProduct);
        $this->_StockOutted['pack_detail'] = $OrderProduct;
    }
    private function _parse_order_product ($Data, $Unqrcode = false) {
        foreach ($Data as $Key => $Value) {
            $this->_Scanned += $Value['scanned'];  // 订单订单产品的扫描件数
            $Pack = $Unqrcode ? $Value['order_product_pack'] : $this->_StockOutted['pack_detail'][$Value['v']]; // 如果没有Unqrcode, 则使用建立发货单时要求发货的件数
            $this->_Pack += $Pack;
            if (!isset($this->_Tables[$Value['order_v']])) {
                $this->_Tables[$Value['order_v']] = array(
                    'p' => $this->_Page++,
                    'dealer' => $Value['dealer'],
                    'delivery_area' => $Value['delivery_area'],
                    'delivery_address' => $Value['delivery_address'],
                    'delivery_linker' => $Value['delivery_linker'],
                    'delivery_phone' => $Value['delivery_phone'],
                    'logistics' => $Value['logistics'],
                    'owner' => $Value['owner'],
                    'end_datetime' => $this->_StockOutted['end_datetime'],
                    'truck' => $this->_StockOutted['truck'],
                    'train' => $this->_StockOutted['train'],
                    'page_pack' => $Pack,
                    'page_collection' => $Value['collection'],
                    'dealer_remark' => $Value['dealer_remark'],
                    'trs' => array()
                );
                $this->_Flag[$Value['order_v']] = false;
            } else {
                $this->_Tables[$Value['order_v']]['page_pack'] += $Pack;
            }
            $PackDetail = json_decode($Value['pack_detail'], true);
            if (!isset($this->_Nos[$Value['order_v']])) {
                $this->_Nos[$Value['order_v']] = 1; // 初始化序列号
            }
            if ($this->_Flag[$Value['order_v']] === false) { // 左列
                $this->_Tmp[$Value['order_v']] = array(
                    'one_no' => $this->_Nos[$Value['order_v']]++,
                    'one_order_product_num' => $Value['order_product_num'],
                    'one_product' => $Value['product'],
                    'one_thick' => isset($PackDetail['thick']) && $PackDetail['thick'] > 0 ? $PackDetail['thick'] : 0,
                    'one_thin' => isset($PackDetail['thin']) && $PackDetail['thin'] > 0 ? $PackDetail['thin'] : 0,
                    'one_pack' => $Pack
                );
                $this->_Flag[$Value['order_v']] = true;
            } else {  // 右列
                $this->_Tmp[$Value['order_v']] = array_merge($this->_Tmp[$Value['order_v']], array(
                    'two_no' => $this->_Nos[$Value['order_v']]++,  // 右列
                    'two_order_product_num' => $Value['order_product_num'],
                    'two_product' => $Value['product'],
                    'two_thick' => isset($PackDetail['thick']) && $PackDetail['thick'] > 0 ? $PackDetail['thick'] : 0,
                    'two_thin' => isset($PackDetail['thin']) && $PackDetail['thin'] > 0 ? $PackDetail['thin'] : 0,
                    'two_pack' => $Pack
                ));
                array_push($this->_Tables[$Value['order_v']]['trs'], $this->_Tmp[$Value['order_v']]);
                unset($this->_Tmp[$Value['order_v']]);
                $this->_Flag[$Value['order_v']] = false;
            }
        }
    }

    /**
    *
    * @return void
    */
    public function edit() {
        $_POST['v'] = isset($_POST['v']) ? $_POST['v'] : $this->input->post('stock_outted_v'); // 通过v或者stock_outted_v传递
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if (!!($this->_StockOutted = $this->stock_outted_model->is_pickable($Where))) {
                $this->_parse_stock_outted();
                $Data = array(
                    'printer' => $this->session->userdata('uid'),
                    'print_datetime' => date('Y-m-d H:i:s'),
                    'status' => $this->config->item('stock_outted_delivered') // 代表已经打印
                );
                if (!!($this->stock_outted_model->update($Data, $Where))) { // 更新拣货单状态
                    if (!!($In = $this->_in())) { // 判断在这个拣货单下面那些是在仓库内的
                        $Data = array(
                            'picker' => $this->session->userdata('uid'),
                            'pick_datetime' => date('Y-m-d H:i:s')
                        );
                        if (!!($this->warehouse_order_product_model->update($Data, $In['v']))) { // 仓库订单产品更新
                            $this->_out($In['warehouse_v']); // 更新仓库状态
                            $this->Message = '更新仓库状态成功';
                        } else {
                            $this->Message = '仓库订单产品更新失败';
                        }
                    } else {
                        $this->Message = '所有订单未入库或者已经出库';
                    }
                    $this->_workflow_order_outed();
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
                }
            } else {
                $this->Message = '重新打印单据!';
                $GLOBALS['workflow_msg'] = '重新打印单据!';
            }
            if (isset($Post['desk'])) { // 设置打印业务
                $this->_add_print_task($Where);
            }
        }
        $this->_ajax_return();
    }

    private function _workflow_order_outed () {
        $this->load->library('workflow/workflow');
        $this->load->model('order/order_model');
        $W = $this->workflow->initialize('order');
        $W->initialize($this->_StockOutted['order_id']);
        if ($W->outed()) {
            $this->Message .= '发货单打印成功, 刷新后生效!';
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = $W->get_failue();
        }
    }

    private function _add_print_task ($SourceV) {
        $this->load->model('desk/print_task_model');
        return $this->print_task_model->insert(array(
            'file' => 'pick_sheet',
            'source_id' => $SourceV,
            'status' => UNPRINT
        ));
    }

    private function _in () {
        if (!!($In = $this->warehouse_order_product_model->is_in_by_order_product_v($this->_Search['order_product_id']))) { //判断是否在库内，还未出库
            $Return = array(
                'v' => array(),
                'warehouse_v' => array()
            );
            foreach ($In as $Key => $Value) {
                array_push($Return['v'], $Value['v']); // 可以移库的库位订单产品编号
                array_push($Return['warehouse_v'], $Value['warehouse_v']); // 可以移库的库位
            }
            return $Return;
        }
        return false;
    }

    /**
     * 出库后更新库位状态
     * @param $WarehouseV
     * @return bool
     */
    private function _out($WarehouseV) {
        if (!!($IsNotOut = $this->warehouse_order_product_model->is_not_out($WarehouseV))) {
            foreach ($IsNotOut as $Value) {
                $WarehouseV = array_diff($WarehouseV, [$Value['warehouse_v']]);
            }
            if (count($WarehouseV) > 0) {
                $this->warehouse_model->update(array('status' => $this->config->item('warehouse_able')), $WarehouseV);
            }
        }
        return true;
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
            if ($this->pick_sheet_print_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
