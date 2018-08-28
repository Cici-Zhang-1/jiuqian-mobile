<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order_product_board_wood_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Order_product_board_wood_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model order/Order_product_board_wood_model Start!');
    }

    /**
     * Select from table order_product_board_wood
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('order_product_board_wood')
                    ->join('order_product_board', 'opb_id = opbw_order_product_board_id', 'left')
                    ->where('opb_order_product_id', $Search['order_product_id'])
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
                $GLOBALS['error'] = '没有符合搜索条件的木框门';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $Query = $this->HostDb->select('count(opbw_id) as num', FALSE)
            ->from('order_product_board_wood')
            ->join('order_product_board', 'opb_id = opbw_order_product_board_id', 'left')
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
     * 通过订单产品编号获取信息
     * @param $Search
     * @return array|bool
     */
    public function select_by_order_product_id ($Search) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('order_product_board_wood')
                ->join('order_product_board', 'opb_id = opbw_order_product_board_id', 'left')
                ->where('opb_order_product_id', $Search['order_product_id'])->get();
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
                $GLOBALS['error'] = '没有符合搜索条件的订单产品木框门信息';
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
            $Query = $this->HostDb->select($Sql)->from('order_product_board_wood')
                ->join('order_product_board', 'opb_id = opbw_order_product_board_id', 'left')
                ->where_in('opb_id', $OrderProductBoardId)->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品木框门信息';
            }
        }
        return $Return;
    }

    /**
     * Insert data to table order_product_board_wood
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('order_product_board_wood', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入木框门数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table order_product_board_wood
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('order_product_board_wood', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入木框门数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table order_product_board_wood
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('opbw_id', $Where);
        } else {
            $this->HostDb->where('opbw_id', $Where);
        }
        $this->HostDb->update('order_product_board_wood', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table order_product_board_wood
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('order_product_board_wood', $Data, 'opbw_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table order_product_board_wood
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('opbw_id', $Where);
        } else {
            $this->HostDb->where('opbw_id', $Where);
        }

        $this->HostDb->delete('order_product_board_wood');
        $this->remove_cache($this->_Module);
        return true;
    }
    /**
     * 通过订单产品编号删除木框门板块
     * @param $OrderProductId
     * @return bool
     */
    public function delete_by_order_product_id ($OrderProductId) {
        $Query = $this->HostDb->select('opb_id')->from('order_product_board')->where_in('opb_order_product_id', is_array($OrderProductId) ? $OrderProductId : array($OrderProductId))->get_compiled_select();
        $this->HostDb->where('opbw_order_product_board_id IN (', $Query . ')', false);
        $this->HostDb->delete('order_product_board_wood');
        $this->remove_cache($this->_Module);
        return true;
    }
}
