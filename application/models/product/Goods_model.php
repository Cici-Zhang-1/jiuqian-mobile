<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Goods_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Goods_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model product/Goods_model Start!');
    }

    /**
     * Select from table goods
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('goods')
                    ->join('boolean_type', 'bt_name = g_status', 'left')
                    ->join('user', 'u_id = g_creator', 'left')
                    ->join('supplier', 's_id = g_supplier_id', 'left')
                    ->join('product', 'p_id = g_product_id', 'left');
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->like('g_name', $Search['keyword']);
                }
                if (!empty($Search['supplier_id'])) {
                    $this->HostDb->where('g_supplier_id', $Search['supplier_id']);
                }
                if (!empty($Search['product_id'])) {
                    $this->HostDb->where('g_product_id', $Search['product_id']);
                }
                $this->HostDb->where('g_status', $Search['status']);
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
                $GLOBALS['error'] = '没有符合搜索条件的商品';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(g_id) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->like('g_name', $Search['keyword']);
        }
        if (!empty($Search['supplier_id'])) {
            $this->HostDb->where('g_supplier_id', $Search['supplier_id']);
        }
        if (!empty($Search['product_id'])) {
            $this->HostDb->where('g_product_id', $Search['product_id']);
        }
        $this->HostDb->where('g_status', $Search['status']);
        $this->HostDb->from('goods');

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
     * Insert data to table goods
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('goods', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入商品数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table goods
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('goods', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入商品数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table goods
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('g_id', $Where);
        } else {
            $this->HostDb->where('g_id', $Where);
        }
        $this->HostDb->update('goods', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table goods
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('goods', $Data, 'g_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table goods
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('g_id', $Where);
        } else {
            $this->HostDb->where('g_id', $Where);
        }

        $this->HostDb->delete('goods');
        $this->remove_cache($this->_Module);
        return true;
    }
}
