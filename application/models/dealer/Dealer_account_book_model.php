<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dealer_account_book_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Dealer_account_book_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model dealer/Dealer_account_book_model Start!');
    }

    /**
     * Select from table dealer_account_book
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('dealer_account_book')
                    ->join('boolean_type', 'bt_name = dab_in' , 'left')
                    ->join('dealer', 'd_id = dab_dealer_id', 'left')
                    ->join('j_dealer_linker', 'dl_dealer_id = d_id && dl_primary = ' . YES, 'left', false)
                    ->join('area', 'a_id = d_area_id', 'left')
                    ->join('user', 'u_id = dab_creator', 'left')
                    ->where('dab_dealer_id', $Search['dealer_id']);
                if (!empty($Search['start_date'])) {
                    $this->HostDb->where('dab_create_datetime >= ', $Search['start_date']);
                }
                if (!empty($Search['end_date'])) {
                    $this->HostDb->where('dab_create_datetime <= ', $Search['end_date']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('dab_flow_num', $Search['keyword'])
                        ->or_like('dab_title', $Search['keyword'])
                        ->or_like('dab_remark', $Search['keyword'])
                        ->or_like('dab_category', $Search['keyword'])
                        ->group_end();
                }
                $Query = $this->HostDb->order_by('dab_id', 'desc')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的客户账本';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(dab_id) as num', FALSE)
            ->where('dab_dealer_id', $Search['dealer_id']);
        if (!empty($Search['start_date'])) {
            $this->HostDb->where('dab_create_datetime >= ', $Search['start_date']);
        }
        if (!empty($Search['end_date'])) {
            $this->HostDb->where('dab_create_datetime <= ', $Search['end_date']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('dab_flow_num', $Search['keyword'])
                    ->or_like('dab_title', $Search['keyword'])
                    ->or_like('dab_remark', $Search['keyword'])
                    ->or_like('dab_category', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('dealer_account_book');

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
     * Insert data to table dealer_account_book
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('dealer_account_book', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入客户账本数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table dealer_account_book
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('dealer_account_book', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入客户账本数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table dealer_account_book
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('dab_id', $Where);
        } else {
            $this->HostDb->where('dab_id', $Where);
        }
        $this->HostDb->update('dealer_account_book', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table dealer_account_book
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('dealer_account_book', $Data, 'dab_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table dealer_account_book
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('dab_id', $Where);
        } else {
            $this->HostDb->where('dab_id', $Where);
        }

        $this->HostDb->delete('dealer_account_book');
        $this->remove_cache($this->_Module);
        return true;
    }
}
