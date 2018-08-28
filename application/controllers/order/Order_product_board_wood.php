<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order product board wood Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Order_product_board_wood extends MY_Controller {
    private $__Search = array(
        'order_product_id' => ZERO
    );
    private $_Statistic = array(
        'total_area' => ZERO,
        'total_amount' => ZERO
    );
    private $_Center = array(); // 中横
    private $_Horizontal = array(); // 横框
    private $_Vertical = array(); // 竖框
    private $_Blinds = array(); // 百叶
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Order_product_board_wood __construct Start!');
        $this->load->model('order/order_product_board_wood_model');
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
            if (!($Data = $this->order_product_board_wood_model->select($this->_Search))) {
                $this->Message = isset($GLOBALS['error']) ? is_array($GLOBALS['error']) ? implode(',', $GLOBALS['error']) : $GLOBALS['error'] : '读取信息失败';
                $this->Code = EXIT_ERROR;
            }
            $Data['query']['order_product_id'] = $this->_Search['order_product_id'];
        } else {
            $this->Message = '请选择订单产品后查看木框门信息';
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
            if(!($Data = $this->order_product_board_wood_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            } else {
                $Data['content'] = $this->_combine($Data['content']);
                $Data['statistic'] = $this->_Statistic;
                $Data['horizontal'] = $this->_Horizontal;
                $Data['vertical'] = $this->_Vertical;
                $Data['center'] = $this->_Center;
                $Data['blinds'] = $this->_Blinds;
            }
            $Data['query']['order_product_id'] = $this->_Search['order_product_id'];
        } else {
            $this->Message = '请选择订单产品后打印板块信息';
            $this->Code = EXIT_ERROR;
        }

        $this->_ajax_return($Data);
    }

    private function _combine($BoardWood) {
        $List = array();
        foreach ($BoardWood as $key => $value){
            $value['key'] = $key + 1;
            $Tmp2 = implode('^', array($value['width'], $value['length'],
                $value['punch'], $value['wood_name'], $value['core'], $value['board'], $value['remark']));
            $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
            if ($value['area'] < MIN_AREA) {
                $value['area'] = MIN_AREA;
            }

            $value['m_width'] = $value['width'] - 3;
            $value['m_length'] = $value['length'] - 3;

            if(isset($List[$Tmp2])){
                $List[$Tmp2]['area'] += $value['area'];
                $List[$Tmp2]['num'] += 1;
            }else{
                $List[$Tmp2] = $value;
            }
            $this->_parse_wood($value, $value['key']);
            $this->_Statistic['total_area'] += $value['area'];
        }
        $this->_Statistic['total_amount'] = count($BoardWood);
        ksort($List);
        $List = array_values($List);
        return $List;
    }

    private function _parse_wood ($Wood, $K) {
        $BoardWidth = 70;
        $ItemWidth = 130;
        if (preg_match('/五五/', $Wood['center'])) {
            $this->_Center[] = array(
                'flag' => $K,
                'name' => '中横',
                'board' => $Wood['board'],
                'length' => $Wood['width'] - $BoardWidth*2,
                'width' => $BoardWidth,
                'amount' => $Wood['amount']
            );
            $CenterFlag = 1;
        }elseif (preg_match('/四六/', $Wood['center'])) {
            $this->_Center[] = array(
                'flag' => $K,
                'name' => '中横',
                'board' => $Wood['board'],
                'length' => $Wood['width'] - $BoardWidth*2,
                'width' => $BoardWidth,
                'amount' => $Wood['amount']
            );
            $CenterFlag = 2;
        }else {
            $CenterFlag = 0;
        }
        $this->_Horizontal[] = array(
            'flag' => $K,
            'name' => '木框横框',
            'board' => $Wood['board'],
            'length' => $Wood['width'] - $BoardWidth*2,
            'width' => $BoardWidth,
            'amount' => $Wood['amount']*2
        );
        $this->_Vertical[] = array(
            'flag' => $K,
            'name' => '木框竖框',
            'board' => $Wood['board'],
            'length' => $Wood['length'],
            'width' => $BoardWidth,
            'amount' => $Wood['amount']*2
        );
        if(preg_match('/百叶/', $Wood['wood_name'])) {
            $Blinds = array('flag' => $K, 'name' => '小百叶', 'board' => $Wood['board'], 'length' => $Wood['width'] - $BoardWidth * 2 + 12, 'width' => $ItemWidth);
            if (1 == $CenterFlag) {
                $Blinds['amount'] = $Wood['amount'] * ceil((($Wood['length'] - $BoardWidth * 3) / 2 + 12) / $ItemWidth) * 2;
            } elseif (2 == $CenterFlag) {
                $Blinds['amount'] = $Wood['amount'] * (ceil((($Wood['length'] * 3 / 5 - $BoardWidth * 3 / 2) + 12) / $ItemWidth) + ceil((($Wood['length'] * 2 / 5 - $BoardWidth * 3 / 2) + 12) / $ItemWidth));
            } else {
                $Blinds['amount'] = $Wood['amount'] * ceil(($Wood['length'] - $BoardWidth * 2 + 12) / $ItemWidth);
            }
            array_push($this->_Blinds, $Blinds);
        } else {
            $Blinds = array(
                'flag' => $K,
                'board' => $Wood['core'],
                'width' => $Wood['width'] - $BoardWidth*2 + 12,
            );
            if (preg_match('/玻璃/', $Wood['wood_name'])){
                $Blinds['name'] = '玻璃门芯';
            } elseif (preg_match('/平板/', $Wood['wood_name'])){
                $Blinds['name'] = '平板门芯';
            }else{
                $Blinds['name'] = '门芯';
            }
            if (1 == $CenterFlag) {
                $Blinds['length'] = ceil(($Wood['length'] - $BoardWidth*3)/2 + 12);
                $Blinds['amount'] = $Wood['amount'] * 2;
                array_push($this->_Blinds, $Blinds);
            }elseif (2 == $CenterFlag) {
                $Blinds['length'] = ceil(($Wood['length']*3/5 - $BoardWidth*3/2) + 12);
                $Blinds['amount'] = $Wood['amount'];
                array_push($this->_Blinds, $Blinds);
                $Blinds['length'] = ceil(($Wood['length']*2/5 - $BoardWidth*3/2) + 12);
                $Blinds['amount'] = $Wood['amount'];
                array_push($this->_Blinds, $Blinds);
            }else {
                $Blinds['length'] = $Wood['length'] - $BoardWidth*2 + 12;
                $Blinds['amount'] = $Wood['amount'];
                array_push($this->_Blinds, $Blinds);
            }
        }
        return true;
    }

    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if(!!($NewId = $this->order_product_board_wood_model->insert($Post))) {
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
            if(!!($this->order_product_board_wood_model->update($Post, $Where))){
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
            if ($this->order_product_board_wood_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
