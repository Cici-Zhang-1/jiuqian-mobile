<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月6日
 * @author Zhangcc
 * @version
 * @des
 * 包装列表
 */
class Pack_model extends Base_Model{
    static $Default;
    private $_Module= 'pack';
    private $_Model;
    private $_Item;
    private $_Cache;
    public function __construct(){
        log_message('debug', 'Model Manage/Pack_model Start!');
        parent::__construct();
        $this->e_cache->open_cache();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
    }

    public function select_pack($Con){
        if(empty($Con['pn'])){
            $Con['pn'] = $this->_page_num($Con);
        }
        if(!empty($Con['pn'])){
            $this->HostDb->select("op_id, 
                if(op_scan_status = 1, '正在扫', if(op_scan_status = 2, '已扫完', '未开始')) as status,
                if(op_scan_start > 0, from_unixtime(op_scan_start), '') as op_scan_start, 
                if(op_scan_end > 0, from_unixtime(op_scan_end), '') as op_scan_end,
                o_id, o_num, o_dealer_name, o_dealer_linker, o_dealer_address, 
            	o_dealer_phone, o_owner, p_name", FALSE);
            $this->HostDb->from('order_product');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            $this->HostDb->join('product', 'p_id = op_product_id', 'left');
            if(!empty($Con['keyword'])){
                $this->HostDb->where("(o_num like '%".$Con['keyword']."%' 
                        || o_dealer_name like '%".$Con['keyword']."%'
	                    || o_dealer_linker like '%".$Con['keyword']."%' 
                        || o_dealer_address like '%".$Con['keyword']."%' )");
            }
            
            if(is_int($Con['status'])){
                $this->HostDb->where('op_scan_status', $Con['status']);
            }
            if(!empty($Con['start_date'])){
                $this->HostDb->where('op_scan_start > ', $Con['start_date']);
            }
            
            if(!empty($Con['end_date'])){
                $this->HostDb->where('op_scan_end < ', $Con['end_date']);
            }
            
            $this->HostDb->where('o_status >= 2 && (p_name = "橱柜" || p_name="衣柜")');
            
            $this->HostDb->limit($Con['pagesize'], ($Con['p']-1)*$Con['pagesize']);
            
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                return $Query->result_array();
            }
        }
        return false;
    }

