<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月30日
 * @author Zhangcc
 * @version
 * @des
 */
class wardrobe_slot_model extends Base_Model{
    private $_Modular = 'data';
    private $_Model = 'wardrobe_slot_model';
    private $_Item;
    private $_Cache;
    public function __construct(){
        log_message('debug', 'Model Data/Wardrobe_slot_model Start!');
        parent::__construct();
        $this->e_cache->open_cache();
        $this->_Item = $this->_Modular.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Modular.'_'.$this->_Model.'_';
    }

    public function select_wardrobe_slot() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $this->HostDb->select('ws_id, ws_name');
            $this->HostDb->from('wardrobe_slot');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $Return = $this->_unformat($Return, $Item, $this->_Modular);
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何衣柜开槽名称';
            }
        }
        return $Return;
    }

    public function insert_wardrobe_slot($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Modular);
        if($this->HostDb->insert('wardrobe_slot', $Set)){
            log_message('debug', "Model '.$Item.' Success!");
            $this->_remove_cache();
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model '.$Item.' Error");
            return false;
        }
    }

    public function update_wardrobe_slot($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Modular);
        $this->HostDb->where('ws_id',$Where);
        if($this->HostDb->update('wardrobe_slot', $Set)){
            $this->_remove_cache();
            return true;
        }else{
            return false;
        }
    }
    /**
     * 删除异形
     * @param unknown $Where
     */
    public function delete_wardrobe_slot($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('ws_id', $Where);
        }else{
            $this->HostDb->where('ws_id', $Where);
        }
        if($this->HostDb->delete('wardrobe_slot')){
            $this->_remove_cache();
            return true;
        }else{
            return false;
        }
    }

    private function _remove_cache(){
        $this->load->helper('file');
        delete_cache_files('(.*wardrobe_slot.*)');
    }
}