<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月19日
 * @author Administrator
 * @version
 * @des
 */
class Truck_model extends MY_Model{
    private $_Module = 'data';
    private $_Model = 'truck_model';
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        log_message('debug', 'Model Data/Truck_model Start!');
    }

    public function select_truck() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql);
            $this->HostDb->from('truck');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何货车名称';
            }
        }
        return $Return;
    }

    public function insert_truck($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('truck', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }

    public function update_truck($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format_re($Set, $Item, $this->_Module);
        $this->HostDb->where('t_id',$Where);
        if($this->HostDb->update('truck', $Set)){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
    /**
     * 删除异形
     * @param unknown $Where
     */
    public function delete_truck($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('t_id', $Where);
        }else{
            $this->HostDb->where('t_id', $Where);
        }
        if($this->HostDb->delete('truck')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}
