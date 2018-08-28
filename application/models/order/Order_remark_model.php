<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order_remark_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Order_remark_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model order/Order_remark_model Start!');
    }

    /**
     * Select from table order_remark
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('order_remark')
                    ->join('order', 'o_id = or_order_id', 'left')
                    ->join('user', 'u_id = or_creator', 'left')
                    ->join('boolean_type', 'bt_name = or_for', 'left')
                    ->join('workflow_order', 'wo_id = or_status', 'left');
                if (!empty($Search['order_id'])) {
                    $this->HostDb->where('or_order_id', $Search['order_id']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('o_num', $Search['keyword'])
                        ->or_like('o_dealer', $Search['keyword'])
                        ->group_end();
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
                $GLOBALS['error'] = '没有符合搜索条件的订单备注';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(or_id) as num', FALSE)
            ->join('order', 'o_id = or_order_id', 'left');
        if (!empty($Search['order_id'])) {
            $this->HostDb->where('or_order_id', $Search['order_id']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('o_num', $Search['keyword'])
                ->or_like('o_dealer', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('order_remark');

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
     * Insert data to table order_remark
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('order_remark', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入订单备注数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table order_remark
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('order_remark', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入订单备注数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table order_remark
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('or_id', $Where);
        } else {
            $this->HostDb->where('or_id', $Where);
        }
        $this->HostDb->update('order_remark', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table order_remark
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('order_remark', $Data, 'or_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table order_remark
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('or_id', $Where);
        } else {
            $this->HostDb->where('or_id', $Where);
        }

        $this->HostDb->delete('order_remark');
        $this->remove_cache($this->_Module);
        return true;
    }
}
