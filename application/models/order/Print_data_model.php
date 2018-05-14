<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月11日
 * @author Administrator
 * @version
 * @des
 */
class Print_data_model extends MY_Model{

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Order/Print_data_model start!');
    }
    
    public function select_print_data($Opid){
        $this->HostDb->select('o_id, o_num,o_dealer_name,o_dealer_linker,o_dealer_address,
    	    o_dealer_phone,o_owner,o_request_outdate',  FALSE);
        $this->HostDb->from('order_product');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
        $this->HostDb->where(array('op_id' => $Opid));
        $this->HostDb->limit(1);
        
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            log_message('debug', 'Get Order Detail Success!');
            return $Query->row_array();
        }
        return false;
    }
    
    /**
     * 获取柜体结构
     * @param unknown $Oid
     * @param unknown $Product
     */
    public function select_print_data_cabinet_struct($Opid){
        $this->HostDb->select('opcs_id, opcs_order_product_id, opcs_struct,
            opcs_dgjg, opcs_dgqc, opcs_dghc, opcs_facefb', false);
        $this->HostDb->from('order_product_cabinet_struct');
        $this->HostDb->where(array('opcs_order_product_id' => $Opid));
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->row_array();
        }
        return false;
    }
    
    public function select_print_data_cabinet($Opid){
        $this->HostDb->select('opbp_id as opbid, opbp_cubicle_num as cubicle_num,
            opbp_thick as thick, opbp_area as area, opb_board as board,
            opbp_cubicle_name as cubicle_name, opbp_plate_name as plate_name, 
            opbp_width as width, opbp_length as length, 
            opbp_left_edge as left_edge, opbp_right_edge as right_edge, 
            opbp_up_edge as up_edge, opbp_down_edge as down_edge,
            opbp_amount as amount', false);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        $this->HostDb->where(array('opb_order_product_id' => $Opid));
        $this->HostDb->order_by('opbp_cubicle_num');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }
    
    public function select_print_data_wardrobe($Opid){
        $this->HostDb->select('opbp_id as opbid, opbp_cubicle_num as cubicle_num,
            sum(opbp_area) as area, opbp_cubicle_name as cubicle_name,
            concat(opbp_plate_name, "^",
            opbp_width,"^", opbp_length,"^", opbp_thick,"^", opbp_edge,"^", opb_board, "^", opbp_remark) as spec,
            count(opbp_id) as amount', false);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        $this->HostDb->where(array('opb_order_product_id' => $Opid));
        $this->HostDb->group_by('spec');
        $this->HostDb->order_by('opbp_plate_name', 'desc');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }
    
    public function select_print_data_door($Opid){
        $this->HostDb->select('opdp_id,opdp_cubicle_name as cubicle_name,opdp_plate_name as plate_name,
                opdp_width as width,opdp_length as length,opdp_thick as thick,
                opdp_punch as punch,opdp_handle as handle,opdp_open_hole as open_hole,
                opdp_invisibility as invisibility,opdp_amount as amount, opdp_color as color,
                concat(ifnull(opdp_color, opd_board), ",", opd_edge) as board,
                opdp_area as area, opdp_amount as amount, opdp_spec as spec', false);
        $this->HostDb->from('order_product_door_plate');
        $this->HostDb->join('order_product_door', 'opd_id = opdp_order_product_door_id', 'left');
        $this->HostDb->where(array('opd_order_product_id' => $Opid));
        $this->HostDb->order_by('opdp_thick', 'desc');
        $this->HostDb->order_by('opdp_plate_name');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }
    
    public function select_print_data_wood($Opid){
        $this->HostDb->select('opdp_id,opdp_cubicle_name as cubicle_name,opdp_plate_name as plate_name,
                opdp_width as width,opdp_length as length,opdp_thick as thick,
                opdp_punch as punch,opdp_amount as amount, opdp_color as color,
                opdp_area as area, opdp_amount as amount, opdp_spec as spec', false);
        $this->HostDb->from('order_product_door_plate');
        $this->HostDb->join('order_product_door', 'opd_id = opdp_order_product_door_id', 'left');
        $this->HostDb->where(array('opd_order_product_id' => $Opid));
        $this->HostDb->order_by('opdp_thick', 'desc');
        $this->HostDb->order_by('opdp_plate_name');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }
    
    public function select_print_data_fitting($Opid){
        $this->HostDb->select('opf_id,opf_type as type,opf_name as name,opf_amount as amount,
            opf_unit as unit,opf_remark as remark');
        $this->HostDb->from('order_product_fitting');
        $this->HostDb->where(array('opf_order_product_id' => $Opid));
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }
    
    public function select_order_product_other($Opid){
        $this->HostDb->select('opo_id,opo_name as name,opo_length as length,opo_width as width ,
            opo_amount as amount,opo_remark as remark, op_order_id');
        $this->HostDb->from('order_product_other');
        $this->HostDb->where(array('opo_order_product_id' => $Opid));
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }
}
