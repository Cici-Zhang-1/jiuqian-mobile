<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board_class_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Board_class_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Board_class_model Start!');
    }

    /**
     * Select from table board_class
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('board_class');
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
                $GLOBALS['error'] = '没有符合搜索条件的板材环保等级';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(bc_name) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
        }
        $this->HostDb->from('board_class');

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
        $Query = $this->HostDb->select('bc_name')
            ->from('board_class')
            ->where('bc_name', $Data['bc_name'])
            ->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table board_class
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('board_class', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入板材环保等级数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table board_class
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            foreach ($Where as $Key => $Value) {
                if ($Value != $Data['bc_name'] && $this->_is_exist($Data)) {
                    $GLOBALS['error'] = $Data['bc_name'] . '板材环保级别已经存在!';
                    return false;
                }
            }
            $this->HostDb->where_in('bc_name', $Where);
        } else {
            if ($Where != $Data['bc_name'] && $this->_is_exist($Data)) {
                $GLOBALS['error'] = '板材环保级别已经存在!';
                return false;
            }
            $this->HostDb->where('bc_name', $Where);
        }
        $this->HostDb->update('board_class', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table board_class
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('board_class', $Data, 'bc_name');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table board_class
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('bc_name', $Where);
        } else {
            $this->HostDb->where('bc_name', $Where);
        }

        $this->HostDb->delete('board_class');
        $this->remove_cache($this->_Module);
        return true;
    }
}
