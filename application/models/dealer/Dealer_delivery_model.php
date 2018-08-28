<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dealer_delivery_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Dealer_delivery_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model dealer/Dealer_delivery_model Start!');
    }

    /**
     * Select from table dealer_delivery
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('dealer_delivery')
                    ->join('boolean_type', 'bt_name = dd_primary', 'left')
                    ->join('area', 'a_id = dd_area_id', 'left');
                if (!empty($Search['v'])) {
                    $this->HostDb->where('dd_dealer_id', $Search['v']);
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
                $GLOBALS['error'] = '没有符合搜索条件的经销商发货信息';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(dd_id) as num', FALSE);
        if (!empty($Search['v'])) {
            $this->HostDb->where('dd_dealer_id', $Search['v']);
        }
        $this->HostDb->from('dealer_delivery');

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

    public function select_primary ($DealerV) {
        $Return = false;
        $Query = $this->HostDb->select('dd_id')->from('dealer_delivery')
            ->where('dd_dealer_id', $DealerV)
            ->where('dd_primary', YES)
            ->limit(ONE)
            ->get();
        if ($Query->num_rows() > 0) {
            $Row = $Query->row_array();
            $Return = $Row['dd_id'];
        }
        return $Return;
    }
    /**
     * 获取收货数量
     * @param $Vs
     * @return mixed
     */
    public function select_dealer_delivery_nums ($Vs) {
        $Compiled = $this->HostDb->select('dd_dealer_id')
            ->from('dealer_delivery')
            ->where_in('dd_id', $Vs)
            ->group_by('dd_dealer_id')
            ->get_compiled_select();
        $Query = $this->HostDb->select('count(dd_dealer_id) as nums, dd_dealer_id as dealer_id', false)
            ->from('dealer_delivery')
            ->where_in('dd_dealer_id', $Compiled, false)
            ->group_by('dd_dealer_id')
            ->get();
        $Return = false;
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        }
        return $Return;
    }
    /**
     * Insert data to table dealer_delivery
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if ($Data['dd_primary']) {
            $this->_update_primary($Data['dd_dealer_id']);
        }
        if($this->HostDb->insert('dealer_delivery', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入经销商发货信息数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table dealer_delivery
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if ($Data['dd_primary']) {
            $this->_update_primary($Data['dd_dealer_id']);
        }
        if (is_array($Where)) {
            $this->HostDb->where_in('dd_id', $Where);
        } else {
            $this->HostDb->where('dd_id', $Where);
        }
        $this->HostDb->update('dealer_delivery', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table dealer_delivery
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('dealer_delivery', $Data, 'dd_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * @param $DealerV
     * @return bool
     */
    public function update_primary ($DealerV) {
        $Compiled = $this->HostDb->select('dd_id')
            ->from('dealer_delivery')
            ->where('dd_dealer_id', $DealerV)
            ->limit(ONE)
            ->get_compiled_select();
        $Compiled = $this->HostDb->select('dd_id')->from('(' . $Compiled . ') AS A', false)->get_compiled_select();
        $this->HostDb->set('dd_primary', YES);
        $this->HostDb->where_in('dd_id', $Compiled, false);
        $this->HostDb->update('dealer_delivery');
        return true;
    }
    /**
     * 切换主要联系人
     * @param $DealerV
     * @return bool
     */
    private function _update_primary ($DealerV) {
        $this->HostDb->set('dd_primary', NO);
        $this->HostDb->where('dd_dealer_id', $DealerV);
        $this->HostDb->update('dealer_delivery');
        return true;
    }
    /**
     * Delete data from table dealer_delivery
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('dd_id', $Where);
        } else {
            $this->HostDb->where('dd_id', $Where);
        }

        $this->HostDb->delete('dealer_delivery');
        $this->remove_cache($this->_Module);
        return true;
    }
}
