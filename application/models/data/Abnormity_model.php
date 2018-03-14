<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-5-7
 * @author ZhangCC
 * @version
 * @description  
 */
class Abnormity_model extends MY_Model{
    private $_Module = 'data';
    private $_Model = 'abnormity_model';
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        log_message('debug', 'Model Data/Abnormity_model Start!');
    }
    
    public function select_abnormity($PrintList, $Scan) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.(Int)$PrintList.(Int)$Scan;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('abnormity');
            if(false !== $PrintList){
                $this->HostDb->where('a_print_list', $PrintList);
            }
            if(false !== $Scan){
                $this->HostDb->where('a_scan', $Scan);
            }
        
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有异形板块信息!';
            }
        }
        return $Return;
    }
    
    public function insert_abnormity($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('abnormity', $Data)){
            log_message('debug', "Model Abnormity_model/insert_abnormity Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Abnormity_model/insert_abnormity Error");
            return false;
        }
    }
    
    public function update_abnormity($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        $this->HostDb->where('a_id', $Where);
        $this->HostDb->update('abnormity', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
    /**
     * 删除异形
     * @param unknown $Where
     */
    public function delete_abnormity($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('a_id', $Where);
        }else{
            $this->HostDb->where('a_id', $Where);
        }
        if($this->HostDb->delete('abnormity')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}
