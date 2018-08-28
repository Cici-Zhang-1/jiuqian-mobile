<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board_thick_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Board_thick_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Board_thick_model Start!');
    }

    /**
     * Select from table board_thick
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('board_thick');
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
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
                $GLOBALS['error'] = '没有符合搜索条件的板材厚度';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(bt_name) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
        }
        $this->HostDb->from('board_thick');

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

    private function _is_exist ($Data) {
        $Query = $this->HostDb->select('bt_name')
            ->from('board_thick')
            ->where('bt_name', $Data['bt_name'])
            ->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table board_thick
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('board_thick', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入板材厚度数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table board_thick
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('board_thick', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入板材厚度数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table board_thick
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            foreach ($Where as $Key => $Value) {
                if ($Value != $Data['bt_name'] && $this->_is_exist($Data)) {
                    $GLOBALS['error'] = $Data['bt_name'] . '板材厚度已经存在!';
                    return false;
                }
            }
            $this->HostDb->where_in('bt_name', $Where);
        } else {
            if ($Where != $Data['bt_name'] && $this->_is_exist($Data)) {
                $GLOBALS['error'] = '板材厚度已经存在!';
                return false;
            }
            $this->HostDb->where('bt_name', $Where);
        }
        $this->HostDb->update('board_thick', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table board_thick
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('board_thick', $Data, 'bt_name');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table board_thick
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('bt_name', $Where);
        } else {
            $this->HostDb->where('bt_name', $Where);
        }

        $this->HostDb->delete('board_thick');
        $this->remove_cache($this->_Module);
        return true;
    }
}
