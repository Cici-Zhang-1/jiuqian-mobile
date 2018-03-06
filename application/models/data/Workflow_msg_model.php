<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月13日
 * @author Zhangcc
 * @version
 * @des
 */
class Workflow_msg_model extends Base_Model{
    private $_Module = 'data';
    private $_Model = 'workflow_msg_model';
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';

        log_message('debug', 'Model Data/Workflow_msg_model Start!');
    }

    public function select() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('workflow_msg');

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

    public function select_by_oid($Oid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Oid;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('workflow_msg');
            $this->HostDb->join('user', 'u_id = wm_creator', 'left');
            $this->HostDb->join('order', 'o_id = wm_source_id', 'left');
            $this->HostDb->where('wm_model', 'order_model');
            $this->HostDb->where('wm_source_id', $Oid);
            
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何订单工作流记录!';
            }
        }
        return $Return;
    }
    
    public function select_by_opids($Opids){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode(',', $Opids);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('workflow_msg');
            $this->HostDb->join('user', 'u_id = wm_creator', 'left');
            $this->HostDb->join('order_product', 'op_id = wm_source_id', 'left');
        
            $this->HostDb->where('wm_model', 'order_product_model');
            $this->HostDb->where_in('wm_source_id', $Opids);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何订单产品工作流记录!';
            }
        }
        return $Return;
    }
    
    public function select_by_opcids($Opcids){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode(',', $Opcids);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('workflow_msg');
            $this->HostDb->join('user', 'u_id = wm_creator', 'left');
            $this->HostDb->join('order_product_classify', 'opc_id = wm_source_id', 'left');
            $this->HostDb->join('order_product', 'op_id = opc_order_product_id');
    
            $this->HostDb->where('wm_model', 'order_product_classify_model');
            $this->HostDb->where_in('wm_source_id', $Opcids);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何订单产品工作流记录!';
            }
        }
        return $Return;
    }
    
    public function insert($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('workflow_msg', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }

    public function insert_batch($Set) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Set as $key => $value){
            $Set[$key] = $this->_format($value, $Item, $this->_Module);
        }
        
        if($this->HostDb->insert_batch('workflow_msg', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }

    public function update($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        $this->HostDb->where('wm_id',$Where);
        if($this->HostDb->update('workflow_msg', $Set)){
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
    public function delete($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('wm_id', $Where);
        }else{
            $this->HostDb->where('wm_id', $Where);
        }
        if($this->HostDb->delete('workflow_msg')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}