<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order_stock_outted_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Order_stock_outted_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model warehouse/Order_stock_outted_model Start!');
        $this->config->load('defaults/stock_outted_status');
    }

    /**
     * Select from table order_stock_outted
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('order_stock_outted')
                    ->join('order', 'o_id = oso_order_id', 'left')
                    ->join('stock_outted', 'so_id = oso_stock_outted_id', 'left')
                    ->join('user', 'u_id = so_creator', 'left');
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('o_num', $Search['keyword'])
                        ->group_end();
                }
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->order_by('oso_id', 'desc')->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单拣货单';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(oso_id) as num', FALSE)
            ->from('order_stock_outted')
            ->join('order', 'o_id = oso_order_id', 'left');
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('o_num', $Search['keyword'])
                ->group_end();
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
     * 判断发货单是否存在，以及
     * @param $StockOuttedId
     * @return bool
     */
    public function are_re_deliverable_by_stock_outted_id ($StockOuttedId) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('order_stock_outted')
            ->join('order', 'o_id = oso_order_id', 'left')
            ->join('stock_outted', 'so_id = oso_stock_outted_id', 'left')
            ->where_in('oso_stock_outted_id', $StockOuttedId)
            ->where_in('so_status', array($this->config->item('stock_outted_able'), $this->config->item('stock_outted_delivered')))
            ->get();
        if ($Query->num_rows() > 0) {
            return $Query->result_array();
        } else {
            return false;
        }
    }

    /**
     * 判断是否可以从新发货
     * @param $V
     * @return bool
     */
    public function is_re_deliverable ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('order_stock_outted')
            ->join('order', 'o_id = oso_order_id', 'left')
            ->join('stock_outted', 'so_id = oso_stock_outted_id', 'left')
            ->where_in('oso_id', $V)
            ->where_in('so_status', array($this->config->item('stock_outted_able'), $this->config->item('stock_outted_delivered')))
            ->limit(ONE)
            ->get();
        if ($Query->num_rows() > 0) {
            return $Query->row_array();
        } else {
            return false;
        }
    }

    /**
     * Insert data to table order_stock_outted
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('order_stock_outted', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入订单拣货单数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table order_stock_outted
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('order_stock_outted', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入订单拣货单数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table order_stock_outted
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('oso_id', $Where);
        } else {
            $this->HostDb->where('oso_id', $Where);
        }
        $this->HostDb->update('order_stock_outted', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table order_stock_outted
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('order_stock_outted', $Data, 'oso_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table order_stock_outted
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('oso_id', $Where);
        } else {
            $this->HostDb->where('oso_id', $Where);
        }

        $this->HostDb->delete('order_stock_outted');
        $this->remove_cache($this->_Module);
        $this->remove_cache('order');
        return true;
    }
}
