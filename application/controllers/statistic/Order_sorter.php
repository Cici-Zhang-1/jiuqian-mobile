<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月24日
 * @author Administrator
 * @version
 * @des
 * 
 * 下单排行榜
 */
class Order_sorter extends MY_Controller {
    private $__Search = array(
        'start_date' => '',
        'end_date' => ''
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order_sorter/Order_sorter __construct Start!');
        $this->load->model('order/order_model');
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if(empty($this->_Search['start_date']) && empty($this->_Search['end_date'])){
            $this->_Search['start_date'] = date('Y-m-01');
        }elseif (empty($this->_Search['start_date']) && !empty($this->_Search['end_date'])){
            $this->_Search['start_date'] = date('Y-m-d', gh_to_sec($this->Search['end_date']) - MONTHS);
        }
        $Data = array();
        if(!!($Query = $this->order_model->select_order_sorter($this->_Search))){
            $Tmp = array();
            foreach ($Query as $key => $value){
                if($value['sum'] > 0 && !empty($value['sum_detail'])) {
                    $value = array_merge($value, json_decode($value['sum_detail'], true));
                    unset($value['sum_detail']);
                    if(empty($Tmp[$value['dealer_id']])) {
                        $value['v'] = $value['dealer_id'];
                        $Tmp[$value['dealer_id']] = $value;
                        $Tmp[$value['dealer_id']]['amount'] = 1;
                    } else {
                        $Tmp[$value['dealer_id']]['amount'] += 1;
                        $Tmp[$value['dealer_id']]['sum'] += $value['sum'];
                        $Tmp[$value['dealer_id']]['cabinet'] += $value['cabinet'];
                        $Tmp[$value['dealer_id']]['wardrobe'] += $value['wardrobe'];
                        $Tmp[$value['dealer_id']]['door'] += $value['door'];
                        $Tmp[$value['dealer_id']]['wood'] += $value['wood'];
                        $Tmp[$value['dealer_id']]['fitting'] += $value['fitting'];
                        $Tmp[$value['dealer_id']]['other'] += $value['other'];
                        $Tmp[$value['dealer_id']]['server'] += $value['server'];
                    }
                }
            }
            $Tmp2 = array_values($Tmp);
            $K = count($Tmp);
            for ($I = 0; $I < $K; $I++) { //排序
                for ($J = 0; $J < $K - $I - 1; $J++) {
                    if ($Tmp2[$J]['sum'] < $Tmp2[$J + 1]['sum']) {
                        $Tmp3 = $Tmp2[$J]['sum'];
                        $Tmp2[$J]['sum'] = $Tmp2[$J + 1]['sum'];
                        $Tmp2[$J + 1]['sum'] = $Tmp3;
                    }
                }
            }
            foreach ($Tmp2 as $Key => $Value) { //修改Key值
                $Tmp2[$Key]['key'] = $Key + 1;
            }
            $Data['content'] = $Tmp2;
            $Data['num'] = count($Data['content']);
            $Data['pn'] = ONE;
            $Data['p'] = ONE;
            $Data['pagesize'] = ALL_PAGESIZE;
            unset($Tmp, $Tmp2);
            unset($Query);
        }else{
            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的订单';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
}