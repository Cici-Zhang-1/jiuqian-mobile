<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月5日
 * @author Zhangcc
 * @version
 * @des
 * 扫描
 */
class Scan_model extends MY_Model{
    
    public function __construct(){
        log_message('debug', 'Model Pack/Scan_model Start!');
        parent::__construct();
    }
    
    public function update_scan($Data, $Where) {
        $this->HostDb->where(array('opbp_scanner' => 0, 'opbp_scan_datetime' => 0));
        $this->HostDb->where_in('opbp_id',$Where);
		return $this->HostDb->update('order_product_board_plate', $Data);
    }
    
    public function update_order_scan_status($Bid){
        $Sql = "SELECT opb_order_product_id, if(min(opbp_scan_datetime) = 0, 1, 2) as bb
                        FROM n9_order_product_board_plate
                        LEFT JOIN n9_order_product_board on opb_id = opbp_order_product_board_id
                        WHERE opb_order_product_id in(
                            SELECT opb_order_product_id FROM n9_order_product_board_plate AS B
                                LEFT JOIN n9_order_product_board on opb_id = opbp_order_product_board_id 
                                WHERE opbp_id = $Bid)  GROUP BY opb_order_product_id";
        
        $Query = $this->HostDb->query($Sql);
        if($Query->num_rows() > 0){
            $Status = $Query->row_array();
            $Query->free_result();
            $Sql = "SELECT opb_order_product_id, min(opbp_scan_datetime) as op_scan_start, max(opbp_scan_datetime) as op_scan_end
                        FROM n9_order_product_board_plate left join n9_order_product_board on opb_id = opbp_order_product_board_id
                        WHERE opb_order_product_id = {$Status['opb_order_product_id']} && opbp_scan_datetime > 0
                        GROUP BY opb_order_product_id";
            $Query = $this->HostDb->query($Sql);
            if($Query->num_rows() > 0){
                $Datetime = $Query->row_array();
                $Query->free_result();
                $Set = array(
                    'op_scan_status' => $Status['bb'],
                    'op_scan_start' => $Datetime['op_scan_start'],
                    'op_scan_end' => $Datetime['op_scan_end']
                );
                $this->HostDb->where(array('op_id'=>$Status['opb_order_product_id']));
                return $this->HostDb->update('order_product', $Set);
            }
        }
        return false;
    }
}
