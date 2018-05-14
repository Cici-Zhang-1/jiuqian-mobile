<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月19日
 * @author Zhangcc
 * @version
 * @des
 */
class Out_method_model extends MY_Model{
    private $_Module = 'data';
    private $_Model;
    private $_Item;
    private $_Cache;
    
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
		$this->_Item = $this->_Module.'/'.$this->_Model.'/';
		$this->_Cache = $this->_Module.'_'.$this->_Model.'_';
		log_message('debug', 'Model Data/Out_method_model Start!');
    }
    
    public function select(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('out_method');
        
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
                $GLOBALS['error'] = '没有出厂方式!';
            }
        }
        return $Return;
    }
    
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('out_method', $Data)){
            log_message('debug', "Model Out_method_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Out_method_model/insert Error");
            return false;
        }
    }
    
    public function update($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        $this->HostDb->where('om_id', $Where);
        $this->HostDb->update('out_method', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
    /**
     * 删除异形
     * @param unknown $Where
     */
    public function delete($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('om_id', $Where);
        }else{
            $this->HostDb->where('om_id', $Where);
        }
        if($this->HostDb->delete('out_method')){
            $this->_remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}