    private function _page_num($Con){
        $this->HostDb->select('op_id', FALSE);
        $this->HostDb->from('order_product');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
        $this->HostDb->join('product', 'p_id = op_product_id', 'left');
        
        if(!empty($Con['keyword'])){
            $this->HostDb->where("(op_num like '%".$Con['keyword']."%' 
                    || o_dealer_name like '%".$Con['keyword']."%'
                    || o_dealer_linker like '%".$Con['keyword']."%' 
                    || o_dealer_address like '%".$Con['keyword']."%' )");
        }
        
        if(is_int($Con['status'])){
            $this->HostDb->where('op_scan_status', $Con['status']);
        }
        if(!empty($Con['start_date'])){
            $this->HostDb->where('op_scan_start > ', $Con['start_date']);
        }
        if(!empty($Con['end_date'])){
            $this->HostDb->where('op_scan_end < ', $Con['end_date']);
        }
        
        $this->HostDb->where('o_status >= 2 && (p_name = "橱柜" || p_name="衣柜")');
        
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Nums = $Query->num_rows();
            $Query->free_result();
            log_message('debug', 'Num is '.$Nums.' and Pagesize is'.$Con['pagesize']);
            $GLOBALS['num'] = $Nums;
            if(intval($Nums%$Con['pagesize']) == 0){
                $GLOBALS['pn'] = intval($Nums/$Con['pagesize']);
            }else{
                $GLOBALS['pn'] = intval($Nums/$Con['pagesize'])+1;
            }
            return $GLOBALS['pn'];
        }else{
            return false;
        }
    }
    
    /**
     * 获取订单产品的Id号板块的信息
     * @param integer $Id
     */
    public function select_pack_detail($OrderProductId){
        $this->HostDb->select('opbp_id,opbp_order_product_board_id,opbp_qrcode,
                opbp_cubicle_name,opbp_cubicle_num,opbp_plate_name,opbp_plate_num,
                opbp_width,opbp_length,opbp_thick,opbp_area,
                opbp_slot,opbp_punch,opbp_edge,opbp_remark, opbp_decide_size, opbp_right_edge,
                opbp_left_edge,opbp_up_edge,opbp_down_edge,opbp_bd_file,opbp_procedure,
                u_truename as scanner,
                if(opbp_scan_datetime,from_unixtime(opbp_scan_datetime),"") as opbp_scan_datetime,
                opb_board', FALSE);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('user', 'u_id = opbp_scanner', 'left');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        
        if(is_array($OrderProductId)){
            $this->HostDb->where_in('opb_order_product_id', $OrderProductId);
        }else{
            $this->HostDb->where('opb_order_product_id', $OrderProductId);
        }
        $this->HostDb->order_by('opbp_thick, scanner');
        
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }
    
    /**
     * 选择未扫完的板块
     * @param unknown $Id
     * @return boolean
     */
    public function select_lack_detail($OrderProductId){
        $this->HostDb->select('opbp_id,opbp_order_product_board_id,opbp_qrcode,
                opbp_cubicle_name,opbp_cubicle_num,opbp_plate_name,opbp_plate_num,
                opbp_width,opbp_length,opbp_thick,opbp_area,
                opbp_slot,opbp_punch,opbp_edge,opbp_remark, opbp_decide_size, opbp_right_edge,
                opbp_left_edge,opbp_up_edge,opbp_down_edge,opbp_bd_file,opbp_procedure,
                opb_board', FALSE);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        
        if(is_array($OrderProductId)){
            $this->HostDb->where_in('opb_order_product_id', $OrderProductId);
        }else{
            $this->HostDb->where('opb_order_product_id', $OrderProductId);
        }
        
        $this->HostDb->where(array('opbp_scan_datetime' => 0));
        
        $this->HostDb->order_by('opbp_thick');
    	$Query = $this->HostDb->get();
    	if($Query->num_rows() > 0){
    		return $Query->result_array();
    	}else{
    		return false;
    	}
    }

    public function select_pack_lack(){
        $this->HostDb->select('op_id, op_flag,
                if(op_scan_status = 1, "正在扫", if(op_scan_status = 2, "已扫完", "未开始")) as status,
                if(op_scan_start > 0, from_unixtime(op_scan_start), "") as op_scan_start, 
                if(op_scan_end > 0, from_unixtime(op_scan_end), "") as op_scan_end,
                o_id, o_num, o_dealer_name, o_dealer_linker, 
        		o_dealer_address, o_dealer_phone, o_owner', FALSE);
        $this->HostDb->from('order_product');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
        $this->HostDb->where(array('op_scan_status' => 1));
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }
    
    
    public function insert_pack($Order, $Bom){
        if(!!($Oid = $this->replace_pack_order($Order))){
            foreach($Bom as $key => $value){
                $Bom[$key]['b_order_id'] = $Oid;
            }
            if(!!($AffectedRows = $this->replace_batch_pack_bom($Bom))){
                return $AffectedRows;
            }
        }
        return false;
    }
    
    public function replace_pack_order($Order){
        $this->HostDb->replace('order', $Order);
        return $this->HostDb->insert_id();
    }
    
    public function replace_batch_pack_bom($Bom){
        log_message('debug', "Model Pack_model/replace_batch_pack_bom Start");
        if($this->HostDb->replace_batch('bom', $Bom)){
            log_message('debug', "Model Pack_model/replace_batch_pack_bom Success!");
            return $this->HostDb->affected_rows();
        }else{
            log_message('debug', "Model Pack_model/replace_batch_pack_bom Error");
            return false;
        }
    }
    /**
     * 插入板块
     * @param unknown $data
     */
    public function insert_pack_batch($data){
        log_message('debug', "Model Pack_model/insert_pack_batch Start");
        if($this->HostDb->replace_batch('board', $data)){
            log_message('debug', "Model Pack_model/insert_pack_batch Success!");
            return $this->HostDb->affected_rows();
        }else{
            log_message('debug', "Model Pack_model/insert_pack_batch Error");
            return false;
        }
    }
    
    /**
     * 查看标记
     * @param unknown $param
     */
    public function select_order_product_flag($Where) {
        $Query = $this->HostDb->select('op_flag')->from('order_product')->where(array('op_id' => $Where))->limit(1)->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            return $Row['op_flag'];
        }
        return false;
    }
    
    public function update_order_product_flag($Set, $Where) {
        $this->HostDb->set(array('op_flag' => $Set));
        $this->HostDb->where(array('op_id' => $Where));
        return $this->HostDb->update('order_product');
    }
    private function _remove_cache(){
        $this->load->helper('file');
        delete_cache_files('(.*'.$this->_Cache.'.*)');
    }
}