<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Track_title_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Track_title_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Track_title_model Start!');
    }

    /**
     * Select from table track_title
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('track_title')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的跟踪主题';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(tt_name) as num', FALSE);
        $this->HostDb->from('track_title');

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
        $Query = $this->HostDb->select('tt_name')
            ->from('track_title')
            ->where('tt_name', $Data['tt_name'])
            ->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table track_title
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('track_title', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入跟踪主题数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table track_title
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('track_title', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入跟踪主题数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table track_title
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            foreach ($Where as $Key => $Value) {
                if ($Value != $Data['tt_name'] && $this->_is_exist($Data)) {
                    $GLOBALS['error'] = $Data['tt_name'] . '跟踪主题已经存在!';
                    return false;
                }
            }
            $this->HostDb->where_in('tt_name', $Where);
        } else {
            if ($Where != $Data['tt_name'] && $this->_is_exist($Data)) {
                $GLOBALS['error'] = '跟踪主题已经存在!';
                return false;
            }
            $this->HostDb->where('tt_name', $Where);
        }
        $this->HostDb->update('track_title', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table track_title
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('track_title', $Data, 'tt_name');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table track_title
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('tt_name', $Where);
        } else {
            $this->HostDb->where('tt_name', $Where);
        }

        $this->HostDb->delete('track_title');
        $this->remove_cache($this->_Module);
        return true;
    }
}
