<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Income_pay_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Income_pay_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model finance/Income_pay_model Start!');
    }

    /**
     * Select from table income_pay
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('income_pay');
                if ($Search['finance_activity_type'] != '') {
                    $this->HostDb->where('ip_finance_activity_type', $Search['finance_activity_type']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->like('ip_name', $Search['keyword']);
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
                $GLOBALS['error'] = '没有符合搜索条件的收支类型';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(ip_name) as num', FALSE);
        if ($Search['finance_activity_type'] != '') {
            $this->HostDb->where('ip_finance_activity_type', $Search['finance_activity_type']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->like('ip_name', $Search['keyword']);
        }
        $this->HostDb->from('income_pay');

        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
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
     * Insert data to table income_pay
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('income_pay', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入收支类型数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table income_pay
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('income_pay', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入收支类型数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table income_pay
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('ip_name', $Where);
        } else {
            $this->HostDb->where('ip_name', $Where);
        }
        $this->HostDb->update('income_pay', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table income_pay
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('income_pay', $Data, 'ip_name');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table income_pay
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('ip_name', $Where);
        } else {
            $this->HostDb->where('ip_name', $Where);
        }

        $this->HostDb->delete('income_pay');
        $this->remove_cache($this->_Module);
        return true;
    }
}
