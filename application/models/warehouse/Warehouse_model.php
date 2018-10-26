<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Warehouse_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Warehouse_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model warehouse/Warehouse_model Start!');
    }

    /**
     * Select from table warehouse
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            if ($Search['status'] != '') {
                $Search['status'] = explode(',', $Search['status']);
            }
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('warehouse')
                    ->join('warehouse_status', 'ws_name = w_status', 'left');
                if (is_array($Search['status'])) {
                    $this->HostDb->where_in('w_status', $Search['status']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                            ->like('w_num', $Search['keyword'])
                        ->group_end();
                }
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->
                    order_by('w_num')->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的库位';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(w_num) as num', FALSE);
        if (is_array($Search['status'])) {
            $this->HostDb->where_in('w_status', $Search['status']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('w_num', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('warehouse');

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
     * 判断是否存在
     * @param $V
     * @return bool
     */
    public function is_exist($V) {
        $Item = $this->_Item . __FUNCTION__;
        if (is_array($V)) {
            $Cache = $this->_Cache . __FUNCTION__ . implode(',', $V);
        } else {
            $Cache = $this->_Cache . __FUNCTION__ . $V;
        }

        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('warehouse');
            if (is_array($V)) {
                $Query = $this->HostDb->where_in('w_num', $V)->get();
                $Return = $Query->result_array();
            } else {
                $Query = $this->HostDb->where('w_num', $V)->get();
                $Return = $Query->row_array();
            }
            $this->cache->save($Cache, $Return, MONTHS);
        }
        return $Return;
    }

    public function select_height($Height, $Limit) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode(',', $Height) . $Limit;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('warehouse')
                    ->where_in('w_height', $Height)
                    ->where('w_status', 1)
                    ->order_by('w_num')
                    ->limit($Limit)
                ->get();
            $Return = $Query->result_array();
            $this->cache->save($Cache, $Return, MONTHS);
        }
        return $Return;
    }

    /**
     * 最大面积和
     * @param $V
     */
    public function select_sum_max_area ($Vs) {
        $Query = $this->HostDb->select_sum('w_max')->from('warehouse')
            ->where_in('w_num', $Vs)
            ->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
            return $Return['w_max'];
        }
        return false;
    }
    /**
     * Insert data to table warehouse
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('warehouse', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入库位数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table warehouse
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('warehouse', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入库位数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table warehouse
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('w_num', $Where);
        } else {
            $this->HostDb->where('w_num', $Where);
        }
        $this->HostDb->update('warehouse', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 通过warehouse_area_num改变货位
     * @param $Data
     * @param $Where
     * @return bool
     */
    public function update_by_warehouse_area_num($Data, $Where) {
        $this->HostDb->select('ws_num');
        if (is_array($Where)) {
            $this->HostDb->where_in('ws_warehouse_area_num', $Where);
        } else {
            $this->HostDb->where('ws_warehouse_area_num', $Where);
        }
        $Query = $this->HostDb->get_compiled_select('warehouse_shelve');

        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        $this->HostDb->where('w_warehouse_shelve_num in', '(' . $Query . ')', false);
        $this->HostDb->update('warehouse', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 通过warehouse_shelve_num改变货位
     * @param $Data
     * @param $Where
     * @return bool
     */
    public function update_by_warehouse_shelve_num($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('w_warehouse_shelve_num', $Where);
        } else {
            $this->HostDb->where('w_warehouse_shelve_num', $Where);
        }
        $this->HostDb->update('warehouse', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table warehouse
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('warehouse', $Data, 'w_num');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table warehouse
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('w_num', $Where);
        } else {
            $this->HostDb->where('w_num', $Where);
        }

        $this->HostDb->delete('warehouse');
        $this->remove_cache($this->_Module);
        return true;
    }
}
