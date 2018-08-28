<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pick_scan_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Pick_scan_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model warehouse/Pick_scan_model Start!');
    }

    /**
     * Select from table pick_scan
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('pick_scan')
                    ->join('user', 'u_id = ps_creator', 'left')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的拣货扫描';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(ps_id) as num', FALSE);
        $this->HostDb->from('pick_scan');

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
     * Insert data to table pick_scan
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('pick_scan', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入拣货扫描数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table pick_scan
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('pick_scan', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入拣货扫描数据失败!';
            return false;
        }
    }

    public function insert_ignore($Data) {
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
        $Query = $this->HostDb->query("INSERT IGNORE INTO n9_pick_scan $Keys values $Data");
        $this->remove_cache($this->_Module);
        $this->remove_cache('order');
        return true;
    }
    public function replace_batch($Data) {
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
        $Query = $this->HostDb->query("REPLACE INTO n9_pick_scan $Keys values $Data");
        $this->remove_cache($this->_Module);
        $this->remove_cache('order');
        return true;
    }

    /**
     * Update the data of table pick_scan
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('ps_id', $Where);
        } else {
            $this->HostDb->where('ps_id', $Where);
        }
        $this->HostDb->update('pick_scan', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table pick_scan
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('pick_scan', $Data, 'ps_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table pick_scan
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('ps_id', $Where);
        } else {
            $this->HostDb->where('ps_id', $Where);
        }

        $this->HostDb->delete('pick_scan');
        $this->remove_cache($this->_Module);
        return true;
    }
}
