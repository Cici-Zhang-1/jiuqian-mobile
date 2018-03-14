<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月24日
 * @author Zhangcc
 * @version
 * @des
 */
class Board_model extends MY_Model{
    private $_Module = 'product';
    private $_Model;
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        
        log_message('debug', 'Model Product/Board_model Start!');
    }

    public function select() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('board');
            $this->HostDb->join('board_thick', 'bt_id = b_thick', 'left');
            $this->HostDb->join('board_color as A', 'A.bc_id = b_color', 'left');
            $this->HostDb->join('board_nature', 'bn_id = b_nature', 'left');
            $this->HostDb->join('board_class as B', 'B.bc_id = b_class', 'left');
            $this->HostDb->join('supplier', 's_id = b_supplier_id', 'left');
	    $this->HostDb->order_by('b_color');
	    $this->HostDb->order_by('b_supplier_id');
            
             
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array()
                );
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
    
    public function select_stock(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('board');
            $this->HostDb->join('board_thick', 'bt_id = b_thick', 'left');
            
            $this->HostDb->where('bt_name > 5');
            $this->HostDb->order_by('b_amount', 'desc');
             
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array()
                );
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
    
    /**
     * 根据板材名称获取板材id号
     * @param unknown $Name
     */
    public function select_board_id($Name){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Name;
        $Bid = false;
        if(!($Bid = $this->cache->get($Cache))){
            $Query = $this->HostDb->select('b_id')->from('board')
                        ->where(array('b_name' => $Name))->limit(1)->get();
            if($Query->num_rows() > 0){
                $Row = $Query->row_array();
                $Query->free_result();
                $Bid = $Row['b_id'];
                $this->cache->save($Cache, $Bid, HOURS);
            }
        }
        return $Bid;
    }

    /**
     * 插入
     * @param unknown $Set
     */
    public function insert($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('board', $Data)){
            log_message('debug', "Model Board_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Board_model/insert Error");
            return false;
        }
    }
    
    /**
     * 批量插入
     * @param unknown $Set
     */
    public function insert_ignore_batch($Set){
        $Item = $this->_Item.__FUNCTION__;
        $value = array();
        foreach ($Set as $key => $value){
            $value = $this->_format($value, $Item, $this->_Module);
            $Set[$key] = '(\''.implode('\',\'', $value).'\')';
        }
        $Keys = array_keys($value);
        $Key = '('.implode(',', $Keys).')';
        $Values = implode(',', $Set);
        $this->remove_cache($this->_Cache);
        return $this->HostDb->query("INSERT IGNORE INTO n9_board {$Key} VALUES {$Values}");
    }

    /**
     * 更新
     * @param unknown $Set
     * @param unknown $Where
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        $this->HostDb->where('b_id', $Where);
        $this->HostDb->update('board', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
    
    /**
     * 批量更新
     * @param unknown $Set
     */
    public function update_batch($Set){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Set as $key => $value){
            $Set[$key] = $this->_format_re($value, $Item, $this->_Module);
        }
        $this->HostDb->update_batch('board',$Set,'b_id');
        $this->remove_cache($this->_Cache);
        return true;
    }
    /**
     * 删除板块
     * @param unknown $Where
     */
    public function delete($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('b_id', $Where);
        }else{
            $this->HostDb->where('b_id', $Where);
        }
        $this->HostDb->delete('board');
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
}
