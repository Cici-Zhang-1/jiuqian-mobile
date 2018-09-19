<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Supplier_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Supplier_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model supplier/Supplier_model Start!');
    }

    /**
     * Select from table supplier
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('supplier')
                    ->join('user', 'u_id = s_creator', 'creator');
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('s_name', $Search['keyword'])
                        ->group_end();
                }
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
                $GLOBALS['error'] = '没有符合搜索条件的供应商';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(s_id) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('s_name', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('supplier');

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

    public function is_exist ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('supplier')
            ->where('s_id', $V)
            ->limit(ONE)
            ->get();
        return $Query->row_array();
    }
    private function _is_exist ($Data, $Where = array()) {
        $this->HostDb->select('s_id')
            ->from('supplier')
            ->group_start()
            ->where('s_name', $Data['s_name'])
            ->or_where('s_code', $Data['s_code'])
            ->group_end();
        if (!empty($Where)) {
            $this->HostDb->where_not_in('s_id', is_array($Where) ? $Where : array($Where));
        }
        $Query = $this->HostDb->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table supplier
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if ($this->_is_exist($Data)) {
            $GLOBALS['error'] = $Data['s_name'] . $Data['s_code'] . '已经存在!';
            return false;
        }
        if($this->HostDb->insert('supplier', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入供应商数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table supplier
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('supplier', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入供应商数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table supplier
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if ($this->_is_exist($Data, $Where)) {
            $GLOBALS['error'] = $Data['s_name'] . $Data['s_code'] . '已经存在!';
            return false;
        }
        if (is_array($Where)) {
            $this->HostDb->where_in('s_id', $Where);
        } else {
            $this->HostDb->where('s_id', $Where);
        }
        $this->HostDb->update('supplier', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table supplier
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('supplier', $Data, 's_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table supplier
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('s_id', $Where);
        } else {
            $this->HostDb->where('s_id', $Where);
        }

        $this->HostDb->delete('supplier');
        $this->remove_cache($this->_Module);
        return true;
    }
}
