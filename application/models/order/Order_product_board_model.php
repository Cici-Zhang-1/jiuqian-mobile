<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月24日
 * @author zhangcc
 * @version
 * @des
 */
class Order_product_board_model extends MY_Model{
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model Order/Order_product_board_model start!');
    }

    public function select ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, false)->from('order_product_board')
                ->join('order_product', 'op_id = opb_order_product_id', 'left')
                ->join('product', 'p_id = op_product_id', 'left')
                ->join('workflow_order_product', 'wop_id = op_status', 'left')
                ->where('op_status > ', OP_REMOVE)
                ->where_in('op_order_id', is_array($Search['order_id']) ? $Search['order_id']: array($Search['order_id']));
            if (!empty($Search['product_id'])) {
                $this->HostDb->where_in('op_product_id', is_array($Search['product_id']) ? $Search['product_id'] : array($Search['product_id']));
            }

            $Query = $this->HostDb->order_by('op_product_id')->order_by('op_id')->order_by('opb_board', 'desc')->get();
            if ($Query->num_rows() > 0) {
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $Query->num_rows(),
                    'p' => ONE,
                    'pn' => ONE,
                    'pagesize' => ALL_PAGESIZE
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单板材信息';
            }
        }
        return $Return;
    }

    /**
     * 根据订单产品编号获取需要确定的板材
     * @param $OrderProductId
     */
    public function select_for_sure ($OrderProductId) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $OrderProductId;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('order_product_board')
                ->where('opb_order_product_id', $OrderProductId);
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单板材信息';
            }
        }
        return $Return;
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
        $Query = $this->HostDb->select($Sql)->from('order_product_board')
            ->where('opb_order_product_id', $OrderProductId)
            ->where('opb_status >= ', WP_PACK)
            ->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }
    /**
     * 是否可以转换
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
            $this->HostDb->from('order_product_board')
                ->join('order_product', 'op_id = opb_order_product_id', 'left')
                ->where_in('opb_id', $Vs)
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
    /**
     * 根据订单ID获取面积
     * @param $OrderV
     * @param $Thick
     * @return bool
     */
    public function select_area_by_order_v($OrderV, $Thick = true) {
        $this->HostDb->select_sum('opb_area')->from('order_product_board')
            ->join('board', 'b_name = opb_board', 'left')
            ->join('order_product', 'op_id = opb_order_product_id', 'left')
            ->join('order', 'o_id = op_order_id', 'left')
            ->where('o_id', $OrderV)
            ->where('o_status != ', O_REMOVE)
            ->where('op_status != ', OP_REMOVE);
        if ($Thick) {
            $this->HostDb->where('b_thick > ', THICK); // 按厚板面积推荐
        }
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
            $Query->free_result();
            return $Return['opb_area'];
        }
        return false;
    }

    /**
     * 通过订单产品V获取需要走生产流水线的订单产品板块
     * @param $OrderProductV
     * @return bool
     */
    public function select_produce_process_by_order_product_v ($OrderProductV) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_board')
            ->join('board', 'b_name = opb_board', 'left')
            ->join('board_thick', 'bt_id = b_thick', 'left')
            ->where_in('opb_order_product_id', $OrderProductV)
            ->where('bt_name > ', THICK)->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    public function select_order_product_v_by_v ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_board')
            ->where_in('opb_id', $V)
            ->group_by('opb_order_product_id')->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    /**
     * 获取订单产品id
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
            $this->HostDb->from('order_product_board')
                ->where_in('opb_id', $Vs)
                ->group_by('opb_order_product_id');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    public function select_next_scan () {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_board')
            ->where('opb_status', OPB_SCAN)->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        } else {
            $GLOBALS['error'] = '没有等待扫描的订单产品!';
        }
        return $Return;
    }

    public function select_next_pack () {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_board')
            ->where('opb_status', OPB_PACK)->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        } else {
            $GLOBALS['error'] = '没有等待打包的订单产品!';
        }
        return $Return;
    }

    /**
     * 判断是否是正在封边
     * @param $V
     * @return bool
     */
    public function is_edging ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_board')
            ->where_in('opb_id', $Vs)
            ->where('opb_status', OPB_EDGING)->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    public function select_user_current_work ($User, $Status) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_board')
            ->join('n9_workflow_order_product_board_msg', 'wopbm_order_product_board_id = opb_id && wopbm_workflow_order_product_board_id = opb_status', 'left', false)
            ->where('wopbm_creator', $User)
            ->where('opb_status', $Status)
            ->group_by('opb_id')->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }
    /**
     * 判断是否是正在封边
     * @param $V
     * @return bool
     */
    public function is_status ($Vs, $Status) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_board')
            ->where_in('opb_id', $Vs)
            ->where('opb_status', $Status)->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    /**
     * 判断是否是某些状态并获取兄弟
     * @param $Vs
     * @param $Status
     * @param $Procedure
     * @return bool
     */
    public function is_status_and_brothers ($Vs, $Status, $Procedure = 0) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Status = is_array($Status) ? $Status : array($Status);
        $this->HostDb->select('opb_order_product_id')->from('order_product_board')
            ->where_in('opb_id', $Vs)
            ->where_in('opb_status', $Status);
        if (!empty($Procedure)) {
            $this->HostDb->where('opc_procedure', $Procedure);
        }
        $S = $this->HostDb->group_by('opb_order_product_id')->get_compiled_select();
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('order_product_board')
            ->where_in('opb_order_product_id', $S, false)
            ->where_in('opb_status', $Status);
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
     * 是否封边
     * @param $Vs
     * @return bool
     */
    public function are_edged_and_brothers ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $S = $this->HostDb->select('opb_order_product_id')->from('order_product_board')
            ->where_in('opb_id', $Vs)
            ->where('opb_edge > ', ZERO)
            ->where('opb_edge_datetime is not null')
            ->group_by('opb_order_product_id')->get_compiled_select();
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_board')
            ->where_in('opb_order_product_id', $S, false)
            ->where('opb_edge > ', ZERO)
            ->where('opb_edge_datetime is not null')->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    public function are_scanned_and_brothers ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $S = $this->HostDb->select('opb_order_product_id')->from('order_product_board')
            ->where_in('opb_id', $Vs)
            ->where('opb_scan > ', ZERO)
            ->where('opb_scan_datetime is not null')
            ->group_by('opb_order_product_id')->get_compiled_select();
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_board')
            ->where_in('opb_order_product_id', $S, false)
            ->where('opb_scan > ', ZERO)
            ->where('opb_scan_datetime is not null')->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }
    /**
     * 是否有兄弟元素
     * @param $V
     * @return bool
     */
    public function has_brothers ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        if (!!($Self = $this->_select_by_v($V))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)
                ->from('order_product_board')
                ->join('order_product', 'op_id = opb_order_product_id', 'left')
                ->join('order', 'o_id = op_order_id', 'left')
                ->where('opb_order_product_id', $Self['order_product_id'])
                ->get();
            if ($Query->num_rows() > ZERO) {
                $Return = $Query->result_array();
            } else {
                $GLOBALS['error'] = '没有找到相应的板材!';
            }
        } else {
            $GLOBALS['error'] = '没有找到相应的板材!';
        }
        return $Return;
    }
    private function _select_by_v ($V) {
        $Query = $this->HostDb->select('opb_order_product_id as order_product_id')
            ->from('order_product_board')
            ->where('opb_id', $V)
            ->limit(ONE)
            ->get();
        if ($Query->num_rows() > ZERO) {
            return $Query->row_array();
        }
        return false;
    }

    /**
     * 获取下一工作流状态
     * @param $Vs
     * @return bool
     */
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
    private function _select_production_line ($Vs) {
        $Query = $this->HostDb->select('opb_id as v, opb_production_line as production_line, opb_procedure as procedure, plp_displayorder as displayorder')
            ->from('order_product_board')
            ->join('j_production_line_procedure', 'plp_production_line = opb_production_line && plp_procedure = opb_procedure', 'left', false)
            ->where_in('opb_id', $Vs)
            ->get();
        $Return = false;
        if ($Query->num_rows() > ZERO) {
            $Return = $Query->result_array();
            $Query->free_result();
        } else {
            $GLOBALS['error'] = '您选择的订单产品板材不存在!';
        }
        return $Return;
    }

    /**
     * 当前工作流
     * @param $V
     * @return bool
     */
    public function select_current_workflow($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('order_product_board')
            ->join('workflow_procedure', 'wp_id = opb_status', 'left')
            ->where('opb_id', $V)->limit(1)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->row_array();
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 获取是柜体的板块
     * @param $Vs
     * @return bool
     */
    public function select_only_guiti ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('order_product_board')
            ->join('order_product', 'op_id = opb_order_product_id', 'left')
            ->where_in('opb_id', $Vs)
            ->where_in('op_product_id', array(CABINET, WARDROBE))
            ->group_by('opb_id')->get();
        if($Query->num_rows() > 0){
            $Return = $Query->result_array();
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 判断订单板块是否存在
     * @param $Opid
     * @param $Board
     * @return bool
     */
    public function is_existed($Opid, $Board) {
        $Cache = $this->_Cache.__FUNCTION__.$Opid.$Board;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Query = $this->HostDb->select('opb_id')
                ->from('order_product_board')
                ->where('opb_order_product_id', $Opid)
                ->where('opb_board', $Board)
                ->limit(1)
                ->get();
            if($Query->num_rows() > 0){
                $Row = $Query->row_array();
                $Query->free_result();
                $Return = $Row['opb_id'];
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    /**
     * @param $Con
     * @return bool
     */
    public function select_board_predict($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE)
                ->from('order_product_board')
                ->join('board', 'b_name = opb_board', 'left')
                ->join('order_product', 'op_id = opb_order_product_id', 'left')
                ->join('order', 'o_id = op_order_id', 'left')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->where('o_order_type', REGULAR_ORDER)
                ->where('od_check_datetime > ', $Con['start_date'])
                ->where('od_check_datetime < ', $Con['end_date'])
                ->where('op_status > ', OP_DISMANTLING)
                ->where('o_status > ', O_CHECKED)
                ->where('opb_area > ', ZERO);

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '本月还没有开始销售!';
                $Return = false;
            }
        }
        return $Return;
    }

    /**
     * 获取核价后的已拆订单，用于拆单统计
     * @param $Con
     * @return bool
     */
    public function select_dismantled($Con) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE)
                ->from('order_product_board')
                ->join('board', 'b_name = opb_board', 'left')
                ->join('order_product', 'op_id = opb_order_product_id', 'left')
                ->join('product', 'p_id = op_product_id', 'left')
                ->join('order', 'o_id = op_order_id', 'left')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->where('op_dismantle', $Con['dismantle'])
                ->where('op_status > ', OP_DISMANTLING)
                ->where('o_status > ', O_CHECKED)
                ->where('od_check_datetime > ', $Con['start_date']);

            if(!empty($Con['end_date'])){
                $this->HostDb->where('od_check_datetime < ', $Con['end_date']);
            }

            $Query = $this->HostDb->order_by('o_id', 'desc')->order_by('op_product_id')->order_by('op_id')->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '在此期间段您没有分解!';
                $Return = false;
            }
        }
        return $Return;
    }

    /**
     * 销售数据
     * @param $Con
     * @return bool
     */
    public function select_sales($Con) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.array_to_string($Con);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);

            $this->HostDb->select($Sql, FALSE)
                ->from('order_product_board')
                ->join('board', 'b_name = opb_board', 'left')
                ->join('order_product', 'op_id = opb_order_product_id', 'left')
                ->join('product', 'p_id = op_product_id', 'left')
                ->join('order', 'o_id = op_order_id', 'left')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->where('op_status > ', OP_DISMANTLING)
                ->where('o_status > ', O_WAIT_SURE)
                ->where('od_sure_datetime > ', $Con['start_date']);
            if(!empty($Con['end_date'])) {
                $this->HostDb->where('od_sure_datetime < ', $Con['end_date']);
            }

            $Group = false;
            if (!empty($Con['board_color'])) {
                if ($Group) {
                    $this->HostDb->or_where_in('b_color', $Con['board_color']);
                } else {
                    $this->HostDb->group_start();
                    $this->HostDb->where_in('b_color', $Con['board_color']);
                    $Group = true;
                }
            }
            if (!empty($Con['board_nature'])) {
                if ($Group) {
                    $this->HostDb->or_where_in('b_nature', $Con['board_nature']);
                } else {
                    $this->HostDb->group_start();
                    $this->HostDb->where_in('b_nature', $Con['board_nature']);
                    $Group = true;
                }
            }
            if (!empty($Con['board_thick'])) {
                if ($Group) {
                    $this->HostDb->or_where_in('b_thick', $Con['board_thick']);
                } else {
                    $this->HostDb->group_start();
                    $this->HostDb->where_in('b_thick', $Con['board_thick']);
                    $Group = true;
                }
            }
            if (!empty($Con['product_id'])) {
                if ($Group) {
                    $this->HostDb->or_where_in('p_id', $Con['product_id']);
                } else {
                    $this->HostDb->group_start();
                    $this->HostDb->where_in('p_id', $Con['product_id']);
                    $Group = true;
                }
            }
            if ($Group) {
                $this->HostDb->group_end();
            }
            if(!empty($Con['keyword'])){
                $this->HostDb->group_start()
                    ->like('o_dealer', $Con['keyword'])
                    ->group_end();
            }

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有对应销售记录';
                $Return = false;
            }
        }
        return $Return;
    }
    /**
     * 插入数据
     * @param $Data
     * @return bool
     */
    public function insert ($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('order_product_board', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入MRP数据失败!';
            return false;
        }
    }
    /**
     * 更新内容
     * @param $Data
     * @param $Where
     * @return bool
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('opb_id', $Where);
        } else {
            $this->HostDb->where('opb_id', $Where);
        }
        $this->HostDb->update('order_product_board', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新
     * @param $Data
     * @return bool
     */
    public function update_batch($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('order_product_board', $Data, 'opb_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 清除板块信息
     * @param $OrderProductId
     * @return mixed
     */
    public function delete_by_order_product_id ($OrderProductId) {
        $this->HostDb->where_in('opb_order_product_id', is_array($OrderProductId) ? $OrderProductId : array($OrderProductId));
        $this->remove_cache($this->_Module);
        return $this->HostDb->delete('order_product_board');
    }
    public function delete_not_in ($OrderProductId, $OrderProductBoardIds) {
        $this->HostDb->where('opb_order_product_id', $OrderProductId);
        $this->HostDb->where_not_in('opb_id', $OrderProductBoardIds);
        $this->remove_cache($this->_Module);
        return $this->HostDb->delete('order_product_board');
    }

    public function clear ($Where) {
        if (is_array($Where)) {
            $this->HostDb->where_in('opb_order_product_id', $Where);
        } else {
            $this->HostDb->where('opb_order_product_id', $Where);
        }
        $this->HostDb->update('order_product_board', array('opb_status' => ZERO, 'opb_procedure' => ZERO, 'opb_production_line' => ZERO, 'opb_print' => ZERO, 'opb_print_datetime' => null));
        $this->remove_cache($this->_Module);
        return true;
    }
}
