<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dealer_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Dealer_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model dealer/Dealer_model Start!');
    }

    /**
     * Select from table dealer
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('dealer')
                    ->join('area', 'a_id = d_area_id', 'left')
                    ->join('j_dealer_owner', 'do_dealer_id = d_id && do_primary = ' . YES, 'left', false)
                    ->join('user', 'u_id = do_owner_id', 'left')
                    ->join('dealer_status', 'ds_name = d_status', 'left')
                    ->join('j_dealer_linker', 'dl_dealer_id = d_id && dl_primary = ' . YES, 'left', false);
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                            ->like('d_name', $Search['keyword'])
                            ->or_like('d_num', $Search['keyword'])
                            ->or_like('dl_truename', $Search['keyword'])
                            ->or_like('dl_mobilephone', $Search['keyword'])
                        ->group_end();
                }
                if ($Search['public'] == NO) {
                    $this->HostDb->where('do_owner_id', $Search['owner']);
                } else {
                    if ($Search['all'] == NO) {
                        $this->HostDb->where('do_owner_id is NULL');
                    }
                }
                /*if (!empty($Search['owner'])) {
                    $this->HostDb->where('do_owner_id', $Search['owner']);
                }*/
                if (isset($Search['status']) && $Search['status'] != '') {
                    $this->HostDb->where('d_status', $Search['status']);
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
                $GLOBALS['error'] = '没有符合搜索条件的经销商基本信息表';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(d_id) as num', FALSE)
            ->join('j_dealer_owner', 'do_dealer_id = d_id && do_primary = ' . YES, 'left', false)
            ->join('j_dealer_linker', 'dl_dealer_id = d_id && dl_primary = ' . YES, 'left', false);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('d_name', $Search['keyword'])
                ->or_like('d_num', $Search['keyword'])
                ->or_like('dl_truename', $Search['keyword'])
                ->or_like('dl_mobilephone', $Search['keyword'])
                ->group_end();
        }
        if ($Search['public'] == NO) {
            $this->HostDb->where('do_owner_id', $Search['owner']);
        } else {
            if ($Search['all'] == NO) {
                $this->HostDb->where('do_owner_id is NULL');
            }
        }
//        if (!empty($Search['owner'])) {
//            $this->HostDb->where('do_owner_id', $Search['owner']);
//        } else {
            // $this->HostDb->where('do_owner_id is NULL');
//        }
        if (isset($Search['status']) && $Search['status'] != '') {
            $this->HostDb->where('d_status', $Search['status']);
        }
        $this->HostDb->from('dealer');

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

    public function select_remote ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('dealer')
                ->join('j_dealer_linker', 'dl_dealer_id = d_id && dl_primary = ' . YES, 'left', false)
                ->join('area', 'a_id = d_area_id', 'left');
            $this->HostDb->group_start()
                ->like('d_name', $Search['keyword'])
                ->or_like('dl_truename', $Search['keyword'])
                ->or_like('dl_mobilephone', $Search['keyword'])
                ->group_end();
            $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        if ($Return == false) {
            $Return = array();
        }
        return $Return;
    }

    /**
     * 获取客户欠款金额
     * @return array
     */
    public function select_dealer_money(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE)
                ->from('dealer')
                ->join('area as d', 'd.a_id = d_area_id', 'left')
                ->join('j_dealer_linker', 'dl_dealer_id = d_id && dl_primary = ' . YES, 'left', false)
                ->join('j_dealer_owner', 'do_dealer_id = d_id && do_primary = ' . YES, 'left', false)
                ->join('user', 'u_id = do_owner_id', 'left')
                ->where('d_status', YES)
                ->where('d_balance < ', ZERO)
                ->order_by('d_balance');

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => ONE,
                    'pn' => ONE,
                    'pagesize' => ALL_PAGESIZE
                );
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的客户欠款信息';
            }
        }
        return $Return;
    }

    public function is_exist ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('dealer')
            ->join('j_dealer_linker', 'dl_dealer_id = d_id && dl_primary = ' . YES, 'left', false)
            ->join('area', 'a_id = d_area_id', 'left')
            ->where('d_id', $V)
            ->limit(ONE)
            ->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        }
        return $Return;
    }
    /**
     * 判断是否是公海池中的客户
     */
    public function is_public ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('dealer')
            ->join('j_dealer_owner', 'do_dealer_id = d_id && do_primary = ' . YES, 'left', false)
            ->where('do_owner_id is NULL')
            ->where_in('d_id', $Vs)
            ->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }
    private function _is_unique_num ($Num, $V) {
        $Query = $this->HostDb->select('d_id')
            ->from('dealer')
            ->where('d_num', $Num)
            ->where('d_id != ', $V)
            ->get();
        return $Query->num_rows();
    }

    public function is_valid ($V) {
        return $this->_is_valid($V);
    }
    private function _is_valid($V){
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->where('d_id', $V)->get('dealer');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            return $Row;
        }else{
            return false;
        }
    }
    /**
     * Insert data to table dealer
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('dealer', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入经销商基本信息表数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table dealer
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('dealer', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入经销商基本信息表数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table dealer
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (isset($Data['d_num']) && $Data['d_num'] != '') {
            if ($this->_is_unique_num($Data['d_num'], $Where)) {
                $GLOBALS['error'] = $Data['d_num'] . '客户编号重复';
                return false;
            }
        }

        if (is_array($Where)) {
            $this->HostDb->where_in('d_id', $Where);
        } else {
            $this->HostDb->where('d_id', $Where);
        }
        $this->HostDb->update('dealer', $Data);
        $this->remove_cache($this->_Module);
        $this->remove_cache('order');
        return true;
    }

    /**
     * 批量更新table dealer
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('dealer', $Data, 'd_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 更新客户收支状况
     * @param $Data
     * @param $Where
     * @return bool
     */
    public function update_dealer_received ($Data, $Where) {
        if(!!($Dealer = $this->_is_valid($Where))){
            $Set = array(
                'd_balance' => $Dealer['d_balance'] + $Data['corresponding'],
                'd_received' => $Dealer['d_received'] + $Data['corresponding'],
                'd_virtual_balance' => $Dealer['d_virtual_balance'] + $Data['corresponding'],
                'd_virtual_received' => $Dealer['d_virtual_received'] + $Data['corresponding']
            );
            $this->HostDb->where('d_id',$Where);

            if($this->HostDb->update('dealer', $Set)) {
                $this->remove_cache($this->_Cache);
                return true;
            } else {
                $GLOBALS['error'] = '更新客户收支时出错';
                return false;
            }
        } else {
            $GLOBALS['error'] = '不是有效的客户';
            return false;
        }
    }
    /**
     * Delete data from table dealer
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('d_id', $Where);
        } else {
            $this->HostDb->where('d_id', $Where);
        }

        $this->HostDb->delete('dealer');
        $this->remove_cache($this->_Module);
        return true;
    }
}
