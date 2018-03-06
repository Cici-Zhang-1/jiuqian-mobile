<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月6日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_drawing_model extends Base_Model{

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Order/Order_drawing_model start!');
    }

    public function select_order_drawing($Opid){
        $this->HostDb->select('op_drawing, o_drawing', false);
        $this->HostDb->from('order_product');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
        $this->HostDb->where(array('op_id' => $Opid));
        $this->HostDb->limit(1);
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->row_array();
        }
        return false;
    }
}