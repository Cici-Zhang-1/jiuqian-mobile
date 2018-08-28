<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Workflow_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Workflow_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model workflow/Workflow_model Start!');
    }

    /**
     * Select from table workflow
     */
    public function select($Search) {
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            if (!empty($Search['order_id'])) {
                $Sql = "SELECT wom_id AS v, o_num AS target, wom_msg AS msg, wom_create_datetime AS create_datetime, u_truename AS creator, wom_timestamp as timestamps FROM j_workflow_order_msg LEFT JOIN j_user ON u_id = wom_creator LEFT JOIN j_order ON o_id = wom_order_id WHERE wom_order_id = {$Search['order_id']} ";
                if (!!($OrderProductId = $this->_select_order_product_id($Search['order_id']))) {
                    $A = implode(',', $OrderProductId);
                    $Sql .= "UNION ALL SELECT wopm_id AS v, op_num AS target, wopm_msg AS msg, wopm_create_datetime AS create_datetime, u_truename AS creator, wopm_timestamp as timestamps FROM j_workflow_order_product_msg LEFT JOIN j_user ON u_id = wopm_creator LEFT JOIN j_order_product on op_id = wopm_order_product_id WHERE wopm_order_product_id IN ({$A}) ";
                    if (!!($OrderProductBoardId = $this->_select_order_product_board_id($OrderProductId))) {
                        $A = implode(',', $OrderProductBoardId);
                        $Sql .= "UNION ALL SELECT wopbm_id AS v, concat(op_num, opb_board) AS target, wopbm_msg AS msg, wopbm_create_datetime AS create_datetime, u_truename AS creator, wopbm_timestamp as timestamps FROM j_workflow_order_product_board_msg LEFT JOIN j_user ON u_id = wopbm_creator LEFT JOIN j_order_product_board ON opb_id = wopbm_order_product_board_id LEFT JOIN j_order_product on op_id = opb_order_product_id WHERE wopbm_order_product_board_id IN ({$A}) ";
                    }
                    if (!!($OrderProductClassifyId = $this->_select_order_product_classify_id($OrderProductId))) {
                        $A = implode(',', $OrderProductClassifyId);
                        $Sql .= "UNION ALL SELECT wopcm_id AS v, concat(op_num, opc_board, c_name) AS target, wopcm_msg AS msg, wopcm_create_datetime AS create_datetime, u_truename AS creator, wopcm_timestamp as timestamps FROM j_workflow_order_product_classify_msg LEFT JOIN j_user ON u_id = wopcm_creator LEFT JOIN j_order_product_classify ON opc_id = wopcm_order_product_classify_id LEFT JOIN j_order_product ON op_id = opc_order_product_id LEFT JOIn j_classify ON c_id = opc_classify_id WHERE wopcm_order_product_classify_id IN ({$A}) ";
                    }
                }
                $Sql .= " ORDER BY timestamps desc";
                $Query = $this->HostDb->query($Sql);
                if ($Query->num_rows() > 0) {
                    $Return = array(
                        'content' => $Query->result_array(),
                        'num' => $Query->num_rows(),
                        'p' => ONE,
                        'pn' => ONE,
                        'pagesize' => ALL_PAGESIZE
                    );
                    $this->cache->save($Cache, $Return, HOURS);
                }
            }
        }
        return $Return;
    }

    private function _select_order_product_id ($V) {
        $Query = $this->HostDb->select('op_id')
            ->from('order_product')
            ->where('op_order_id', $V)
            ->get();
        $Result = false;
        if ($Query->num_rows() > 0) {
            $Result = $Query->result_array();
            foreach ($Result as $Key => $Value) {
                $Result[$Key] = $Value['op_id'];
            }
        }
        return $Result;
    }

    private function _select_order_product_classify_id ($Vs) {
        $Query = $this->HostDb->select('opc_id')
            ->from('order_product_classify')
            ->where_in('opc_order_product_id', $Vs)
            ->get();
        $Result = false;
        if ($Query->num_rows() > 0) {
            $Result = $Query->result_array();
            foreach ($Result as $Key => $Value) {
                $Result[$Key] = $Value['opc_id'];
            }
        }
        return $Result;
    }

    private function _select_order_product_board_id ($Vs) {
        $Query = $this->HostDb->select('opb_id')
            ->from('order_product_board')
            ->where_in('opb_order_product_id', $Vs)
            ->get();
        $Result = false;
        if ($Query->num_rows() > 0) {
            $Result = $Query->result_array();
            foreach ($Result as $Key => $Value) {
                $Result[$Key] = $Value['opb_id'];
            }
        }
        return $Result;
    }
}