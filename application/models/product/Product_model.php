<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月26日
 * @author Zhangcc
 * @version
 * @des
 */
class Product_model extends Base_Model{
    private $_Module = 'product';
    private $_Model;
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        log_message('debug', 'Model Product/Product_model Start!');
    }

    public function select($Type = false){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Type;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('product');
            
            if(false !== $Type){
                $this->HostDb->where('p_code', strtoupper($Type));
            }
            $this->HostDb->order_by('p_id', 'desc'); 
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
    
    public function select_undelete(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('product');
            $this->HostDb->where('p_delete', 0);
            
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
     * 获取可以修改售后的
     * @return multitype:NULL
     */
    public function select_post_salable(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('product');
            $this->HostDb->where('p_name', '配件');
            $this->HostDb->or_where('p_name', '外购');
            $this->HostDb->or_where('p_name', '服务');
    
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

    public function select_product_id($Name) {
        $Query = $this->HostDb->select('p_id')->from('product')->where(array('p_name' => $Name))->limit(1)->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            return $Row['p_id'];
        }
        return false;
    }
    
    public function select_product_id_by_code($Code){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.$Code.__FUNCTION__;
        $Pid = false;
        if(!($Pid = $this->cache->get($Cache))){
            $Query = $this->HostDb->select('p_id')->from('product')->where(array('p_code' => $Code))->limit(1)->get();
            if($Query->num_rows() > 0){
                $Row = $Query->row_array();
                $Query->free_result();
                $Pid = $Row['p_id'];
                $this->cache->save($Cache, $Pid, HOURS);
            }
        }
        return $Pid;
    }
    
    /**
     * 通过产品id号获取产品code
     * @param unknown $Ids
     */
    public function select_product_code_by_id($Ids){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode(',', $Ids);
        $Return = array();
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->HostDb->select($Sql)->from('product')->where_in('p_id', $Ids)->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
    
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item, $this->_Module);
        if($this->HostDb->insert('product', $Data)){
            log_message('debug', "Model Product_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Product_model/insert Error");
            return false;
        }
    }

    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item, $this->_Module);
        $this->HostDb->where('p_id', $Where);
        $this->HostDb->update('product', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
    
    /**
     * 删除异形
     * @param unknown $Where
     */
    public function delete($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('p_id', $Where);
        }else{
            $this->HostDb->where('p_id', $Where);
        }
        $this->HostDb->where('p_delete', 1);
        $this->HostDb->delete('product');
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
}