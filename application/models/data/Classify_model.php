<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classify_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Classify_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Classify_model Start!');
    }

    /**
     * Select from table classify
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('classify')
                    ->join('production_line', 'pl_id = c_production_line', 'left');
                if (isset($Search['class']) && $Search['class'] != '') {
                    $this->HostDb->where('c_class', $Search['class']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->like('c_name', $Search['keyword']);
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
                $GLOBALS['error'] = '没有符合搜索条件的板块分类';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(c_id) as num', FALSE);
        if (isset($Search['class']) && $Search['class'] != '') {
            $this->HostDb->where('c_class', $Search['class']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->like('c_name', $Search['keyword']);
        }
        $this->HostDb->from('classify');

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
     * 通过名称获取数据
     * @param $Name
     */
    public function select_by_name ($Name) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Name;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('classify')
                ->join('production_line', 'pl_id = c_production_line', 'left');
            $this->HostDb->like('c_name', $Name);
            $Query = $this->HostDb->limit(ONE)->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的板块分类';
            }
        }
        return $Return;
    }

    /**
     * 获取所有子类
     */
    public function select_children () {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)
                ->from('classify')
                ->where('c_class > 0')
                ->where('c_status',1)
                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
    /**
     * 判断是否存在
     */
    public function is_exist ($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('classify')
            ->where('c_id', $V);

        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            return $Query->row_array();
        } else {
            return false;
        }
    }
    /**
     * Insert data to table classify
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('classify', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入板块分类数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table classify
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('classify', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入板块分类数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table classify
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('c_id', $Where);
        } else {
            $this->HostDb->where('c_id', $Where);
        }
        $this->HostDb->update('classify', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table classify
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('classify', $Data, 'c_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table classify
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('c_id', $Where);
        } else {
            $this->HostDb->where('c_id', $Where);
        }

        $this->HostDb->delete('classify');
        $this->remove_cache($this->_Module);
        return true;
    }
}
