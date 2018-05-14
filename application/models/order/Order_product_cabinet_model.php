<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月24日
 * @author zhangcc
 * @version
 * @des
 */
class Order_product_cabinet_model extends MY_Model{
	private $_Module = 'order';
	private $_Model = 'order_product_cabinet_model';
	private $_Item;
	private $_Cache;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Order/Order_product_cabinet_model start!');
        $this->e_cache->open_cache();
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
    }
    
    public function select_order_product_cabinet_by_opcsid($Where){
        $Item = $this->_Item.__FUNCTION__;
        if(is_array($Where)){
            $Cache = $this->_Cache.implode('_', $Where).__FUNCTION__;
        }else{
            $Cache = $this->_Cache.$Where.__FUNCTION__;
        }
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $this->HostDb->select('opc_id, opc_cubicle_num,
                    opc_cubicle_name, opc_width, opc_depth, opc_height, opc_amount, opc_gebangu,
                    opc_gebanhuo, opc_gebanjian, opc_beiban, opc_size, opc_left_width,opc_left_depth,
                    opc_right_width, opc_right_depth, opc_zbilu, opc_ybilu, opc_diguiabnormity, 
                    opc_weibolu', false);
            $this->HostDb->from('order_product_cabinet');
            if(is_array($Where)){
                $this->HostDb->where_in('opc_order_product_cabinet_struct_id', $Where);
            }else{
                $this->HostDb->where('opc_order_product_cabinet_struct_id', $Where);
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
	 * 批量插入柜体
	 * @param unknown $Set
	 * @return boolean
	 */    
    public function insert_batch($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	foreach ($Set as $key => $value){
    		$Set[$key] = $this->_format($value, $Item, $this->_Module);
    	}
    	if($this->HostDb->insert_batch('order_product_cabinet', $Set)){
    		log_message('debug', "Model Order_product_cabinet_model/insert_batch Success!");
    		$this->remove_cache($this->_Module);
    		return true;
    	}else{
    		log_message('debug', "Model Order_product_cabinet_model/insert_batch Error");
    		return false;
    	}
    }
    
    /**
     * 删除橱柜柜体(整柜)
     * @param unknown $Where order_product_cabinet_struct_id
     */
    public function delete_by_opcsid($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('opc_order_product_cabinet_struct_id', $Where);
        }else{
            $this->HostDb->where('opc_order_product_cabinet_struct_id', $Where);
        }
        if(!!($this->HostDb->delete('order_product_cabinet'))){
            $this->remove_cache($this->_Module);
            return true;
        }else{
            return false;
        }
    }
    
    /*
     * 删除橱柜相关的内容
     */
    public function delete_relate($Opid){
        if(is_array($Opid)){
            $Where = implode(',', $Opid);
        }else{
            $Where = $Opid;
        }
        $this->remove_cache($this->_Module);
        return $this->HostDb->query("DELETE n9_order_product_cabinet, n9_order_product_cabinet_struct 
                    FROM n9_order_product_cabinet LEFT JOIN n9_order_product_cabinet_struct 
                    ON opcs_id = opc_order_product_cabinet_struct_id
                    WHERE opcs_order_product_id in ($Where)");
    }
    

    private function _remove_cache(){
    	$this->load->helper('file');
    	delete_cache_files('(.*'.$this->_Module.'.*)');
    }
}
