<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Production_line_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Production_line_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Production_line_model Start!');
    }

    /**
     * Select from table production_line
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('production_line');
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
                $GLOBALS['error'] = '没有符合搜索条件的生产线';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(pl_id) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
        }
        $this->HostDb->from('production_line');

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
        $Query = $this->HostDb->select('pl_id')
            ->from('production_line')
            ->where('pl_id', $Data['pl_id'])
            ->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table production_line
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('production_line', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入生产线数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table production_line
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            foreach ($Where as $Key => $Value) {
                if ($Value != $Data['pl_id'] && $this->_is_exist($Data)) {
                    $GLOBALS['error'] = $Data['pl_id'] . '生产线已经存在!';
                    return false;
                }
            }
            $this->HostDb->where_in('pl_id', $Where);
        } else {
            if ($Where != $Data['pl_id'] && $this->_is_exist($Data)) {
                $GLOBALS['error'] = '生产线已经存在!';
                return false;
            }
            $this->HostDb->where('pl_id', $Where);
        }
        $this->HostDb->update('production_line', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table production_line
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('pl_id', $Where);
        } else {
            $this->HostDb->where('pl_id', $Where);
        }

        $this->HostDb->delete('production_line');
        $this->remove_cache($this->_Module);
        return true;
    }
}
