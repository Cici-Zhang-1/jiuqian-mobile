<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Stock_outted_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Stock_outted_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model warehouse/Stock_outted_model Start!');
    }

    /**
     * Select from table stock_outted
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)
                    ->from('stock_outted')
                    ->join('user AS CREATOR', 'CREATOR.u_id = so_creator', 'left')
                    ->join('user AS PRINTER', 'PRINTER.u_id = so_printer', 'left')
                    ->join('stock_outted_status', 'sos_name = so_status', 'left')
                    ->where('so_end_datetime', $Search['start_date'])
                    ->where('so_status', $Search['status'])
                    ->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])
                    ->order_by('so_end_datetime', 'desc')
                    ->order_by('so_id', 'desc')->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的发货单';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(so_id) as num', FALSE)
            ->from('stock_outted')
            ->where('so_end_datetime', $Search['start_date'])
            ->where('so_status', $Search['status']);

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
     * 判断发货单是否存在
     * @param $V
     * @return bool
     */
    public function is_exist ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('stock_outted')
            ->where('so_id', $V)
            ->limit(ONE)
            ->get();
        if ($Query->num_rows() > 0) {
            return $Query->row_array();
        } else {
            return false;
        }
    }

    /**
     * 判断是否已经打印过
     * @param $V
     * @return bool
     */
    public function is_picked ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('stock_outted')->where('so_id', $V)
                ->where('so_status', 2)->limit(1)->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
            }
            $this->cache->save($Cache, $Return, MINUTES);
        }
        return $Return;
    }

    /**
     * 是否是第一次打印
     * @param $V
     * @return bool
     */
    public function is_pickable ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('stock_outted')
                ->join('order_stock_outted', 'oso_stock_outted_id = so_id', 'left')
                ->where('so_id', $V)
                ->where('so_status', 1)->limit(1)->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
            }
            $this->cache->save($Cache, $Return, MINUTES);
        }
        return $Return;
    }
    /**
     * Insert data to table stock_outted
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('stock_outted', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入出库数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table stock_outted
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('stock_outted', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入出库数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table stock_outted
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('so_id', $Where);
        } else {
            $this->HostDb->where('so_id', $Where);
        }
        $this->HostDb->update('stock_outted', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table stock_outted
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('stock_outted', $Data, 'so_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table stock_outted
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('so_id', $Where);
        } else {
            $this->HostDb->where('so_id', $Where);
        }

        $this->HostDb->delete('stock_outted');
        $this->remove_cache($this->_Module);
        return true;
    }
}
