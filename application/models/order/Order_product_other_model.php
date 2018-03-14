<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月2日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_product_other_model extends MY_Model{
	private $_Module = 'order';
	private $_Model;
	private $_Item;
	private $_Cache;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Order/Order_product_other_model start!');
        $this->e_cache->open_cache();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
    }
    
    /**
     * 通过order_product_id 获取外购信息
     * @param unknown $Opid
     */
    public function select_order_product_other_by_opid($Where){
        $Item = $this->_Item.__FUNCTION__;
        if(is_array($Where)){
            $Cache = $this->_Cache.implode('_', $Where).__FUNCTION__;
        }else{
            $Cache = $this->_Cache.$Where.__FUNCTION__;
        }
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $this->HostDb->select('opo_other_id, p_name, opo_other, opo_unit, opo_spec, 
                opo_unit, opo_amount, opo_unit_price, opo_sum, opo_remark', false);
            $this->HostDb->from('order_product_other');
            $this->HostDb->join('other', 'o_id = opo_other_id', 'left');
            $this->HostDb->join('product', 'p_id = o_type_id', 'left');
            if(is_array($Where)){
                $this->HostDb->where_in('opo_order_product_id', $Where);
            }else{
                $this->HostDb->where('opo_order_product_id', $Where);
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
    
    /**
     * 获取外购金额账目
     * @param unknown $Id
     * @param unknown $Pid
     */
    public function select_check_by_opid($Id, $Pid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Id.$Pid;
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, false);
            $this->HostDb->from('order_product_other');
            $this->HostDb->join('order_product', 'op_id = opo_order_product_id', 'left');
            $this->HostDb->join('other', 'o_id = opo_other_id', 'left');
            $this->HostDb->join('product', 'p_id = o_type_id', 'left');
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
    
    public function select_order_product_other_opid($Ids){
        $this->HostDb->select('op_id', false);
        $this->HostDb->from('order_product_other');
        $this->HostDb->join('order_product', 'op_id = opo_order_product_id', 'left');
        $this->HostDb->where_in('opo_id', $Ids);
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
    public function select_order_product_other_area($Where){
        $this->HostDb->select('opo_id, sum(opo_area) as opo_area, count(opo_id) as opo_amount', false);
        $this->HostDb->from('order_product_other');
        $this->HostDb->join('order_product_board', 'opo_id = opo_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opo_order_product_id', 'left');
        if(is_array($Where)){
            $this->HostDb->where_in('op_order_id', $Where);
        }else{
            $this->HostDb->where(array('op_order_id' => $Where));
        }
        $this->HostDb->group_by('opo_id');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }
    

    public function insert_order_product_other($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	$Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('order_product_other', $Set)){
            log_message('debug', "Model Order_product_other_model/insert_order_product_other Success!");
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Order_product_other_model/insert_order_product_other Error");
            return false;
        }
    }
    
    public function insert_batch($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	foreach ($Set as $key => $value){
    		$Set[$key] = $this->_format($value, $Item, $this->_Module);
    	}
    	if($this->HostDb->insert_batch('order_product_other', $Set)){
    		log_message('debug', "Model Order_product_other_model/insert_batch Success!");
    		$this->remove_cache($this->_Module);
    		return true;
    	}else{
    		log_message('debug', "Model Order_product_other_model/insert_batch Error");
    		return false;
    	}
    }    
    /**
     * 删除之前的板块(修改后重新生成)
     * @param unknown $Where
     */
    public function delete_by_opid($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('opo_order_product_id', $Where);
        }else{
            $this->HostDb->where('opo_order_product_id', $Where);
        }
        if(!!($this->HostDb->delete('order_product_other'))){
            $this->remove_cache($this->_Module);
            return true;
        }else{
            return false;
        }
    }
    
    public function update_order_product_other($Data, $Where){
        if(is_array($Where)){
            $this->HostDb->where_in('opo_id', $Where);
        }else{
            $this->HostDb->where(array('opo_id' => $Where));
        }
        return $this->HostDb->update('order_product_other', $Data);
    }
    /**
     * 更新已统计的其它的信息
     * @param unknown $Data
     * @return boolean
     */
    public function update_batch($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item, $this->_Module);
        }
        $this->HostDb->update_batch('order_product_other', $Data, 'opo_id');
        log_message('debug', "Model Order_product_other_model/update_batch!");
        $this->remove_cache($this->_Module);
        return true;
    }
    
    /**
     * 更新已统计的其它的信息
     * @param unknown $Data
     * @return boolean
     */
    public function update_batch_order_product_other($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Set[$key] = $this->_format($value, $Item, $this->_Module);
        }
        $this->HostDb->update_batch('order_product_other', $Set, 'opo_id');
        log_message('debug', "Model Order_product_other_model/update_batch_order_product_other!");
        $this->_remove_cache();
        return true;
    }
    
    private function _remove_cache(){
    	$this->load->helper('file');
    	delete_cache_files('(.*'.$this->_Module.'.*)');
    }
}
