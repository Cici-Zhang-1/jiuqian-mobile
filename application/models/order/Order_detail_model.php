<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-28
 * @author ZhangCC
 * @version
 * @description  
 */
class Order_detail_model extends MY_Model{
	public static $RowArray;
	public function __construct(){
		parent::__construct();
		log_message('debug', 'Model Order/Order_detail_model start!');
	}
	
	/**
	 * 选择某个订单的某个产品的订单详情
	 * @param unknown $Oid
	 * @param unknown $Pid
	 * @return boolean
	 */
	public function select_order_detail($Oid,$Pid){
		$this->HostDb->select('od_id, od_order_id, od_product_id, od_count, od_amount, od_remark', false);
		$this->HostDb->from('order_detail');
		$this->HostDb->where('od_order_id', $Oid);
		$this->HostDb->where('od_product_id', $Pid);
		$Query = $this->HostDb->get();
		if($Query->num_rows() > 0){
			return $Query->row_array();
		}else{
			return false;
		}
	}
	
	/**
	 * 选择某个订单的详情
	 * @param unknown $Oid
	 * @return boolean
	 */
	public function select_order_detail_by_id($Oid){
		$this->HostDb->select('od_id, od_order_id, o_num, d_id, d_des, o_owner,
				from_unixtime(o_request_outdate, \'%Y-%m-%d\') as o_request_outdate, 
				od_product_id, od_count,  od_amount,
				od_amount_real, od_remark, p_des, p_num, g_des', false);
		$this->HostDb->from('order_detail');
		$this->HostDb->join('product', 'p_id = od_product_id', 'left');
		$this->HostDb->join('order', 'o_id = od_order_id', 'left');
		$this->HostDb->join('dealer', 'd_id = o_dealer_id', 'left');
		$this->HostDb->join('goods', 'g_id = od_goods_id', 'left');
		$this->HostDb->where('od_order_id', $Oid);
		
		$Query = $this->HostDb->get();
		if($Query->num_rows() > 0){
			return $Query->result_array();
		}else{
			return false;
		}
	}
	
	public function insert_batch_order_detail($Data){
		log_message('debug', "Model Order_detail_model/insert_batch_order_detail Start");
		if(!!($Query = $this->HostDb->insert_batch('order_detail', $Data))){
			log_message('debug', "Model Order_detail_model/insert_batch_order_detail Success!");
			return $this->HostDb->affected_rows();
		}else{
			log_message('debug', "Model Order_detail_model/insert_batch_order_detail Error");
			return false;
		}
	}
	
	public function insert_order_detail($Data){
		log_message('debug', "Model Order_detail_model/insert_order_detail Start");
		if($this->HostDb->insert('order_detail', $Data)){
			log_message('debug', "Model Order_detail_model/insert_order_detail Success!");
			return $this->HostDb->insert_id();
		}else{
			log_message('debug', "Model Order_detail_model/insert_order_detail Error");
			return false;
		}
	}
	
	public function update_order_detail_by_dismantle($Oid, $Pid, $Count, $Amount){
		$Query = $this->HostDb->query("INSERT INTO n9_order_detail(od_order_id, od_product_id, od_count, od_amount) 
				VALUES ($Oid, $Pid, $Count, $Amount) ON DUPLICATE KEY UPDATE od_count=$Count,od_amount=$Amount");
		if($Query){
			$this->HostDb->select('sum(od_count) as o_count, sum(od_amount) as o_amount_real');
			$Query = $this->HostDb->get_where('order_detail', array('od_order_id'=>$Oid));
			if($Query->num_rows() > 0){
				return $Query->row_array();
			}
		}
		return false;
	}
	
	public function delete_order_detail($Where){
		$this->HostDb->where_in('od_order_id', $Where);
		return $this->HostDb->delete('order_detail');
	}
}
