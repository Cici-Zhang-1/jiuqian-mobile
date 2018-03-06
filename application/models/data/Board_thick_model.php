<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月26日
 * @author Zhangcc
 * @version
 * @des
 */
class Board_thick_model extends Base_Model{
    private $_Module = 'data';
    private $_Model;
    private $_Item;
    private $_Cache;
    
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        log_message('debug', 'Model Data/Board_thick_model Start!');
    }

    public function select_board_thick() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('board_thick');
        
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有板块厚度信息!';
            }
        }
        return $Return;
    }

    public function insert_board_thick($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item, $this->_Module);
        if($this->HostDb->insert('board_thick', $Data)){
            log_message('debug', "Model Board_thick_model/insert_board_thick Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Board_thick_model/insert_board_thick Error");
            return false;
        }
    }

    public function update_board_thick($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item, $this->_Module);
        $this->HostDb->where('bt_id', $Where);
        $this->HostDb->update('board_thick', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
    /**
     * @param unknown $Where
     */
    public function delete_board_thick($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('bt_id', $Where);
        }else{
            $this->HostDb->where('bt_id', $Where);
        }
        if($this->HostDb->delete('board_thick')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}