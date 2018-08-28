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
    private $_MaxLineNum = 17;
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller warehouse/Pick_sheet_print __construct Start!');
        $this->load->model('order/order_product_model');
        $this->load->model('warehouse/warehouse_order_product_model');
        $this->load->model('stock/stock_outted_model');
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
        $this->__Search['v'] = $this->input->get('stock_outted_v');
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Query = $this->order_product_model->select_pick_sheet_print($this->_Search))){
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
        }
        $this->_ajax_return($Data);
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
            if ($this->stock_outted_model->is_picked($Where)) {
                $this->Message = '重新打印单据!';
            } else {
                $Data = array(
                    'printer' => $this->session->userdata('uid'),
                    'print_datetime' => date('Y-m-d H:i:s'),
                    'status' => 2 // 代表已经打印
                );
                if (!!($this->stock_outted_model->update($Data, $Where))) { // 更新拣货单状态
                    if (!!($In = $this->_in($Where))) { // 判断在这个拣货单下面那些是在仓库内的
                        $Data = array(
                            'picker' => $this->session->userdata('uid'),
                            'pick_datetime' => date('Y-m-d H:i:s')
                        );
                        if(!!($this->warehouse_order_product_model->update($Data, $In['v']))){ // 仓库订单产品更新
                            $this->_out($In['warehouse_v']); // 更新仓库状态
                            $this->Message = '更新仓库状态成功, 刷新后生效!';
                        }else{
                            $this->Message = '仓库订单产品更新失败';
                        }
                    } else {
                        $this->Message = '所有订单未入库或者已经出库';
                    }
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
                }
            }
            if (isset($Post['desk'])) { // 设置打印业务
                $this->_add_print_task($Where);
            }
        }
        $this->_ajax_return();
    }

    private function _add_print_task ($SourceV) {
        $this->load->model('desk/print_task_model');
        return $this->print_task_model->insert(array(
            'file' => 'pick_sheet',
            'source_id' => $SourceV,
            'status' => UNPRINT
        ));
    }

    private function _in ($StockOuttedV) {
        if (!!($In = $this->warehouse_order_product_model->is_in_by_stock_outted_v($StockOuttedV))) { //判断是否在库内，还未出库
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

    private function _out($WarehouseV) {
        if (!!($IsNotOut = $this->warehouse_order_product_model->is_not_out($WarehouseV))) {
            foreach ($IsNotOut as $Value) {
                $WarehouseV = array_diff($WarehouseV, [$Value['warehouse_v']]);
            }
            if (count($WarehouseV) > 0) {
                $this->warehouse_model->update(array('status' => 1), $WarehouseV);
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
