<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月2日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_product_fitting_model extends Base_Model{
	private $_Module = 'order';
	private $_Model;
	private $_Item;
	private $_Cache;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Order/Order_product_fitting_model start!');
        $this->e_cache->open_cache();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
    }
    
    /**
     * 通过order_product_id 获取板块信息
     * @param unknown $Opid
     */
    public function select_order_product_fitting_by_opid($Where){
        $Item = $this->_Item.__FUNCTION__;
        if(is_array($Where)){
            $Cache = $this->_Cache.__FUNCTION__.implode('_', $Where);
        }else{
            $Cache = $this->_Cache.__FUNCTION__.$Where;
        }
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, false);
            $this->HostDb->from('order_product_fitting');
            $this->HostDb->join('fitting', 'f_id = opf_fitting_id', 'left');
            $this->HostDb->join('product', 'p_id = f_type_id', 'left');
            $this->HostDb->join('order_product', 'op_id = opf_order_product_id', 'left');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');

	        $this->HostDb->order_by('op_num');
            if(is_array($Where)){
                $this->HostDb->where_in('opf_order_product_id', $Where);
            }else{
                $this->HostDb->where('opf_order_product_id', $Where);
            }
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '获取订单产品配件失败!';
            }
        }
        return $Return;
    }
    
    /**
     * 获取需要核价的配件产品
     * @param unknown $Id
     * @param unknown $Pid
     * @return multitype:
     */
    public function select_check_by_opid($Id, $Pid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Id.$Pid;
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, false);
            $this->HostDb->from('order_product_fitting');
            $this->HostDb->join('order_product', 'op_id = opf_order_product_id', 'left');
            $this->HostDb->join('fitting', 'f_id = opf_fitting_id', 'left');
            $this->HostDb->join('product', 'p_id = f_type_id', 'left');
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
    
    public function select_order_product_fitting_opid($Ids){
        $this->HostDb->select('op_id', false);
        $this->HostDb->from('order_product_fitting');
        $this->HostDb->join('order_product', 'op_id = opf_order_product_id', 'left');
        $this->HostDb->where_in('opf_id', $Ids);
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
    public function select_order_product_fitting_area($Where){
        $this->HostDb->select('opf_id, sum(opf_area) as opf_area, count(opf_id) as opf_amount', false);
        $this->HostDb->from('order_product_fitting');
        $this->HostDb->join('order_product_board', 'opf_id = opf_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opf_order_product_id', 'left');
        if(is_array($Where)){
            $this->HostDb->where_in('op_order_id', $Where);
        }else{
            $this->HostDb->where(array('op_order_id' => $Where));
        }
        $this->HostDb->group_by('opf_id');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }
    

    public function insert_order_product_fitting($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	$Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('order_product_fitting', $Set)){
            log_message('debug', "Model Order_product_fitting_model/insert_order_product_fitting Success!");
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Order_product_fitting_model/insert_order_product_fitting Error");
            return false;
        }
    }
    
    public function insert_batch($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	foreach ($Set as $key => $value){
    		$Set[$key] = $this->_format($value, $Item, $this->_Module);
    	}
    	if($this->HostDb->insert_batch('order_product_fitting', $Set)){
    		log_message('debug', "Model Order_product_fitting_model/insert_batch Success!");
    		$this->remove_cache($this->_Module);
    		return true;
    	}else{
    		log_message('debug', "Model Order_product_fitting_model/insert_batch Error");
    		return false;
    	}
    }    
    /**
     * 删除之前的板块(修改后重新生成)
     * @param unknown $Where
     */
    public function delete_by_opid($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('opf_order_product_id', $Where);
        }else{
            $this->HostDb->where('opf_order_product_id', $Where);
        }
        if(!!($this->HostDb->delete('order_product_fitting'))){
            $this->remove_cache($this->_Module);
            return true;
        }else{
            return false;
        }
    }
    
    public function update_order_product_fitting($Data, $Where){
        if(is_array($Where)){
            $this->HostDb->where_in('opf_id', $Where);
        }else{
            $this->HostDb->where(array('opf_id' => $Where));
        }
        return $this->HostDb->update('order_product_fitting', $Data);
    }
    
    public function update_batch($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item, $this->_Module);
        }
        $this->HostDb->update_batch('order_product_fitting', $Data, 'opf_id');
        log_message('debug', "Model Order_product_fitting_model/update_batch!");
        $this->remove_cache($this->_Module);
        return true;
    }
    /**
     * 更新已统计的配件的信息
     * @param unknown $Data
     * @return boolean
     */
    public function update_batch_order_product_fitting($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Set[$key] = $this->_format($value, $Item, $this->_Module);
        }
        $this->HostDb->update_batch('order_product_fitting', $Set, 'opf_id');
        log_message('debug', "Model Order_product_fitting_model/update_batch_order_product_fitting!");
        $this->_remove_cache();
        return true;
    }
    
    private function _remove_cache(){
    	$this->load->helper('file');
    	delete_cache_files('(.*'.$this->_Module.'.*)');
    }
}