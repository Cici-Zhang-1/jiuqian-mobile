<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Workflow_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Print_list_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model order/Print_list_model Start!');
    }

    /**
     * Select from table workflow
     */
    public function select($Search) {
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            if ($Search['status'] == NO) {
                return $this->_select_no($Cache);
            } else {
                return $this->_select_yes($Cache, $Search);
            }
        }
        return $Return;
    }

    private function _select_no ($Cache) {
        $Sql = "SELECT opc_id AS v, op_num as num, c_name as product, u_truename as dismantle, op_remark as remark, o_id as order_id, o_dealer as dealer, o_owner as owner, o_remark as order_remark, o_request_outdate as request_outdate, od_sure_datetime as sure_datetime, 0 as type FROM j_order_product_classify LEFT JOIN j_classify ON c_id = opc_classify_id LEFT JOIN j_order_product ON op_id = opc_order_product_id LEFT JOIN j_order ON o_id = op_order_id LEFT JOIN j_order_datetime ON od_order_id = o_id LEFT JOIN j_user ON u_id = op_dismantle WHERE opc_status = " . WP_PRINT_LIST . ' GROUP BY op_id, opc_classify_id';
        $Sql .= " UNION ALL SELECT opb_id AS v, op_num as num, op_product as product, u_truename as dismantle, op_remark as remark, o_id as order_id, o_dealer as dealer, o_owner as owner, o_remark as order_remark, o_request_outdate as request_outdate, od_sure_datetime as sure_datetime, 1 as type FROM j_order_product_board LEFT JOIN j_order_product ON op_id = opb_order_product_id LEFT JOIN j_order ON o_id = op_order_id LEFT JOIN j_order_datetime ON od_order_id = o_id LEFT JOIN j_user ON u_id = op_dismantle WHERE opb_status = " . WP_PRINT_LIST . ' GROUP BY op_id';

        $Sql .= " ORDER BY num desc";
        $Query = $this->HostDb->query($Sql);
        $Return = false;
        if ($Query->num_rows() > 0) {
            $Return = array(
                'content' => $Query->result_array(),
                'num' => $Query->num_rows(),
                'p' => ONE,
                'pn' => ONE,
                'pagesize' => ALL_PAGESIZE
            );
            $this->cache->save($Cache, $Return, HOURS);
        } else {
            $GLOBALS['error'] = '没有符合搜索条件的打印清单';
        }
        return $Return;
    }

    private function _select_yes ($Cache, $Search) {
        $Search['pn'] = $this->_page_yes($Search);
        $Return = false;
        if(!empty($Search['pn'])){
            $Sql = "SELECT opc_id AS v, opc_print_datetime as print_datetime, op_num as num, c_name as product, u_truename as dismantle, op_remark as remark, o_id as order_id, o_dealer as dealer, o_owner as owner, o_remark as order_remark, o_request_outdate as request_outdate, od_sure_datetime as sure_datetime, 0 as type FROM j_order_product_classify LEFT JOIN j_classify ON c_id = opc_classify_id LEFT JOIN j_order_product ON op_id = opc_order_product_id LEFT JOIN j_order ON o_id = op_order_id LEFT JOIN j_order_datetime ON od_order_id = o_id LEFT JOIN j_user ON u_id = op_dismantle WHERE opc_print > " . ZERO . ' GROUP BY op_id, opc_classify_id';
            $Sql .= " UNION ALL SELECT opb_id AS v, opb_print_datetime as print_datetime, op_num as num, op_product as product, u_truename as dismantle, op_remark as remark, o_id as order_id, o_dealer as dealer, o_owner as owner, o_remark as order_remark, o_request_outdate as request_outdate, od_sure_datetime as sure_datetime, 1 as type FROM j_order_product_board LEFT JOIN j_order_product ON op_id = opb_order_product_id LEFT JOIN j_order ON o_id = op_order_id LEFT JOIN j_order_datetime ON od_order_id = o_id LEFT JOIN j_user ON u_id = op_dismantle WHERE opb_print > " . ZERO . ' GROUP BY op_id';

            $Sql .= " ORDER BY num desc LIMIT " . ($Search['p']-1)*$Search['pagesize'] . ', ' . $Search['pagesize'];

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
            $GLOBALS['error'] = '没有符合搜索条件的打印清单';
        }
        return $Return;
    }

    private function _page_yes ($Search) {
        $Sql = "SELECT opc_id AS v FROM j_order_product_classify WHERE opc_print > " . ZERO . ' GROUP BY opc_order_product_id, opc_classify_id';
        $Sql .= " UNION ALL SELECT opb_id AS v FROM j_order_product_board WHERE opb_print > " . ZERO . ' GROUP BY opb_order_product_id';

        $Query = $this->HostDb->query($Sql);
        if($Query->num_rows() > 0){
            $this->_Num = $Query->num_rows();
            $Query->free_result();
            if(intval($this->_Num%$Search['pagesize']) == 0){
                $Pn = intval($this->_Num/$Search['pagesize']);
            }else{
                $Pn = intval($this->_Num/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }
}
