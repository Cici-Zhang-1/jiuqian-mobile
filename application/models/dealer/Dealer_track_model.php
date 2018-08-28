<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dealer_track_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Dealer_track_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model dealer/Dealer_track_model Start!');
    }

    /**
     * Select from table dealer_track
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('dealer_track')
                        ->join('shop', 's_id = dt_shop_id', 'left')
                        ->join('user', 'u_id = dt_creator', 'left');
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('dt_track', $Search['keyword'])
                        ->group_end();
                }
                if (!empty($Search['dealer_id'])) {
                    $this->HostDb->where('dt_dealer_id', $Search['dealer_id']);
                }
                if (!empty($Search['shop_id'])) {
                    $this->HostDb->where('dt_shop_id', $Search['shop_id']);
                }
                $Query = $this->HostDb->order_by('dt_create_datetime', 'desc')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的客户跟踪';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(dt_id) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('dt_track', $Search['keyword'])
                ->group_end();
        }
        if (!empty($Search['dealer_id'])) {
            $this->HostDb->where('dt_dealer_id', $Search['dealer_id']);
        }
        if (!empty($Search['shop_id'])) {
            $this->HostDb->where('dt_shop_id', $Search['shop_id']);
        }
        $this->HostDb->from('dealer_track');

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
     * Insert data to table dealer_track
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('dealer_track', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入客户跟踪数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table dealer_track
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('dealer_track', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入客户跟踪数据失败!';
            return false;
        }
    }
}
