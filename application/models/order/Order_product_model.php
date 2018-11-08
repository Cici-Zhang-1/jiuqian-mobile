<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月24日
 * @author Zhangcc
 * @version
 * @des
 * 订单产品
 */
class Order_product_model extends MY_Model{
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model Order/Order_product_model start!');
    }

    public function select ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('order_product')
                    ->join('order', 'o_id = op_order_id', 'left')
                    ->join('task_level', 'tl_id = o_task_level', 'left')
                    ->join('workflow_order_product', 'wop_id = op_status', 'left')
                    ->join('user as D', 'D.u_id = op_dismantle', 'left')
                    ->join('user AS C', 'C.u_id = op_creator', 'left')
                    ->where('o_status > ', O_REMOVE);
                if (empty($Search['all'])) {
                    $this->HostDb->where('op_creator', $this->session->userdata('uid'));
                }
                if (!empty($Search['product_id'])) {
                    $this->HostDb->where_in('op_product_id', $Search['product_id']);
                }
                if (!empty($Search['status'])) {
                    $this->HostDb->where_in('op_status', $Search['status']);
                }
                if (!empty($Search['order_id'])) {
                    $this->HostDb->where('op_order_id', $Search['order_id']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('op_num', $Search['keyword'])
                        ->or_like('o_dealer', $Search['keyword'])
                        ->or_like('o_owner', $Search['keyword'])
                        ->group_end();
                }

                $Query = $this->HostDb->order_by('op_id', 'desc')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品';
            }
        }
        return $Return;
    }

    private function _page_num ($Search) {
        $this->HostDb->select('op_id', FALSE)
            ->from('order_product')
            ->join('order', 'o_id = op_order_id', 'left')
            ->join('workflow_order_product', 'wop_id = op_status', 'left')
            ->where('o_status > ', O_REMOVE);
        if (empty($Search['all'])) {
            $this->HostDb->where('op_creator', $this->session->userdata('uid'));
        }
        if (!empty($Search['product_id'])) {
            $this->HostDb->where_in('op_product_id', $Search['product_id']);
        }
        if (!empty($Search['status'])) {
            $this->HostDb->where_in('op_status', $Search['status']);
        }
        if (!empty($Search['order_id'])) {
            $this->HostDb->where('op_order_id', $Search['order_id']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('op_num', $Search['keyword'])
                ->or_like('o_dealer', $Search['keyword'])
                ->or_like('o_owner', $Search['keyword'])
                ->group_end();
        }

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $this->_Num = $Query->num_rows();
            $Query->free_result();
            if(intval($this->_Num%$Search['pagesize']) == 0){
                $Pn = intval($this->_Num/$Search['pagesize']);
            }else{
                $Pn = intval($this->_Num/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * 获取订单详细信息
     * @param $Search
     * @return array|bool
     */
    public function select_detail ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('order_product')
                ->join('order', 'o_id = op_order_id', 'left')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->join('task_level', 'tl_id = o_task_level', 'left')
                ->join('pay_status', 'ps_name = o_pay_status', 'left')
                ->join('workflow_order', 'wo_id = o_status', 'left')
                ->where('op_id', $Search['order_product_id'])
                ->get();

            $Return = array(
                'content' => $Query->row_array(),
                'num' => ONE,
                'p' => ONE,
                'pn' => ONE,
                'pagesize' => ALL_PAGESIZE
            );
            $this->cache->save($Cache, $Return, MONTHS);
        }
        return $Return;
    }

    /**
     * 通过V获取信息
     * @param $Vs
     * @return bool
     */
    public function select_by_v ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode(',', $Vs);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('order_product')
                ->join('product', 'p_id = op_product_id', 'left')
                ->where_in('op_id', $Vs);

            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品';
            }
        }
        return $Return;
    }
    /**
     * 通过订单编号获取订单产品信息
     * @param $OrderId
     */
    public function select_by_order_id ($OrderId) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $OrderId;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('order_product')
                ->join('order', 'o_id = op_order_id', 'left')
                ->join('dealer', 'd_id = o_dealer_id', 'left')
                ->join('shop', 's_id = o_shop_id', 'left')
                ->join('product', 'p_id = op_product_id', 'left');
            $this->HostDb->where('op_order_id', $OrderId);
            $this->HostDb->where('op_status != ', OP_REMOVE);
            $this->HostDb->where('o_status != ', O_REMOVE);

            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品';
            }
        }
        return $Return;
    }

    /**
     * 获取可以拆单的订单产品
     */
    public function select_dismantle ($OrderId) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $OrderId;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('order_product')
                ->join('workflow_order_product', 'wop_id = op_status', 'left')
                ->join('product', 'p_id = op_product_id', 'left')
                ->join('order', 'o_id = op_order_id', 'left');

            $this->HostDb->where_in('op_status', array(OP_CREATE, OP_DISMANTLING, OP_DISMANTLED));
            $this->HostDb->where('op_order_id', $OrderId);

            $Query = $this->HostDb->order_by('op_status')->order_by('op_id')->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品';
            }
        }
        return $Return;
    }

    /**
     * 獲取可以售後的訂單
     * @param $OrderId
     * @return bool
     */
    public function select_post_sale ($OrderId) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $OrderId;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('order_product')
                ->join('workflow_order_product', 'wop_id = op_status', 'left')
                ->join('product', 'p_id = op_product_id', 'left')
                ->join('order', 'o_id = op_order_id', 'left');

            $this->HostDb->where('op_status >= ', OP_DISMANTLED);
            $this->HostDb->where('op_order_id', $OrderId);

            $Query = $this->HostDb->order_by('op_status')->order_by('op_id')->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品';
            }
        }
        return $Return;
    }

    public function select_produce_process_tracking ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num_produce_process_tracking($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('order_product')
                    ->join('order', 'o_id = op_order_id', 'left')
                    ->join('order_product_classify', 'opc_order_product_id = op_id', 'left')
                    ->join('mrp', 'm_id = opc_mrp_id', 'left')
                    ->join('workflow_order_product', 'wop_id = op_status', 'left')
                    ->join('user', 'u_id = op_producing', 'left');
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('op_num', $Search['keyword'])
                        ->or_like('o_dealer', $Search['keyword'])
                        ->or_like('o_owner', $Search['keyword'])
                        ->or_like('m_batch_num', $Search['keyword'])
                        ->group_end();
                }
                if (isset($Search['order_type']) && $Search['order_type'] != '') {
                    $this->HostDb->where('o_order_type', $Search['order_type']);
                }
                if (isset($Search['warn_date']) && $Search['warn_date'] != '') {
                    $this->HostDb->where('op_producing_datetime <= ', $Search['warn_date']);
                }
                $this->HostDb->where_in('op_status', array(OP_PRODUCING, OP_PACKING));
                $this->HostDb->where('o_status > ', O_PRODUCE);
                $this->HostDb->order_by('op_producing_datetime', 'desc');
                $this->HostDb->group_by('op_id');
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的生产中订单';
            }
        }
        return $Return;
    }

    private function _page_num_produce_process_tracking ($Search) {
        /*$this->HostDb->select('op_id', FALSE)
            ->from('mrp')
            ->join('n9_workflow_mrp_msg', 'wmm_mrp_id = m_id && wmm_workflow_mrp_id = ' . M_ELECTRONIC_SAW, 'left', false)
            ->join('n9_order_product_classify', 'opc_optimize_datetime = m_batch_num && opc_board = m_board', 'left', false)*/
        $this->HostDb->select('op_id', FALSE)->from('order_product')
            ->join('order', 'o_id = op_order_id', 'left')
            ->join('order_product_classify', 'opc_order_product_id = op_id', 'left')
            ->join('mrp', 'm_id = opc_mrp_id', 'left');
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('op_num', $Search['keyword'])
                ->or_like('o_dealer', $Search['keyword'])
                ->or_like('o_owner', $Search['keyword'])
                ->or_like('m_batch_num', $Search['keyword'])
                ->group_end();
        }
        if (isset($Search['order_type']) && $Search['order_type'] != '') {
            $this->HostDb->where('o_order_type', $Search['order_type']);
        }
        if (isset($Search['warn_date']) && $Search['warn_date'] != '') {
            $this->HostDb->where('op_producing_datetime <= ', $Search['warn_date']);
        }
        $this->HostDb->where_in('op_status', array(OP_PRODUCING, OP_PACKING));
        $this->HostDb->where('o_status > ', O_PRODUCE);
        $this->HostDb->group_by('op_id');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->num_rows();
            $Query->free_result();
            $this->_Num = $Row;
            if(intval($Row%$Search['pagesize']) == 0){
                $Pn = intval($Row/$Search['pagesize']);
            }else{
                $Pn = intval($Row/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    public function select_pack ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['in'] = $this->HostDb->select('wop_order_product_num')->from('warehouse_order_product')->where('wop_picker', 0)->get_compiled_select();
            $Search['pn'] = $this->_page_num_pack($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql, FALSE)
                    ->from('order_product')
                    ->join('order', 'o_id = op_order_id', 'left')
                    ->join('user', 'u_id = o_creator', 'left')
                    ->join('scan_status', 'ss_name = op_scan_status', 'left')
                    ->where_in('op_product_id', array(CABINET, WARDROBE))   /*可扫描的产品类型*/
                    ->where('op_status > ', OP_DISMANTLED)  /*已经生产的订单产品才可以扫描*/
                    ->where('o_order_type', $Search['order_type']);
                if (isset($Search['status']) && $Search['status'] != '') { /*扫描状态*/
                    $this->HostDb->where('op_scan_status', $Search['status']);
                }

                if(!empty($Search['start_date'])){ /*扫描开始日期*/
                    $this->HostDb->where('op_scan_start > ', $Search['start_date']);
                }

                if(!empty($Search['end_date'])){ /*扫描截止日期*/
                    $this->HostDb->where('op_scan_end < ', $Search['end_date']);
                }

                if(isset($Search['keyword']) && $Search['keyword'] != ''){
                    $this->HostDb->group_start()
                            ->like('op_remark', $Search['keyword'])
                            ->or_like('op_num', $Search['keyword'])
                            ->or_like('o_dealer', $Search['keyword'])
                            ->or_like('o_owner', $Search['keyword'])
                            ->or_like('o_remark', $Search['keyword'])
                        ->group_end();
                }

                $this->HostDb->order_by('op_num', 'desc');
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();

                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MINUTES);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品';
            }
        }
        return $Return;
    }

    private function _page_num_pack ($Search) {
        $this->HostDb->select('count(op_id) as num', FALSE)
            ->from('order_product')
            ->join('order', 'o_id = op_order_id', 'left')
            ->where_in('op_product_id', array(CABINET, WARDROBE))   /*可扫描的产品类型*/
            ->where('op_status > ', OP_DISMANTLED)  /*已经生产的订单产品才可以扫描*/
            ->where('o_order_type', $Search['order_type']);
        if (isset($Search['status']) && $Search['status'] != '') { /*扫描状态*/
            $this->HostDb->where('op_scan_status', $Search['status']);
        }

        if (!empty($Search['start_date'])) { /*扫描开始日期*/
            $this->HostDb->where('op_scan_start > ', $Search['start_date']);
        }

        if (!empty($Search['end_date'])) { /*扫描截止日期*/
            $this->HostDb->where('op_scan_end < ', $Search['end_date']);
        }

        if (!empty($Search['keyword'])) {
            $this->HostDb->group_start()
                ->like('op_remark', $Search['keyword'])
                ->or_like('op_num', $Search['keyword'])
                ->or_like('o_dealer', $Search['keyword'])
                ->or_like('o_owner', $Search['keyword'])
                ->or_like('o_remark', $Search['keyword'])
                ->group_end();
        }
        $Query = $this->HostDb->get();

        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Search['pagesize']) == 0){
                $Pn = intval($Row['num']/$Search['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * 包装统计
     * @param $Search
     * @return array|bool
     */
    public function select_packed ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num_packed($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql, FALSE)
                    ->from('order_product')
                    ->join('order', 'o_id = op_order_id', 'left')
                    ->where('op_status > ', OP_PRODUCING);  /*已经生产的订单产品才可以扫描*/

                if(isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('op_remark', $Search['keyword'])
                        ->or_like('op_num', $Search['keyword'])
                        ->or_like('o_dealer', $Search['keyword'])
                        ->or_like('o_owner', $Search['keyword'])
                        ->or_like('o_remark', $Search['keyword'])
                        ->group_end();
                }

                $this->HostDb->order_by('op_id', 'desc');
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();

                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MINUTES);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品';
            }
        }
        return $Return;
    }
    private function _page_num_packed ($Search) {
        $this->HostDb->select('count(op_id) as num', FALSE)
            ->from('order_product')
            ->join('order', 'o_id = op_order_id', 'left')
            ->where('op_status > ', OP_PRODUCING); /*已经生产的订单产品才可以扫描*/
        if (!empty($Search['keyword'])) {
            $this->HostDb->group_start()
                ->like('op_remark', $Search['keyword'])
                ->or_like('op_num', $Search['keyword'])
                ->or_like('o_dealer', $Search['keyword'])
                ->or_like('o_owner', $Search['keyword'])
                ->or_like('o_remark', $Search['keyword'])
                ->group_end();
        }
        $Query = $this->HostDb->get();

        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Search['pagesize']) == 0){
                $Pn = intval($Row['num']/$Search['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }
    /**
     * 等待入库的订单产品
     * @param $Search
     * @return array|bool
     */
    public function select_warehouse_waiting_in ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_warehouse_waiting_in_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('order_product')
                            ->join('order', 'o_id = op_order_id', 'left')
                            ->where('op_status', OP_INED)
                            ->where('o_status > ', O_PRODUCE)
                            ->where('o_status < ', O_DELIVERING)
                            ->where('op_warehouse_num is null');
                if (!empty($Search['start_date'])) {
                    $this->HostDb->where('op_inned_datetime > ', $Search['start_date']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('op_num', $Search['keyword'])
                        ->group_end();
                }
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])
                            ->order_by('op_inned_datetime')->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MINUTES);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品';
            }
        }
        return $Return;
    }
    private function _page_warehouse_waiting_in_num($Search){
        $this->HostDb->select('count(op_id) as num', FALSE)->from('order_product')
            ->join('order', 'o_id = op_order_id', 'left')
            ->where('op_status', OP_INED)
            ->where('o_status > ', O_PRODUCE)
            ->where('o_status < ', O_DELIVERING)
            ->where('op_warehouse_num is null');
        if (!empty($Search['start_date'])) {
            $this->HostDb->where('op_inned_datetime > ', $Search['start_date']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('op_num', $Search['keyword'])
                ->group_end();
        }
        $Query = $this->HostDb->get();

        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Search['pagesize']) == 0){
                $Pn = intval($Row['num']/$Search['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * 获取拟定发货计划的订单产品
     * @param $Vs
     * @return array|bool
     */
    public function select_work_out($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Vs);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('order_product')
                ->join('order', 'o_id = op_order_id', 'left')
                ->where_in('o_id', $Vs)
                ->where('op_status >= ', OP_INED)
                ->where('o_status > ', O_PRODUCING)
                ->where('op_product_id != ', SERVER)
                ->having('wait_delivery > ', ZERO)
                ->order_by('o_dealer_id')
                ->order_by('o_id')
                ->order_by('op_id')
                ->get();
            if ($Query->num_rows() > ZERO) {
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $Query->num_rows(),
                    'p' => ONE,
                    'pn' => ONE,
                    'pagesize' => ALL_PAGESIZE
                );
                $this->cache->save($Cache, $Return, MINUTES);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品';
            }
        }
        return $Return;
    }
    /**
     * 拣货单明细
     * @param $Search
     * @return array|bool
     */
    public function select_pick_sheet_detail ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_pick_sheet_detail_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Scanned = $this->HostDb->select('ps_order_product_num, ps_stock_outted_id, count(ps_stock_outted_id) as scanned')
                    ->from('pick_scan')
                    ->where('ps_stock_outted_id', $Search['v'])
                    ->group_by('ps_order_product_num')
                    ->get_compiled_select();
                $Query = $this->HostDb->select($Sql)->from('order_product')
                    ->join('order', 'o_id = op_order_id', 'left')
                    ->join('(' . $Scanned . ') as A', 'A.ps_order_product_num = op_num', 'left')
                    ->where_in('op_id', $Search['order_product_id'])
                    ->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])
                    ->order_by('op_num')
                    ->order_by('no')->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MINUTES);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品';
            }
        }
        return $Return;
    }

    private function _page_pick_sheet_detail_num($Search){
        $Query = $this->HostDb->select('count(op_id) as num', FALSE)->from('order_product')
            ->where_in('op_id', $Search['order_product_id'])
            ->get();

        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Search['pagesize']) == 0){
                $Pn = intval($Row['num']/$Search['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * 打印拣货单
     * @param $Search
     * @return bool
     */
    public function select_pick_sheet_print ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Scanned = $this->HostDb->select('ps_order_product_num, ps_stock_outted_id, count(ps_stock_outted_id) as scanned')
                ->from('pick_scan')
                ->where('ps_stock_outted_id', $Search['v'])
                ->group_by('ps_order_product_num')
                ->get_compiled_select();
            $Query = $this->HostDb->select($Sql)->from('order_product')
                ->join('order', 'o_id = op_order_id', 'left')
                ->join('pay_status', 'ps_name = o_pay_status', 'left')
                ->join('(' . $Scanned . ') as A', 'A.ps_order_product_num = op_num', 'left')
                ->where_in('op_id', $Search['order_product_id'])
                ->order_by('op_product_id')
                ->order_by('no')->get();
            $Return = $Query->result_array();
            $this->cache->save($Cache, $Return, MINUTES);
        }
        return $Return;
    }

    /**
     * 订单是否可以拆单
     * @param $V
     */
    public function is_order_dismantlable ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('order_product')
            ->join('product', 'p_id = op_product_id', 'left')
            ->join('order', 'o_id = op_order_id', 'left')
            ->where('o_status > ', O_REMOVE) /*订单没有删除*/
            ->where('o_status < ', O_DISMANTLED);  /*订单没有确认拆单*/
        if (preg_match(REG_ORDER_PRODUCT_STRICT, $V)) {
            $this->HostDb->where('op_num', $V);
        } else {
            $this->HostDb->where('op_id', $V);
        }
        $Query = $this->HostDb->limit(ONE)->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        }
        return $Return;
    }

    /**
     * 判斷訂單是否可以添加售後
     * @param $V
     * @return bool
     */
    public function is_order_post_salable ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('order_product')
            ->join('product', 'p_id = op_product_id', 'left')
            ->join('order', 'o_id = op_order_id', 'left')
            ->join('dealer', 'd_id = o_dealer_id', 'left')
            ->where_in('p_id', array(FITTING, OTHER, SERVER)) // 只有這三個可以在確認后修改
            ->where('op_status > ', OP_DISMANTLING)
            ->where('o_status > ', O_WAIT_SURE);  /*已经确认的订单*/
        if (preg_match(REG_ORDER_PRODUCT_STRICT, $V)) {
            $this->HostDb->where('op_num', $V);
        } else {
            $this->HostDb->where('op_id', $V);
        }
        $Query = $this->HostDb->limit(ONE)->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        }
        return $Return;
    }
    /**
     * 判断订单产品是否可以拆单
     * 拆单时，清除板块数据时使用
     * @param $V
     */
    public function is_dismantlable ($V, $Num = '') {
        if (empty($V) && empty($Num)) {
            return false;
        }
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('order_product')
            ->join('product', 'p_id = op_product_id', 'left')
            ->join('order', 'o_id = op_order_id', 'left');

        if (!empty($V)) {
            $this->HostDb->where('op_id', $V);
        }
        if (!empty($Num)) {
            $this->HostDb->where('op_num', $Num);
        }
        $Query = $this->HostDb->where('op_status > ', OP_REMOVE) /*订单没有删除*/
            ->where('op_status < ', OP_DISMANTLED)  /*订单没有确认拆单*/
            ->limit(1)->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        }
        return $Return;
    }
    public function are_dismantlable ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product')
            ->join('product', 'p_id = op_product_id', 'left')
            ->where_in('op_id', $Vs)
            ->where('op_status > ', OP_REMOVE) /*订单没有删除*/
            ->where('op_status < ', OP_DISMANTLED)  /*订单没有确认拆单*/
            ->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    /**
     * 判断该订单下所有产品已经拆单
     * @param $OrderId
     * @return bool
     */
    public function are_dismantled ($OrderId) {
        $Query = $this->HostDb->select('op_id')
            ->from('order_product')
            ->where('op_order_id', $OrderId)
            ->where_in('op_status', array(OP_CREATE, OP_DISMANTLING))
            ->limit(ONE)
            ->get();
        return $Query->num_rows() == 0;
    }

    /**
     * 判断状态
     * @param $Vs
     * @param $Status
     * @return bool
     */
    public function are_status ($Vs, $Status) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('order_product')
            ->where_in('op_id', $Vs)
            ->where_in('op_status', $Status)
            ->get();
        $Return = false;
        if ($Query->num_rows() > ZERO) {
            $Return = $Query->result_array();
        }
        return $Return;
    }
    /**
     * 订单产品编码
     * @param $OrderProductNum
     * @return array|bool
     */
    public function is_exist($OrderProductNum = '', $V = ZERO) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $OrderProductNum . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('order_product')
                ->join('product', 'p_id = op_product_id', 'left')
                ->join('order', 'o_id = op_order_id', 'left');
            if (!empty($OrderProductNum)) {
                $this->HostDb->where('op_num', $OrderProductNum);
            }
            if (!empty($V)) {
                $this->HostDb->where('op_id', $V);
            }
            $Query = $this->HostDb->limit(ONE)->get();
            $Return = $Query->row_array();
            $this->cache->save($Cache, $Return, MINUTES);
        }
        return $Return;
    }

    /**
     *
     * @param $Brothers
     * @param $Self
     * @return bool
     */
    public function select_brothers($Brothers, $Self){
        $Cache = $this->_Cache.__FUNCTION__.'_'.$Self;
        $Item = $this->_Item.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql,  FALSE)
                ->from('order_product')
                ->like('op_num', $Brothers)
                ->where('op_num != ', $Self)
                ->where('op_status >= ', OP_DISMANTLED)
                ->order_by('op_num')
                ->get();

            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $Return = false;
            }
        }
        return $Return;
    }

    public function select_brothers_by_order_id ($OrderV) {
        $Query = $this->HostDb->select('op_num as num')->from('order_product')
            ->where('op_order_id', $OrderV)
            ->where('op_status >= ', OP_CREATE)
            ->where_in('op_product_id', array(CABINET, WARDROBE, DOOR, WOOD))
            ->order_by('op_product_id')
            ->order_by('op_id')
            ->get();
        if ($Query->num_rows() > 0) {
            return $Query->result_array();
        }
        return false;
    }
    /**
     * 判断是不是所有订单产品都已经入库
     * @param $OrderId
     * @return bool
     */
    public function is_all_inned ($OrderId) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('order_product')
            ->join('product', 'p_id = op_product_id', 'left')
            ->where('op_order_id', $OrderId)
            ->where('op_product_id != ', SERVER)
            ->where('op_status != ', OP_REMOVE)
            ->get();
        if ($Query->num_rows() > 0) {
            return $Query->result_array();
        } else {
            return false;
        }
    }

    /**
     * 通过订单产品获取订单Id
     * @param $OrderProductId
     * @return bool
     */
    public function select_order_id_by_order_product_id ($OrderProductIds) {
        $OrderProductIds = is_array($OrderProductIds) ? $OrderProductIds : array($OrderProductIds);
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode(',', $OrderProductIds);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('order_product')
                        ->join('order', 'o_id  = op_order_id', 'left')
                        ->where_in('op_id', $OrderProductIds)
                        ->group_by('o_id')
                        ->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
            } else {
                $GLOBALS['error'] = '没有符合要求的订单';
            }
        }
        return $Return;
    }

    /**
     * 统计已发货数量
     * @param $OrderId
     * @return bool
     */
    public function select_delivered ($OrderId) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product')
            ->where_in('op_order_id', $OrderId)
            ->group_by('op_order_id')
            ->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        } else {
            $GLOBALS['error'] = '没有符合要求的已发货订单';
        }
        return $Return;
    }

    /**
     * 获取已发货件数
     * @param $V
     * @param $OrderId
     * @return bool
     */
    public function select_delivered_by_v ($V, $OrderId = array()) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('order_product')
            ->where_in('op_id', $V);
        if (!empty($OrderId)) {
            $this->HostDb->where_in('op_order_id', $OrderId);
        }
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    /**
     * 获取当前订单的工作流
     */
    public function select_current_workflow($Opid){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product')
            ->join('workflow_order_product', 'wop_id = op_status', 'left')
            ->where('op_id', $Opid)->limit(1)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->row_array();
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 获取BD文件列表
     * @param $Search
     * @return array|bool
     */
    public function select_bd ($Search) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Search);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Search['pn'] = $this->_page_bd($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item, $this->_Module);
                $this->HostDb->select($Sql, FALSE);
                $this->HostDb->from('order_product')
                    ->join('order', 'o_id = op_order_id', 'left')
                    ->join('user', 'u_id = op_dismantle', 'left')
                    ->where_in('op_product_id', array(CABINET, WARDROBE))
                    ->where('op_status > ', OP_DISMANTLING)
                    ->where('o_status > ', O_PRODUCE);
                if ($Search['status'] == NO) {
                    $this->HostDb->where('op_bd', YES);
                } else {
                    $this->HostDb->where('op_bd', TWO);
                }

                if(!empty($Search['keyword'])){
                    $this->HostDb->group_start()
                        ->like('o_num', $Search['keyword'])
                        ->group_end();
                }

                $this->HostDb->order_by('o_id', 'desc')->order_by('op_product_id')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize']);

                $Query = $this->HostDb->get();
                if($Query->num_rows() > 0){
                    $Result = $Query->result_array();
                    $Return = array(
                        'content' => $Result,
                        'num' => $this->_Num,
                        'p' => $Search['p'],
                        'pn' => $Search['pn']
                    );
                    $this->cache->save($Cache, $Return, HOURS);
                }
            }else{
                $GLOBALS['error'] = '没有符合要求需要导出BD的订单!';
            }
        }
        return $Return;
    }
    private function _page_bd ($Search) {
        $this->HostDb->select('count(op_id) as num', FALSE)
            ->from('order_product')
            ->join('order', 'o_id = op_order_id', 'left')
            ->where_in('op_product_id', array(CABINET, WARDROBE))
            ->where('op_status > ', OP_DISMANTLING)
            ->where('o_status > ', O_PRODUCE);
        if ($Search['status'] == NO) {
            $this->HostDb->where('op_bd', YES);
        } else {
            $this->HostDb->where('op_bd', TWO);
        }

        if(!empty($Search['keyword'])){
            $this->HostDb->group_start()
                ->like('o_num', $Search['keyword'])
                ->group_end();
        }

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Search['pagesize']) == 0){
                $Pn = intval($Row['num']/$Search['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Search['pagesize'])+1;
            }
            log_message('debug', 'Num is '.$Row['num'].' and Pagesize is'.$Search['pagesize'].' and Page Nums is'.$Pn);
            return $Pn;
        }else{
            return false;
        }
    }
    /**
     * `generate_order_product_num`(IN `id` INT(10), IN `num` INT(5), IN `product_id` SMALLINT(4), `IN `code` VARCHAR(2), IN `product` VARCHAR(64), OUT `opid` VARCHAR(1024), OUT `orderProductNum` VARCHAR(1024))
     * begin
    declare maxNum int default 0;
    declare step int default 1;
    declare orderNum varchar(16) default '';
    declare prefix varchar(64) default '';
    declare old varchar(64) default '';
    declare new varchar(64) default '';

    select o_num into orderNum from j_order where o_id = id;
    select concat(orderNum,'-',code) into prefix;
    select ifnull(op_num, '') into old
    from j_order_product where op_num REGEXP concat('^',prefix)
    order by substring(op_num,CHAR_LENGTH(prefix) + 1)+0 desc limit 1;
    if old != '' then
    set maxNum = convert(substring(old, CHAR_LENGTH(prefix) + 1), decimal);
    end if;

    set opid = '';
    set orderProductNum = '';
    label1: LOOP
    select concat(prefix, (maxNum+step)) into new;
    insert into j_order_product(op_order_id, op_num,op_product_id, op_product) values (id, new, product_id, product);
    select concat(orderProductNum, ',', new) into orderProductNum;
    select concat(opid, ',', LAST_INSERT_ID()) into opid;
    if step < num then
    set step = step + 1;
    ITERATE label1;
    END IF;
    LEAVE label1;
    END LOOP label1;
    END
     * @param $Product
     * @param $Order
     * @param $Set
     * @param $Data
     * @return array
     */
    public function insert ($Product, $Order, $Set = 1, $Data = array()) {
        if(is_array($Order) && 2 == count($Order)){
            $OrderId = $Order['v'];
        } else {
            $OrderId = $Order;
        }
        $Return = array();
        foreach ($Product as $key => $value){
            if(!!($this->HostDb->query("call generate_order_product_num($OrderId, $Set, '{$value['code']}', '{$value['name']}', {$value['v']}, @opid, @order_product_num)", FALSE))){
                if(!!($Query = $this->HostDb->query('select @opid as opid, @order_product_num as order_product_num', FALSE))){
                    $Row = $Query->row_array();
                    $Query->free_result();
                    $Opids = explode(',', trim($Row['opid'], ','));
                    $OrderProductNum = explode(',', trim($Row['order_product_num'], ','));
                    $Data['creator'] = $this->session->userdata('uid');
                    $Data['create_datetime'] = date('Y-m-d H:i:s');
                    $this->update($Data, $Opids);
                    foreach ($Opids as $ikey => $ivalue){
                        $Return[] = array(
                            'v' => $ivalue,
                            'order_product_num' => $OrderProductNum[$ikey]
                        );
                    }
                }else{
                    $GLOBALS['error'] = '获得新建订单产品编号失败!';
                    break;
                }
            }else{
                $GLOBALS['error'] = '订单产品新建失败!';
                break;
            }
        }
        $this->remove_cache($this->_Module);
        return $Return;
    }
    public function update($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('op_id', $Where);
        } else {
            $this->HostDb->where('op_id', $Where);
        }
        $this->HostDb->update('order_product', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('order_product', $Data, 'op_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 更新工作流
     * @param unknown $Set
     * @param unknown $Where
     */
    public function update_workflow($Set, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format_re($Set, $Item);
        if(is_array($Where)){
            $this->HostDb->where_in('op_id',$Where);
        }else{
            $this->HostDb->where('op_id',$Where);
        }

        $this->HostDb->update('order_product', $Set);
        log_message('debug', "Model order_product_model/update_workflow");
        $this->remove_cache($this->_Module);
        return true;
    }
}
