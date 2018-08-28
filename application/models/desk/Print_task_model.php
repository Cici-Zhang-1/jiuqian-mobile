<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Print_task_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Print_task_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model desk/Print_task_model Start!');
    }

    /**
     * Select from table print_task
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('print_task')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的打印任务';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(pt_id) as num', FALSE);
        $this->HostDb->from('print_task');

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
     * 获取下一个打印任务
     * @return bool
     */
    public function select_next () {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ ;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('print_task')
            ->where('pt_status', UNPRINT)
            ->order_by('pt_create_datetime')
            ->limit(ONE)->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        }
        return $Return;
    }

    /**
     * Insert data to table print_task
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('print_task', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入打印任务数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table print_task
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('print_task', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入打印任务数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table print_task
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('pt_id', $Where);
        } else {
            $this->HostDb->where('pt_id', $Where);
        }
        $this->HostDb->update('print_task', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table print_task
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('print_task', $Data, 'pt_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table print_task
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('pt_id', $Where);
        } else {
            $this->HostDb->where('pt_id', $Where);
        }

        $this->HostDb->delete('print_task');
        $this->remove_cache($this->_Module);
        return true;
    }
}
