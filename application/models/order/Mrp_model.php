<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mrp_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Mrp_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model order/Mrp_model Start!');
    }

    /**
     * Select from table mrp
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('mrp')
                    ->join('user', 'u_id = m_distribution', 'left')
                    ->join('workflow_mrp', 'wm_id = m_status', 'left');
                if (isset($Search['keyword'])) {
                    $this->HostDb->like('m_batch_num', $Search['keyword']);
                }
                if (!empty($Search['distribution'])) {
                    $this->HostDb->where('m_distribution', $Search['distribution']);
                }
                if (isset($Search['status']) && $Search['status'] != '') {
                    $this->HostDb->where('m_status', $Search['status']);
                }
                $this->HostDb->group_by('m_batch_num');
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

    private function _page_num($Search){
        $this->HostDb->select('m_id', FALSE);
        if (isset($Search['keyword'])) {
            $this->HostDb->like('m_batch_num', $Search['keyword']);
        }
        if (!empty($Search['distribution'])) {
            $this->HostDb->where('m_distribution', $Search['distribution']);
        }
        if (isset($Search['status']) && $Search['status'] != '') {
            $this->HostDb->where('m_status', $Search['status']);
        }
        $this->HostDb->group_by('m_batch_num');
        $this->HostDb->from('mrp');

        $Query = $this->HostDb->get();
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

    /**
     * 获取为下料的板块清单
     * @param bool $Thick
     * @return int
     */
    public function select_un_electronic_sawed ($Thick = true) {
        $this->HostDb->select_sum('m_num')
            ->from('mrp')
            ->join('board', 'b_name = m_board', 'left')
            ->where_in('m_status', array(M_SHEAR, M_SHEARED, M_ELECTRONIC_SAW));
        if ($Thick) {
            $this->HostDb->where('b_thick > ', THICK);
        } else {
            $this->HostDb->where('b_thick < ', THICK);
        }
        $Query = $this->HostDb->get();
        $Row = $Query->row_array();
        $Row['m_num'] = $Row['m_num'] ? $Row['m_num'] : ZERO;
        return $Row['m_num'];
    }

    /**
     * 获取已经下料的统计数据
     * @param $Day
     * @return bool
     */
    public function select_electronic_sawed ($Day) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Day;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('mrp')
                ->join('board', 'b_name = m_board', 'left')
                ->join('user AS C', 'C.u_id = m_distribution', 'left')
                ->join('user as E', 'E.u_id = m_saw', 'left');
            $this->HostDb->where('m_saw_datetime >= ', $Day);

            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    /**
     * 电子锯
     */
    public function select_electronic_saw($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num_electronic_saw($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('mrp')
                    ->join('user AS S', 'S.u_id = m_shear', 'left')
                    ->join('user as E', 'E.u_id = m_saw', 'left');
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->like('m_batch_num', $Search['keyword']);
                }
                if (!empty($Search['distribution'])) {
                    $this->HostDb->where('m_distribution', $Search['distribution']);
                }
                if (!empty($Search['start_date'])) {
                    $this->HostDb->where('m_saw_datetime >= ', $Search['start_date']);
                }
                if (!empty($Search['end_date'])) {
                    $this->HostDb->where('m_saw_datetime <= ', $Search['end_date']);
                }
                if (isset($Search['status']) && $Search['status'] != '') {
                    $this->HostDb->where('m_status', $Search['status']);
                }
                $this->HostDb->group_by('m_batch_num');  // 不分板材，只按批次
                if ($Search['status'] == M_ELECTRONIC_SAW) { // 未下料则按安排日期排序
                    $this->HostDb->order_by('m_shear_datetime');
                } else {
                    $this->HostDb->order_by('m_saw_datetime', 'desc'); // 已下料则按下料日期倒序
                }
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

    private function _page_num_electronic_saw($Search) {
        $this->HostDb->select('m_id', FALSE)->from('mrp');
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->like('m_batch_num', $Search['keyword']);
        }
        if (!empty($Search['distribution'])) {
            $this->HostDb->where('m_distribution', $Search['distribution']);
        }
        if (!empty($Search['start_date'])) {
            $this->HostDb->where('m_saw_datetime >= ', $Search['start_date']);
        }
        if (!empty($Search['end_date'])) {
            $this->HostDb->where('m_saw_datetime <= ', $Search['end_date']);
        }
        if (isset($Search['status']) && $Search['status'] != '') {
            $this->HostDb->where('m_status', $Search['status']);
        }
        $this->HostDb->group_by('m_batch_num');
        $Query = $this->HostDb->get();
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

    /**
     * 获取生产过程跟踪
     * @param $Search
     * @return array|bool
     */
//    public function select_produce_process_tracking ($Search) {
//        $Item = $this->_Item . __FUNCTION__;
//        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
//        $Return = false;
//        if (!($Return = $this->cache->get($Cache))) {
//            $Search['pn'] = $this->_page_num_produce_process_tracking($Search);
//            if(!empty($Search['pn'])){
//                $Sql = $this->_unformat_as($Item);
//                $this->HostDb->select($Sql)->from('n9_order_product_classify')
//                    ->join('n9_mrp', 'm_batch_num = opc_batch_num && m_board = opc_board', 'left', false)
//                    ->join('n9_workflow_mrp_msg', 'wmm_mrp_id = m_id && wmm_workflow_mrp_id = ' . M_ELECTRONIC_SAW, 'left', false)
//                    ->join('order_product', 'op_id = opc_order_product_id', 'left')
//                    ->join('order', 'o_id = op_order_id', 'left')
//                    ->join('user', 'u_id = o_creator', 'left');
//                if (isset($Search['keyword']) && $Search['keyword'] != '') {
//                    $this->HostDb->group_start()
//                        ->like('op_num', $Search['keyword'])
//                        ->or_like('o_dealer', $Search['keyword'])
//                        ->or_like('o_owner', $Search['keyword'])
//                        ->group_end();
//                }
//                if (isset($Search['order_type']) && $Search['order_type'] != '') {
//                    $this->HostDb->where('o_order_type', $Search['order_type']);
//                }
//                if (isset($Search['warn_date']) && $Search['warn_date'] != '') {
//                    $this->HostDb->where('wmm_create_datetime <= ', $Search['warn_date']);
//                }
//                $this->HostDb->where_in('m_status', array(M_ELECTRONIC_SAW, M_ELECTRONIC_SAWED));
//                $this->HostDb->where_in('op_status', array(OP_PRODUCING, OP_PACKED, OP_PACKING));
//                $this->HostDb->where('o_status > ', O_PRODUCE);
//                $this->HostDb->where('o_status < ', O_INED);
//                $this->HostDb->group_by('op_id');
//                $this->HostDb->order_by('wmm_create_datetime');
//
//                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
//                $Return = array(
//                    'content' => $Query->result_array(),
//                    'num' => $this->_Num,
//                    'p' => $Search['p'],
//                    'pn' => $Search['pn'],
//                    'pagesize' => $Search['pagesize']
//                );
//                $this->cache->save($Cache, $Return, MONTHS);
//            } else {
//                $GLOBALS['error'] = '没有符合搜索条件的生产过程跟踪';
//            }
//        }
//        return $Return;
//    }

//    private function _page_num_produce_process_tracking ($Search) {
//        $this->HostDb->select('op_id', FALSE)->from('n9_order_product_classify')
//            ->join('n9_mrp', 'm_batch_num = opc_batch_num && m_board = opc_board', 'left', false)
//            ->join('n9_workflow_mrp_msg', 'wmm_mrp_id = m_id && wmm_workflow_mrp_id = ' . M_ELECTRONIC_SAW, 'left', false)
//            ->join('order_product', 'op_id = opc_order_product_id', 'left')
//            ->join('order', 'o_id = op_order_id', 'left')
//            ->join('user', 'u_id = o_creator', 'left');
//        if (isset($Search['keyword']) && $Search['keyword'] != '') {
//            $this->HostDb->group_start()
//                ->like('op_num', $Search['keyword'])
//                ->or_like('o_dealer', $Search['keyword'])
//                ->or_like('o_owner', $Search['keyword'])
//                ->group_end();
//        }
//        if (isset($Search['order_type']) && $Search['order_type'] != '') {
//            $this->HostDb->where('o_order_type', $Search['order_type']);
//        }
//        if (isset($Search['warn_date']) && $Search['warn_date'] != '') {
//            $this->HostDb->where('wmm_create_datetime <= ', $Search['warn_date']);
//        }
//        $this->HostDb->where_in('m_status', array(M_ELECTRONIC_SAW, M_ELECTRONIC_SAWED));
//        $this->HostDb->where_in('op_status', array(OP_PRODUCING, OP_PACKED, OP_PACKING));
//        $this->HostDb->where('o_status > ', O_PRODUCE);
//        $this->HostDb->where('o_status < ', O_INED);
//        $this->HostDb->group_by('op_id');
//
//        $Query = $this->HostDb->get();
//        if($Query->num_rows() > 0){
//            $Row = $Query->num_rows();
//            $Query->free_result();
//            $this->_Num = $Row;
//            if(intval($Row%$Search['pagesize']) == 0){
//                $Pn = intval($Row/$Search['pagesize']);
//            }else{
//                $Pn = intval($Row/$Search['pagesize'])+1;
//            }
//            return $Pn;
//        }else{
//            return false;
//        }
//    }

    public function select_user_current_work ($User, $Status) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('mrp')
            ->where('m_distribution', $User)
            ->where('m_status', $Status)
            ->group_by('m_id')->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }
    /**
     * 判断是否是等待下料
     */
    public function is_electronic_saw ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('mrp')
            ->where_in('m_id', $Vs)
            ->where('m_status', M_ELECTRONIC_SAW)->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    /**
     * 判断MRP状态
     * @param $Vs
     * @param $Status
     * @return bool
     */
    public function is_status_and_brothers ($Vs, $Status) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $this->HostDb->select('m_batch_num')->from('mrp')
            ->where_in('m_id', $Vs);
        if (is_array($Status)) {
            $this->HostDb->where_in('m_status', $Status);
        } else {
            $this->HostDb->where('m_status', $Status);
        }
        $S = $this->HostDb->group_by('m_batch_num')->get_compiled_select();
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('mrp')
            ->where_in('m_batch_num', $S, false);
        if (is_array($Status)) {
            $this->HostDb->where_in('m_status', $Status);
        } else {
            $this->HostDb->where('m_status', $Status);
        }
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    /**
     * 判断是否可以分配任务
     * @param $Vs
     * @return bool
     */
    public function is_distributable($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('mrp')
            ->where_in('m_id', $Vs)
            ->where_in('m_status', array(M_ELECTRONIC_SAW, M_SHEAR, M_SHEARED))->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    /**
     * 判断是否是已经存在的BatchNum
     * @param $BatchNum
     * @param $Board
     * @return bool
     */
    public function is_exist_batch_num ($BatchNum, $Board) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$BatchNum . $Board;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('mrp')
                ->where('m_batch_num', $BatchNum)
                ->where('m_board', $Board);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    public function is_exist ($BatchNum) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$BatchNum;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('mrp')
                ->where('m_batch_num', $BatchNum);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    /**
     * 通过V获取订单产品V
     * @param $Vs
     * @return bool
     */
    public function select_order_product_v_by_v($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('mrp')
            ->join('order_product_classify', 'opc_batch_num = m_batch_num', 'left')
            ->where_in('m_id', $Vs)
            ->group_by('opc_order_product_id')->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    /**
     * 获取OPC_ID
     * @param $Vs
     * @return bool
     */
    public function select_order_product_classify_id ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_classify')
            ->join('mrp', 'm_id = opc_mrp_id', 'left')
            ->where_in('m_id', $Vs)
            ->group_by('opc_id')->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
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
            ->from('mrp')
            ->join('workflow_mrp', 'wm_id = m_status', 'left')
            ->where('m_id', $V)->limit(1)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->row_array();
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * Insert data to table mrp
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('mrp', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入MRP数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table mrp
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('mrp', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入MRP数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table mrp
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('m_id', $Where);
        } else {
            $this->HostDb->where('m_id', $Where);
        }
        $this->HostDb->update('mrp', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table mrp
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('mrp', $Data, 'm_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table mrp
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('m_id', $Where);
        } else {
            $this->HostDb->where('m_id', $Where);
        }

        $this->HostDb->delete('mrp');
        $this->remove_cache($this->_Module);
        return true;
    }
}
