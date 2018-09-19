<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pack label Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Pack_label extends MY_Controller {
    private $_OrderProductClassify = array();
    private $_OrderProductBoard = array();
    private $_OrderProductFitting = array();
    private $_OrderProductOther = array();
    private $_Classify;
    private $_Brothers;
    private $_Together;
    private $_Pack;
    private $_Sum;
    private $_OrderProductId;
    private $_PackDetail;
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Pack_label __construct Start!');
        $this->load->model('order/order_product_model');
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

    private function _prints () {
        $Item = $this->_Item . __FUNCTION__;
        $Data['action'] = site_url($Item);
        $this->load->view('header2');
        $this->load->view($Item, $Data);
    }

    public function read () {
        $Data = array();
        if ($this->_do_form_validation()) {
            $Year = $this->input->post('year', true);
            $Month = $this->input->post('month', true);
            $Prefix = $this->input->post('prefix', true);
            $Middle = $this->input->post('middle', true);
            $Code = $this->input->post('code', true); /*产品类型*/
            $Type = $this->input->post('type', true); /*正常单、增补单*/
            $OrderProductNum = sprintf('%s%d%02d%04d-%s%s',$Type,$Year,$Month,$Prefix,$Code,$Middle);
            if (!!($OrderProduct = $this->order_product_model->is_exist($OrderProductNum))) {
                $Data = $OrderProduct;
                $Tmp = json_decode($OrderProduct['pack_detail'], true);
                if(is_array($Tmp)){
                    $Data = array_merge($OrderProduct, $Tmp);
                }
                if ($Code == CABINET_NUM || $Code == WARDROBE_NUM || $Code == DOOR_NUM || $Code == WOOD_NUM) {
                    if (!!($OrderProductPackable = $this->_is_packable_order_product_classify($OrderProduct['v'])) || !!($OrderProductPackable = $this->_is_packable_order_product_board($OrderProduct['v']))) {
                        $Data = array_merge($Data, $OrderProductPackable);
                        $OrderProductBrothers = sprintf('%s%d%02d%04d-%s',$Type,$Year,$Month,$Prefix,$Code);
                        $Data['brothers'] = $this->_read_brothers($OrderProductBrothers, $OrderProductNum);
                        $this->Message= '成功获得要打印包装标签的订单';
                    } else {
                        $this->Message = $OrderProductNum . '当前订单状态不能打包';
                        $this->Code = EXIT_ERROR;
                    }
                } elseif ($Code == FITTING_NUM) {
                    if (!!($OrderProductPackable = $this->_is_packable_order_product_fitting($OrderProduct['v']))) {
                        $Data = array_merge($Data, $OrderProductPackable);
                        $OrderProductBrothers = sprintf('%s%d%02d%04d-%s',$Type,$Year,$Month,$Prefix,$Code);
                        $Data['brothers'] = $this->_read_brothers($OrderProductBrothers, $OrderProductNum);
                        $this->Message= '成功获得要打印包装标签的订单';
                    } else {
                        $this->Message = $OrderProductNum . '当前订单状态不能打包';
                        $this->Code = EXIT_ERROR;
                    }
                } elseif ($Code == OTHER_NUM) {
                    if (!!($OrderProductPackable = $this->_is_packable_order_product_other($OrderProduct['v']))) {
                        $Data = array_merge($Data, $OrderProductPackable);
                        $OrderProductBrothers = sprintf('%s%d%02d%04d-%s',$Type,$Year,$Month,$Prefix,$Code);
                        $Data['brothers'] = $this->_read_brothers($OrderProductBrothers, $OrderProductNum);
                        $this->Message= '成功获得要打印包装标签的订单';
                    } else {
                        $this->Message = $OrderProductNum . '当前订单状态不能打包';
                        $this->Code = EXIT_ERROR;
                    }
                } else {
                    $this->Message= '您要打包的订单类型不存在';
                    $this->Code = EXIT_ERROR;
                }
            } else {
                $this->Message = $OrderProductNum . '当前订单不存在';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return($Data);
    }

    /**
     * 获取兄弟类订单，用于合包
     * @param $OrderProductBrothers
     * @param $OrderProductNum
     * @return bool
     */
    private function _read_brothers($OrderProductBrothers, $OrderProductNum){
        if(!!($Data = $this->order_product_model->select_brothers($OrderProductBrothers, $OrderProductNum))){
            foreach ($Data as $key => $value){
                $Tmp = json_decode($value['pack_detail'], true);
                if (is_array($Tmp)) {
                    $Data[$key] = array_merge($Data[$key], $Tmp);
                }
                if (!!($OrderProductClassify = $this->_is_packable_order_product_classify($value['v']))) {
                    $Data[$key] = array_merge($Data[$key], $OrderProductClassify);
                } elseif (!!($OrProductBoard = $this->_is_packable_order_product_board($value['v']))) {
                    $Data[$key] = array_merge($Data[$key], $OrProductBoard);
                }
            }
            return $Data;
        }
        return false;
    }

    private function _is_packable_order_product_classify ($OrderProductId) {
        $this->load->model('order/order_product_classify_model');
        $Data = array('pack_type' => array(), 'packer' => array());
        if (!!($this->_OrderProductClassify = $this->order_product_classify_model->select_packable_by_order_product_id($OrderProductId))) {
            foreach ($this->_OrderProductClassify as $Key => $Value) {
                if ($Value['procedure'] == P_PACK) {  // 合包
                    if (!in_array('both', $Data['pack_type'])) {
                        array_push($Data['pack_type'], 'both');
                        if (!empty($Value['pack'])) {
                            $Data['packer']['both'] = $Value['packer'];
                        }
                    }
                    break;
                } elseif ($Value['procedure'] == P_PACK_THICK) { // 厚板打包
                    if (!in_array('thick', $Data['pack_type'])) {
                        array_push($Data['pack_type'], 'thick');
                        if (!empty($Value['pack'])) {
                            $Data['packer']['thick'] = $Value['packer'];
                        }
                    }
                } elseif ($Value['procedure'] == P_PACK_THIN) { // 薄板打包
                    if (!in_array('thin', $Data['pack_type'])) {
                        array_push($Data['pack_type'], 'thin');
                        if (!empty($Value['pack'])) {
                            $Data['packer']['thin'] = $Value['packer'];
                        }
                    }
                }
            }
            $Data['un_scanned'] = $this->_read_un_scanned($OrderProductId); // 是否缺板材
            return $Data;
        }
        return false;
    }

    private function _is_packable_order_product_board ($OrderProductId) {
        $this->load->model('order/order_product_board_model');
        $Data = array('pack_type' => array());
        if (!!($this->_OrderProductBoard = $this->order_product_board_model->select_packable_by_order_product_id($OrderProductId))) {
            array_push($Data['pack_type'], 'both'); // 是否缺板材
            return $Data;
        }
        return false;
    }

    private function _is_packable_order_product_fitting ($OrderProductId) {
        $this->load->model('order/order_product_fitting_model');
        $Data = array('pack_type' => array());
        if (!!($this->_OrderProductFitting = $this->order_product_fitting_model->select_packable_by_order_product_id($OrderProductId))) {
            array_push($Data['pack_type'], 'both'); // 是否缺板材
            return $Data;
        }
        return false;
    }

    private function _is_packable_order_product_other ($OrderProductId) {
        $this->load->model('order/order_product_other_model');
        $Data = array('pack_type' => array());
        if (!!($this->_OrderProductOther = $this->order_product_other_model->select_packable_by_order_product_id($OrderProductId))) {
            array_push($Data['pack_type'], 'both'); // 是否缺板材
            return $Data;
        }
        return false;
    }

    private function _read_un_scanned ($OrderProductId) {
        $this->load->model('order/order_product_board_plate_model');
        return $this->order_product_board_plate_model->select_un_scanned_by_order_product_id($OrderProductId);
    }

    public function prints () {
        $this->_OrderProductId = $this->input->post('order_product_id', true);
        $this->_Pack = $this->input->post('pack', true);
        $this->_Classify = $this->input->post('classify', true);
        $this->_Brothers = $this->input->post('brothers', true);
        $this->_Together = !empty($this->_Brothers);
        $GLOBALS['creator'] = $this->input->post('packer', true);
        $GLOBALS['creator'] = intval(trim($GLOBALS['creator']));
        if (empty($GLOBALS['creator'])) { // 设置打包人，可以由前端指定，如果不指定则为当前登录用户
            $GLOBALS['creator'] = $this->session->userdata('uid');
        }
        if (!!($OrderProduct = $this->order_product_model->is_exist('', $this->_OrderProductId))) {
            $PackDetail = json_decode($OrderProduct['pack_detail'], true);
            if (!is_array($PackDetail)) {
                $PackDetail = array();
            }
            if ('both' == $this->_Classify) {
                $PackDetail = array();
                $PackDetail['both'] = $this->_Pack;
            } else {
                unset($PackDetail['both']);
                $PackDetail[$this->_Classify] = $this->_Pack;
            }
            $Sum = array_values($PackDetail);
            foreach (array_keys($Sum, -1) as $ivalue){
                unset($Sum[$ivalue]);
            }
            $this->_Sum = array_sum($Sum);
            $this->_PackDetail = json_encode($PackDetail);
            unset($Sum, $PackDetail);
            if ($OrderProduct['code'] == CABINET_NUM || $OrderProduct['code'] == WARDROBE_NUM || $OrderProduct['code'] == DOOR_NUM || $OrderProduct['code'] == WOOD_NUM) {
                if (!!($OrderProductClassify = $this->_is_packable_order_product_classify($this->_OrderProductId))) {
                    $this->_edit_order_product_classify();
                } elseif (!!($OrProductBoard = $this->_is_packable_order_product_board($this->_OrderProductId))) {
                    $this->_edit_order_product_board();
                } else {
                    $this->Message = $OrderProduct['num'] . '当前订单状态不能打包';
                    $this->Code = EXIT_ERROR;
                }
                if ($this->Code == EXIT_SUCCESS && $this->_Together) {
                    $this->_edit_brothers();
                }
            } elseif ($OrderProduct['code'] == FITTING_NUM) {
                if (!!($OrderProductFitting = $this->_is_packable_order_product_fitting($this->_OrderProductId))) {
                    $this->_edit_order_product_fitting();
                } else {
                    $this->Message = $OrderProduct['num'] . '当前订单状态不能打包';
                    $this->Code = EXIT_ERROR;
                }
            } elseif ($OrderProduct['code'] == OTHER_NUM) {
                if (!!($OrderProductOther = $this->_is_packable_order_product_other($this->_OrderProductId))) {
                    $this->_edit_order_product_other();
                } else {
                    $this->Message = $OrderProduct['num'] . '当前订单状态不能打包';
                    $this->Code = EXIT_ERROR;
                }
            } else {
                $this->Message= '您要打包的订单类型不存在';
                $this->Code = EXIT_ERROR;
            }

        } else {
            $this->Message = '您要打包的订单不存在!';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return();
    }

    private function _edit_order_product_classify () {
        $OrderProductClassifyId = array();
        $Packed = true;
        if ($this->_Classify == 'thick') {
            foreach ($this->_OrderProductClassify as $Key => $Value) {
                if ($Value['procedure'] == P_PACK_THICK) {
                    array_push($OrderProductClassifyId, $Value['v']);
                } else {
                    if ($Value['pack'] == ZERO) {
                        $Packed = false;
                    }
                }
            }
        } elseif ($this->_Classify == 'thin') {
            foreach ($this->_OrderProductClassify as $Key => $Value) {
                if ($Value['procedure'] == P_PACK_THIN) {
                    array_push($OrderProductClassifyId, $Value['v']);
                } else {
                    if ($Value['pack'] == ZERO) {
                        $Packed = false;
                    }
                }
            }
        } else {
            foreach ($this->_OrderProductClassify as $Key => $Value) {
                array_push($OrderProductClassifyId, $Value['v']);
            }
        }
        if (!empty($OrderProductClassifyId)) {
            if ('thick' == $this->_Classify){
                $Message = '打包厚板, 入库' . $this->_Pack . '件, 总件数' . $this->_Sum . '件';
            }elseif ('thin' == $this->_Classify){
                $Message = '打包薄板, 入库' . $this->_Pack . '件, 总件数' . $this->_Sum . '件';
            }else{
                $Message = '打包入库' . $this->_Pack . '件, 总件数' . $this->_Sum . '件';
            }
            if($this->_Brothers){
                $Message .= ', 这次为合包, 且打印包标签';
            }
            $this->load->library('workflow/workflow');
            $this->load->model('order/order_product_classify_model');
            $W = $this->workflow->initialize('order_product_classify');
            $W->initialize($OrderProductClassifyId);
            if ($W->packed($Message, array(
                'pack' => $this->_Sum,
                'pack_detail' => $this->_PackDetail,
                'packed' => $Packed
            ))) {
                return true;
            } else {
                $this->Message = $W->get_failue();
                $this->Code = EXIT_ERROR;
                return false;
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '没有找到相应的板块分类!';
            return false;
        }
    }
    private function _edit_order_product_board () {
        $OrderProductBoardId = array();
        foreach ($this->_OrderProductBoard as $Key => $Value) {
            array_push($OrderProductBoardId, $Value['v']);
        }
        if (!empty($OrderProductBoardId)) {
            if ('thick' == $this->_Classify){
                $Message = '打包厚板, 入库' . $this->_Pack . '件, 总件数' . $this->_Sum . '件';
            }elseif ('thin' == $this->_Classify){
                $Message = '打包薄板, 入库' . $this->_Pack . '件, 总件数' . $this->_Sum . '件';
            }else{
                $Message = '打包入库' . $this->_Pack . '件, 总件数' . $this->_Sum . '件';
            }
            if($this->_Brothers){
                $Message .= ', 这次为合包, 且打印包标签';
            }
            $this->load->library('workflow/workflow');
            $this->load->model('order/order_product_board_model');
            $W = $this->workflow->initialize('order_product_board');
            $W->initialize($OrderProductBoardId);
            if ($W->packed($Message, array(
                'pack' => $this->_Sum,
                'pack_detail' => $this->_PackDetail,
                'packed' => true
            ))) {
                // $this->_edit_order_product();
                return true;
            } else {
                $this->Message = $W->get_failue();
                $this->Code = EXIT_ERROR;
                return false;
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '没有找到相应的板块分类!';
            return false;
        }
    }

    private function _edit_order_product_fitting () {
        $OrderProductFittingId = array();
        foreach ($this->_OrderProductFitting as $Key => $Value) {
            array_push($OrderProductFittingId, $Value['v']);
        }
        if (!empty($OrderProductFittingId)) {
            $Message = '配件打包入库' . $this->_Pack . '件, 总件数' . $this->_Sum . '件';
            $this->load->library('workflow/workflow');
            $this->load->model('order/order_product_fitting_model');
            $W = $this->workflow->initialize('order_product_fitting');
            $W->initialize($OrderProductFittingId);
            if ($W->packed($Message, array(
                'pack' => $this->_Sum,
                'pack_detail' => $this->_PackDetail,
                'packed' => true
            ))) {
                return true;
            } else {
                $this->Message = $W->get_failue();
                $this->Code = EXIT_ERROR;
                return false;
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '没有找到相应的配件清单!';
            return false;
        }
    }

    private function _edit_order_product_other () {
        $OrderProductOtherId = array();
        foreach ($this->_OrderProductOther as $Key => $Value) {
            array_push($OrderProductOtherId, $Value['v']);
        }
        if (!empty($OrderProductOtherId)) {
            $Message = '外购打包入库' . $this->_Pack . '件, 总件数' . $this->_Sum . '件';
            $this->load->library('workflow/workflow');
            $this->load->model('order/order_product_other_model');
            $W = $this->workflow->initialize('order_product_other');
            $W->initialize($OrderProductOtherId);
            if ($W->packed($Message, array(
                'pack' => $this->_Sum,
                'pack_detail' => $this->_PackDetail,
                'packed' => true
            ))) {
                return true;
            } else {
                $this->Message = $W->get_failue();
                $this->Code = EXIT_ERROR;
                return false;
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '没有找到相应的外购清单!';
            return false;
        }
    }

    private function _edit_order_product () {
        if ('thick' == $this->_Classify){
            $GLOBALS['workflow_msg'] = '打包厚板, 入库' . $this->_Pack . '件, 总件数' . $this->_Sum . '件';
        }elseif ('thin' == $this->_Classify){
            $GLOBALS['workflow_msg'] = '打包薄板, 入库' . $this->_Pack . '件, 总件数' . $this->_Sum . '件';
        }else{
            $GLOBALS['workflow_msg'] = '打包入库' . $this->_Pack . '件, 总件数' . $this->_Sum . '件';
        }
        if($this->_Brothers){
            $GLOBALS['workflow_msg'] .= ', 这次为合包, 且打印包标签';
        }
        $this->load->library('workflow/workflow');
        $this->load->model('order/order_product_model');
        $W = $this->workflow->initialize('order_product');
        $W->initialize($this->_OrderProductId, array(
            'pack' => $this->_Sum,
            'pack_detail' => $this->_PackDetail
        ));
        if ($W->packed()) {
            return true;
        } else {
            $this->Message = $W->get_failue();
            $this->Code = EXIT_ERROR;
            return false;
        }
    }

    private function _edit_brothers () {
        if(!is_array($this->_Brothers)){
            $this->_Brothers = explode(',', $this->_Brothers);
        }
        $this->_Pack = -1;
        foreach ($this->_Brothers as $Key => $Value) {
            $this->_OrderProductId = $Value;
            if (!!($OrderProduct = $this->order_product_model->is_exist('', $this->_OrderProductId))) {
                $PackDetail = json_decode($OrderProduct['pack_detail'], true);
                if (!is_array($PackDetail)) {
                    $PackDetail = array();
                }
                if ('both' == $this->_Classify) {
                    $PackDetail = array();
                    $PackDetail['both'] = $this->_Pack;
                } else {
                    unset($PackDetail['both']);
                    $PackDetail[$this->_Classify] = $this->_Pack;
                }
                $Sum = array_values($PackDetail);
                foreach (array_keys($Sum, -1) as $ivalue){
                    unset($Sum[$ivalue]);
                }
                $this->_Sum = array_sum($Sum);
                $this->_PackDetail = json_encode($PackDetail);
                unset($Sum, $PackDetail);
                if (!!($OrderProductClassify = $this->_is_packable_order_product_classify($this->_OrderProductId))) {
                    $this->_edit_order_product_classify();
                } elseif (!!($OrProductBoard = $this->_is_packable_order_product_board($this->_OrderProductId))) {
                    $this->_edit_order_product_board();
                } else {
                    $this->Message = $OrderProduct['num'] . '当前订单状态不能打包';
                    $this->Code = EXIT_ERROR;
                    break;
                }
            } else {
                $this->Message = '您要打包的订单不存在!';
                $this->Code = EXIT_ERROR;
                break;
            }
        }
    }
}
