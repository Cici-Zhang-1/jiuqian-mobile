<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月9日
 * @author Zhangcc
 * @version
 * @des
 * 财务进账
 */
class Finance_received_model extends MY_Model{
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
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql,  FALSE);
                $this->HostDb->from('finance_received');
                $this->HostDb->join('finance_account', 'fa_id = fr_finance_account_id', 'left');
                $this->HostDb->join('user', 'u_id = fr_creator', 'left');

                if(isset($Con['status']) && '' != $Con['status']){
                    $this->HostDb->where("fr_status in ({$Con['status']})");
                }

                if(!empty($Con['account'])){
                    $this->HostDb->where("fr_finance_account_id in ({$Con['account']})");
                }else{
                    $this->HostDb->where('fa_intime', 1);
                }
                
                if(!empty($Con['type'])){
                    $this->HostDb->where_in('fr_type', $Con['type']);
                }
                if(!empty($Con['start_date'])){
                    $this->HostDb->where('fr_create_datetime > ', $Con['start_date']);
                }
                if(!empty($Con['end_date'])){
                    $this->HostDb->where('fr_create_datetime < ', $Con['end_date']);
                }
                
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                                        ->like('fr_dealer', $Con['keyword'])
                                        ->or_like('fr_remark', $Con['keyword'])
                                    ->group_end();
                }
                 
                $this->HostDb->order_by('fr_create_datetime', 'desc');
                 
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
        $this->HostDb->select('count(fr_id) as num', FALSE);
        $this->HostDb->from('finance_received');
        $this->HostDb->join('finance_account', 'fa_id = fr_finance_account_id', 'left');

        if(isset($Con['status']) && '' != $Con['status']){
            $this->HostDb->where("fr_status in ({$Con['status']})");
        }
        
        if(!empty($Con['account'])){
            $this->HostDb->where("fr_finance_account_id in ({$Con['account']})");
        }else{
            $this->HostDb->where('fa_intime', 1);
        }
        if(!empty($Con['type'])){
            $this->HostDb->where_in('fr_type', $Con['type']);
        }
        if(!empty($Con['start_date'])){
            $this->HostDb->where('fr_create_datetime > ', $Con['start_date']);
        }
        if(!empty($Con['end_date'])){
            $this->HostDb->where('fr_create_datetime < ', $Con['end_date']);
        }
        
        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
            ->like('fr_dealer', $Con['keyword'])
            ->or_like('fr_remark', $Con['keyword'])
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
     * 非及时到账收入
     * @param unknown $Con
     */
    public function select_outtime($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.implode('_', $Con).__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(!empty($Con['type'])){
                $Con['type'] = explode(',', $Con['type']);
            }
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page($Con);
            }
            if(!empty($Con['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql,  FALSE);
                $this->HostDb->from('finance_received');
                $this->HostDb->join('finance_account', 'fa_id = fr_finance_account_id', 'left');
                $this->HostDb->join('user', 'u_id = fr_creator', 'left');
    
                if(isset($Con['status']) && '' != $Con['status']){
                    $this->HostDb->where("fr_status in ({$Con['status']})");
                }
    
                if(!empty($Con['account'])){
                    $this->HostDb->where("fr_finance_account_id in ({$Con['account']})");
                }else{
                    $this->HostDb->where('fa_intime', 0);
                }
                if(!empty($Con['type'])){
                    $this->HostDb->where_in('fr_type', $Con['type']);
                }
                if(!empty($Con['start_date'])){
                    $this->HostDb->where('fr_create_datetime > ', $Con['start_date']);
                }
                if(!empty($Con['end_date'])){
                    $this->HostDb->where('fr_create_datetime < ', $Con['end_date']);
                }
    
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                    ->like('fr_dealer', $Con['keyword'])
                    ->or_like('fr_remark', $Con['keyword'])
                    ->group_end();
                }
                 
                $this->HostDb->order_by('fr_create_datetime', 'desc');
                 
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

    private function _page($Con){
        $this->HostDb->select('count(fr_id) as num', FALSE);
        $this->HostDb->from('finance_received');
        $this->HostDb->join('finance_account', 'fa_id = fr_finance_account_id', 'left');
    
        if(isset($Con['status']) && '' != $Con['status']){
            $this->HostDb->where("fr_status in ({$Con['status']})");
        }
    
        if(!empty($Con['account'])){
            $this->HostDb->where("fr_finance_account_id in ({$Con['account']})");
        }else{
            $this->HostDb->where('fa_intime', 0);
        }
        if(!empty($Con['type'])){
            $this->HostDb->where_in('fr_type', $Con['type']);
        }
        if(!empty($Con['start_date'])){
            $this->HostDb->where('fr_create_datetime > ', $Con['start_date']);
        }
        if(!empty($Con['end_date'])){
            $this->HostDb->where('fr_create_datetime < ', $Con['end_date']);
        }
    
        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
            ->like('fr_dealer', $Con['keyword'])
            ->or_like('fr_remark', $Con['keyword'])
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
     * 获取对账的进账
     * @param unknown $Did
     * @param unknown $StartDatetime
     * @param unknown $EndDatetime
     */
    public function select_for_debt($Did, $StartDatetime, $EndDatetime){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.'_'.$Did.$StartDatetime.$EndDatetime;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql,  FALSE);
            $this->HostDb->from('finance_received');
            $this->HostDb->join('finance_account', 'fa_id = fr_finance_account_id', 'left');
            
            $this->HostDb->where('fr_create_datetime >', $StartDatetime);
            $this->HostDb->where('fr_create_datetime <', $EndDatetime);
            $this->HostDb->where('fr_dealer_id', $Did);
            $this->HostDb->where('fr_status > 0');
    
            $this->HostDb->order_by('fr_create_datetime', 'desc');
             
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合条件的进账';
            }
        }
        return $Return;
    }
    /**
     * 判断是否为有效的财务进账(存在，没有被任亮)
     * @param unknown $Id
     */
    public function is_valid_finance_received($Id, $Status = 1){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql,  FALSE);
        $this->HostDb->from('finance_received');
        $this->HostDb->join('finance_account', 'fa_id = fr_finance_account_id', 'left');
        if(is_array($Id)){
            $Multiple = true;
            $this->HostDb->where_in('fr_id', $Id);
        }else{
            $Multiple = false;
            $this->HostDb->where('fr_id', $Id);
        }
        if(is_array($Status)){
            $this->HostDb->where_in('fr_status', $Status);
        }else{
            $this->HostDb->where('fr_status', $Status);
        }
        
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
            $GLOBALS['error'] = '不是有效的进账登记';
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
        if($this->HostDb->insert('finance_received', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }
    public function insert_outtime($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('finance_received', $Set)){
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
        if(!!($FinanceReceived = $this->is_valid_finance_received($Where))){
            $Item = $this->_Item.__FUNCTION__;
            $Set = $this->_format_re($Set, $Item, $this->_Module);
            $this->HostDb->where('fr_id',$Where);
            $this->HostDb->where('fr_status',1);
            if($this->HostDb->update('finance_received', $Set)){
                if($FinanceReceived['faid'] == $Set['fr_finance_account_id']){
                    /*没有更改财务账户*/
                    $Data = array(
                        $FinanceReceived['faid'] => array(
                            'amount' => $Set['fr_amount'] - $FinanceReceived['amount'],
                            'fee' => $Set['fr_fee'] - $FinanceReceived['fee']
                        )
                    );
                }else{
                    /*更改了财务账户*/
                    $Data = array(
                        $FinanceReceived['faid'] => array(
                            'amount' => -1*$FinanceReceived['amount'],
                            'fee' => -1*$FinanceReceived['fee']
                        ),
                        $Set['fr_finance_account_id'] => array(
                            'amount' => $Set['fr_amount'],
                            'fee' => $Set['fr_fee']
                        )
                    );
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
     * 返款？
     * @param unknown $Where
     * @param unknown $Status
     */
    public function update_back($Where, $Status){
        if(!!($Query = $this->is_valid_finance_received($Where, $Status))){
            $Where = array();
            foreach ($Query as $key => $value){
                $Where[] = $value['frid'];
            }
            $this->HostDb->set('fr_status', 3);
            $this->HostDb->where_in('fr_id', $Where);
            $this->HostDb->update('finance_received');
            $this->remove_cache($this->_Module);
            return $Query;
        }else{
            return false;
        }
    }
    /**
     * 财务进账认领更新
     * @param unknown $Set
     * @param unknown $Frid
     */
    public function update_finance_received_pointer($Set, $Frid){
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format_re($Set, $Item, $this->_Module);
        $this->HostDb->set($Set);
        $this->HostDb->set('fr_status', 2);
        $this->HostDb->where('fr_id', $Frid);
        $this->HostDb->where('fr_status',1);
        $this->HostDb->update("finance_received");
        $this->remove_cache($this->_Module);
        return true;
    }
    
    /**
     * 非及时到账的进账更新
     * @param unknown $Set
     * @param unknown $Frid
     */
    public function update_outtime($Set, $Frid){
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format_re($Set, $Item, $this->_Module);
        $this->HostDb->set($Set);
        $this->HostDb->set('fr_status', 2);
        $this->HostDb->where('fr_id', $Frid);
        $this->HostDb->where('fr_status',1);
        $this->HostDb->update("finance_received");
        $this->remove_cache($this->_Module);
        return true;
    }
    /**
     * 删除异形
     * @param unknown $Where
     */
    public function delete($Where){
        if(!!($FinanceReceived = $this->is_valid_finance_received($Where))){
            $Account = array();
            $Where = array();
            foreach ($FinanceReceived as $key => $value){
                if(empty($Account[$value['faid']])){
                    $Account[$value['faid']] = array(
                        'amount' => -1*$value['amount'],
                        'fee' => -1*$value['fee']
                    );
                }else{
                    $Account[$value['faid']]['amount'] += -1*$value['amount'];
                    $Account[$value['faid']]['fee'] += -1*$value['fee'];
                }
                $Where[] = $value['frid'];
            }
            $this->HostDb->where_in('fr_id',$Where);
            $this->HostDb->delete('finance_received');
            $this->remove_cache($this->_Module);
            return $Account;
        }else{
            return false;
        }
    }
    
    /**
     * 删除及时登帐的进账，进账状态设为0
     * @param unknown $Where
     */
    public function delete_intime($Where){
        if(!!($FinanceReceived = $this->is_valid_finance_received($Where, array(1,2)))){
            $Data = array();
            $Account = array();
            $Dealer = array();
            $Where = array();
            foreach ($FinanceReceived as $key => $value){
                if(empty($Account[$value['faid']])){
                    $Account[$value['faid']] = array(
                        'amount' => -1*$value['amount'],
                        'fee' => -1*$value['fee']
                    );
                }else{
                    $Account[$value['faid']]['amount'] += -1*$value['amount'];
                    $Account[$value['faid']]['fee'] += -1*$value['fee'];
                }
                if(!empty($value['did'])){
                    if(empty($Dealer[$value['did']])){
                        $Dealer[$value['did']] = -1*$value['corresponding'];
                    }else{
                        $Dealer[$value['did']] += -1*$value['corresponding'];
                    }
                }
                $Where[] = $value['frid'];
            }
            
            $this->HostDb->set('fr_status', 0);
            $this->HostDb->where_in('fr_id',$Where);
            $this->HostDb->update('finance_received');
            $this->remove_cache($this->_Module);
            $Data['account'] = $Account;
            $Data['dealer'] = $Dealer;
            return $Data;
        }else{
            return false;
        }
    }
    
    /**
     * 非及时到账的删除
     * @param unknown $Where
     */
    public function delete_outtime($Where){
        $this->HostDb->set('fr_status', 0);
        $this->HostDb->where('fr_id', $Where);
        $this->HostDb->update('finance_received');
        //$this->HostDb->delete('finance_received');
        $this->remove_cache($this->_Module);
        return true;
    }
}
