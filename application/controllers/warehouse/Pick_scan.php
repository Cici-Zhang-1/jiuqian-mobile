<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pick scan Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Pick_scan extends MY_Controller {
    private $__Search = array(
        'paging' => 0,
        'v' => 0
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller warehouse/Pick_scan __construct Start!');
        $this->load->model('warehouse/pick_scan_model');
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
        $this->load->model('order/order_product_model');
        if(!($Data = $this->order_product_model->select_pick_sheet_detail($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $this->load->model('warehouse/unqrcode_model');
            if (!!($Unqrcode = $this->unqrcode_model->select_pick_sheet_detail($this->_Search))) {
                foreach ($Unqrcode['content'] as $Key => $Value) {
                    array_push($Data['content'], $Value);
                }
                $Data['num'] += $Unqrcode['num'];
            }
            $Scanned = array();
            if(!!($PickScan = $this->pick_scan_model->select($this->_Search))){
                $Scanned = $PickScan['content'];
                foreach ($PickScan['content'] as $Key => $Value) {
                    $Scanned[$Value['qrcode']] = $Value;
                }
                unset($PickScan);
            }
            $this->load->helper('json_helper');
            $ScanList = array();
            foreach ($Data['content'] as $Key => $Value) {
                $Value['warehouse_v'] = discode_warehouse_v($Value['warehouse_v']);
                $PackDetail = json_decode($Value['pack_detail'], true);
                if (is_array($PackDetail) && count($PackDetail) > 0) {
                    foreach ($PackDetail as $Ikey => $Ivalue) {
                        $Classify = ($Ikey == 'thick') ? '厚板' : ($Ikey == 'thin' ? '薄板' : '');
                        if ($Ivalue > 0) {
                            for ($I = 1; $I <= $Ivalue; $I++) {
                                $Qrcode = $Value['order_product_num'] . '-' .$Ivalue . '-' . $I . '-' ;
                                $Tmp = array(
                                    'v' => $Qrcode . $Ikey,
                                    'label' => $Qrcode . $Classify . '-' . $Value['warehouse_v']
                                );
                                if (isset($Scanned[$Tmp['v']])) {
                                    $Tmp['checked'] = true;
                                    $Tmp['label'] .= '-' . $Scanned[$Tmp['v']]['creator'] . '-' . $Scanned[$Tmp['v']]['create_datetime'];
                                } else {
                                    $Tmp['checked'] = false;
                                }
                                array_push($ScanList, $Tmp);
                            }
                        }
                    }
                }
            }
            $Data['content'] = $ScanList;
            $Data['num'] = count($ScanList);
        }
        $this->_ajax_return($Data);
    }

    /**
     *
     * @return void
     */
    public function add() {
        $OrderProductNum = $this->input->post('order_product_num');
        $_POST['order_product_num'] = explode(',', $OrderProductNum);
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Data = array();
            $this->load->model('order/order_product_model');
            $this->load->model('warehouse/unqrcode_model');
            foreach ($Post['order_product_num'] as $Key => $Value) {
                if (preg_match(REG_PACK_LABEL, $Value, $Matches)
                    && (!!($OrderProduct = $this->order_product_model->is_exist($Matches[1]))
                    || !!($Unqrocode = $this->unqrcode_model->is_exist($Matches[1])))) {
                    $Data[] = array(
                        'qrcode' => $Value,
                        'stock_outted_v' => $Post['stock_outted_v'],
                        'order_product_num' => $Matches[1],
                        'pack' => $Matches[3],
                        'num' => $Matches[4],
                        'classify' => $Matches[5]
                    );
                }
            }
            if(!!($NewId = $this->pick_scan_model->insert_ignore($Data))) {
                $this->load->model('stock/stock_outted_model');
                $this->stock_outted_model->update(array('delivered_pack' => count($Data)), $Post['stock_outted_v']);
                $this->Message = '扫描成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'扫描失败!';
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
            if(!!($this->pick_scan_model->update($Post, $Where))){
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
            if ($this->pick_scan_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
