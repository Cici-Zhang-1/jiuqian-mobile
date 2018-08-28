<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Position_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Position_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Position_model Start!');
    }

    /**
     * Select from table position
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('position');
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
                $GLOBALS['error'] = '没有符合搜索条件的经销商联系人职位';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(p_name) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
        }
        $this->HostDb->from('position');

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
        $Query = $this->HostDb->select('p_name')
            ->from('position')
            ->where('p_name', $Data['p_name'])
            ->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table position
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('position', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入经销商联系人职位数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table position
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            foreach ($Where as $Key => $Value) {
                if ($Value != $Data['p_name'] && $this->_is_exist($Data)) {
                    $GLOBALS['error'] = $Data['p_name'] . '职位名称已经存在!';
                    return false;
                }
            }
            $this->HostDb->where_in('p_name', $Where);
        } else {
            if ($Where != $Data['p_name'] && $this->_is_exist($Data)) {
                $GLOBALS['error'] = '职位名称已经存在!';
                return false;
            }
            $this->HostDb->where('p_name', $Where);
        }
        $this->HostDb->where('p_undelete', NO);
        $this->HostDb->update('position', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table position
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('position', $Data, 'p_name');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table position
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('p_name', $Where);
        } else {
            $this->HostDb->where('p_name', $Where);
        }
        $this->HostDb->where('p_undelete', NO);

        $this->HostDb->delete('position');
        $this->remove_cache($this->_Module);
        return true;
    }
}
