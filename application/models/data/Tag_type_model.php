<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tag_type_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Tag_type_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Tag_type_model Start!');
    }

    /**
     * Select from table tag_type
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            if(empty($Search['pn'])){
                $Search['pn'] = $this->_page_num($Search);
            }else{
                $this->_Num = $Search['num'];
            }
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('tag_type')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                if ($Query->num_rows() > 0) {
                    $Return = array(
                        'content' => $Query->result_array(),
                        'num' => $this->_Num,
                        'p' => $Search['p'],
                        'pn' => $Search['pn']
                    );
                    $this->cache->save($Cache, $Return, MONTHS);
                } else {
                    $GLOBALS['error'] = '没有符合搜索条件的Tag类型';
                }
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(tt_name) as num', FALSE);
        $this->HostDb->from('tag_type');

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
     * Insert data to table tag_type
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('tag_type', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入Tag类型数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table tag_type
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('tag_type', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入Tag类型数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table tag_type
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('tt_name', $Where);
        } else {
            $this->HostDb->where('tt_name', $Where);
        }
        $this->HostDb->update('tag_type', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table tag_type
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('tag_type', $Data, 'tt_name');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table tag_type
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('tt_name', $Where);
        } else {
            $this->HostDb->where('tt_name', $Where);
        }

        $this->HostDb->delete('tag_type');
        $this->remove_cache($this->_Module);
        return true;
    }
}
