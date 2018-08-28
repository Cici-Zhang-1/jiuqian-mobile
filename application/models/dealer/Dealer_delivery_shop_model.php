<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dealer_delivery_shop_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Dealer_delivery_shop_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model dealer/Dealer_delivery_shop_model Start!');
    }

    /**
     * Select from table dealer_delivery_shop
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('dealer_delivery_shop')
                    ->join('dealer_delivery', 'dd_id = dds_dealer_delivery_id', 'left')
                    ->join('area', 'a_id = dd_area_id', 'left')
                    ->join('boolean_type', 'bt_name = dds_primary', 'left');
                if (!empty($Search['shop_id'])) {
                    $this->HostDb->where('dds_shop_id', $Search['shop_id']);
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
                $GLOBALS['error'] = '没有符合搜索条件的店面发货';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(dds_id) as num', FALSE);
        if (!empty($Search['shop_id'])) {
            $this->HostDb->where('dds_shop_id', $Search['shop_id']);
        }
        $this->HostDb->from('dealer_delivery_shop');

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
    public function select_primary ($ShopV) {
        $Return = false;
        $Query = $this->HostDb->select('dds_id')->from('dealer_delivery_shop')
            ->where('dds_shop_id', $ShopV)
            ->where('dds_primary', YES)
            ->limit(ONE)
            ->get();
        if ($Query->num_rows() > 0) {
            $Row = $Query->row_array();
            $Return = $Row['dds_id'];
        }
        return $Return;
    }

    /**
     * 获取联系人数量
     * @param $Vs
     * @return mixed
     */
    public function select_dealer_delivery_shop_nums ($Vs) {
        $Compiled = $this->HostDb->select('dds_shop_id')
            ->from('dealer_delivery_shop')
            ->where_in('dds_id', $Vs)
            ->group_by('dds_shop_id')
            ->get_compiled_select();
        $Query = $this->HostDb->select('count(dds_shop_id) as nums, dds_shop_id as shop_id', false)
            ->from('dealer_delivery_shop')
            ->where_in('dds_shop_id', $Compiled, false)
            ->group_by('dds_shop_id')
            ->get();
        $Return = false;
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        }
        return $Return;
    }
    /**
     * Insert data to table dealer_delivery_shop
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if ($Data['dds_primary']) {
            $this->_update_primary($Data['dds_shop_id']);
        }
        if($this->HostDb->insert('dealer_delivery_shop', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入店面发货数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table dealer_delivery_shop
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if ($Data['dds_primary']) {
            $this->_update_primary($Data['dds_shop_id']);
        }
        if (is_array($Where)) {
            $this->HostDb->where_in('dds_id', $Where);
        } else {
            $this->HostDb->where('dds_id', $Where);
        }
        $this->HostDb->update('dealer_delivery_shop', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table dealer_delivery_shop
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('dealer_delivery_shop', $Data, 'dds_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * @param $DealerV
     * @return bool
     */
    public function update_primary ($ShopV) {
        $Compiled = $this->HostDb->select('dds_id')
            ->from('dealer_delivery_shop')
            ->where('dds_shop_id', $ShopV)
            ->limit(ONE)
            ->get_compiled_select();
        $Compiled = $this->HostDb->select('dds_id')->from('(' . $Compiled . ') AS A', false)->get_compiled_select();
        $this->HostDb->set('dds_primary', YES);
        $this->HostDb->where_in('dds_id', $Compiled, false);
        $this->HostDb->update('dealer_delivery_shop');
        return true;
    }
    /**
     * 切换主要联系人
     * @param $DealerV
     * @return bool
     */
    private function _update_primary ($ShopV) {
        $this->HostDb->set('dds_primary', NO);
        $this->HostDb->where('dds_shop_id', $ShopV);
        $this->HostDb->update('dealer_delivery_shop');
        return true;
    }
    /**
     * Delete data from table dealer_delivery_shop
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('dds_id', $Where);
        } else {
            $this->HostDb->where('dds_id', $Where);
        }

        $this->HostDb->delete('dealer_delivery_shop');
        $this->remove_cache($this->_Module);
        return true;
    }
}
