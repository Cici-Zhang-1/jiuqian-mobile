<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Test_model extends MY_Model {

    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model test/Test_model Start!');
    }

    /**
     * Select from table test
     */
    public function select() {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('test')->get();
            if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的test';
            }
        }
        return $Return;
    }

    /**
     * Insert data to table test
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('card', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入test数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table test
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('test', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入test数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table test
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('t_id', $Where);
        } else {
            $this->HostDb->where('t_id', $Where);
        }
        $this->HostDb->update('test', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table test
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('test', $Data, 't_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table test
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('t_id', $Where);
        } else {
            $this->HostDb->where('t_id', $Where);
        }

        $this->HostDb->delete('test');
        $this->remove_cache($this->_Module);
        return true;
    }
}
