<?php
/**
 * Created by JiuQian.
 * User: ZhangCC
 * Date: 2018/5/7
 * Time: 17:24
 */
class Sales extends MY_Controller {
    private $__Search = array(
        'start_date' => '',
        'end_date' => '',
        'product_id' => '',
        'board_thick' => '',
        'board_color' => '',
        'board_nature' => '',
        'y_axis' => YES
    );

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Statistic/Sales __construct Start!');
        $this->load->model('order/order_model');
        $this->load->model('order/order_product_board_model');
        $this->load->model('order/order_product_fitting_model');
        $this->load->model('order/order_product_server_model');
        $this->load->model('order/order_product_other_model');
    }

    public function read() {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['start_date']) && empty($this->_Search['end_date'])) {
            $this->_Search['start_date'] = date('Y-m-01');
            $this->_Search['end_date'] = date('Y-m-d', strtotime('+1 day'));
        } elseif (empty($this->_Search['start_date']) && !empty($this->_Search['end_date'])) {
            $this->_Search['start_date'] = date('Y-m-01', gh_to_sec($this->_Search['end_date']));
        } else {
            $this->_Search['end_date'] = date('Y-m-d', strtotime('+1 day'));
        }
        if ($this->_Search['start_date'] >= $this->_Search['end_date']) {
            $this->_Search['start_date'] = date('Y-m-01', gh_to_sec($this->_Search['end_date']));
        }
        if ($this->_Search['board_color'] != '' && !is_array($this->_Search['board_color'])) {
            $this->_Search['board_color'] = explode(',', $this->_Search['board_color']);
        }
        if ($this->_Search['board_nature'] != '' && !is_array($this->_Search['board_nature'])) {
            $this->_Search['board_nature'] = explode(',', $this->_Search['board_nature']);
        }
        if ($this->_Search['board_thick'] != '' && !is_array($this->_Search['board_thick'])) {
            $this->_Search['board_thick'] = explode(',', $this->_Search['board_thick']);
        }
        if ($this->_Search['product_id'] != '' && !is_array($this->_Search['product_id'])) {
            $this->_Search['product_id'] = explode(',', $this->_Search['product_id']);
        }

        $Data = array();
        $Return = array();
        if ($Query = $this->order_product_board_model->select_sales($this->_Search)) {
            $Return = array_merge($Return, $Query);
        }
        if ($Query = $this->order_product_fitting_model->select_sales($this->_Search)) {
            $Return = array_merge($Return, $Query);
        }
        if ($Query = $this->order_product_server_model->select_sales($this->_Search)) {
            $Return = array_merge($Return, $Query);
        }
        if ($Query = $this->order_product_other_model->select_sales($this->_Search)) {
            $Return = array_merge($Return, $Query);
        }
        if(!empty($Return)){
            if ($this->_Search['y_axis']) {
                $Data['y_axis'] = '销售面积m2';
                $Data['content']= $this->statistic_by_area($Return);
            } else {
                $Data['y_axis'] = '销售额';
                $Data['content'] = $this->statistic_by_sum($Return);
            }
            $Data['num'] = count($Data['content']);
            $Data['p'] = ONE;
            $Data['pn'] = ONE;
            $Data['pagesize'] = ALL_PAGESIZE;
            unset($Return);
        }else{
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的订单';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    private function statistic_by_area($Query) {
        $Tmp = array();
        foreach ($Query as $key => $value){
            if(isset($value['area']) && $value['area'] > 0) {
                $value['dealer'] = gh_parse_dealer($value['dealer'], 'dealer');
                if (empty($Tmp[$value['dealer']])) {
                    $Tmp[$value['dealer']] = array();
                }
                if (in_array($value['product_id'], $this->_Search['product_id'])) {
                    if (empty($Tmp[$value['dealer']]['产品'][$value['product']])) {
                        $Tmp[$value['dealer']]['产品'][$value['product']] = 0;
                    }
                    $Tmp[$value['dealer']]['产品'][$value['product']] += $value['area'];
                }
                if (isset($value['board_thick']) && in_array($value['board_thick'], $this->_Search['board_thick'])) {
                    if (empty($Tmp[$value['dealer']]['板块厚度'][$value['board_thick']])) {
                        $Tmp[$value['dealer']]['板块厚度'][$value['board_thick']] = 0;
                    }
                    $Tmp[$value['dealer']]['板块厚度'][$value['board_thick']] += $value['area'];
                }
                if (isset($value['board_color']) && in_array($value['board_color'], $this->_Search['board_color'])) {
                    if (empty($Tmp[$value['dealer']]['板块颜色'][$value['board_color']])) {
                        $Tmp[$value['dealer']]['板块颜色'][$value['board_color']] = 0;
                    }
                    $Tmp[$value['dealer']]['板块颜色'][$value['board_color']] += $value['area'];
                }
                if (isset($value['board_nature']) && in_array($value['board_nature'], $this->_Search['board_nature'])) {
                    if (empty($Tmp[$value['dealer']]['板块材质'][$value['board_nature']])) {
                        $Tmp[$value['dealer']]['板块材质'][$value['board_nature']] = 0;
                    }
                    $Tmp[$value['dealer']]['板块材质'][$value['board_nature']] += $value['area'];
                }
            }
        }
        return $Tmp;
    }

    private function statistic_by_sum($Query) {
        $Tmp = array();
        foreach ($Query as $key => $value){
            if($value['sum'] > 0){
                $value['dealer'] = gh_parse_dealer($value['dealer'], 'dealer');
                if (empty($Tmp[$value['dealer']])) {
                    $Tmp[$value['dealer']] = array();
                }
                if (in_array($value['product_id'], $this->_Search['product_id'])) {
                    if (empty($Tmp[$value['dealer']]['产品'][$value['product']])) {
                        $Tmp[$value['dealer']]['产品'][$value['product']] = 0;
                    }
                    $Tmp[$value['dealer']]['产品'][$value['product']] += $value['sum'];
                }
                if (isset($value['board_thick']) && in_array($value['board_thick'], $this->_Search['board_thick'])) {
                    if (empty($Tmp[$value['dealer']]['板块厚度'][$value['board_thick']])) {
                        $Tmp[$value['dealer']]['板块厚度'][$value['board_thick']] = 0;
                    }
                    $Tmp[$value['dealer']]['板块厚度'][$value['board_thick']] += $value['sum'];
                }
                if (isset($value['board_color']) && in_array($value['board_color'], $this->_Search['board_color'])) {
                    if (empty($Tmp[$value['dealer']]['板块颜色'][$value['board_color']])) {
                        $Tmp[$value['dealer']]['板块颜色'][$value['board_color']] = 0;
                    }
                    $Tmp[$value['dealer']]['板块颜色'][$value['board_color']] += $value['sum'];
                }
                if (isset($value['board_nature']) && in_array($value['board_nature'], $this->_Search['board_nature'])) {
                    if (empty($Tmp[$value['dealer']]['板块材质'][$value['board_nature']])) {
                        $Tmp[$value['dealer']]['板块材质'][$value['board_nature']] = 0;
                    }
                    $Tmp[$value['dealer']]['板块材质'][$value['board_nature']] += $value['sum'];
                }
            }
        }
        return $Tmp;
    }
}