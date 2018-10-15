<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Workflow_model Model
 *
 * @package  CodeIgniter
 * @category Model
 * @des 打孔组
 */
class Punch_one_model extends MY_Model {
    private $_Num;
    public function __construct() {
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model order/Punch_one_model Start!');
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
                if ($Search['status'] == WP_PUNCHED) {
                    $Sql = $Sql . ' ORDER BY punch_datetime desc LIMIT ' . ($Search['p']-1)*$Search['pagesize'] . ', ' . $Search['pagesize'];
                } else {
                    $Sql = $Sql . ' ORDER BY punch_datetime, num LIMIT ' . ($Search['p']-1)*$Search['pagesize'] . ', ' . $Search['pagesize'];
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
                $GLOBALS['error'] = '没有符合搜索条件的打孔订单';
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
            ->join('user', 'u_id = opc_punch', 'left');
        if ($Search['status'] == WP_PUNCH) {
            $this->HostDb->where('opc_status', $Search['status'])
                ->where('opc_procedure', P_PUNCH_ONE);
        } elseif ($Search['status'] == WP_PUNCHING) { // 已经分配到某个打孔组，但是还没有打孔完成
            $this->HostDb->join('user as E', 'E.u_id = opc_punch', 'left');
            if (!empty($Search['puncher'])) {
                $this->HostDb->where('opc_punch', $Search['puncher']);
            }
            $this->HostDb->where('opc_status', $Search['status'])
                ->where('opc_punch > ', ZERO)
                ->where('opc_punch_datetime is null')
                ->where('opc_procedure', P_PUNCH_ONE);
        } else {
            if (!empty($Search['puncher'])) {
                $this->HostDb->where('opc_punch', $Search['puncher']);
            }
            $this->HostDb->where('opc_punch > ', ZERO);
            $this->HostDb->where('opc_punch_datetime is not null');
            if (!empty($Search['start_date'])) {
                $this->HostDb->where('opc_punch_datetime >= ', $Search['start_date']);
            }
            if (!empty($Search['end_date'])) {
                $this->HostDb->where('opc_punch_datetime <= ', $Search['end_date']);
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
            ->join('user', 'u_id = opb_punch', 'left');
        if ($Search['status'] == WP_EDGE) {
            $this->HostDb->where('opb_status', $Search['status'])
                ->where('opb_procedure', P_PUNCH_ONE);
        } elseif ($Search['status'] == WP_EDGING) { // 已经分配到某个打孔组，但是还没有打孔完成
            $this->HostDb->join('user as E', 'E.u_id = opb_punch', 'left');
            if (!empty($Search['puncher'])) {
                $this->HostDb->where('opb_punch', $Search['puncher']);
            }
            $this->HostDb->where('opb_status', $Search['status'])
                ->where('opb_punch > ', ZERO)
                ->where('opb_punch_datetime is null')
                ->where('opb_procedure', P_PUNCH_ONE);
        } else {
            if (!empty($Search['puncher'])) {
                $this->HostDb->where('opb_punch', $Search['puncher']);
            }
            $this->HostDb->where('opb_punch > ', ZERO);
            $this->HostDb->where('opb_punch_datetime is not null');
            if (!empty($Search['start_date'])) {
                $this->HostDb->where('opb_punch_datetime >= ', $Search['start_date']);
            }
            if (!empty($Search['end_date'])) {
                $this->HostDb->where('opb_punch_datetime <= ', $Search['end_date']);
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

        if ($Search['status'] == WP_PUNCH) {
            $this->HostDb->where('opc_status', $Search['status'])
                ->where('opc_procedure', P_PUNCH_ONE);
        } elseif ($Search['status'] == WP_PUNCHING) { // 已经分配到某个打孔组，但是还没有打孔完成
            $this->HostDb->join('user as E', 'E.u_id = opc_punch', 'left');
            if (!empty($Search['punches'])) {
                $this->HostDb->where('opc_punch', $Search['punches']);
            }
            $this->HostDb->where('opc_status', $Search['status'])
                ->where('opc_punch > ', ZERO)
                ->where('opc_punch_datetime is null')
                ->where('opc_procedure', P_PUNCH_ONE);
        } else {
            $this->HostDb->join('user as E', 'E.u_id = opc_punch', 'left');
            if (!empty($Search['punches'])) {
                $this->HostDb->where('opc_punch', $Search['punches']);
            }
            $this->HostDb->where('opc_punch > ', ZERO);
            $this->HostDb->where('opc_punch_datetime is not null');
            if (!empty($Search['start_date'])) {
                $this->HostDb->where('opc_punch_datetime >= ', $Search['start_date']);
            }
            if (!empty($Search['end_date'])) {
                $this->HostDb->where('opc_punch_datetime <= ', $Search['end_date']);
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

        if ($Search['status'] == WP_PUNCH) {
            $this->HostDb->where('opb_status', $Search['status'])
                ->where('opb_procedure', P_PUNCH_ONE);
        } elseif ($Search['status'] == WP_PUNCHING) { // 已经分配到某个打孔组，但是还没有打孔完成
            $this->HostDb->join('user as E', 'E.u_id = opb_punch', 'left');
            if (!empty($Search['punches'])) {
                $this->HostDb->where('opb_punch', $Search['punches']);
            }
            $this->HostDb->where('opb_status', $Search['status'])
                ->where('opb_punch > ', ZERO)
                ->where('opb_punch_datetime is null')
                ->where('opb_procedure', P_PUNCH_ONE);
        } else {
            $this->HostDb->join('user as E', 'E.u_id = opb_punch', 'left');
            if (!empty($Search['punches'])) {
                $this->HostDb->where('opb_punch', $Search['punches']);
            }
            $this->HostDb->where('opb_punch > ', ZERO);
            $this->HostDb->where('opb_punch_datetime is not null');
            if (!empty($Search['start_date'])) {
                $this->HostDb->where('opb_punch_datetime >= ', $Search['start_date']);
            }
            if (!empty($Search['end_date'])) {
                $this->HostDb->where('opb_punch_datetime <= ', $Search['end_date']);
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
}
