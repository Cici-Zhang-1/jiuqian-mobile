<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Finance_income_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Finance_income_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model finance/Finance_income_model Start!');
    }

    /**
     * Select from table finance_income
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('finance_income')
                    ->join('finance_account', 'fa_id = fi_finance_account_id', 'left')
                    ->join('user', 'u_id = fi_creator', 'left')
                    ->where('fa_intime', $Search['intime']);
                if (!empty($Search['finance_account_id'])) {
                    $this->HostDb->where_in('fi_finance_account_id', $Search['finance_account_id']);
                }
                if (!empty($Search['start_date'])) {
                    $this->HostDb->where('fi_bank_date >= ', $Search['start_date']);
                }
                if (!empty($Search['end_date'])) {
                    $this->HostDb->where('fi_bank_date <= ', $Search['end_date']);
                }
                if (!empty($Search['income_pay'])) {
                    $this->HostDb->where('fi_income_pay', $Search['income_pay']);
                }
                if (isset($Search['inned'])) {
                    $this->HostDb->where_in('fi_inned', $Search['inned']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('fi_remark', $Search['keyword'])
                        ->or_like('fi_dealer', $Search['keyword'])
                        ->group_end();
                }
                $Query = $this->HostDb->order_by('fi_id', 'desc')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的财务收入';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(fi_id) as num', FALSE)
            ->from('finance_income')
            ->join('finance_account', 'fa_id = fi_finance_account_id', 'left')
            ->where('fa_intime', $Search['intime']);
        if (!empty($Search['finance_account_id'])) {
            $this->HostDb->where_in('fi_finance_account_id', $Search['finance_account_id']);
        }
        if (!empty($Search['start_date'])) {
            $this->HostDb->where('fi_bank_date >= ', $Search['start_date']);
        }
        if (!empty($Search['end_date'])) {
            $this->HostDb->where('fi_bank_date <= ', $Search['end_date']);
        }
        if (!empty($Search['income_pay'])) {
            $this->HostDb->where('fi_income_pay', $Search['income_pay']);
        }
        if (isset($Search['inned'])) {
            $this->HostDb->where_in('fi_inned', $Search['inned']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('fi_remark', $Search['keyword'])
                ->or_like('fi_dealer', $Search['keyword'])
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
     * 判断收入是否有效
     * @param $V
     * @return bool
     */
    public function is_valid ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('finance_income')
            ->where('fi_id', $V)
            ->where('fi_status', YES);
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        } else {
            $GLOBALS['error'] = '没有符合搜索条件的财务收入';
        }
        return $Return;
    }
    /**
     * 判断是否是没有删除和没有认领的财务收入
     * @param $Vs
     * @return bool
     */
    public function are_valid ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Vs);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('finance_income')
                ->where_in('fi_id', $Vs)
                ->where('fi_status', YES);
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的财务收入';
            }
        }
        return $Return;
    }

    /**
     * 还未认领的有效进账记录
     * @param $V
     * @return bool
     */
    public function is_un_claimed ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('finance_income')
            ->where('fi_id', $V)
            ->where('fi_status', YES)
            ->where('fi_inned', NO);
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        } else {
            $GLOBALS['error'] = '没有符合搜索条件的财务收入';
        }
        return $Return;
    }

    public function is_un_inned ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('finance_income')
            ->where('fi_id', $V)
            ->where('fi_status', YES)
            ->where('fi_inned', NO);
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        } else {
            $GLOBALS['error'] = '没有符合搜索条件的财务收入';
        }
        return $Return;
    }
    /**
     * Insert data to table finance_income
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('finance_income', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入财务收入数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table finance_income
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('finance_income', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入财务收入数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table finance_income
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('fi_id', $Where);
        } else {
            $this->HostDb->where('fi_id', $Where);
        }
        $this->HostDb->update('finance_income', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table finance_income
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('finance_income', $Data, 'fi_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table finance_income
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('fi_id', $Where);
        } else {
            $this->HostDb->where('fi_id', $Where);
        }

        $this->HostDb->delete('finance_income');
        $this->remove_cache($this->_Module);
        return true;
    }
}
