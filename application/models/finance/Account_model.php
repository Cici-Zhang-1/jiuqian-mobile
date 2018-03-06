<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月9日
 * @author Zhangcc
 * @version
 * @des
 * 财务账户
 */
class Account_model extends Base_Model{
    private $_Module = 'finance';
    private $_Model;
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        
        log_message('debug', 'Model Data/Account_model Start!');
    }

    public function select_account() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->HostDb->select($Sql)
                                    ->from('finance_account')
                                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何财务账号';
            }
        }
        return $Return;
    }
    
    public function select_account_name() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql);
            $this->HostDb->from('finance_account');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何财务账号';
            }
        }
        return $Return;
    }
    
    /**
     * 及时到账账户
     */
    public function select_intime() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql);
            $this->HostDb->from('finance_account');
            $this->HostDb->where('fa_intime', 1);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何财务账号';
            }
        }
        return $Return;
    }
    

    /**
     * 非及时到账客户
     */
    public function select_outtime() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql);
            $this->HostDb->from('finance_account');
            $this->HostDb->where('fa_intime', 0);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何财务账号';
            }
        }
        return $Return;
    }
    /**
     * 判断是否为有效地账号
     * @param unknown $Id
     */
    private function _is_valid_account($Id){
        $Query = $this->HostDb->where('fa_id', $Id)->get('finance_account');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            return $Row;
        }else{
            return false;
        }
    }

    public function insert_account($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('finance_account', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }

    public function update_account($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        $this->HostDb->where('fa_id',$Where);
        if($this->HostDb->update('finance_account', $Set)){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            $GLOBALS['error'] = '不是有效地财务账户';
            return false;
        }
    }
    
    /**
     *  更新财务进账
     * @param unknown $Data
     * @param unknown $Where
     * @return boolean
     */
    public function update_balance_in($Data, $Where){
        if(!!($Account = $this->_is_valid_account($Where))){
            $Item = $this->_Item.__FUNCTION__;
            $Set = array(
                'fa_balance' => $Data['in'] + $Account['fa_balance'],
                'fa_in' => $Data['in'] + $Account['fa_in'],
                'fa_in_fee' => $Data['in_fee'] + $Account['fa_in_fee'],
            );
            $this->HostDb->where('fa_id',$Where);
            
            if($this->HostDb->update('finance_account', $Set)){
                $this->remove_cache($this->_Cache);
                return true;
            }else{
                $GLOBALS['error'] = '更新财务账户进账出错';
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     *  更新财务进账
     * @param unknown $Data
     * @param unknown $Where
     * @return boolean
     */
    public function update_balance_out($Data, $Where){
        if(!!($Account = $this->_is_valid_account($Where))){
            $Item = $this->_Item.__FUNCTION__;
            $Set = array(
                'fa_balance' => $Account['fa_balance'] - $Data['out'] - $Data['out_fee'],
                'fa_out' => $Data['out'] + $Account['fa_out'],
                'fa_out_fee' => $Data['out_fee'] + $Account['fa_out_fee'],
            );
            $this->HostDb->where('fa_id',$Where);
    
            if($this->HostDb->update('finance_account', $Set)){
                $this->remove_cache($this->_Cache);
                return true;
            }else{
                $GLOBALS['error'] = '更新财务账户进账出错';
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
    public function delete_account($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('fa_id', $Where);
        }else{
            $this->HostDb->where('fa_id', $Where);
        }
        if($this->HostDb->delete('finance_account')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}