<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Unqrcode_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Unqrcode_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model warehouse/Unqrcode_model Start!');
    }

    /**
     * Select from table unqrcode
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('unqrcode')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的无码入库';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(u_id) as num', FALSE);
        $this->HostDb->from('unqrcode');

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

    public function select_pick_sheet_detail ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_pick_sheet_detail_num($Search);
            if(!empty($Search['pn'])){
                $Scanned = $this->HostDb->select('ps_order_product_num, ps_stock_outted_id, count(ps_stock_outted_id) as scanned')
                    ->from('pick_scan')
                    ->where('ps_stock_outted_id', $Search['v'])
                    ->group_by('ps_order_product_num')
                    ->get_compiled_select();
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('unqrcode')
                    ->join('order', 'o_id = u_order_id', 'left')
                    ->join('(' . $Scanned . ') as A', 'A.ps_order_product_num = u_num', 'left')
                    ->where('o_stock_outted_id', $Search['v'])
                    ->where('o_status > ', O_PRODUCE)
                    ->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])
                    ->order_by('u_num')->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MINUTES);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的无码入库';
            }
        }
        return $Return;
    }

    private function _page_pick_sheet_detail_num($Search){
        $Query = $this->HostDb->select('count(u_id) as num', FALSE)->from('unqrcode')
            ->join('order', 'o_id = u_order_id', 'left')
            ->where('o_stock_outted_id', $Search['v'])
            ->where('o_status > ', O_PRODUCE)
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
     * 打印拣货单
     * @param $Search
     * @return bool
     */
    public function select_pick_sheet_print ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Scanned = $this->HostDb->select('ps_order_product_num, ps_stock_outted_id, count(ps_stock_outted_id) as scanned')
                ->from('pick_scan')
                ->where('ps_stock_outted_id', $Search['v'])
                ->group_by('ps_order_product_num')
                ->get_compiled_select();
            $Query = $this->HostDb->select($Sql)->from('unqrcode')
                ->join('order', 'o_id = u_order_id', 'left')
                ->join('stock_outted', 'so_id = o_stock_outted_id', 'left')
                ->join('(' . $Scanned . ') as A', 'A.ps_order_product_num = u_num', 'left')
                ->where('o_stock_outted_id', $Search['v'])
                ->where('o_status > ', O_PRODUCE)
                ->order_by('u_num')->get();
            $Return = $Query->result_array();
            $this->cache->save($Cache, $Return, MINUTES);
        }
        return $Return;
    }
    /**
     * 打印标签
     * @param $V
     * @return bool
     */
    public function select_for_label ($Search) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('unqrcode')
            ->join('order', 'o_id = u_order_id', 'left')
            ->where('u_id', $Search['v'])->limit(1)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->row_array();
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 判断是否已经有兄弟unqrcode
     * @param $OrderV
     * @return bool
     */
    public function has_brother ($OrderV) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('unqrcode')
            ->where('u_order_id', $OrderV)->order_by('u_create_datetime', 'desc')->limit(1)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->row_array();
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 判断是否存在
     * @param $Num
     * @return bool
     */
    public function is_exist ($Num) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Num;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('unqrcode')
                ->join('order', 'o_id = u_order_id', 'left')
                ->where('u_num', $Num)
                ->limit(1)->get();
            $Return = $Query->row_array();
            $this->cache->save($Cache, $Return, MINUTES);
        }
        return $Return;
    }
    /**
     * Insert data to table unqrcode
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('unqrcode', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入无码入库数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table unqrcode
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('unqrcode', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入无码入库数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table unqrcode
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('u_id', $Where);
        } else {
            $this->HostDb->where('u_id', $Where);
        }
        $this->HostDb->update('unqrcode', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table unqrcode
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('unqrcode', $Data, 'u_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table unqrcode
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('u_id', $Where);
        } else {
            $this->HostDb->where('u_id', $Where);
        }

        $this->HostDb->delete('unqrcode');
        $this->remove_cache($this->_Module);
        return true;
    }
}
