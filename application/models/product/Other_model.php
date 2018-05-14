<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月30日
 * @author Zhangcc
 * @version
 * @des
 * 其它模块
 */
class Other_model extends MY_Model{
    private $_Module = 'product';
    private $_Model = 'other_model';
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        
        log_message('debug', 'Model Product/Other_model Start!');
    }

    public function select_other() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('other');
            $this->HostDb->join('product', 'p_id = o_type_id', 'left');
            $this->HostDb->join('supplier', 's_id = o_supplier_id', 'left');
            $this->HostDb->order_by('o_type_id');
        
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array()
                );
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何配件';
            }
        }
        return $Return;
        
    }

    public function insert_other($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('other', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }

    public function update_other($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        $this->HostDb->where('o_id',$Where);
        if($this->HostDb->update('other', $Set)){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
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
        $this->HostDb->update_batch('other',$Set,'o_id');
        $this->remove_cache($this->_Cache);
        return true;
    }
    /**
     * 删除异形
     * @param unknown $Where
     */
    public function delete_other($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('o_id', $Where);
        }else{
            $this->HostDb->where('o_id', $Where);
        }
        if($this->HostDb->delete('other')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}
