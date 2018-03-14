<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月22日
 * @author Zhangcc
 * @version
 * @des
 * 经销商跟踪
 */
class Dealer_trace_model extends MY_Model{
    private $_Module = 'dealer';
    private $_Model;
    private $_Item;
    private $_Cache;

    public function __construct(){
        parent::__construct();

        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        log_message('debug', 'Model Dealer/Dealer_trace_model Start!');
    }

    public function select($Did){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Did;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('dealer_trace');
            $this->HostDb->join('user', 'u_id = dt_creator', 'left');
            
            $this->HostDb->where('dt_dealer_id', $Did);

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '该经销商没有跟踪信息!';
            }
        }
        return $Return;
    }

    /**
     * 插入经销商 联系人
     * @param unknown $Data
     */
    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        
        $Data = $this->_format($Data, $Item);
         
        if($this->HostDb->insert('dealer_trace', $Data)){
            log_message('debug', "Model Dealer_trace_model/insert_dealer_trace Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Dealer_trace_model/insert_dealer_trace Error");
            return false;
        }
    }
}
