<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/1
 * Time: 13:32
 */
class Finance_account_flow_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model finance/Finance_account_flow_model Start!');
    }

    /**
     * Select from table finance_pay
     * @param $Search
     * @return Array
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $FinanceAccountIncome = $this->_select_finance_account_income($Search);
                $FinanceAccountPay = $this->_select_finance_account_pay($Search);
                $Sql = $FinanceAccountIncome . ' UNION ALL ' . $FinanceAccountPay;
                $Sql = $Sql . ' ORDER BY flow_num DESC LIMIT ' . ($Search['p']-1)*$Search['pagesize'] . ', ' . $Search['pagesize'];
                $Query = $this->HostDb->query($Sql);
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

    private function _select_finance_account_income ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('finance_income')
            ->join('finance_account', 'fa_id = fi_finance_account_id', 'left')
            ->join('user', 'u_id = fi_creator', 'left')
            ->where('fi_finance_account_id', $Search['finance_account_id'])
            ->where('fi_status', YES);
        $this->HostDb->group_start()
                ->where('fa_intime', YES)
                ->or_group_start()
                    ->where('fa_intime', NO)
                    ->where('fi_inned', YES)
                ->group_end()
            ->group_end();
        if (!empty($Search['start_date'])) {
            $this->HostDb->where('fi_bank_date >= ', $Search['start_date']);
        }
        if (!empty($Search['end_date'])) {
            $this->HostDb->where('fi_bank_date <= ', $Search['end_date']);
        }

        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('fi_remark', $Search['keyword'])
                ->group_end();
        }
        return $this->HostDb->get_compiled_select();
    }
    private function _select_finance_account_pay ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('finance_pay')
            ->join('finance_account', 'fa_id = fp_finance_account_id', 'left')
            ->join('user', 'u_id = fp_creator', 'left')
            ->where('fp_finance_account_id', $Search['finance_account_id'])
            ->where('fp_status', YES);
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
        return $this->HostDb->get_compiled_select();
    }

    private function _page_num($Search){
        $Pn = false;
        $this->_Num = $this->_page_num_finance_account_income($Search) + $this->_page_num_finance_account_pay($Search);
        if ($this->_Num > ZERO) {
            if(intval($this->_Num%$Search['pagesize']) == 0){
                $Pn = intval($this->_Num/$Search['pagesize']);
            }else{
                $Pn = intval($this->_Num/$Search['pagesize'])+1;
            }
        }
        return $Pn;
    }

    private function _page_num_finance_account_income ($Search) {
        $this->HostDb->select('fi_id')->from('finance_income')
            ->join('finance_account', 'fa_id = fi_finance_account_id', 'left')
            ->where('fi_finance_account_id', $Search['finance_account_id'])
            ->where('fi_status', YES);

        $this->HostDb->group_start()
                ->where('fa_intime', YES)
                ->or_group_start()
                    ->where('fa_intime', NO)
                    ->where('fi_inned', YES)
                ->group_end()
            ->group_end();
        if (!empty($Search['start_date'])) {
            $this->HostDb->where('fi_bank_date >= ', $Search['start_date']);
        }
        if (!empty($Search['end_date'])) {
            $this->HostDb->where('fi_bank_date <= ', $Search['end_date']);
        }

        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('fi_remark', $Search['keyword'])
                ->group_end();
        }
        $Query = $this->HostDb->get();
        return $Query->num_rows();
    }
    private function _page_num_finance_account_pay ($Search) {
        $this->HostDb->select('fp_id')->from('finance_pay')
            ->join('finance_account', 'fa_id = fp_finance_account_id', 'left')
            ->where('fp_finance_account_id', $Search['finance_account_id'])
            ->where('fp_status', YES);

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
        $Query = $this->HostDb->get();
        return $Query->num_rows();
    }
}