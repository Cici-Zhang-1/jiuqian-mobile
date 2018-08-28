<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Shop_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Shop_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model dealer/Shop_model Start!');
    }

    /**
     * Select from table shop
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('shop')
                    ->join('area', 'a_id = s_area_id', 'left');
                if (!empty($Search['v'])) {
                    $this->HostDb->where('s_dealer_id', $Search['v']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('s_name', $Search['keyword'])
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
                $GLOBALS['error'] = '没有符合搜索条件的店面';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(s_id) as num', FALSE);
        if (!empty($Search['v'])) {
            $this->HostDb->where('s_dealer_id', $Search['v']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('s_name', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('shop');

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
     * 获取个人店面信息
     * @return array|bool
     */
    public function select_my_shop ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('shop')
                ->join('dealer', 'd_id = s_dealer_id', 'left')
                ->join('j_dealer_linker_shop', 'dls_shop_id = s_id && dls_primary = ' . YES, 'left', false)
                ->join('dealer_linker', 'dl_id = dls_dealer_linker_id', 'left')
                ->join('area', 'a_id = s_area_id', 'left')
                ->join('dealer_owner', 'do_dealer_id = d_id', 'left');
            if (!empty($Search['v'])) {
                $this->HostDb->where('s_dealer_id', $Search['v']);
            }
            $this->HostDb->where('do_owner_id', $Search['owner']);
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $Query->num_rows(),
                    'p' => $Search['p'],
                    'pn' => ONE,
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    /**
     * 获取店面首要信息
     */
    public function select_primary_info ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('shop')
                ->join('dealer', 'd_id = s_dealer_id', 'left')
                ->join('j_dealer_linker_shop', 'dls_shop_id = s_id && dls_primary = ' . YES, 'left', false)
                ->join('dealer_linker', 'dl_id = dls_dealer_linker_id', 'left')
                ->join('j_dealer_delivery_shop', 'dds_shop_id = s_id && dds_primary = ' . YES, 'left', false)
                ->join('dealer_delivery', 'dd_id = dds_dealer_delivery_id', 'left')
                ->join('area', 'a_id = dd_area_id', 'left');
            $this->HostDb->where('s_id', $V);
            $Query = $this->HostDb->limit(ONE)->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
    private function _select_max_num ($DealerV) {
        $Query = $this->HostDb->select_max('s_num', 'num')
            ->from('shop')
            ->where('s_dealer_id', $DealerV)
            ->get();
        if ($Query->num_rows() > 0) {
            $Row = $Query->row_array();
            $Query->free_result();
            return $Row['num'];
        }
        return ZERO;
    }

    /**
     * Insert data to table shop
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        $Data['s_num'] = $this->_select_max_num($Data['s_dealer_id']) + ONE;
        if($this->HostDb->insert('shop', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入店面数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table shop
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $MaxNum = $this->_select_max_num($Data['s_dealer_id']);
        $Count = 0;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
            $Data[$key]['s_num'] = $MaxNum + ++$Count;
        }
        if($this->HostDb->insert_batch('shop', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入店面数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table shop
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('s_id', $Where);
        } else {
            $this->HostDb->where('s_id', $Where);
        }
        $this->HostDb->update('shop', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table shop
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('shop', $Data, 's_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table shop
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('s_id', $Where);
        } else {
            $this->HostDb->where('s_id', $Where);
        }

        $this->HostDb->delete('shop');
        $this->remove_cache($this->_Module);
        return true;
    }
}
