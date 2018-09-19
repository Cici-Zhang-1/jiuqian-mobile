<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Workflow_model Model
 *
 * @package  CodeIgniter
 * @category Model
 * @des 封边组
 */
class Edge_model extends MY_Model {
    private $_Num;
    public function __construct() {
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model order/Edge_model Start!');
    }

    public function select ($Search) {
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $OrderProductClassify = $this->_select_order_product_classify($Search);
                $OrderProductBoard = $this->_select_order_product_board($Search);
                $Sql = $OrderProductClassify . ' UNION ALL ' . $OrderProductBoard;
                if ($Search['status'] == WP_EDGED) {
                    $Sql = $Sql . ' ORDER BY edge_datetime desc LIMIT ' . ($Search['p']-1)*$Search['pagesize'] . ', ' . $Search['pagesize'];
                } else {
                    $Sql = $Sql . ' ORDER BY sort_datetime, num LIMIT ' . ($Search['p']-1)*$Search['pagesize'] . ', ' . $Search['pagesize'];
                }
                $Query = $this->HostDb->query($Sql);
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的封边订单';
            }
        }
        return $Return;
    }

    private function _select_order_product_classify ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('order_product_classify')
            ->join('workflow_procedure', 'wp_id = opc_status', 'left')
            ->join('order_product', 'op_id = opc_order_product_id', 'left')
            ->join('product', 'p_id = op_id', 'left')
            ->join('order', 'o_id = op_order_id', 'left')
            ->join('user', 'u_id = opc_edge', 'left');
        if ($Search['status'] == WP_EDGE) {
            $this->HostDb->where('opc_status', $Search['status']);
        } elseif ($Search['status'] == WP_EDGING) { // 已经分配到某个封边组，但是还没有封边完成
            $this->HostDb->join('user as E', 'E.u_id = opc_edge', 'left');
            if (!empty($Search['edge'])) {
                $this->HostDb->where('opc_edge', $Search['edge']);
            }
            $this->HostDb->where('opc_status', $Search['status']);
            $this->HostDb->where('opc_edge > ', ZERO);
            $this->HostDb->where('opc_edge_datetime is null');
        } else {
            if (!empty($Search['edge'])) {
                $this->HostDb->where('opc_edge', $Search['edge']);
            }
            $this->HostDb->where('opc_edge > ', ZERO);
            $this->HostDb->where('opc_edge_datetime is not null');
            if (!empty($Search['start_date'])) {
                $this->HostDb->where('opc_edge_datetime >= ', $Search['start_date']);
            }
            if (!empty($Search['end_date'])) {
                $this->HostDb->where('opc_edge_datetime <= ', $Search['end_date']);
            }
        }

        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('op_num', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->group_by('op_id');
        return $this->HostDb->get_compiled_select();
    }
    private function _select_order_product_board ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('order_product_board')
            ->join('workflow_procedure', 'wp_id = opb_status', 'left')
            ->join('order_product', 'op_id = opb_order_product_id', 'left')
            ->join('product', 'p_id = op_id', 'left')
            ->join('order', 'o_id = op_order_id', 'left')
            ->join('user', 'u_id = opb_edge', 'left');
        if ($Search['status'] == WP_EDGE) {
            $this->HostDb->where('opb_status', $Search['status']);
        } elseif ($Search['status'] == WP_EDGING) { // 已经分配到某个封边组，但是还没有封边完成
            $this->HostDb->join('user as E', 'E.u_id = opb_edge', 'left');
            if (!empty($Search['edge'])) {
                $this->HostDb->where('opb_edge', $Search['edge']);
            }
            $this->HostDb->where('opb_status', $Search['status']);
            $this->HostDb->where('opb_edge > ', ZERO);
            $this->HostDb->where('opb_edge_datetime is null');
        } else {
            if (!empty($Search['edge'])) {
                $this->HostDb->where('opb_edge', $Search['edge']);
            }
            $this->HostDb->where('opb_edge > ', ZERO);
            $this->HostDb->where('opb_edge_datetime is not null');
            if (!empty($Search['start_date'])) {
                $this->HostDb->where('opb_edge_datetime >= ', $Search['start_date']);
            }
            if (!empty($Search['end_date'])) {
                $this->HostDb->where('opb_edge_datetime <= ', $Search['end_date']);
            }
        }

        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('op_num', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->group_by('op_id');
        return $this->HostDb->get_compiled_select();
    }

    private function _page_num ($Search) {
        $Pn = false;
        $this->_Num = $this->_page_num_order_product_board($Search) + $this->_page_num_order_product_classify($Search);
        if ($this->_Num > ZERO) {
            if(intval($this->_Num%$Search['pagesize']) == 0){
                $Pn = intval($this->_Num/$Search['pagesize']);
            }else{
                $Pn = intval($this->_Num/$Search['pagesize'])+1;
            }
        }
        return $Pn;
    }
    private function _page_num_order_product_classify ($Search) {
        $this->HostDb->select('op_id')->from('order_product_classify')
            ->join('order_product', 'op_id = opc_order_product_id', 'left');

        if ($Search['status'] == WP_EDGE) {
            $this->HostDb->where('opc_status', $Search['status']);
        } elseif ($Search['status'] == WP_EDGING) { // 已经分配到某个封边组，但是还没有封边完成
            $this->HostDb->join('user as E', 'E.u_id = opc_edge', 'left');
            if (!empty($Search['edge'])) {
                $this->HostDb->where('opc_edge', $Search['edge']);
            }
            $this->HostDb->where('opc_status', $Search['status']);
            $this->HostDb->where('opc_edge > ', ZERO);
            $this->HostDb->where('opc_edge_datetime is null');
        } else {
            $this->HostDb->join('user as E', 'E.u_id = opc_edge', 'left');
            if (!empty($Search['edge'])) {
                $this->HostDb->where('opc_edge', $Search['edge']);
            }
            $this->HostDb->where('opc_edge > ', ZERO);
            $this->HostDb->where('opc_edge_datetime is not null');
            if (!empty($Search['start_date'])) {
                $this->HostDb->where('opc_edge_datetime >= ', $Search['start_date']);
            }
            if (!empty($Search['end_date'])) {
                $this->HostDb->where('opc_edge_datetime <= ', $Search['end_date']);
            }
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('op_num', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->group_by('op_id');
        $Query = $this->HostDb->get();
        return $Query->num_rows();
    }
    private function _page_num_order_product_board ($Search) {
        $this->HostDb->select('op_id')->from('order_product_board')
            ->join('order_product', 'op_id = opb_order_product_id', 'left');

        if ($Search['status'] == WP_EDGE) {
            $this->HostDb->where('opb_status', $Search['status']);
        } elseif ($Search['status'] == WP_EDGING) { // 已经分配到某个封边组，但是还没有封边完成
            $this->HostDb->join('user as E', 'E.u_id = opb_edge', 'left');
            if (!empty($Search['edge'])) {
                $this->HostDb->where('opb_edge', $Search['edge']);
            }
            $this->HostDb->where('opb_status', $Search['status']);
            $this->HostDb->where('opb_edge > ', ZERO);
            $this->HostDb->where('opb_edge_datetime is null');
        } else {
            $this->HostDb->join('user as E', 'E.u_id = opb_edge', 'left');
            if (!empty($Search['edge'])) {
                $this->HostDb->where('opb_edge', $Search['edge']);
            }
            $this->HostDb->where('opb_edge > ', ZERO);
            $this->HostDb->where('opb_edge_datetime is not null');
            if (!empty($Search['start_date'])) {
                $this->HostDb->where('opb_edge_datetime >= ', $Search['start_date']);
            }
            if (!empty($Search['end_date'])) {
                $this->HostDb->where('opb_edge_datetime <= ', $Search['end_date']);
            }
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                    ->like('op_num', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->group_by('op_id');
        $Query = $this->HostDb->get();
        return $Query->num_rows();
    }

    public function select_next_edge () {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $OrderProductV = $this->HostDb->select('opb_order_product_id')->from('order_product_board')->join('n9_workflow_order_product_board_msg', 'wopbm_order_product_board_id = opb_id && wopbm_workflow_order_product_board_id = ' . OPB_EDGE, 'left', false)->where('opb_status', OPB_EDGE)->order_by('wopbm_create_datetime')->limit(ONE)->get_compiled_select();
        $OrderProductV = $this->HostDb->select('opb_order_product_id')->from('(' . $OrderProductV . ') AS A', false)->get_compiled_select();
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_board')
            ->where_in('opb_order_product_id', $OrderProductV, false)
            ->where('opb_status', OPB_EDGE)->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        } else {
            $GLOBALS['error'] = '没有等待封边的订单产品!';
        }
        return $Return;
    }
}
