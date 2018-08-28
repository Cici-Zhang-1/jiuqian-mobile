<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Door_edge_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Door_edge_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Door_edge_model Start!');
    }

    /**
     * Select from table door_edge
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('door_edge');
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->like('de_name', $Search['keyword']);
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
                $GLOBALS['error'] = '没有符合搜索条件的门板封边';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(de_name) as num', FALSE);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->like('de_name', $Search['keyword']);
        }
        $this->HostDb->from('door_edge');

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
        $Query = $this->HostDb->select('de_name')
            ->from('door_edge')
            ->where('de_name', $Data['de_name'])
            ->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table door_edge
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('door_edge', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入门板封边数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table door_edge
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            foreach ($Where as $Key => $Value) {
                if ($Value != $Data['de_name'] && $this->_is_exist($Data)) {
                    $GLOBALS['error'] = $Data['de_name'] . '已经存在!';
                    return false;
                }
            }
            $this->HostDb->where_in('de_name', $Where);
        } else {
            if ($Where != $Data['de_name'] && $this->_is_exist($Data)) {
                $GLOBALS['error'] = '门板封边已经存在!';
                return false;
            }
            $this->HostDb->where('de_name', $Where);
        }
        $this->HostDb->update('door_edge', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table door_edge
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('de_name', $Where);
        } else {
            $this->HostDb->where('de_name', $Where);
        }

        $this->HostDb->delete('door_edge');
        $this->remove_cache($this->_Module);
        return true;
    }
}
