<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Area_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Area_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Area_model Start!');
    }

    /**
     * Select from table area
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('area');
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('a_province', $Search['keyword'])
                        ->or_like('a_city', $Search['keyword'])
                        ->or_like('a_county', $Search['keyword'])
                        ->group_end();
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
                $GLOBALS['error'] = '没有符合搜索条件的';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(a_id) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('a_province', $Search['keyword'])
                ->or_like('a_city', $Search['keyword'])
                ->or_like('a_county', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('area');

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

    private function _is_exist($Area) {
        $Query = $this->HostDb->select('a_id')
            ->from('area')
            ->where('a_province', $Area['a_province'])
            ->where('a_city', $Area['a_city'])
            ->where('a_county', $Area['a_county'])
            ->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table area
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if (!$this->_is_exist($Data)) {
            if($this->HostDb->insert('area', $Data)){
                $this->remove_cache($this->_Module);
                return $this->HostDb->insert_id();
            } else {
                $GLOBALS['error'] = '插入数据失败!';
                return false;
            }
        } else {
            $GLOBALS['error'] = '该地址系统中已经存在!';
            return false;
        }
    }

    /**
     * Update the data of table area
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (!$this->_is_exist($Data)) {
            if (is_array($Where)) {
                $this->HostDb->where_in('a_id', $Where);
            } else {
                $this->HostDb->where('a_id', $Where);
            }
            $this->HostDb->update('area', $Data);
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '该地址系统中已经存在!';
            return false;
        }
    }

    /**
     * Delete data from table area
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('a_id', $Where);
        } else {
            $this->HostDb->where('a_id', $Where);
        }

        $this->HostDb->delete('area');
        $this->remove_cache($this->_Module);
        return true;
    }
}
