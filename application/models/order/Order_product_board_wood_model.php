<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月3日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_product_board_wood_model extends MY_Model{
    private $_Module = 'order';
    private $_Model;
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Order/Order_product_board_wood_model start!');
        $this->e_cache->open_cache();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
    }

    /**
     * 通过order_product_id 获取板块信息
     * @param unknown $Opid
     */
    public function select_order_product_board_wood_by_opid($Where){
        $Item = $this->_Item.__FUNCTION__;
        if(is_array($Where)){
            $Cache = $this->_Cache.implode('_', $Where).__FUNCTION__;
        }else{
            $Cache = $this->_Cache.$Where.__FUNCTION__;
        }
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $this->HostDb->select('opbw_wood_name,opbw_wood_num,opbw_width,opbw_length,
                opbw_thick,opbw_area,opbw_amount,opbw_punch,
                opbw_remark,opbw_core, opbw_center, opb_board', false);
            $this->HostDb->from('order_product_board_wood');
            $this->HostDb->join('order_product_board', 'opb_id = opbw_order_product_board_id', 'left');
            if(is_array($Where)){
                $this->HostDb->where_in('opb_order_product_id', $Where);
            }else{
                $this->HostDb->where('opb_order_product_id', $Where);
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

    public function select_order_product_board_opid($Ids){
        $this->HostDb->select('op_id', false);
        $this->HostDb->from('order_product_board_wood');
        $this->HostDb->join('order_product_board', 'opb_id = opbw_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
        $this->HostDb->where_in('opbw_id', $Ids);
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
    public function select_order_product_board_wood_area($Where){
        $this->HostDb->select('opb_id, sum(opbw_area) as opb_area, count(opbw_id) as opb_amount', false);
        $this->HostDb->from('order_product_board_wood');
        $this->HostDb->join('order_product_board', 'opb_id = opbw_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        if(is_array($Where)){
            $this->HostDb->where_in('op_order_id', $Where);
        }else{
            $this->HostDb->where(array('op_order_id' => $Where));
        }
        $this->HostDb->group_by('opb_id');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }


    public function insert_order_product_board_wood($Set){
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('order_product_board_wood', $Set)){
            log_message('debug', "Model Order_product_board_wood_model/insert_order_product_board_wood Success!");
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Order_product_board_wood_model/insert_order_product_board_wood Error");
            return false;
        }
    }

    public function insert_batch($Set){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Set as $key => $value){
            $Set[$key] = $this->_format($value, $Item, $this->_Module);
        }
        if($this->HostDb->insert_batch('order_product_board_wood', $Set)){
            log_message('debug', "Model Order_product_board_wood_model/insert_batch Success!");
            $this->remove_cache($this->_Module);
            return true;
        }else{
            log_message('debug', "Model Order_product_board_wood_model/insert_batch Error");
            return false;
        }
    }

    public function update_order_product_board_wood($Data, $Where){
        if(is_array($Where)){
            $this->HostDb->where_in('opbw_id', $Where);
        }else{
            $this->HostDb->where(array('opbw_id' => $Where));
        }
        return $this->HostDb->update('order_product_board_wood', $Data);
    }

    public function update_order_product_board_wood_board($Data, $Where){
        if(is_array($Where)){
            $this->HostDb->where_in('opbw_id', $Where);
        }else{
            $this->HostDb->where(array('opbw_id' => $Where));
        }
        return $this->HostDb->update('order_product_board_wood', array('opbw_order_product_board_id' => $Data));
    }
    /**
     * 删除之前的板块(修改后重新生成)
     * @param unknown $Where
     */
    public function delete_by_opbid($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('opbw_order_product_board_id', $Where);
        }else{
            $this->HostDb->where('opbw_order_product_board_id', $Where);
        }
        if(!!($this->HostDb->delete('order_product_board_wood'))){
            $this->remove_cache($this->_Module);
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 删除拼框门相关内容
     */
    public function delete_relate($Opid){
        if(is_array($Opid)){
            $Where = implode(',', $Opid);
        }else{
            $Where = $Opid;
        }
        $this->remove_cache($this->_Module);
        return $this->HostDb->query("DELETE n9_order_product_board_wood, n9_order_product_board
            FROM n9_order_product_board_wood LEFT JOIN n9_order_product_board ON opb_id = opbw_order_product_board_id
            WHERE opb_order_product_id in ($Where)");
    }
    private function _remove_cache(){
        $this->load->helper('file');
        delete_cache_files('(.*'.$this->_Module.'.*)');
    }
}
