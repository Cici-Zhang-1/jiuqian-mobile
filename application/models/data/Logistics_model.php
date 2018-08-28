<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Logistics_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Logistics_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Logistics_model Start!');
    }

    /**
     * Select from table logistics
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('logistics')
                    ->join('user', 'u_id = l_creator', 'left')
                    ->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的物流信息';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(l_name) as num', FALSE);
        $this->HostDb->from('logistics');

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

    private function _is_exist($Data) {
        $Query = $this->HostDb->select('l_name')
            ->from('logistics')
            ->where('l_name', $Data['l_name'])
            ->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table logistics
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('logistics', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入物流信息数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table logistics
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            foreach ($Where as $Key => $Value) {
                if ($Value != $Data['l_name'] && $this->_is_exist($Data)) {
                    $GLOBALS['error'] = $Data['l_name'] . '物流名称已经存在!';
                    return false;
                }
            }
            $this->HostDb->where_in('l_name', $Where);
        } else {
            if ($Where != $Data['l_name'] && $this->_is_exist($Data)) {
                $GLOBALS['error'] = '物流名称已经存在!';
                return false;
            }
            $this->HostDb->where('l_name', $Where);
        }
        $this->HostDb->update('logistics', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table logistics
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('l_name', $Where);
        } else {
            $this->HostDb->where('l_name', $Where);
        }

        $this->HostDb->delete('logistics');
        $this->remove_cache($this->_Module);
        return true;
    }
}
