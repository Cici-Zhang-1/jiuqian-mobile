<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Warehouse_order_product_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Warehouse_order_product_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model warehouse/Warehouse_order_product_model Start!');
    }

    /**
     * Select from table warehouse_order_product
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('warehouse_order_product')
                    ->join('user as CREATOR', 'CREATOR.u_id = wop_creator', 'left')
                    ->join('user as PICKER', 'PICKER.u_id = wop_picker', 'left');
                if (isset($Search['v']) && $Search['v'] != '') {
                    $this->HostDb->where('wop_warehouse_num', $Search['v']);
                }
                if (isset($Search['status']) && $Search['status'] == 0) { // 只获取没有出库的订单
                    $this->HostDb->where('wop_picker', 0);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                            ->like('wop_order_product_num', $Search['keyword'])
                            ->or_like('wop_order_product_num', $Search['keyword'])
                        ->group_end();
                    // $this->HostDb->like('wop_order_product_num', $Search['keyword']);
                }
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])
                    ->order_by('wop_picker')->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的库位订单产品';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(wop_id) as num', FALSE);
        if (isset($Search['v']) && $Search['v'] != '') {
            $this->HostDb->where('wop_warehouse_num', $Search['v']);
        }
        if (isset($Search['status']) && $Search['status'] == 0) { // 只获取没有出库的订单
            $this->HostDb->where('wop_picker', 0);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                    ->like('wop_order_product_num', $Search['keyword'])
                    ->or_like('wop_order_product_num', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('warehouse_order_product');

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
     * 库内订单产品
     * @param $OrderProductNum
     * @return bool
     */
    public function select_order_product_inned ($OrderProductNum) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $OrderProductNum);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('warehouse_order_product')
                ->join('order_product', 'op_num = wop_order_product_num', 'left')
                ->where_in('wop_order_product_num', $OrderProductNum)
                ->get();
            $Return = $Query->result_array();
            $this->cache->save($Cache, $Return, MONTHS);
        }
        return $Return;
    }

    public function select_order_inned ($OrderNum) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $OrderNum);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('warehouse_order_product')
                ->join('order', 'o_num = wop_order_num', 'left')
                ->where_in('wop_order_num', $OrderNum)
                ->get();
            $Return = $Query->result_array();
            $this->cache->save($Cache, $Return, MONTHS);
        }
        return $Return;
    }
    /**
     * 判断是否在库内
     * @param $WarehouseOrderProductV
     * @return array|bool
     */
    public function is_in($WarehouseOrderProductV) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $WarehouseOrderProductV);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('warehouse_order_product')
                ->join('order_product', 'op_num = wop_order_product_num', 'left')
                ->join('order', 'o_id = op_order_id', 'left')
                ->where_in('wop_id', $WarehouseOrderProductV)
                ->where('wop_picker', 0)
                ->get();
            $Return = $Query->result_array();
            $this->cache->save($Cache, $Return, MONTHS);
        }
        return $Return;
    }

    /**
     * 通过v判断是否存在订单在库存中
     * @param $StockOuttedV
     */
    public function is_in_by_order_product_v($OrderProductId) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($OrderProductId);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('warehouse_order_product')
                ->join('order_product', 'op_num = wop_order_product_num', 'left')
                ->where_in('op_id', $OrderProductId)
                ->where('wop_picker', ZERO)
                ->get();
            $Return = $Query->result_array();
            $this->cache->save($Cache, $Return, MONTHS);
        }
        return $Return;
    }
    /**
     * 判断是否库位已经全部出库
     * @param $Warehouse
     * @return bool
     */
    public function is_not_out($Warehouse) {
        $Query = $this->HostDb->select('wop_warehouse_num as warehouse_v')->from('warehouse_order_product')
            ->where('wop_picker', ZERO)->where_in('wop_warehouse_num', $Warehouse)->get();
        if ($Query->num_rows() > ZERO) {
            return $Query->result_array();
        } else {
            return false;
        }
    }

    /**
     * Insert data to table warehouse_order_product
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('warehouse_order_product', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入库位订单产品数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table warehouse_order_product
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('warehouse_order_product', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入库位订单产品数据失败!';
            return false;
        }
    }

    public function insert_ignore_batch($Set){
        $Item = $this->_Item.__FUNCTION__;
        $value = array();
        foreach ($Set as $key => $value){
            $value = $this->_format($value, $Item);
            $Set[$key] = '(\''.implode('\',\'', $value).'\')';
        }
        $Keys = array_keys($value);
        $Key = '('.implode(',', $Keys).')';
        $Values = implode(',', $Set);
        $this->remove_cache($this->_Cache);
        return $this->HostDb->query("INSERT IGNORE INTO j_warehouse_order_product {$Key} VALUES {$Values}");
    }

    public function replace_batch($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        log_message('debug', "Model warehouse_order_product_model/replace_batch Start");
        if($this->HostDb->replace_batch('warehouse_order_product', $Data)){
            log_message('debug', "Model warehouse_order_product_model/replace_batch Success!");
            return $this->HostDb->affected_rows();
        }else{
            log_message('debug', "Model warehouse_order_product_model/replace_batch Error");
            return false;
        }
    }
    /**
     * Update the data of table warehouse_order_product
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('wop_id', $Where);
        } else {
            $this->HostDb->where('wop_id', $Where);
        }
        $this->HostDb->update('warehouse_order_product', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 移库更新
     * @param $Data
     * @param $Where
     * @return bool
     */
    public function update_move ($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;

        $this->HostDb->select('wop_order_product_num, wop_classify')
            ->from('warehouse_order_product');
        if (is_array($Where)) {
            $this->HostDb->where_in('wop_id', $Where);
        } else {
            $this->HostDb->where('wop_id', $Where);
        }
        $QuerySql = $this->HostDb->get_compiled_select();
        $Query = $this->HostDb->select('wop_id')
            ->from('warehouse_order_product')
            ->where('wop_warehouse_num', $Data['warehouse_v'])
            ->where_in('(wop_order_product_num, wop_classify)', $QuerySql, false)
            ->get();
        if ($Query->num_rows() > 0) {
            $Result = $Query->result_array();
            foreach ($Result as $Key => $Value) {
                $Result[$Key] = $Value['wop_id'];
            }
            $this->delete($Result);
        }
        if (is_array($Where)) {
            $this->HostDb->where_in('wop_id', $Where);
        } else {
            $this->HostDb->where('wop_id', $Where);
        }
        $Data = $this->_format_re($Data, $Item);
        $this->HostDb->update('warehouse_order_product', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 出库时更新，只更新没有出库记录的
     * @param $Data
     * @param $Where
     * @return bool
     */
    /* public function update_out($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('wop_id', $Where);
        } else {
            $this->HostDb->where('wop_id', $Where);
        }
        $this->HostDb->where('wop_picker', 0);
        $this->HostDb->update('warehouse_order_product', $Data);
        $this->remove_cache($this->_Module);
        return true;
    } */
    /**
     * 批量更新table warehouse_order_product
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('warehouse_order_product', $Data, 'wop_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table warehouse_order_product
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('wop_id', $Where);
        } else {
            $this->HostDb->where('wop_id', $Where);
        }

        $this->HostDb->delete('warehouse_order_product');
        $this->remove_cache($this->_Module);
        return true;
    }
}
