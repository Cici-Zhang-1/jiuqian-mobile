<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mrp_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Table_saw_model extends MY_Model {
    private $_Num;
    public function __construct() {
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model order/Table_saw_model Start!');
    }

    /**
     * 选择table_saw
     * @param $Search
     * @return array|bool
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('order_product_board')
                    ->join('workflow_procedure', 'wp_id = opb_status', 'left')
                    ->join('order_product', 'op_id = opb_order_product_id', 'left')
                    ->join('product', 'p_id = op_id', 'left')
                    ->join('order', 'o_id = op_order_id', 'left')
                    ->join('user', 'u_id = opb_edge', 'left');
                if ($Search['status'] == WP_ELECTRONIC_SAW) {
                    $this->HostDb->where('opb_status', $Search['status'])
                        ->where('opb_procedure', P_TABLE_SAW);
                } elseif ($Search['status'] == WP_ELECTRONIC_SAWING) { // 已经分配到某个封边组，但是还没有封边完成
                    $this->HostDb->join('user as E', 'E.u_id = opb_saw', 'left');
                    if (!empty($Search['saw'])) {
                        $this->HostDb->where('opb_saw', $Search['saw']);
                    }
                    $this->HostDb->where('opb_status', $Search['status'])
                        ->where('opb_saw > ', ZERO)
                        ->where('opb_saw_datetime is null')
                        ->where('opb_procedure', P_TABLE_SAW);
                } else {
                    if (!empty($Search['saw'])) {
                        $this->HostDb->where('opb_saw', $Search['saw']);
                    }
                    $this->HostDb->where('opb_saw > ', ZERO);
                    $this->HostDb->where('opb_saw_datetime is not null');
                    if (!empty($Search['start_date'])) {
                        $this->HostDb->where('opb_saw_datetime >= ', $Search['start_date']);
                    }
                    if (!empty($Search['end_date'])) {
                        $this->HostDb->where('opb_saw_datetime <= ', $Search['end_date']);
                    }
                }

                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('op_num', $Search['keyword'])
                        ->group_end();
                }
                $this->HostDb->group_by('op_id');
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的MRP';
            }
        }
        return $Return;
    }

    private function _page_num($Search) {
        $this->HostDb->select('op_id')->from('order_product_board')
            ->join('order_product', 'op_id = opb_order_product_id', 'left');

        if ($Search['status'] == WP_ELECTRONIC_SAW) {
            $this->HostDb->where('opb_status', $Search['status'])
                ->where('opb_procedure', P_TABLE_SAW); // 正在等待推台锯下料的板块
        } elseif ($Search['status'] == WP_ELECTRONIC_SAWING) { // 推台锯正在下料
            $this->HostDb->join('user as E', 'E.u_id = opb_saw', 'left');
            if (!empty($Search['saw'])) {
                $this->HostDb->where('opb_saw', $Search['saw']);
            }
            $this->HostDb->where('opb_status', $Search['status']);
            $this->HostDb->where('opb_saw > ', ZERO);
            $this->HostDb->where('opb_saw_datetime is null');
        } else {
            $this->HostDb->join('user as E', 'E.u_id = opb_saw', 'left');
            if (!empty($Search['saw'])) {
                $this->HostDb->where('opb_saw', $Search['saw']);
            }
            $this->HostDb->where('opb_saw > ', ZERO);
            $this->HostDb->where('opb_saw_datetime is not null');
            if (!empty($Search['start_date'])) {
                $this->HostDb->where('opb_saw_datetime >= ', $Search['start_date']);
            }
            if (!empty($Search['end_date'])) {
                $this->HostDb->where('opb_saw_datetime <= ', $Search['end_date']);
            }
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('op_num', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->group_by('op_id');
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            $this->_Num = $Query->num_rows();
            $Query->free_result();
            if (intval($this->_Num%$Search['pagesize']) == 0) {
                $Pn = intval($this->_Num/$Search['pagesize']);
            } else {
                $Pn = intval($this->_Num/$Search['pagesize'])+1;
            }
            return $Pn;
        } else {
            return false;
        }
    }
}
