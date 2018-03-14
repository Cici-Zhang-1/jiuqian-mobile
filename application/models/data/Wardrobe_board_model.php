<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月28日
 * @author Zhangcc
 * @version
 * @des
 */
class Wardrobe_board_model extends MY_Model{
    private $_Module = 'data';
    private $_Model = 'wardrobe_board_model';
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        
        log_message('debug', 'Model Data/Wardrobe_board_model Start!');
    }

    public function select_wardrobe_board() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('wardrobe_board');
        
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何衣柜板块名称!';
            }
        }
        return $Return;
    }

    public function insert_wardrobe_board($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('wardrobe_board', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }

    public function update_wardrobe_board($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        $this->HostDb->where('wb_id',$Where);
        if($this->HostDb->update('wardrobe_board', $Set)){
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
    public function delete_wardrobe_board($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('wb_id', $Where);
        }else{
            $this->HostDb->where('wb_id', $Where);
        }
        if($this->HostDb->delete('wardrobe_board')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}
