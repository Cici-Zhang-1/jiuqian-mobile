<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order_product_classify_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Order_product_classify_model extends MY_Model {
    private $_Num;
    public function __construct() {
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model order/Order_product_classify_model Start!');
    }

    /**
     * Select from table order_product_classify
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('order_product_classify')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品板块分类';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(opc_id) as num', FALSE);
        $this->HostDb->from('order_product_classify');

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Search['pagesize']) == 0){
                $Pn = intval($Row['num']/$Search['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    public function select_optimize ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            if (!is_array($Search['status'])) {
                $Search['status'] = explode(',', $Search['status']);
            }
            if (!is_array($Search['product_id'])) {
                $Search['product_id'] = explode(',', $Search['product_id']);
            }
            $Search['pn'] = $this->_page_optimize_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('order_product_classify')
                                ->join('classify', 'c_id = opc_classify_id', 'left')
                                ->join('order_product', 'op_id = opc_order_product_id', 'left')
                                ->join('order', 'o_id = op_order_id', 'left')
                                ->join('order_datetime', 'od_order_id = o_id', 'left')
                                ->join('user AS D', 'D.u_id = op_dismantle', 'left')
                                ->join('user AS O', 'O.u_id = opc_optimize', 'left')
                                ->where('op_status >= ', OP_DISMANTLED)
                                ->where('o_status >= ', O_PRODUCE)
                                ->where('opc_amount > ', ZERO)
                                ->where_in('op_product_id', $Search['product_id']);
                if (!empty($Search['keyword'])) {
                    $this->HostDb->group_start()
                        ->like('op_remark', $Search['keyword'])
                        ->or_like('o_remark', $Search['keyword'])
                        ->or_like('o_dealer', $Search['keyword'])
                        ->or_like('o_owner', $Search['keyword'])
                        ->or_like('op_num', $Search['keyword'])
                        ->or_like('opc_board', $Search['keyword'])
                        ->or_like('opc_optimize_datetime', $Search['keyword'])
                        ->group_end();
                }
                if (in_array(YES, $Search['status']) && in_array(NO, $Search['status'])) {  // 所有的
                    $this->HostDb->group_start()
                        ->where('opc_optimize > ', ZERO)
                        ->or_where('opc_status', WP_OPTIMIZE)
                        ->group_end();
                } elseif (in_array(YES, $Search['status']) &&  !in_array(NO, $Search['status'])) { // 已经优化的
                    $this->HostDb->where('opc_optimize > ', ZERO);
                    $this->HostDb->order_by('opc_optimize_datetime', 'desc');
                } else { // 未优化的
                    $this->HostDb->where('opc_status', WP_OPTIMIZE);
                }
                $this->HostDb->order_by('op_num')->order_by('opc_board');

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
                $GLOBALS['error'] = '没有符合搜索条件的优化订单';
            }
        }
        return $Return;
    }
    private function _page_optimize_num ($Search) {
        $this->HostDb->select('count(opc_id) as num', FALSE)
            ->from('order_product_classify')
            ->join('classify', 'c_id = opc_classify_id', 'left')
            ->join('order_product', 'op_id = opc_order_product_id', 'left')
            ->join('order', 'o_id = op_order_id', 'left')
            ->where('op_status >= ', OP_DISMANTLED)
            ->where('o_status >= ', O_PRODUCE)
            ->where('opc_amount > ', ZERO)
            ->where_in('op_product_id', $Search['product_id']);
        if (!empty($Search['keyword'])) {
            $this->HostDb->group_start()
                ->like('op_remark', $Search['keyword'])
                ->or_like('o_remark', $Search['keyword'])
                ->or_like('o_dealer', $Search['keyword'])
                ->or_like('o_owner', $Search['keyword'])
                ->or_like('op_num', $Search['keyword'])
                ->or_like('opc_board', $Search['keyword'])
                ->or_like('opc_optimize_datetime', $Search['keyword'])
                ->group_end();
        }
        if (in_array(YES, $Search['status']) && in_array(NO, $Search['status'])) {  // 所有的
            $this->HostDb->group_start()
                ->where('opc_optimize > ', ZERO)
                ->or_where('opc_status', WP_OPTIMIZE)
                ->group_end();
        } elseif (in_array(YES, $Search['status']) &&  !in_array(NO, $Search['status'])) { // 已经优化的
            $this->HostDb->where('opc_optimize > ', ZERO);
            $this->HostDb->order_by('opc_optimize_datetime', 'desc');
        } else { // 未优化的
            $this->HostDb->where('opc_status', WP_OPTIMIZE);
        }

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Search['pagesize']) == 0){
                $Pn = intval($Row['num']/$Search['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * 获取板块分类是否可以打包
     * @param $OrderProductId
     * @return bool
     */
    public function select_packable_by_order_product_id ($OrderProductId) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_classify')
            ->join('user', 'u_id = opc_pack', 'left')
            ->where('opc_order_product_id', $OrderProductId)
            ->where('opc_status >= ', WP_ELECTRONIC_SAWED)
            ->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }
    /**
     * 判断是否是某种状态以及兄弟项
     * @param $Vs
     * @param $Status
     * @param $Procedure
     * @return bool
     */
    public function is_status_and_brothers ($Vs, $Status, $Procedure = 0) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Status = is_array($Status) ? $Status : array($Status);
        $this->HostDb->select('opc_order_product_id')->from('order_product_classify')
            ->where_in('opc_id', $Vs)
            ->where_in('opc_status', $Status);
        if (!empty($Procedure)) {
            $this->HostDb->where('opc_procedure', $Procedure);
        }
        $S = $this->HostDb->group_by('opc_order_product_id')->get_compiled_select();
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('order_product_classify')
            ->where_in('opc_order_product_id', $S, false)
            ->where_in('opc_status', $Status);
        if (!empty($Procedure)) {
            $this->HostDb->where('opc_procedure', $Procedure);
        }
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    /**
     * 判断是否已经封边和兄弟项
     * @param $Vs
     * @return bool
     */
    public function are_edged_and_brothers ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $S = $this->HostDb->select('opc_order_product_id')->from('order_product_classify')
            ->where_in('opc_id', $Vs)
            ->where('opc_edge > ', ZERO)
            ->where('opc_edge_datetime is not null')
            ->group_by('opc_order_product_id')->get_compiled_select();
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_classify')
            ->where_in('opc_order_product_id', $S, false)
            ->where('opc_edge > ', ZERO)
            ->where('opc_edge_datetime is not null')->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    /**
     * 是否已扫描和兄弟项
     * @param $Vs
     * @return bool
     */
    public function are_scanned_and_brothers ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_classify')
            ->where_in('opc_id', $Vs)
            ->where('opc_scan > ', ZERO)
            ->where('opc_scan_datetime is not null')
            ->get();

        /* $Query = $this->HostDb->select($Sql)->from('order_product_classify')
            ->where_in('opc_order_product_id', $S, false)
            ->where('opc_scan > ', ZERO)
            ->where('opc_scan_datetime is not null')->get(); */
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    public function has_brothers ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;

        if (!!($Self = $this->_select_by_v($V))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)
                ->from('order_product_classify')
                ->join('classify', 'c_id = opc_classify_id', 'left')
                ->join('order_product', 'op_id = opc_order_product_id', 'left')
                ->join('order', 'o_id = op_order_id', 'left')
                ->where('opc_order_product_id', $Self['order_product_id'])
                ->where('opc_classify_id', $Self['classify_id'])
                ->get();
            if ($Query->num_rows() > ZERO) {
                $Return = $Query->result_array();
            } else {
                $GLOBALS['error'] = '没有找到相应的板块分类!';
            }
        } else {
            $GLOBALS['error'] = '没有找到相应的板块分类!';
        }
        return $Return;
    }

    private function _select_by_v ($V) {
        $Query = $this->HostDb->select('opc_classify_id as classify_id, opc_order_product_id as order_product_id')
            ->from('order_product_classify')
            ->where('opc_id', $V)
            ->limit(ONE)
            ->get();
        if ($Query->num_rows() > ZERO) {
            return $Query->row_array();
        }
        return false;
    }

    /**
     * 判断这个批次号中是否有订单处于或不处于某个状态
     * @param $Vs
     * @param $Status
     * @param bool $Not
     * @return bool
     */
    public function are_status_by_mrp_id ($Vs, $Status, $Not = false) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)
            ->from('order_product_classify')
            ->where_in('opc_mrp_id', $Vs);
        if ($Not) {
            $this->HostDb->where_not_in('opc_status', $Status);
        } else {
            $this->HostDb->where_in('opc_status', $Status);
        }
        $Query = $this->HostDb->get();
        $Return = false;
        if ($Query->num_rows() > ZERO) {
            $Return = $Query->result_array();
        }
        return $Return;
    }
    /**
     * 判断订单产品板块分类是否存在
     * @param $Classify
     * @return bool
     */
    public function is_exist($Classify) {
        $Cache = $this->_Cache.__FUNCTION__.implode(',', $Classify);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Query = $this->HostDb->select('opc_id')
                ->from('order_product_classify')
                ->where('opc_order_product_id', $Classify['order_product_id'])
                ->where('opc_board', $Classify['board'])
                ->where('opc_classify_id', $Classify['classify_id'])
                ->limit(1)
                ->get();
            if ($Query->num_rows() > 0) {
                $Row = $Query->row_array();
                $Query->free_result();
                $Return = $Row['opc_id'];
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    /**
     * 判断是否是存在的Batch_num
     * @param $BatchNum
     * @param $Board
     * @return array|bool
     */
    public function is_exist_batch_num ($BatchNum, $Board) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$BatchNum . $Board;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product_classify')
                ->where('opc_board', $Board)
                ->where('opc_optimize_datetime', $BatchNum);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    /**
     * 获取订单产品ID
     * @param $Vs
     * @return bool
     */
    public function select_order_product_id ($Vs) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__ . array_to_string($Vs);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product_classify')
                ->where_in('opc_id', $Vs)
                ->group_by('opc_order_product_id');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    /**
     * 是否可以工作流转换
     * @param $Vs
     * @return bool
     */
    public function are_to_able ($Vs) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__ . array_to_string($Vs);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product_classify')
                ->join('order_product', 'op_id = opc_order_product_id', 'left')
                ->where_in('opc_id', $Vs)
                ->where('op_status >=', OP_DISMANTLED)
                ->where('op_status <=', OP_PRE_PRODUCING)
                ->group_by('opc_order_product_id');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    public function select_workflow_next ($Vs) {
        if ($Now = $this->_select_production_line($Vs)) {
            $this->load->library('workflow/compute_workflow');
            $this->compute_workflow->initialize($this->HostDb);
            foreach ($Now as $Key => $Value) {
                $Value['displayorder']++; // 下一执行工序
                $Next = $this->compute_workflow->compute_next($Value['production_line'], $Value['displayorder']);
                if ($Next !== false) {
                    $Now[$Key] = array_merge($Now[$Key], $Next);
                    continue;
                }
                unset($Now[$Key]);
            }
        }
        return $Now;
    }

    public function select_workflow_previous ($Vs) {
        if ($Now = $this->_select_production_line($Vs)) {
            $this->load->library('workflow/compute_workflow');
            $this->compute_workflow->initialize($this->HostDb);
            foreach ($Now as $Key => $Value) {
                $Value['displayorder']--; // 下一执行工序
                $Next = $this->compute_workflow->compute_next($Value['production_line'], $Value['displayorder']);
                if ($Next !== false) {
                    $Now[$Key] = array_merge($Now[$Key], $Next);
                    continue;
                }
                unset($Now[$Key]);
            }
        }
        return $Now;
    }
    private function _select_production_line ($Vs) {
        $Query = $this->HostDb->select('opc_id as v, opc_production_line as production_line, opc_procedure as procedure, plp_displayorder as displayorder')
            ->from('order_product_classify')
            ->join('j_production_line_procedure', 'plp_production_line = opc_production_line && plp_procedure = opc_procedure', 'left', false)
            ->where_in('opc_id', $Vs)
            ->get();
        $Return = false;
        if ($Query->num_rows() > ZERO) {
            $Return = $Query->result_array();
            $Query->free_result();
        } else {
            $GLOBALS['error'] = '您选择的订单产品板块分类不存在!';
        }
        return $Return;
    }

    /**
     * 获取当前工作流
     * @param $Opcid
     * @param $Type
     * @return bool
     */
    public function select_current_workflow ($Opcid) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('order_product_classify')
            ->join('workflow_procedure', 'wp_id = opc_status', 'left')
            ->where('opc_id', $Opcid)->limit(1)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->row_array();
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }
    /**
     * Insert data to table order_product_classify
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('order_product_classify', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入订单产品板块分类数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table order_product_classify
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('order_product_classify', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入订单产品板块分类数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table order_product_classify
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('opc_id', $Where);
        } else {
            $this->HostDb->where('opc_id', $Where);
        }
        $this->HostDb->update('order_product_classify', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table order_product_classify
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('order_product_classify', $Data, 'opc_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    public function update_optimize($Ids, $Time){
        $OrderProductBoard = array();
        $Shift = array(); /*不同的订单产品进行区分-标志*/
        $Except = array(9);
        $Subs = array(0);
        $Return = array();
        $Query = $this->HostDb->select('opc_id, op_id')
            ->from('order_product_classify')
            ->join('order_product', 'op_id = opc_order_product_id', 'left')
            ->order_by('op_product_id')
            ->order_by('op_id')
            ->where_in('opc_id', $Ids)
            ->get();
        if($Query->num_rows() > 0){
            $Oids = $Query->result_array();
            $Query->free_result();

            $Num = 1;
            $Uid = $this->session->userdata('uid');
            foreach ($Oids as $key => $value){
                if(!isset($Shift[$value['op_id']])){
                    if (in_array($Num, $Except)) {
                        $Shift[$value['op_id']] = array_pop($Subs);
                        $Num++;
                    }else {
                        $Shift[$value['op_id']] = $Num++;
                    }
                }
                $OrderProductBoard[] = array(
                    'opc_id' => $value['opc_id'],
                    'opc_sn' => $Shift[$value['op_id']],
                    'opc_optimize' => $Uid,
                    'opc_optimize_datetime' => $Time
                );
                $Return[] = $value['op_id'];
            }
            $this->HostDb->update_batch('order_product_classify', $OrderProductBoard, 'opc_id');
            $this->remove_cache($this->_Module);
            $Return = array_unique($Return);
            return $Return;
        }else{
            $GLOBALS['error'] = '您要查看优化的订单不存在';
            return false;
        }
    }
    /**
     * Delete data from table order_product_classify
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('opc_id', $Where);
        } else {
            $this->HostDb->where('opc_id', $Where);
        }

        $this->HostDb->delete('order_product_classify');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 清除订单产品板块分类信息
     * @param $Where
     * @return bool
     */
    public function clear ($Where) {
        if(is_array($Where)) {
            $this->HostDb->where_in('opc_order_product_id', $Where);
        } else {
            $this->HostDb->where('opc_order_product_id', $Where);
        }

        $this->HostDb->delete('order_product_classify');
        $this->remove_cache($this->_Module);
        return true;
    }
}
