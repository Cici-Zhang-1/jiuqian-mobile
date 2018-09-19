<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Finance_pay_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Finance_pay_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model finance/Finance_pay_model Start!');
    }

    /**
     * Select from table finance_pay
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('finance_pay')
                    ->join('finance_account AS O', 'O.fa_id = fp_finance_account_id', 'left')
                    ->join('finance_account AS I', 'I.fa_id = fp_in_finance_account_id', 'left')
                    ->join('user', 'u_id = fp_creator', 'left')
                    ->where('fp_status', $Search['status']);
                if (!empty($Search['finance_account_id'])) {
                    $this->HostDb->where('fp_finance_account_id', $Search['finance_account_id']);
                }
                if (!empty($Search['start_date'])) {
                    $this->HostDb->where('fp_bank_date >= ', $Search['start_date']);
                }
                if (!empty($Search['end_date'])) {
                    $this->HostDb->where('fp_bank_date <= ', $Search['end_date']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('fp_remark', $Search['keyword'])
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
                $GLOBALS['error'] = '没有符合搜索条件的财务支出';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(fp_id) as num', FALSE)
            ->where('fp_status', $Search['status']);
        if (!empty($Search['finance_account_id'])) {
            $this->HostDb->where('fp_finance_account_id', $Search['finance_account_id']);
        }
        if (!empty($Search['start_date'])) {
            $this->HostDb->where('fp_bank_date >= ', $Search['start_date']);
        }
        if (!empty($Search['end_date'])) {
            $this->HostDb->where('fp_bank_date <= ', $Search['end_date']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('fp_remark', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('finance_pay');

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
     * 判断是否有效
     * @param $Vs
     * @return bool
     */
    public function are_valid ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Vs);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('finance_pay')
                ->where_in('fp_id', $Vs)
                ->where('fp_status', YES);
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的财务支出';
            }
        }
        return $Return;
    }

    /**
     * Insert data to table finance_pay
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('finance_pay', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入财务支出数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table finance_pay
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('finance_pay', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入财务支出数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table finance_pay
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('fp_id', $Where);
        } else {
            $this->HostDb->where('fp_id', $Where);
        }
        $this->HostDb->update('finance_pay', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table finance_pay
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('finance_pay', $Data, 'fp_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table finance_pay
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('fp_id', $Where);
        } else {
            $this->HostDb->where('fp_id', $Where);
        }
        $this->HostDb->set('fp_status', NO);
        $this->HostDb->update('finance_pay');
        $this->remove_cache($this->_Module);
        return true;
    }
}
