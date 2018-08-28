<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Goods_speci_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Goods_speci_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model product/Goods_speci_model Start!');
    }

    /**
     * Select from table goods_speci
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('goods_speci')
                    ->join('boolean_type', 'bt_name = gs_status', 'left')
                    ->join('user', 'u_id = gs_creator', 'left')
                    ->join('goods', 'g_id = gs_goods_id', 'left')
                    ->join('product', 'p_id = g_product_id', 'left')
                    ->join('supplier', 's_id = g_supplier_id', 'left')
                    ->join('j_saler_goods_price', 'sgp_goods_speci_id = gs_id && sgp_creator = ' . $this->session->userdata('uid'), 'left', false);
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('g_name', $Search['keyword'])
                        ->or_like('gs_speci', $Search['keyword'])
                        ->group_end();
                }
                if (!empty($Search['goods_id'])) {
                    $this->HostDb->where('gs_goods_id', $Search['goods_id']);
                }
                if (!empty($Search['supplier_id'])) {
                    $this->HostDb->where('g_supplier_id', $Search['supplier_id']);
                }
                if (!empty($Search['product_id'])) {
                    $this->HostDb->where('g_product_id', $Search['product_id']);
                }
                $this->HostDb->where('gs_status', $Search['status']);
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
                $GLOBALS['error'] = '没有符合搜索条件的商品规格';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(gs_id) as num', FALSE)
            ->join('goods', 'g_id = gs_goods_id', 'left');
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('g_name', $Search['keyword'])
                ->or_like('gs_speci', $Search['keyword'])
                ->group_end();
        }
        if (!empty($Search['goods_id'])) {
            $this->HostDb->where('gs_goods_id', $Search['goods_id']);
        }
        if (!empty($Search['supplier_id'])) {
            $this->HostDb->where('g_supplier_id', $Search['supplier_id']);
        }
        if (!empty($Search['product_id'])) {
            $this->HostDb->where('g_product_id', $Search['product_id']);
        }
        $this->HostDb->where('gs_status', $Search['status']);
        $this->HostDb->from('goods_speci');

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
     * 通过产品代码获取商品信息
     * @param $Code
     * @return array|bool
     */
    public function select_by_product_code ($Code) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Code;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('goods_speci')
                ->join('goods', 'g_id = gs_goods_id', 'left')
                ->join('product', 'p_id = g_product_id', 'left')
                ->join('j_saler_goods_price', 'sgp_goods_speci_id = gs_id && sgp_creator = ' . $this->session->userdata('uid'), 'left', false);

            $this->HostDb->where('p_code', $Code);
            $this->HostDb->where('gs_status', YES);
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
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的商品规格';
            }
        }
        return $Return;
    }

    /**
     * 判断是否是有效商品
     * @param $Fitting
     * @param $Speci
     * @param $Unit
     * @return array|bool
     */
    public function is_valid_goods_speci ($Fitting, $Speci, $Unit) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Fitting . $Speci . $Unit;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('goods_speci')
                ->join('goods', 'g_id = gs_goods_id', 'left')
                ->join('product', 'p_id = g_product_id', 'left')
                ->join('j_saler_goods_price', 'sgp_goods_speci_id = gs_id && sgp_creator = ' . $this->session->userdata('uid'), 'left', false);

            $this->HostDb->where('gs_speci', $Speci);
            $this->HostDb->where('g_name', $Fitting);
            $this->HostDb->where('g_unit', $Unit);
            $this->HostDb->where('gs_status', YES);
            $Query = $this->HostDb->limit(ONE)->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的商品规格';
            }
        }
        return $Return;
    }
    /**
     * Insert data to table goods_speci
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('goods_speci', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入商品规格数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table goods_speci
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('goods_speci', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入商品规格数据失败!';
            return false;
        }
    }

    /**
     * 批量插入更新
     * @param $Data
     * @return bool
     */
    public function insert_batch_update ($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Keys = '';
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
            if(empty($Keys)){
                $Keys = '('.implode(',', array_keys($Data[$key])).')';
                $Data[$key] = '("'.implode('","', $Data[$key]).'")';
            }else{
                $Data[$key] = '("'.implode('","', $Data[$key]).'")';
            }
        }
        $Data = implode(',', $Data);
        $this->HostDb->query("INSERT INTO j_goods_speci $Keys VALUES $Data ON DUPLICATE KEY UPDATE gs_purchase = VALUES(gs_purchase), gs_unit_price= VALUES(gs_unit_price), gs_creator = VALUES(gs_creator), gs_create_datetime = VALUES(gs_create_datetime)");
        $this->remove_cache($this->_Module);
        return true;
    }
    /**
     * Update the data of table goods_speci
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('gs_id', $Where);
        } else {
            $this->HostDb->where('gs_id', $Where);
        }
        $this->HostDb->update('goods_speci', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table goods_speci
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('goods_speci', $Data, 'gs_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table goods_speci
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('gs_id', $Where);
        } else {
            $this->HostDb->where('gs_id', $Where);
        }

        $this->HostDb->delete('goods_speci');
        $this->remove_cache($this->_Module);
        return true;
    }
}
