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
    private $_V;
    private $_Type;
    private $_Vs; // 同类板块V
    private $_OrderProduct;
    private $_Plate;
    private $_Struct = array();
    private $_Abnormity = array();

    private $__Search = array(
        'status' => NO
    );

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Chart/Print_list Start !');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){ // Condition View
            $View = '_'.$View;
            $this->$View();
        }else{  // General View
            $this->_index($View);
        }
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->print_list_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    public function prints () {
        $this->_V = $this->input->get('v', true);
        $this->_V = intval(trim($this->_V));
        $this->_Type = $this->input->get('type', true);
        $this->_Type = intval(trim($this->_Type));

        if (empty($this->_V)) {
            show_error('请选择需要打印的订单');
        } else {
            if (ZERO == $this->_Type) {
                $A = $this->_read_order_product_classify();
            } else {
                $A = $this->_read_order_product_board();
            }
            if ($A) {
                $Data['Info'] = $this->_OrderProduct;
                $Data['Info']['type'] = $this->_Type;
                if (empty($Data['Info']['classify_name'])) {
                    $Data['Info']['classify_name'] = $Data['Info']['product'];
                }
                $this->load->view('header2');
                if ($this->_OrderProduct['product_id'] == CABINET) {
                    $Old = $this->input->get('old', true);
                    $this->_read_cabinet_struct();
                    if ($Old) {
                        $this->_read_cabinet_old();
                        $Data['Plate'] = $this->_Plate;
                        $Data['Struct'] = $this->_Struct;
                        $Data['Abnormity'] = $this->_Abnormity;
                        $this->load->view($this->_Item . __FUNCTION__ . '_cabinet_old', $Data);
                    } else {
                        $this->_read_order_product_board_plate();
                        $Data['Plate'] = $this->_Plate;
                        $Data['Struct'] = $this->_Struct;
                        $Data['Abnormity'] = $this->_Abnormity;
                        $this->load->view($this->_Item . __FUNCTION__ . '_plate', $Data);
                    }
                } elseif ($this->_OrderProduct['product_id'] == WARDROBE) {
                    $this->_read_order_product_board_plate();
                    $Data['Plate'] = $this->_Plate;
                    $Data['Abnormity'] = $this->_Abnormity;
                    $this->load->view($this->_Item . __FUNCTION__ . '_plate', $Data);
                } elseif ($this->_OrderProduct['product_id'] == DOOR) {
                    $this->_read_door_struct();
                    $this->_read_door();
                    $Data['Plate'] = $this->_Plate;
                    $Data['Struct'] = $this->_Struct;
                    $this->load->view($this->_Item . __FUNCTION__ . '_door', $Data);
                } elseif ($this->_OrderProduct['product_id'] == WOOD) {
                    $this->_read_wood();
                    $Data['Plate'] = $this->_Plate;
                    $this->load->view($this->_Item . __FUNCTION__ . '_wood', $Data);
                } else {
                    $this->load->view($this->_Item . __FUNCTION__, $Data);
                }
                $this->_workflow();
            } else {
                show_error('没有找到对应的打印清单');
            }
        }
    }

    private function _read_order_product_classify () {
        $this->load->model('order/order_product_classify_model');
        if (!!($Query = $this->order_product_classify_model->has_brothers($this->_V))) {
            $this->_OrderProduct = $Query[array_rand($Query, ONE)];
            $this->_Vs = array();
            foreach ($Query as $Key => $Value) {
                $this->_Vs[] = $Value['v'];
            }
            return true;
        }
        return false;
    }

    private function _read_order_product_board () {
        $this->load->model('order/order_product_board_model');
        if (!!($Query = $this->order_product_board_model->has_brothers($this->_V))) {
            $this->_OrderProduct = $Query[array_rand($Query, ONE)];
            $this->_Vs = array();
            foreach ($Query as $Key => $Value) {
                $this->_Vs[] = $Value['v'];
            }
            return true;
        }
        return false;
    }

    private function _read_order_product_board_plate () {
        $Data = array('Statistic' => array(), 'FourH' => array('Amount' => 0, 'Area' => 0), 'Amount' => 0, 'Area' => 0, 'Face' => '');
        $this->load->model('order/order_product_board_plate_model');
        if (ZERO == $this->_Type) {
            $Plate = $this->order_product_board_plate_model->select_by_order_product_classify_id($this->_Vs);
        } else {
            $this->_read_abnormity();
            $this->_read_order_product_num();
            $Plate = $this->order_product_board_plate_model->select_by_order_product_board_id($this->_Vs);
        }
        if ($Plate) {
            $List = array();
            $Face = $this->_get_face();
            $FaceOut = array(
                '右抽侧',
                '左抽侧',
                '抽后',
                '抽前',
                '裤侧左(圆)',
                '裤侧右(圆)',
                '右侧(圆)',
                '左侧(圆)',
                '后板',
                '裤后'
            );
            $SuffQrcodes = array();
            foreach ($Plate as $key => $value){
                if ((isset($Face[$value['punch'] . $value['slot']]) || isset($Face[A_ALL . $value['slot']]) || isset($Face[$value['punch'] . A_ALL]))
                    && !in_array($value['plate_name'], $FaceOut)) {
                    $Qrcode = explode('-', $value['qrcode']);
                    $SuffQrcodes[] = array_pop($Qrcode);
                }
                $value['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                $value['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                if($value['area'] < MIN_AREA){
                    $value['area'] = MIN_AREA;
                }

                $Tmp2 = implode('^', array($value['thick'], $value['plate_name'], $value['width'], $value['length'],
                    $value['edge'], $value['slot'], $value['punch'], $value['good'], $value['remark']));

                if(isset($List[$Tmp2])){
                    $List[$Tmp2]['area'] += $value['area'];
                    $List[$Tmp2]['num'] += 1;
                }else{
                    $List[$Tmp2] = $value;
                }
                if(isset($Data['Statistic'][$value['thick']])){
                    $Data['Statistic'][$value['thick']]['amount'] += 1;
                    $Data['Statistic'][$value['thick']]['area'] += $value['area'];
                }else{
                    $Data['Statistic'][$value['thick']] = array('amount' => 1, 'area' => $value['area']);
                }
                if ('4H' == $value['edge']) {
                    $Data['FourH']['Amount'] += 1;
                    $Data['FourH']['Area'] += $value['area'];
                }
                $Data['Area'] += $value['area'];
            }
            $Data['Amount'] = count($Plate);
            ksort($List);
            $Data['List'] = $List;
            sort($SuffQrcodes);
            $Data['Face'] = implode('; ', $SuffQrcodes);
        }
        $this->_Plate = $Data;

        return $Data;
    }

    private function _read_order_product_num () {
        $Data = array();
        $this->load->model('order/order_product_model');
        if ($Query = $this->order_product_model->select_by_order_id($this->_OrderProduct['order_id'])) {
            foreach ($Query as $Key => $Value) {
                $Tmp = explode('-', $Value['num']);
                $Data[] = array_pop($Tmp);
            }
        }
        $this->_All = implode(',', $Data);
        return $this->_All;
    }
    private function _read_abnormity () {
        $this->load->library('d_dismantle');
        $Data = array('Statistic' => array(), 'Amount' => 0, 'Area' => 0);
        $this->load->model('order/order_product_board_plate_model');
        $Plate = $this->order_product_board_plate_model->select_abnormity_by_order_product_id($this->_OrderProduct['order_product_id']);
        if($Plate){
            $List = array();
            foreach ($Plate as $key => $value){
                $Tmp2 = implode('^', array($value['thick'], $value['plate_name'], $value['width'], $value['length'],
                    $value['punch'], $value['slot'], $value['punch'], $value['good'], $value['remark']));
                $value['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                $value['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                if ($value['area'] < MIN_AREA) {
                    $value['area'] = MIN_AREA;
                }
                if(isset($List[$Tmp2])){
                    $List[$Tmp2]['area'] += $value['area'];
                    $List[$Tmp2]['num'] += 1;
                }else{
                    $List[$Tmp2] = $value;
                }
                if(isset($Data['Statistic'][$value['thick']])){
                    $Data['Statistic'][$value['thick']]['amount'] += 1;
                    $Data['Statistic'][$value['thick']]['area'] += $value['area'];
                }else{
                    $Data['Statistic'][$value['thick']] = array('amount' => 1, 'area' => $value['area']);
                }
                $Data['Area'] += $value['area'];
            }
            $Data['Amount'] = count($Plate);
            ksort($List);
            $Data['List'] = $List;
        }else{
            $Data = false;
        }
        $this->_Abnormity = $Data;
        return $Data;
    }

    private function _read_cabinet_struct () {
        $this->load->model('order/order_product_cabinet_struct_model');
        $this->_Struct = $this->order_product_cabinet_struct_model->select_one(array('order_product_id' => $this->_OrderProduct['order_product_id']));
    }

    private function _get_face () {
        $this->load->model('data/face_model');
        $Face = array();
        if (!!($Query = $this->face_model->select())) {
            foreach ($Query as $value) {
                $Face[$value['punch'] . $value['slot']] = $value['flag'];
            }
        }
        return $Face;
    }

    private function _read_cabinet_old () {
        $ColorFlag = array(
            '<i>', '<i class="fa fa-square">','<i class="fa fa-circle"','<i class="fa fa-heart"',
            '<i class="fa fa-star"','<i class="fa fa-smile-o"','<i class="fa fa-diamond"','<i class="fa fa-female"',
            '<i class="fa fa-futbol-o"','<i class="fa fa-music"','<i class="fa fa-plane"','<i class="fa fa-moon-o"',
            '<i class="fa fa-square-o"','<i class="fa fa-heart-o"','<i class="fa fa-car"','<i class="fa fa-tree"'
        );
        $ColorFlagChar = 'A';
        $Data = array('Statistic' => array());
        $this->load->model('order/order_product_board_plate_model');
        if (ZERO == $this->_Type) {
            $Plate = $this->order_product_board_plate_model->select_by_order_product_classify_id($this->_Vs);
        } else {
            $Plate = $this->order_product_board_plate_model->select_by_order_product_board_id($this->_Vs);
        }
        if($Plate){
            $List = array();
            $Other = array();
            $Implode = array();
            $BoardPlateTmp = array();
            foreach ($Plate as $key => $value){
                $Implode[] = implode('^', array($value['cubicle_num'].$value['cubicle_name'], $value['plate_name'],$key));
            }
            sort($Implode,SORT_STRING);
            foreach ($Implode as $key => $value){
                $value = explode('^', $value);
                $BoardPlateTmp[] = $Plate[$value[2]];
            }
            $BoardPlate = $BoardPlateTmp;
            unset($BoardPlateTmp);
            $Implode = array();
            foreach ($BoardPlate as $key => $value){
                $Name = $value['plate_name'];
                switch ($value['plate_name']){
                    case '左立板':
                    case '右立板':
                    case '右侧板':
                    case '左侧板':
                    case '左右板':
                    case '左板':
                    case '右板':
                        $Name = '立板';
                        break;
                    case '顶板':
                    case '底板':
                    case '前后板':
                    case '前板':
                    case '后板':
                        $Name = '顶底板';
                        break;
                    case '活动隔板':
                        break;
                    case '固定隔板':
                        break;
                    case '前撑':
                    case '后撑':
                        $Name = '连接条';
                        break;
                    case '背板':
                        break;
                    default:
                        $Name = '其它板块';
                        break;
                }
                $value['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                $value['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                if($value['area'] < MIN_AREA){
                    $value['area'] = MIN_AREA;
                }

                if(isset($Data['Statistic'][$value['good']])){
                    $Data['Statistic'][$value['good']]['amount'] += 1;
                    $Data['Statistic'][$value['good']]['area'] += $value['area'];
                }else{
                    $Data['Statistic'][$value['good']] = array('amount' => 1, 'area' => $value['area']);
                }
                $Implode[] = implode('^', array($value['cubicle_num'].$value['cubicle_name'], $Name,
                    $value['width'].'x'.$value['length'], $value['thick'], $value['good'], $value['remark']
                ));
            }
            $Implode = array_count_values($Implode); /*计算重复出现的次数*/
            arsort($Implode);
            foreach ($Implode as $key => $value){
                $Keys = explode('^', $key);
                if(!isset($List[$Keys[0]])){
                    $List[$Keys[0]] = array(
                        'li' => '',
                        'dingdi' => '',
                        'huo' => '',
                        'gu' => '',
                        'lian' => '',
                        'bei' => ''
                    );
                }
                if(!isset($Color[$Keys[4]])){
                    if(!empty($ColorFlag)){
                        $Color[$Keys[4]] = array_shift($ColorFlag);
                    }else{
                        $Color[$Keys[4]] = '<i>'.$ColorFlagChar;
                        $ColorFlagChar++;
                    }
                }
                $Value = $Color[$Keys[4]].'</i>&nbsp;'.$Keys[2].'x'.$value.'<br />';
                if(!empty($Keys[5])){
                    $Value = $Value.$Keys[5].'<br />';
                }
                switch ($Keys[1]){
                    case '立板':
                        $List[$Keys[0]]['li'] .= $Value;
                        break;
                    case '顶底板':
                        $List[$Keys[0]]['dingdi'] .= $Value;
                        break;
                    case '活动隔板':
                        $List[$Keys[0]]['huo'] .= $Value;
                        break;
                    case '固定隔板':
                        $List[$Keys[0]]['gu'] .= $Value;
                        break;
                    case '连接条':
                        $List[$Keys[0]]['lian'] .= $Value;
                        break;
                    case '背板':
                        $List[$Keys[0]]['bei'] .= $Value;
                        break;
                    case '其它板块':
                        if(isset($Other[$Keys[0]])){
                            $Other[$Keys[0]] .= $Value;
                        }else{
                            $Other[$Keys[0]] = $Value;
                        }
                        break;
                }
            }
            ksort($List,SORT_NUMERIC);
            $Data['List'] = $List;
            $Data['Other'] = $Other;
        }
        $this->_Plate = $Data;
        return $Data;
    }

    private function _read_door () {
        $Data = array('Amount' => 0, 'Area' => 0, 'OpenHole' => 0, 'Invisibility' => 0);
        $this->load->model('order/order_product_board_door_model');
        $Plate = $this->order_product_board_door_model->select_by_order_product_board_id($this->_Vs);
        if($Plate){
            $List = array();
            foreach ($Plate as $key => $value){
                $value['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                $value['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                if ($value['area'] < MIN_M_AREA) {
                    $value['area'] = MIN_M_AREA;
                }

                $Tmp2 = implode('^', array($value['good'], $value['width'], $value['length'],
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
                $Data['Area'] += $value['area'];
                $Data['OpenHole'] += $value['open_hole'];
                $Data['Invisibility'] += $value['invisibility'];
            }
            $Data['Amount'] = count($Plate);
            ksort($List);
            $Data['List'] = $List;
        }
        $this->_Plate = $Data;
        return $Data;
    }

    private function _read_door_struct () {
        $this->load->model('order/order_product_door_model');
        $this->_Struct = $this->order_product_door_model->select_one(array('order_product_id' => $this->_OrderProduct['order_product_id']));
    }

    private function _read_wood () {
        $Data = array('Amount' => 0, 'Area' => 0);
        $this->load->model('order/order_product_board_wood_model');
        $Plate = $this->order_product_board_wood_model->select_by_order_product_board_id($this->_Vs);
        if($Plate){
            $List = array();
            foreach ($Plate as $key => $value){
                $Tmp2 = implode('^', array($value['width'], $value['length'],
                    $value['punch'], $value['wood_name'], $value['core'], $value['good'], $value['remark']));
                $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                if ($value['area'] < MIN_K_AREA) {
                    $value['area'] = MIN_K_AREA;
                }

                $value['m_width'] = $value['width'] - 3;
                $value['m_length'] = $value['length'] - 3;

                if(isset($List[$Tmp2])){
                    $List[$Tmp2]['area'] += $value['area'];
                    $List[$Tmp2]['amount'] += 1;
                }else{
                    $List[$Tmp2] = $value;
                }
                $Data['Area'] += $value['area'];
            }
            $Data['Amount'] = count($Plate);
            $Data['List'] = $List;
        }
        $this->_Plate = $Data;
        return $Data;
    }

    private function _workflow () {
        if (ZERO == $this->_Type) {
            $W = $this->_workflow_classify();
        } else {
            $W = $this->_workflow_board();
        }
        if(!!($W->initialize($this->_Vs))){
            $W->printed_list();
            return true;
        }else{
            $this->Code = EXIT_ERROR;
            $this->Message = $W->get_failue();
            return false;
        }
    }
    private function _workflow_classify () {
        $this->load->library('workflow/workflow');
        return $this->workflow->initialize('order_product_classify');
    }

    private function _workflow_board () {
        $this->load->library('workflow/workflow');
        return $this->workflow->initialize('order_product_board');
    }
}
