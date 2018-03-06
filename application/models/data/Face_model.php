<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月30日
 * @author Zhangcc
 * @version
 * @des
 */
class Face_model extends Base_Model{
    private $_Modular = 'data';
    private $_Model = 'face_model';
    private $_Item;
    private $_Cache;
    public function __construct(){
        log_message('debug', 'Model Data/Wardrobe_punch_model Start!');
        parent::__construct();
        $this->e_cache->open_cache();
        $this->_Item = $this->_Modular.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Modular.'_'.$this->_Model.'_';
    }

    public function select_face() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Modular);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('face');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
            }else{
                $GLOBALS['error'] = '无任何单双面';
            }
        }
        return $Return;
    }

    public function insert_face($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Modular);
        if($this->HostDb->insert('face', $Set)){
            log_message('debug', "Model '.$Item.' Success!");
            $this->_remove_cache();
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model '.$Item.' Error");
            return false;
        }
    }

    public function update_face($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Modular);
        $this->HostDb->where('f_id',$Where);
        if($this->HostDb->update('face', $Set)){
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
    public function delete_face($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('f_id', $Where);
        }else{
            $this->HostDb->where('f_id', $Where);
        }
        if($this->HostDb->delete('face')){
            $this->_remove_cache();
            return true;
        }else{
            return false;
        }
    }

    private function _remove_cache(){
        $this->load->helper('file');
        delete_cache_files('(.*face.*)');
    }
}