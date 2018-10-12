<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order_product_board_plate_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Order_product_board_plate_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model order/Order_product_board_plate_model Start!');
    }

    /**
     * Select from table order_product_board_plate
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('order_product_board_plate')
                    ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
                    ->join('workflow_procedure AS B', 'B.wp_id = opb_status', 'left')
                    ->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left')
                    ->join('workflow_procedure AS C', 'C.wp_id = opc_status', 'left')
                    ->where('opb_order_product_id', $Search['order_product_id'])
                    ->order_by('opbp_qrcode')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品板材板块';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $Query = $this->HostDb->select('count(opbp_id) as num', FALSE)
            ->from('order_product_board_plate')
            ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
            ->where('opb_order_product_id', $Search['order_product_id'])
            ->get();

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
     * 根据订单产品编号获取确认时需要更改的板块信息
     * @param $OrderProductId
     * @return bool
     */
    public function select_for_sure ($OrderProductId) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql, false)
            ->from('order_product_board_plate')
            ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
            ->join('order_product', 'op_id = opb_order_product_id', 'left')
            ->where('op_id', $OrderProductId)
            ->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }

    /**
     * 获取需要优化的板块
     * @param $Ids
     * @return bool
     */
    public function select_optimize ($Ids) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Ids);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->HostDb->select($Sql,false)
                ->from('order_product_board_plate')
                ->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left')
                ->join('order_product', 'op_id = opc_order_product_id', 'left')
                ->join('order','o_id = op_order_id', 'left')
                ->join('dealer','d_id = o_dealer_id', 'left')
                ->join('shop', 's_id = o_shop_id', 'left')
                ->where_in('opbp_order_product_classify_id', $Ids)
                ->order_by('opc_sn', 'acs')->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合要求需要优化的订单清单!';
            }
        }
        return $Return;
    }

    /**
     * 板块是否已经都扫描
     * @param $OrderProductClassifyId
     * @param bool $Not
     * @return bool
     */
    public function are_scanned ($OrderProductClassifyId, $Not = false) {
        $this->HostDb->select('opbp_id')
            ->from('order_product_board_plate')
            ->where_in('opbp_order_product_classify_id', $OrderProductClassifyId);
        if ($Not) {
            $this->HostDb->where('opbp_scanner', ZERO);
        } else {
            $this->HostDb->where('opbp_scanner > ', ZERO);
        }
        $Query = $this->HostDb->get();
        return $Query->num_rows() > ZERO;
    }

    /**
     * 判断编号是否存在
     * @param $Qrcode
     * @return bool
     */
    public function is_exist ($Qrcode = '', $V = ZERO) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql, false)
            ->from('order_product_board_plate')
            ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
            ->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left')
            ->join('order_product', 'op_id = opb_order_product_id', 'left')
            ->join('order', 'o_id = op_order_id', 'left');
        if (!empty($Qrcode)) {
            $this->HostDb->where('opbp_qrcode', $Qrcode);
        }
        if (!empty($V)) {
            $this->HostDb->where('opbp_id', $V);
        }
        $this->HostDb->limit(ONE);
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->row_array();
        }else{
            return false;
        }
    }

    /**
     * 获取板块清单
     * @param $OrderProductId
     * @return bool | array
     */
    public function select_scan_list($OrderProductId){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item, $this->_Module);
        $this->HostDb->select($Sql, false);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('user', 'u_id = opbp_scanner', 'left');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        $this->HostDb->where(array('op_id' => $OrderProductId));
        $this->HostDb->order_by('opbp_thick');
        $this->HostDb->order_by('opbp_qrcode');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Return = array(
                'content' => $Query->result_array(),
                'num' => $Query->num_rows(),
                'p' => ONE,
                'pn' => ONE,
                'pagesize' => ALL_PAGESIZE
            );
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 获取扫描缺板块列表
     * @param $Search
     * @return array|bool
     */
    public function select_scan_lack($Search) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Search['pn'] = $this->_page_num_scan_lack($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('order_product_board_plate')
                    ->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left')
                    ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
                    ->join('order_product', 'op_id = opc_order_product_id', 'left')
                    ->join('order', 'o_id = op_order_id', 'left')
                    ->join('order_datetime', 'od_order_id = o_id', 'left')
                    ->join('user', 'u_id = od_creator', 'left')
                    ->where('o_order_type', $Search['order_type'])
                    ->where('opbp_scanner', ZERO)
                    // ->where('op_producing_datetime > ', $Search['start_date'])
                    ->group_start()
                        ->where('opc_status', WP_SCANNING)
                        ->or_where('opb_status', WP_SCANNING)
                    ->group_end()
                    ->group_by('op_id')
                    ->having('lack <= ', $Search['lack'])
                    ->order_by('op_num')
                    ->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的差板块';
            }
        }
        return $Return;
    }

    private function _page_num_scan_lack($Search) {
        $this->HostDb->select('count(op_id) as lack', FALSE)
            ->from('order_product_board_plate')
            ->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left')
            ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
            ->join('order_product', 'op_id = opc_order_product_id', 'left')
            ->join('order', 'o_id = op_order_id', 'left')
            ->where('o_order_type', $Search['order_type'])
            ->where('opbp_scanner', ZERO)
            // ->where('op_producing_datetime > ', $Search['start_date'])
            ->group_start()
                ->where('opc_status', WP_SCANNING)
                ->or_where('opb_status', WP_SCANNING)
            ->group_end()
            ->group_by('op_id')
            ->having('lack <= ', $Search['lack']);

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row['num'] = $Query->num_rows();
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
     * 订单产品扫描详情
     * @param $Search
     * @return array|bool
     */
    public function select_scan_lack_detail($Search) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('order_product_board_plate')
                ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
                ->join('workflow_procedure AS B', 'B.wp_id = opb_status', 'left')
                ->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left')
                ->join('workflow_procedure AS C', 'C.wp_id = opc_status', 'left')
                ->join('user', 'u_id = opbp_scanner', 'left')
                ->where('opc_order_product_id', $Search['v'])
                ->order_by('opbp_scan_datetime')
                ->order_by('opbp_qrcode')->get();
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
                $GLOBALS['error'] = '没有符合搜索条件的订单产品板材板块';
            }
        }
        return $Return;
    }

    /**
     * 通过订单产品编号获取未扫描的板块
     * @param $OrderProductId
     * @return array|bool
     */
    public function select_un_scanned_by_order_product_id ($OrderProductId) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $OrderProductId;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('order_product_board_plate')
                ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
                ->where('opb_order_product_id', $OrderProductId)
                ->where('opbp_scanner', ZERO)
                ->where('(opbp_scan_datetime = "0000-00-00 00:00:00" || opbp_scan_datetime is null)')
                ->group_by('opb_id')
                ->get();
            if ($Query->num_rows() > ZERO) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品板材板块';
            }
        }
        return $Return;
    }
    /**
     * 通过订单产品V获取板块
     * @param $V
     */
    public function select_by_order_product_id ($Search) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('order_product_board_plate')
                ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
                ->join('user', 'u_id = opbp_scanner', 'left')
                ->where('opb_order_product_id', $Search['order_product_id'])
                ->order_by('opbp_scan_datetime')->get();
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
                $GLOBALS['error'] = '没有符合搜索条件的订单产品板材板块';
            }
        }
        return $Return;
    }

    /**
     * 通过板材分类获取板块信息
     * @param $OrderProductClassifyId
     * @return array|bool
     */
    public function select_by_order_product_classify_id ($OrderProductClassifyId) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $OrderProductClassifyId);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('order_product_board_plate')
                ->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left')
                ->where_in('opc_id', $OrderProductClassifyId)
                ->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品板材板块';
            }
        }
        return $Return;
    }

    /**
     * 通过板材获取板块信息
     * @param $OrderProductBoardId
     * @return bool
     */
    public function select_by_order_product_board_id ($OrderProductBoardId) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $OrderProductBoardId);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('order_product_board_plate')
                ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
                ->where_in('opb_id', $OrderProductBoardId)
                ->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品板材板块';
            }
        }
        return $Return;
    }

    /**
     * 通过订单产品编号获取异形板块
     * @param $OrderProductId
     * @return array
     */
    public function select_abnormity_by_order_product_id($OrderProductId){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($OrderProductId);
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, false)
                ->from('order_product_board_plate')
                ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
            if(is_array($OrderProductId)){
                $this->HostDb->where_in('opb_order_product_id', $OrderProductId);
            }else{
                $this->HostDb->where('opb_order_product_id', $OrderProductId);
            }
            $this->HostDb->where('opbp_abnormity', YES);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    public function select_classify_batch_num ($Qrcode) {
        $Item = $this->_Item.__FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_board_plate')
            ->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left')
            ->join('order_product', 'op_id = opc_order_product_id', 'left')
            ->join('mrp', 'm_id = opc_mrp_id', 'left')
            ->where('op_status', OP_PRE_PRODUCING)
            ->where_in('opbp_qrcode', $Qrcode)
            ->group_by('opc_id')->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        } else {
            $GLOBALS['error'] = '没有符合搜索条件的订单产品板材分类';
        }
        return $Return;
    }

    /**
     * 是否已经上传
     * @param $Qrcode
     */
    public function is_uploaded ($Qrcode) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Qrcode;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)
                ->from('order_product_board_plate')
                ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
                ->where('opbp_qrcode', $Qrcode)
                ->limit(1)
                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    /**
     * 获取bd文件
     * @param $Ids
     * @return bool
     */
    public function select_bd_files($Ids){
        $Query = $this->HostDb->select('opbp_qrcode as qrcode, opbp_bd_file as bd_file', false)
            ->from('order_product_board_plate')
            ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
            ->where_in('opb_order_product_id', $Ids)
            ->where('opbp_bd_file is not null')
            ->where('opbp_bd_file != ""')
            ->get();
        if($Query->num_rows() > 0){
            $Return = $Query->result_array();
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 检测BD文件是否存在
     * @param $Vs
     * @return bool
     */
    public function select_checked_bd ($Vs) {
        $Query = $this->HostDb->select('opbp_qrcode as qrcode', false)
            ->from('order_product_board_plate')
            ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
            ->where_in('opb_order_product_id', $Vs)
            ->group_start()
                ->where('opbp_qrcode is not null')
                ->or_where('opbp_qrcode != ""')
            ->group_end()
            ->group_start()
                ->where('opbp_bd_file is null')
                ->or_where('opbp_bd_file', '')
            ->group_end()
            ->get();
        if($Query->num_rows() > 0){
            $Return = $Query->result_array();
            return $Return;
        }else{
            return false;
        }
    }
    /**
     * Insert data to table order_product_board_plate
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('order_product_board_plate', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入订单产品板材板块数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table order_product_board_plate
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('order_product_board_plate', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入订单产品板材板块数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table order_product_board_plate
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('opbp_id', $Where);
        } else {
            $this->HostDb->where('opbp_id', $Where);
        }
        $this->HostDb->update('order_product_board_plate', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table order_product_board_plate
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('order_product_board_plate', $Data, 'opbp_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     *
     * @param $V
     */
    public function update_clear_qrcode ($V) {
        $Query = $this->HostDb->select('opb_id')
            ->from('order_product_board')
            ->join('order_product', 'op_id = opb_order_product_id', 'left')
            ->where_in('op_order_id', is_array($V) ? $V : array($V))
            ->get_compiled_select();

        $this->HostDb->where('opbp_order_product_board_id IN (', $Query . ')', false);
        $this->HostDb->group_start()
            ->where('opbp_bd_file', '')
            ->or_where('opbp_bd_file is null')
        ->group_end();
        $this->remove_cache($this->_Module);
        $this->HostDb->update('order_product_board_plate', array('opbp_qrcode' => null));
        return true;
    }

    /**
     * 更新扫描状态
     * @param $Where
     * @return mixed
     */
    public function update_scan($Where){
        $Set = array(
            'opbp_scanner' => $this->session->userdata('uid'),
            'opbp_scan_datetime' => date('Y-m-d H:i:s')
        );
        $this->HostDb->where('opbp_scanner', ZERO);
        $this->HostDb->where('opbp_scan_datetime is null');
        $this->HostDb->where_in('opbp_id',$Where);
        $this->remove_cache($this->_Module);
        return $this->HostDb->update('order_product_board_plate', $Set);;
    }
    /**
     * Delete data from table order_product_board_plate
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('opbp_id', $Where);
        } else {
            $this->HostDb->where('opbp_id', $Where);
        }

        $this->HostDb->delete('order_product_board_plate');
        $this->remove_cache($this->_Module);
        return true;
    }

    public function delete_by_order_product_id ($OrderProductId) {
        $Query = $this->HostDb->select('opb_id')->from('order_product_board')->where_in('opb_order_product_id', is_array($OrderProductId) ? $OrderProductId : array($OrderProductId))->get_compiled_select();
        $this->HostDb->where('opbp_order_product_board_id IN (', $Query . ')', false);
        $this->HostDb->delete('order_product_board_plate');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 清除订单产品板块信息
     * @param $Where
     * @return bool
     */
    public function clear ($Where) {
        $Query = $this->HostDb->select('opb_id')->from('order_product_board')->where_in('opb_order_product_id', is_array($Where) ? $Where : array($Where))->get_compiled_select();
        $this->HostDb->where('opbp_order_product_board_id IN (', $Query . ')', false);
        $this->HostDb->group_start()
            ->where('opbp_bd_file', '')
            ->or_where('opbp_bd_file is null')
            ->group_end();
        $this->HostDb->update('order_product_board_plate', array('opbp_qrcode' => null, 'opbp_order_product_classify_id' => ZERO));
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 清除classifyId
     * @param $OrderProductId
     * @return bool
     */
    public function clear_classify ($OrderProductId) {
        $Query = $this->HostDb->select('opb_id')->from('order_product_board')->where_in('opb_order_product_id', is_array($OrderProductId) ? $OrderProductId : array($OrderProductId))->get_compiled_select();
        $this->HostDb->where('opbp_order_product_board_id IN (', $Query . ')', false);
        $this->HostDb->update('order_product_board_plate', array('opbp_order_product_classify_id' => ZERO));
        $this->remove_cache($this->_Module);
        return true;
    }
}
