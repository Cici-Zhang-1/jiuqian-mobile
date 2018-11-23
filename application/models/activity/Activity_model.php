<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Activity_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Activity_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model activity/Activity_model Start!');
    }

    /**
     * Select from table activity
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('activity')
                    ->join('boolean_type AS A', 'A.bt_name = a_status', 'left')
                    ->join('boolean_type AS B', 'B.bt_name = a_notice', 'left')
                    ->join('user', 'u_id = a_creator', 'left');
                if (isset($Search['status']) && $Search['status'] !== '') {
                    $this->HostDb->where('a_status', $Search['status']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                }
                $Query = $this->HostDb->order_by('a_notice', 'desc')->order_by('a_id', 'desc')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的九千活动';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(a_id) as num', FALSE);
        if (isset($Search['status']) && $Search['status'] !== '') {
            $this->HostDb->where('a_status', $Search['status']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
        }
        $this->HostDb->from('activity');

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

    public function select_image ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('activity')
                ->where('a_id', $Search['activity_id']);
            $Query = $this->HostDb->limit(ONE)->get();
            if ($Query->num_rows() > 0) {
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => ONE,
                    'p' => ONE,
                    'pn' => ONE,
                    'pagesize' => ALL_PAGESIZE
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的九千活动';
            }
        }
        return $Return;
    }

    /**
     * 获取主页
     * @return array|bool
     */
    public function select_notice () {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('activity')
                ->where('a_notice', YES);
            $Query = $this->HostDb->limit(ONE)->get();
            if ($Query->num_rows() > 0) {
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => ONE,
                    'p' => ONE,
                    'pn' => ONE,
                    'pagesize' => ALL_PAGESIZE
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的九千活动';
            }
        }
        return $Return;
    }
    private function _max_num () {
        $Query = $this->HostDb->select('a_num')
            ->from('activity')
            ->order_by('a_id', 'desc')
            ->limit(ONE)
            ->get();
        if ($Query->num_rows() > 0) {
            $Row = $Query->row_array();
            return intval(substr($Row['a_num'], 1));
        } else {
            return ZERO;
        }
    }
    /**
     * Insert data to table activity
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        $Num = $this->_max_num();
        $Num++;
        $Data['a_num'] = sprintf('F%06d', $Num);
        if ($Data['a_notice'] == YES) {
            $this->_update_notice();
        }
        if($this->HostDb->insert('activity', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入九千活动数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table activity
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('activity', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入九千活动数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table activity
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('a_id', $Where);
        } else {
            if ($Data['a_notice'] == YES) {
                $this->_update_notice();
            }
            $this->HostDb->where('a_id', $Where);
        }
        $this->HostDb->update('activity', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table activity
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('activity', $Data, 'a_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 更新图片
     * @param $Path
     * @param $Num
     * @return bool
     */
    public function update_image ($Path, $Num) {
        $this->HostDb->set('a_image', $Path)
            ->where('a_num', $Num);
        $this->HostDb->update('activity');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 取消notice
     * @return bool
     */
    private function _update_notice () {
        $this->HostDb->set('a_notice', ZERO);
        $this->HostDb->update('activity');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table activity
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('a_id', $Where);
        } else {
            $this->HostDb->where('a_id', $Where);
        }

        $this->HostDb->delete('activity');
        $this->remove_cache($this->_Module);
        return true;
    }
}
