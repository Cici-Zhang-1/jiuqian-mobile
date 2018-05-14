<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月3日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_product_wardrobe_struct_model extends MY_Model{
    private $_Module = 'order';
    private $_Model = 'order_product_wardrobe_struct_model';
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Order/Order_product_wardrobe_struct_model start!');
        $this->e_cache->open_cache();
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
    }
    /**
     * 通过order_product_id获取衣柜柜体结构
     * @param unknown $Id
     */
    public function select_order_product_wardrobe_struct_by_opid($Id){
        $Item =  $this->_Item.__FUNCTION__;
        if(is_array($Id)){
            $Cache = $this->_Cache.implode('_', $Id).__FUNCTION__;
        }else{
            $Cache = $this->_Cache.$Id.__FUNCTION__;
        }
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $this->HostDb->select('opws_id, b_id, b_name,
                opws_struct', false);
            $this->HostDb->from('order_product_wardrobe_struct');
            $this->HostDb->join('board', 'b_id = opws_board_id', 'left');
            if(is_array($Id)){
                $this->HostDb->where_in('opws_order_product_id', $Id);
            }else{
                $this->HostDb->where('opws_order_product_id', $Id);
            }
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $Return = $this->_unformat($Return, $Item, $this->_Module);
                $Return = array_shift($Return);
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    public function insert($Set){
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('order_product_wardrobe_struct', $Set)){
            log_message('debug', "Model Order_product_wardrobe_struct_model/insert Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Order_product_wardrobe_struct_model/insert Error");
            return false;
        }
    }

    public function update($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        $this->HostDb->where('opws_id',$Where);
        $this->HostDb->update('order_product_wardrobe_struct', $Set);
        log_message('debug', "Model Order_product_wardrobe_struct_model/update");
        $this->remove_cache($this->_Module);
        return true;
    }
    
    /**
     * 删除衣柜结构
     * @param unknown $Id
     */
    public function delete($Opid) {
        if(is_array($Opid)){
            $this->HostDb->where_in('opws_order_product_id', $Opid);
        }else{
            $this->HostDb->where('opws_order_product_id', $Opid);
        }
        $this->remove_cache($this->_Cache);
        return $this->HostDb->delete('order_product_wardrobe_struct');
    }

    private function _remove_cache(){
        $this->load->helper('file');
        delete_cache_files('(.*'.$this->_Cache.'.*)');
    }
}
