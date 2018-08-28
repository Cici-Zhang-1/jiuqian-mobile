<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Saler_board_price_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Saler_board_price_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model product/Saler_board_price_model Start!');
    }

    /**
     * Select from table saler_board_price
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('saler_board_price')
                    ->join('user', 'u_id = sbp_creator', 'left');
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->like('sbp_board', $Search['keyword']);
                }
                if (isset($Search['board']) && $Search['board'] != '') {
                    $this->HostDb->where('sbp_board', $Search['board']);
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
                $GLOBALS['error'] = '没有符合搜索条件的销售板材定价';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(sbp_id) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->like('sbp_board', $Search['keyword']);
        }
        if (isset($Search['board']) && $Search['board'] != '') {
            $this->HostDb->where('sbp_board', $Search['board']);
        }
        $this->HostDb->from('saler_board_price');

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
     * Insert data to table saler_board_price
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('saler_board_price', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入销售板材定价数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table saler_board_price
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('saler_board_price', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入销售板材定价数据失败!';
            return false;
        }
    }

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
        $this->HostDb->query("INSERT INTO j_saler_board_price $Keys VALUES $Data ON DUPLICATE KEY UPDATE sbp_unit_price = VALUES(sbp_unit_price), sbp_create_datetime = VALUES(sbp_create_datetime)");
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Update the data of table saler_board_price
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('sbp_id', $Where);
        } else {
            $this->HostDb->where('sbp_id', $Where);
        }
        $this->HostDb->update('saler_board_price', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table saler_board_price
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('saler_board_price', $Data, 'sbp_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table saler_board_price
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('sbp_id', $Where);
        } else {
            $this->HostDb->where('sbp_id', $Where);
        }

        $this->HostDb->delete('saler_board_price');
        $this->remove_cache($this->_Module);
        return true;
    }
}
