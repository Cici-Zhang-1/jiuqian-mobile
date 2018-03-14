<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月4日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_product_server_model extends MY_Model{
    private $_Module = 'order';
    private $_Model;
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Order/Order_product_server_model start!');
        $this->e_cache->open_cache();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
    }

    /**
     * 通过order_product_id 获取板块信息
     * @param unknown $Opid
     */
    public function select_order_product_server_by_opid($Where){
        $Item = $this->_Item.__FUNCTION__;
        if(is_array($Where)){
            $Cache = $this->_Cache.implode('_', $Where).__FUNCTION__;
        }else{
            $Cache = $this->_Cache.$Where.__FUNCTION__;
        }
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $this->HostDb->select('ops_server_id, p_name, ops_server, ops_unit, ops_amount, 
                ops_unit_price, ops_sum, ops_remark', false);
            $this->HostDb->from('order_product_server');
            $this->HostDb->join('server', 's_id = ops_server_id', 'left');
            $this->HostDb->join('product', 'p_id = s_type_id', 'left');
            if(is_array($Where)){
                $this->HostDb->where_in('ops_order_product_id', $Where);
            }else{
                $this->HostDb->where('ops_order_product_id', $Where);
            }
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $Return = $this->_unformat($Return, $Item, $this->_Module);
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    
    public function select_check_by_opid($Id, $Pid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Id.$Pid;
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, false);
            $this->HostDb->from('order_product_server');
            $this->HostDb->join('order_product', 'op_id = ops_order_product_id', 'left');
            $this->HostDb->join('server', 's_id = ops_server_id', 'left');
            $this->HostDb->join('product', 'p_id = s_type_id', 'left');
            $this->HostDb->where(array('op_order_id' => $Id, 'op_product_id' => $Pid));
            $this->HostDb->where('op_status != 0');
            $this->HostDb->order_by('op_num');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    public function select_order_product_server_opid($Ids){
        $this->HostDb->select('op_id', false);
        $this->HostDb->from('order_product_server');
        $this->HostDb->join('order_product', 'op_id = ops_order_product_id', 'left');
        $this->HostDb->where_in('ops_id', $Ids);
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            return $Row['op_id'];
        }
        return false;
    }

    /**
     * 获取板材面积和
     */
    public function select_order_product_server_area($Where){
        $this->HostDb->select('ops_id, sum(ops_area) as ops_area, count(ops_id) as ops_amount', false);
        $this->HostDb->from('order_product_server');
        $this->HostDb->join('order_product_board', 'ops_id = ops_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = ops_order_product_id', 'left');
        if(is_array($Where)){
            $this->HostDb->where_in('op_order_id', $Where);
        }else{
            $this->HostDb->where(array('op_order_id' => $Where));
        }
        $this->HostDb->group_by('ops_id');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }


    public function insert_order_product_server($Set){
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('order_product_server', $Set)){
            log_message('debug', "Model Order_product_server_model/insert_order_product_server Success!");
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Order_product_server_model/insert_order_product_server Error");
            return false;
        }
    }

    public function insert_batch($Set){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Set as $key => $value){
            $Set[$key] = $this->_format($value, $Item, $this->_Module);
        }
        if($this->HostDb->insert_batch('order_product_server', $Set)){
            log_message('debug', "Model Order_product_server_model/insert_batch Success!");
            $this->remove_cache($this->_Module);
            return true;
        }else{
            log_message('debug', "Model Order_product_server_model/insert_batch Error");
            return false;
        }
    }
    /**
     * 删除之前的板块(修改后重新生成)
     * @param unknown $Where
     */
    public function delete_by_opid($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('ops_order_product_id', $Where);
        }else{
            $this->HostDb->where('ops_order_product_id', $Where);
        }
        if(!!($this->HostDb->delete('order_product_server'))){
            $this->remove_cache($this->_Module);
            return true;
        }else{
            return false;
        }
    }
    
    public function update_order_product_server($Data, $Where){
        if(is_array($Where)){
            $this->HostDb->where_in('ops_id', $Where);
        }else{
            $this->HostDb->where(array('ops_id' => $Where));
        }
        return $this->HostDb->update('order_product_server', $Data);
    }

    /**
     * 更新已统计的服务的信息
     * @param unknown $Data
     * @return boolean
     */
    public function update_batch($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item, $this->_Module);
        }
        $this->HostDb->update_batch('order_product_server', $Data, 'ops_id');
        log_message('debug', "Model Order_product_server_model/update_batch!");
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 更新已统计的服务的信息
     * @param unknown $Data
     * @return boolean
     */
    public function update_batch_order_product_server($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Set[$key] = $this->_format($value, $Item, $this->_Module);
        }
        $this->HostDb->update_batch('order_product_server', $Set, 'ops_id');
        log_message('debug', "Model Order_product_server_model/update_batch_order_product_server!");
        $this->_remove_cache();
        return true;
    }
    private function _remove_cache(){
        $this->load->helper('file');
        delete_cache_files('(.*'.$this->_Module.'.*)');
    }
}
