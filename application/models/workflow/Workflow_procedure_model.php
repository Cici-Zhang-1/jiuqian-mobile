<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Workflow_procedure_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Workflow_procedure_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model workflow/Workflow_procedure_model Start!');
    }

    /**
     * Select from table workflow_procedure
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('workflow_procedure');
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
                $GLOBALS['error'] = '没有符合搜索条件的工序工作流';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(wp_id) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
        }
        $this->HostDb->from('workflow_procedure');

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
     * 通过名称获取工作流
     * @param $Name
     * @return bool
     */
    public function select_by_name ($Name) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Name;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('workflow_procedure')
                ->where('wp_name', $Name)
                ->limit('ONE');
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的工序工作流';
            }
        }
        return $Return;
    }

    /**
     * 是否存在
     * @param $V
     * @return bool
     */
    public function is_exist ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('workflow_procedure')
                ->where('wp_id', $V)
                ->limit('ONE');
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的工序工作流';
            }
        }
        return $Return;
    }

    /**
     * 是否存在
     * @param $Data
     * @return bool
     */
    private function _is_exist ($Data) {
        $Query = $this->HostDb->select('wp_id')
            ->from('workflow_procedure')
            ->where('wp_id', $Data['wp_id'])
            ->limit(ONE)
            ->get();
        return $Query->row_array() > 0;
    }
    /**
     * Insert data to table workflow_procedure
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('workflow_procedure', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入工序工作流数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table workflow_procedure
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('workflow_procedure', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入工序工作流数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table workflow_procedure
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            foreach ($Where as $Key => $Value) {
                if ($Value != $Data['wp_id'] && $this->_is_exist($Data)) {
                    $GLOBALS['error'] = $Data['wp_id'] . '编号已经存在!';
                    return false;
                }
            }
            $this->HostDb->where_in('wp_id', $Where);
        } else {
            if ($Where != $Data['wp_id'] && $this->_is_exist($Data)) {
                $GLOBALS['error'] = '编号已经存在!';
                return false;
            }
            $this->HostDb->where('wp_id', $Where);
        }
        $this->HostDb->update('workflow_procedure', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table workflow_procedure
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('workflow_procedure', $Data, 'wp_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table workflow_procedure
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('wp_id', $Where);
        } else {
            $this->HostDb->where('wp_id', $Where);
        }

        $this->HostDb->delete('workflow_procedure');
        $this->remove_cache($this->_Module);
        return true;
    }
}
