<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Workflow_msg_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Workflow_msg_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model workflow/Workflow_msg_model Start!');
    }

    /**
     * Select from table workflow_msg
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('workflow_msg')
                    ->order_by('wm_create_datetime', 'desc')
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
                $GLOBALS['error'] = '没有符合搜索条件的工作流信息';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(wm_id) as num', FALSE);
        $this->HostDb->from('workflow_msg');

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
     * 通过订单产品V获取Message
     * @param $Search
     * @return mixed
     */
    public function select_by_order_product_v($Search){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode(',', $Search);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('workflow_msg');
            $this->HostDb->join('user', 'u_id = wm_creator', 'left');
            $this->HostDb->join('order_product', 'op_id = wm_source_id', 'left');

            $this->HostDb->where('wm_model', 'order_product_model');
            $this->HostDb->where('wm_source_id', $Search['v']);
            $this->HostDb->order_by('wm_create_datetime', 'desc');
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $Query->num_rows(),
                    'p' => ONE,
                    'pn' => ONE,
                    'pagesize' => ALL_PAGESIZE
                );
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何订单产品工作流记录!';
            }
        }
        return $Return;
    }
    public function select_by_oid($Oid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Oid;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('workflow_msg');
            $this->HostDb->join('user', 'u_id = wm_creator', 'left');
            $this->HostDb->join('order', 'o_id = wm_source_id', 'left');
            $this->HostDb->where('wm_model', 'order_model');
            $this->HostDb->where('wm_source_id', $Oid);

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何订单工作流记录!';
            }
        }
        return $Return;
    }

    public function select_by_opids($Opids){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode(',', $Opids);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('workflow_msg');
            $this->HostDb->join('user', 'u_id = wm_creator', 'left');
            $this->HostDb->join('order_product', 'op_id = wm_source_id', 'left');

            $this->HostDb->where('wm_model', 'order_product_model');
            $this->HostDb->where_in('wm_source_id', $Opids);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何订单产品工作流记录!';
            }
        }
        return $Return;
    }

    public function select_by_opcids($Opcids){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode(',', $Opcids);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('workflow_msg');
            $this->HostDb->join('user', 'u_id = wm_creator', 'left');
            $this->HostDb->join('order_product_classify', 'opc_id = wm_source_id', 'left');
            $this->HostDb->join('order_product', 'op_id = opc_order_product_id');

            $this->HostDb->where('wm_model', 'order_product_classify_model');
            $this->HostDb->where_in('wm_source_id', $Opcids);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何订单产品工作流记录!';
            }
        }
        return $Return;
    }

    /**
     * Insert data to table workflow_msg
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('workflow_msg', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入工作流信息数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table workflow_msg
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('workflow_msg', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入工作流信息数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table workflow_msg
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('wm_id', $Where);
        } else {
            $this->HostDb->where('wm_id', $Where);
        }
        $this->HostDb->update('workflow_msg', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table workflow_msg
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('workflow_msg', $Data, 'wm_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table workflow_msg
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('wm_id', $Where);
        } else {
            $this->HostDb->where('wm_id', $Where);
        }

        $this->HostDb->delete('workflow_msg');
        $this->remove_cache($this->_Module);
        return true;
    }
}
