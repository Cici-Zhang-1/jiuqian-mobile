<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Board_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model product/Board_model Start!');
    }

    /**
     * 不分页获取全部数据
     * @return array|bool
     */
    private function _select () {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('board')
                ->join('j_saler_board_price', 'sbp_board = b_name && sbp_creator = ' . $this->session->userdata('uid'), 'left', false);
            $this->HostDb->where('b_status', YES);
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的板材';
            }
        }
        return $Return;
    }
    /**
     * @param array $Search
     * @return array|bool
     */
    public function select($Search = array()) {
        if (!empty($Search)) {
            $Item = $this->_Item . __FUNCTION__;
            $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
            $Return = false;
            if (!($Return = $this->cache->get($Cache))) {
                $Search['pn'] = $this->_page_num($Search);
                if(!empty($Search['pn'])){
                    $Sql = $this->_unformat_as($Item);
                    $this->HostDb->select($Sql)->from('board')
                        ->join('user', 'u_id = b_creator', 'left')
                        ->join('supplier', 's_id = b_supplier_id', 'left')
                        ->join('boolean_type', 'bt_name = b_status', 'left')
                        ->join('j_saler_board_price', 'sbp_board = b_name && sbp_creator = ' . $this->session->userdata('uid'), 'left', false);
                    if (isset($Search['keyword']) && $Search['keyword'] != '') {
                        $this->HostDb->group_start()
                            ->like('b_name', $Search['keyword'])
                            ->group_end();
                    }
                    $this->HostDb->where('b_status', $Search['status']);
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
                    $GLOBALS['error'] = '没有符合搜索条件的板材';
                }
            }
            return $Return;
        } else {
            return $this->_select();
        }
    }

    private function _page_num($Search){
        $this->HostDb->select('count(b_name) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('b_name', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->where('b_status', $Search['status']);
        $this->HostDb->from('board');

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
     * 判断是否存在
     * @param $Board
     * @return bool
     */
    public function is_exist ($Board) {
        $Query = $this->HostDb->select('b_name')
            ->from('board')
            ->where('b_name', $Board)
            ->limit(ONE)
            ->get();
        return $Query->num_rows() > 0;
    }
    private function _is_exist ($Data, $Where = array()) {
        $this->HostDb->select('b_name')
            ->from('board')
            ->where('b_name', $Data['b_name']);
        if (!empty($Where)) {
            $this->HostDb->where_not_in('b_name', $Where);
        }
        $Query = $this->HostDb->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table board
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if ($this->_is_exist($Data)) {
            $GLOBALS['error'] = $Data['b_name'] . '已经存在!';
        } else {
            if($this->HostDb->insert('board', $Data)){
                $this->remove_cache($this->_Module);
                return $this->HostDb->insert_id();
            } else {
                $GLOBALS['error'] = '插入板材数据失败!';
            }
        }
        return false;
    }

    /**
     * Insert batch data to table board
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
            if ($this->_is_exist($Data[$key])) {
                $GLOBALS['error'] = $Data[$key]['b_name'] . '已经存在!';
                return false;
            }
        }
        if($this->HostDb->insert_batch('board', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入板材数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table board
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (isset($Data['b_name']) && $this->_is_exist($Data, $Where)) {
            $GLOBALS['error'] = $Data['b_name'] . '已经存在!';
            return false;
        }
        if (is_array($Where)) {
            $this->HostDb->where_in('b_name', $Where);
        } else {
            $this->HostDb->where('b_name', $Where);
        }
        $this->HostDb->update('board', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table board
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('board', $Data, 'b_name');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table board
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('b_name', $Where);
        } else {
            $this->HostDb->where('b_name', $Where);
        }

        $this->HostDb->delete('board');
        $this->remove_cache($this->_Module);
        return true;
    }
}
