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
                $Query = $this->HostDb->select($Sql)->from('warehouse_order_product')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
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
