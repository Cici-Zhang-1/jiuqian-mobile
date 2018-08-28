<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Configs_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Configs_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Configs_model Start!');
    }

    /**
     * Select from table configs
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('configs')
                            ->join('configs_type', 'ct_name = c_type', 'left');
                if (isset($Search['type']) && $Search['type'] != '') {
                    $this->HostDb->where('c_type', $Search['type']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                            ->like('c_label', $Search['keyword'])
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
                $GLOBALS['error'] = '没有符合搜索条件的系统配置';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(c_id) as num', FALSE);
        if (isset($Search['type']) && $Search['type'] != '') {
            $this->HostDb->where('c_type', $Search['type']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('c_label', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('configs');

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
    public function select_by_name ($Name) {
        $Cache = $this->_Cache . __FUNCTION__ . $Name;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Query = $this->HostDb->select('c_config')->from('configs')
                ->where('c_name', $Name)->limit(1)->get();
            if ($Query->num_rows() > 0) {
                $Row = $Query->row_array();
                $Return = $Row['c_config'];
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    private function _is_exist ($Data, $Where) {
        $Query = $this->HostDb->select('c_id')
            ->from('configs')
            ->where('c_name', $Data['c_name'])
            ->where_not_in('c_id', is_array($Where) ? $Where : array($Where))
            ->limit(ONE)
            ->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table configs
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('configs', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入系统配置数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table configs
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('configs', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入系统配置数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table configs
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if ($this->_is_exist($Data, $Where)) {
            $GLOBALS['error'] = $Data['c_name'] . '已经存在!';
            return false;
        }
        if (is_array($Where)) {
            $this->HostDb->where_in('c_id', $Where);
        } else {
            $this->HostDb->where('c_id', $Where);
        }
        $this->HostDb->update('configs', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table configs
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('configs', $Data, 'c_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table configs
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('c_id', $Where);
        } else {
            $this->HostDb->where('c_id', $Where);
        }

        $this->HostDb->delete('configs');
        $this->remove_cache($this->_Module);
        return true;
    }
}
