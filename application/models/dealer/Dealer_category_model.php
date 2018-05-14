<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年9月22日
 * @author Zhangcc
 * @version
 * @des
 * 经销商类别
 */
class Dealer_category_model extends MY_Model{
    private $_Module = 'dealer';
    private $_Model;
    private $_Item;
    private $_Cache;
    
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        log_message('debug', 'Model Dealer/Dealer_category_model Start!');
        
    }

    public function select(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('dealer_category');
             
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Result = $Query->result_array();
                $Return = array(
                    'content' => $Result,
                    'num' => $Query->num_rows(),
                    'p' => 1,
                    'pn' => 1
                );
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有经销商类型信息!';
            }
        }
        return $Return;
    }

    /**
     * 插入经销商类别
     * @param unknown $Data
     */
    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('dealer_category', $Data)){
            log_message('debug', "Model Dealer_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Dealer_model/insert Error");
            return false;
        }
    }

    /**
     * 更新经销商类别信息
     * @param unknown $Data
     * @param unknown $Where
     */
    public function update($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        $this->HostDb->where('dc_id', $Where);
        $this->HostDb->update('dealer_category', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
}
