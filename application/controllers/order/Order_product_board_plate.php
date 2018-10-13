<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order product board plate Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Order_product_board_plate extends MY_Controller {
    private $__Search = array(
        'order_product_id' => ZERO,
        'paging' => NO
    );
    private $_Statistic = array(
        'thick_amount' => 0,
        'thick_area' => 0,
        'thin_amount' => 0,
        'thin_area' => 0,
        '4h_amount' => 0,
        '4h_area' => 0,
        'total_area' => 0,
        'total_amount' => 0,
        'face' => ''
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Order_product_board_plate __construct Start!');
        $this->load->model('order/order_product_board_plate_model');
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
            if(!($Data = $this->order_product_board_plate_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            } else {
                foreach ($Data['content'] as $Key => $Value) {
                    if ($Value['status'] == '正在扫描' && $Value['scanner'] > 0) {
                        $Value['status'] = '已扫描';
                    }
                    $Data['content'][$Key] = $Value;
                }
            }
            $Data['query']['order_product_id'] = $this->_Search['order_product_id'];
        } else {
            $this->Message = '请选择订单产品后查看板块信息';
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
            if(!($Data = $this->order_product_board_plate_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            } else {
                $Data['content'] = $this->_combine($Data['content']);
                $Data['statistic'] = $this->_Statistic;
            }
            $Data['query']['order_product_id'] = $this->_Search['order_product_id'];
        } else {
            $this->Message = '请选择订单产品后打印板块信息';
            $this->Code = EXIT_ERROR;
        }

        $this->_ajax_return($Data);
    }

    /**
     * 合并同类项
     * @param $BoardPlate
     * @return array
     */
    private function _combine ($BoardPlate) {
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
        foreach ($BoardPlate as $key => $value){
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
                $value['edge'], $value['slot'], $value['punch'], $value['board'], $value['remark']));

            if(isset($List[$Tmp2])){
                $List[$Tmp2]['area'] += $value['area'];
                $List[$Tmp2]['amount'] += 1;
            }else{
                $List[$Tmp2] = $value;
            }
            if ($value['thick'] > THICK) {
                $this->_Statistic['thick_amount'] += ONE;
                $this->_Statistic['thick_area'] += $value['area'];
            } else {
                $this->_Statistic['thin_amount'] += ONE;
                $this->_Statistic['thin_area'] += $value['area'];
            }

            if ('4H' == $value['edge']) {
                $this->_Statistic['4h_amount'] += ONE;
                $this->_Statistic['4h_area'] += $value['area'];
            }
            $this->_Statistic['total_area'] += $value['area'];
        }
        $this->_Statistic['total_amount'] = count($BoardPlate);
        ksort($List);
        $List = array_values($List);
        sort($SuffQrcodes);
        $this->_Statistic['face'] = implode('; ', $SuffQrcodes);
        return $List;
    }

    /**
     * 获取单双面信息
     * @return array
     */
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

    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if(!!($NewId = $this->order_product_board_plate_model->insert($Post))) {
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
            if(!!($this->order_product_board_plate_model->update($Post, $Where))){
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
            if ($this->order_product_board_plate_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }

    private function _label () {
        $Item = $this->_Item . __FUNCTION__;
        $Data['action'] = site_url($Item);
        $this->load->view('header2');
        $this->load->view($Item, $Data);
    }

    public function label () {
        $OrderProductNum = $this->input->post('order_product_num', true);
        $OrderProductNum = trim($OrderProductNum);
        $OrderProductNum = strtoupper($OrderProductNum);

        $Data = array();
        if(preg_match(REG_ORDER_PRODUCT_STRICT, $OrderProductNum)){
            $this->load->model('order/order_product_model');
            if (!!($OrderProduct = $this->order_product_model->is_exist($OrderProductNum))) {
                $_GET['order_product_id'] = $OrderProduct['v'];
                $this->_page_search();
                if(!($Data = $this->order_product_board_plate_model->select($this->_Search))){
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有找到板块信息';
                    $this->Code = EXIT_ERROR;
                } else {
                    foreach ($Data['content'] as $Key => $Value) {
                        $Data['content'][$Key]['width'] = $Value['width'] - $Value['up_edge'] - $Value['down_edge'];
                        $Data['content'][$Key]['length'] = $Value['length'] - $Value['left_edge'] - $Value['right_edge'];
                    }
                    $Data['order_product'] = $OrderProduct;
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '订单不存在';
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '订单编号不正确';
        }
        $this->_ajax_return($Data);
    }
}
