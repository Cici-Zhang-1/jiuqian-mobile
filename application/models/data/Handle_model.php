<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handle_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Handle_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Handle_model Start!');
    }

    /**
     * Select from table handle
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('handle')
                    ->join('boolean_type as A', 'A.bt_name = h_open_hole', 'left')
                    ->join('boolean_type as B', 'B.bt_name = h_invisibility', 'left');
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->like('h_name', $Search['keyword']);
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
                $GLOBALS['error'] = '没有符合搜索条件的门板封边拉手';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(h_name) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->like('h_name', $Search['keyword']);
        }
        $this->HostDb->from('handle');

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
     * Insert data to table handle
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('handle', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入门板封边拉手数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table handle
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('handle', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入门板封边拉手数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table handle
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('h_name', $Where);
        } else {
            $this->HostDb->where('h_name', $Where);
        }
        $this->HostDb->update('handle', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table handle
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('handle', $Data, 'h_name');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table handle
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('h_name', $Where);
        } else {
            $this->HostDb->where('h_name', $Where);
        }

        $this->HostDb->delete('handle');
        $this->remove_cache($this->_Module);
        return true;
    }
}
