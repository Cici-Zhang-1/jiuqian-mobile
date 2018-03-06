<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月22日
 * @author Zhangcc
 * @version
 * @des
 * 财政支付
 */
class Finance_pay_model extends Base_Model{
    private $_Module = 'finance';
    private $_Model;
    private $_Item;
    private $_Cache;

    private $_Num;
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';

        log_message('debug', 'Model '.$this->_Item.' start!');
    }

    public function select($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.implode('_', $Con).__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(!empty($Con['type'])){
                $Con['type'] = explode(',', $Con['type']);
            }
            
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page_num($Con);
            }
            if(!empty($Con['pn'])){
                $Sql = $this->_unformat_as($Item, $this->_Module);
                $this->HostDb->select($Sql,  FALSE);
                $this->HostDb->from('finance_pay');
                $this->HostDb->join('finance_account as a', 'a.fa_id = fp_finance_account_id', 'left');
                $this->HostDb->join('finance_account as b', 'b.fa_id = fp_in_finance_account_id', 'left');
                $this->HostDb->join('user', 'u_id = fp_creator', 'left');

                if(!empty($Con['account'])){
                    $this->HostDb->where("fp_finance_account_id in ({$Con['account']})");
                }
                if(!empty($Con['type'])){
                    $this->HostDb->where_in('fp_type', $Con['type']);
                }
                if(!empty($Con['start_date'])){
                    $this->HostDb->where('fp_bank_date > ', $Con['start_date']);
                }
                if(!empty($Con['end_date'])){
                    $this->HostDb->where('fp_bank_date < ', $Con['end_date']);
                }

                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                                    ->like('fp_dealer', $Con['keyword'])
                                    ->or_like('fp_remark', $Con['keyword'])
                                ->group_end();
                }
                 
                $this->HostDb->order_by('fp_create_datetime', 'desc');
                $this->HostDb->limit($Con['pagesize'], ($Con['p']-1)*$Con['pagesize']);
                 
                $Query = $this->HostDb->get();
                if($Query->num_rows() > 0){
                    $Result = $Query->result_array();
                    $Return = array(
                        'content' => $Result,
                        'num' => $this->_Num,
                        'p' => $Con['p'],
                        'pn' => $Con['pn']
                    );
                    $this->cache->save($Cache, $Return, HOURS);
                }
            }else{
                $GLOBALS['error'] = '没有符合要求需要拆单的订单!';
            }
        }
        return $Return;
    }

    private function _page_num($Con){
        $this->HostDb->select('count(fp_id) as num', FALSE);
        $this->HostDb->from('finance_pay');

        if(!empty($Con['account'])){
            $this->HostDb->where("fp_finance_account_id in ({$Con['account']})");
        }
        if(!empty($Con['type'])){
            $this->HostDb->where_in('fp_type', $Con['type']);
        }
        if(!empty($Con['start_date'])){
            $this->HostDb->where('fp_bank_date > ', $Con['start_date']);
        }
        if(!empty($Con['end_date'])){
            $this->HostDb->where('fp_bank_date < ', $Con['end_date']);
        }

        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                            ->like('fp_dealer', $Con['keyword'])
                            ->or_like('fp_remark', $Con['keyword'])
                        ->group_end();
        }

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Con['pagesize']) == 0){
                $Pn = intval($Row['num']/$Con['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Con['pagesize'])+1;
            }
            log_message('debug', 'Num is '.$Row['num'].' and Pagesize is'.$Con['pagesize'].' and Page Nums is'.$Pn);
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * 判断是否为有效的财务进账(存在，没有被任亮)
     * @param unknown $Id
     */
    public function is_valid_finance_pay($Id){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item, $this->_Module);
        $this->HostDb->select($Sql,  FALSE);
        $this->HostDb->from('finance_pay');
        $this->HostDb->join('finance_account', 'fa_id = fp_finance_account_id', 'left');
        if(is_array($Id)){
            $Multiple = true;
            $this->HostDb->where_in('fp_id', $Id);
        }else{
            $Multiple = false;
            $this->HostDb->where('fp_id', $Id);
        }
        $this->HostDb->where('fp_status', 1);
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            if($Multiple){
                $Return = $Query->result_array();
            }else {
                $Return = $Query->row_array();
            }
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 新插入收款
     * @param unknown $Set
     */
    public function insert($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('finance_pay', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }
    /**
     * 新插入收款
     * @param unknown $Set
     */
    public function insert_batch($Set) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Set as $key => $value){
            $Set[$key] = $this->_format($value, $Item, $this->_Module);
        }
        
        if($this->HostDb->insert_batch('finance_pay', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }
    /**
     * 当登记的帐目发生修改时，修改进账
     * 注意切换账户时
     * @param unknown $Set
     * @param unknown $Where
     */
    public function update($Set, $Where) {
        if(!!($FinancePay = $this->is_valid_finance_pay($Where))){
            $Item = $this->_Item.__FUNCTION__;
            $Set = $this->_format_re($Set, $Item, $this->_Module);
            $this->HostDb->where('fp_id',$Where);
            $this->HostDb->where('fp_status',1);
            if($this->HostDb->update('finance_pay', $Set)){
                if($FinancePay['faid'] == $Set['fp_finance_account_id']){
                    /*没有更改财务账户*/
                    $Out = array(
                        $FinancePay['faid'] => array(
                            'amount' => $Set['fp_amount'] - $FinancePay['amount'],
                            'fee' => $Set['fp_fee'] - $FinancePay['fee']
                        )
                    );
                }else{
                    /*更改了财务账户*/
                    $Out = array(
                        $FinancePay['faid'] => array(
                            'amount' => -1*$FinancePay['amount'],
                            'fee' => -1*$FinancePay['fee']
                        ),
                        $Set['fp_finance_account_id'] => array(
                            'amount' => $Set['fp_amount'],
                            'fee' => $Set['fp_fee']
                        )
                    );
                }
                $Data['out'] = $Out;
                if(!empty($FinancePay['in_faid']) && '内部转账' == $FinancePay['type']){
                    $In = array(
                        $FinancePay['in_faid'] => array(
                            'amount' => -1*$FinancePay['amount'],
                            'fee' => 0
                        )
                    );
                    $Data['in'] = $In;
                }
                $this->remove_cache($this->_Module);
                return $Data;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 删除异形
     * @param unknown $Where
     */
    public function delete($Where){
        if(!!($FinancePay = $this->is_valid_finance_pay($Where))){
            $Data = array();
            $Where = array();
            $Account = array();
            $In = array();
            foreach ($FinancePay as $key => $value){
                if(empty($Account[$value['faid']])){
                    $Account[$value['faid']] = array(
                        'amount' => -1*$value['amount'],
                        'fee' => -1*$value['fee']
                    );
                }else{
                    $Account[$value['faid']]['amount'] += -1*$value['amount'];
                    $Account[$value['faid']]['fee'] += -1*$value['fee'];
                }
                $Where[] = $value['fpid'];
                
                if(!empty($value['in_faid']) && '内部转账' == $value['type']){
                    if(empty($In[$value['in_faid']])){
                        $In[$value['in_faid']] = array(
                            'amount' => -1*$value['amount'],
                            'fee' => -1*$value['fee']
                        );
                    }else{
                        $In[$value['in_faid']]['amount'] += -1*$value['amount'];
                        $In[$value['in_faid']]['fee'] += -1*$value['fee'];
                    }
                }
            }
            $Data['out'] = $Account;
            if(!empty($In)){
                $Data['in'] = $In;
            }
            unset($Account, $In);
            $this->HostDb->where_in('fp_id',$Where);
            $this->HostDb->delete('finance_pay');
            $this->remove_cache($this->_Module);
            return $Data;
        }else{
            return false;
        }
    }
}