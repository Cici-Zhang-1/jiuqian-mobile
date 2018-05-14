<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月26日
 * @author Zhangcc
 * @version
 * @des
 */
class Board_class_model extends MY_Model{
    private $_Module = 'data';
    private $_Model;
    private $_Item;
    private $_Cache;
    
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        log_message('debug', 'Model Data/Board_class_model Start!');
    }

    public function select_board_class() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('board_class');
        
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有环保等级信息!';
            }
        }
        return $Return;
    }

    public function insert_board_class($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('board_class', $Data)){
            log_message('debug', "Model Board_class_model/insert_board_class Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Board_class_model/insert_board_class Error");
            return false;
        }
    }

    public function update_board_class($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        $this->HostDb->where('bc_id', $Where);
        $this->HostDb->update('board_class', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
    /**
     * @param unknown $Where
     */
    public function delete_board_class($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('bc_id', $Where);
        }else{
            $this->HostDb->where('bc_id', $Where);
        }
        if($this->HostDb->delete('board_class')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}
