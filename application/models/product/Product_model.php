<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Product_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Product_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model product/Product_model Start!');
    }

    /**
     * Select from table product
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('product')
                    ->join('production_line', 'pl_id = p_production_line', 'left');
                if (!empty($Search['code'])) {
                    $this->HostDb->where_in('p_code', $Search['code']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                }
                if (!empty($Search['undelete'])) {
                    $this->HostDb->where('p_delete', NO);
                }
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])
                    ->order_by('p_code')
                    ->order_by('p_name')->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的产品';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(p_id) as num', FALSE);
        if (!empty($Search['code'])) {
            $this->HostDb->where_in('p_code', $Search['code']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
        }
        if (!empty($Search['undelete'])) {
            $this->HostDb->where('p_delete', NO);
        }
        $this->HostDb->from('product');

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

    public function select_by_code ($Code) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Code;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('product')
                ->join('production_line', 'pl_id = p_production_line', 'left')
                ->where('p_code', $Code)
                ->where('p_delete', NO)
                ->limit(ONE);

            $Query = $this->HostDb->get();
            $Return = $Query->row_array();
            $this->cache->save($Cache, $Return, MONTHS);
        }
        return $Return;
    }
    public function select_product_code_by_id ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Vs);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('product')
                ->join('production_line', 'pl_id = p_production_line', 'left')
                ->where_in('p_id', $Vs);

            $Query = $this->HostDb->get();
            $Return = $Query->result_array();
            $this->cache->save($Cache, $Return, MONTHS);
        }
        return $Return;
    }

    public function is_exist ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('product')
            ->join('production_line', 'pl_id = p_production_line', 'left')
            ->where('p_id', $V);

        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            return $Query->row_array();
        } else {
            return false;
        }
    }
    /**
     * Insert data to table product
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('product', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入产品数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table product
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('product', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入产品数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table product
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('p_id', $Where);
        } else {
            $this->HostDb->where('p_id', $Where);
        }
        $this->HostDb->update('product', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table product
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('product', $Data, 'p_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table product
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('p_id', $Where);
        } else {
            $this->HostDb->where('p_id', $Where);
        }

        $this->HostDb->delete('product');
        $this->remove_cache($this->_Module);
        return true;
    }
}
