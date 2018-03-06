<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 2017/10/14
 * Time: 09:18
 *
 * Desc:
 */


class Position_order_product_model extends Base_Model{
    private $_Module = 'position';
    private $_Model;
    private $_Item;
    private $_Cache;
    private $_Num;
    private $_Table = 'position_order_product';
    private $_TableOne = 'order_product';
    private $_TableTwo = 'user';
    private $_TableThree = 'position';
    private $_Out = 0;
    private $_In = 1;

    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';

        log_message('debug', 'Model Data/Position_model Start!');
    }

    public function select($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.implode('_', $Con).__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page_num($Con);
            }else{
                $this->_Num = $Con['num'];
            }
            if(!empty($Con['pn'])){
                $Sql = $this->_unformat_as($Item, $this->_Module);
                $this->HostDb->select($Sql, FALSE);
                $this->HostDb->from($this->_Table);
                $this->HostDb->join($this->_TableOne, 'op_id = pop_order_product_id', 'left');
                $this->HostDb->join($this->_TableThree, 'p_id = pop_position_id', 'left');
                $this->HostDb->join($this->_TableTwo . ' as a', 'a.u_id = pop_creator', 'left');
                $this->HostDb->join($this->_TableTwo . ' as b', 'b.u_id = pop_destroy', 'left');

                if(!empty($Con['id'])){
                    $this->HostDb->where('pop_position_id', $Con['id']);
                }

                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                        ->like('op_num', $Con['keyword'])
                        ->or_like('p_name', $Con['keyword'])
                        ->group_end();
                }

                $this->HostDb->order_by('pop_create_datetime', 'desc');

                $this->HostDb->limit($Con['pagesize'], ($Con['p']-1)*$Con['pagesize']);

                $Query = $this->HostDb->get();
                if($Query->num_rows() > 0){
                    $Result = $Query->result_array();
                    $Return = array(
                        'content' => $Result,
                        'num' => $this->_Num,
                        'p' => $Con['p'],
                        'pn' => $Con['pn']
                    );
                    $this->cache->save($Cache, $Return, HOURS);
                }
            }else{
                $GLOBALS['error'] = '没有符合要求需要核价的订单!';
            }
        }
        return $Return;
    }

    private function _page_num($Con){
        $this->HostDb->select('count(pop_id) as num', FALSE);
        $this->HostDb->from($this->_Table);

        $this->HostDb->join($this->_TableThree, 'p_id = pop_position_id', 'left');

        if(!empty($Con['id'])){
            $this->HostDb->where('pop_position_id', $Con['id']);
        }

        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                ->like('op_num', $Con['keyword'])
                ->or_like('p_name', $Con['keyword'])
                ->group_end();
        }

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Con['pagesize']) == 0){
                $Pn = intval($Row['num']/$Con['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Con['pagesize'])+1;
            }
            log_message('debug', 'Num is '.$Row['num'].' and Pagesize is'.$Con['pagesize'].' and Page Nums is'.$Pn);
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * @param $Oids
     * @return bool
     * 通过Oid获取订单的库位
     */
    public function select_position_by_oid($Oids) {
        $Result = false;
        $Query = $this->HostDb->select('pop_order_product_id as opid, group_concat(concat(p_name, "|", pop_count) ORDER BY p_name ASC) as name', false)
            ->from($this->_Table)
            ->join($this->_TableThree, 'p_id = pop_position_id', 'left')
            ->join('order_product', 'op_id = pop_order_product_id', 'left')
            ->where_in('op_order_id', $Oids)
            ->where('op_status > ', 0)
            ->group_by('pop_order_product_id')
            ->get();

        if ($Query->num_rows() > 0) {
            $Result = $Query->result_array();
        }

        return $Result;
    }
    /**
     * @param $Opid
     * @return bool
     * 通过订单产品编号获取所在库位
     */
    public function select_position_by_opid($Opid) {
        $Result = false;
        $Query = $this->HostDb->select('pop_order_product_id, group_concat(p_name) as name', false)
            ->from($this->_Table)
            ->join($this->_TableThree, 'p_id = pop_position_id', 'left')
            ->where('pop_order_product_id', $Opid)
            ->group_by('pop_order_product_id')
            ->get();

        if ($Query->num_rows() > 0) {
            $Result = $Query->row_array();
        }

        return $Result;

    }
    /**
     * @param $Opids
     * @return bool
     * 通过Opid 获取pid
     */
    public function select_pid_by_opid($Opids) {
        $Result = false;
        $this->HostDb->select('pop_position_id as pid')
            ->from($this->_Table);
        if (is_array($Opids)) {
            $this->HostDb->where_in('pop_order_product_id', $Opids);
        }else {
            $this->HostDb->where('pop_order_product_id', $Opids);
        }
        $this->HostDb->group_by('pop_position_id');
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            $Result = $Query->result_array();
        }
        return $Result;
    }

    /**
     * @param $Popid
     * @return bool
     * 通过Popid获取pid
     * 
     */
    public function select_pid_by_popid($Popid) {
        $Result = false;
        $this->HostDb->select('pop_position_id as pid')
            ->from($this->_Table);
        if (is_array($Popid)) {
            $this->HostDb->where_in('pop_id', $Popid);
        }else {
            $this->HostDb->where('pop_id', $Popid);
        }
        $this->HostDb->group_by('pop_position_id');
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            $Result = $Query->result_array();
        }
        return $Result;
    }

    /**
     * @param $Pids
     * @return bool
     * 获取已经有货出厂的货架上哪些是装有其他货物的
     */
    public function select_unfull_pid($Pids) {
        $Result =false;
        $this->HostDb->select('pop_position_id as pid')
            ->from($this->_Table);
        if (is_array($Pids)) {
            $this->HostDb->where_in('pop_position_id', $Pids);
        }else {
            $this->HostDb->where('pop_position_id', $Pids);
        }
        $this->HostDb->where('pop_status', $this->_In);
        $this->HostDb->group_by('pop_position_id');
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            $Result = $Query->result_array();
        }
        return $Result;
    }

    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item, $this->_Module);
        if($this->HostDb->insert($this->_Table, $Data)){
            log_message('debug', "Model Position_order_product_model/insert Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Position_order_product_model/insert Error");
            return false;
        }
    }

    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item, $this->_Module);
        if (is_array($Where)) {
            $this->HostDb->where_in('pop_id', $Where);
        }else {
            $this->HostDb->where('pop_id', $Where);
        }
        $this->HostDb->update($this->_Table, $Data);
        $this->remove_cache($this->_Module);
        return TRUE;
    }

    /**
     * @param $Data
     * @param $Where
     * @return bool
     * 在货物下架以后状态更新
     */
    public function update_after_out($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item, $this->_Module);
        if (is_array($Where)) {
            $this->HostDb->where_in('pop_order_product_id', $Where);
        }else {
            $this->HostDb->where('pop_order_product_id', $Where);
        }
        $this->HostDb->update($this->_Table, $Data);
        $this->remove_cache($this->_Module);
        return TRUE;
    }

    /**
     * 删除
     * @param unknown $Where
     */
    public function delete($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('pop_id', $Where);
        }else{
            $this->HostDb->where('pop_id', $Where);
        }
        $this->HostDb->delete($this->_Table);
        $this->remove_cache($this->_Module);
        return TRUE;
    }
}