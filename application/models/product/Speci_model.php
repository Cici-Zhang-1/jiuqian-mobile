<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Speci_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Speci_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model product/Speci_model Start!');
    }

    /**
     * Select from table speci
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('speci')
                    ->join('user', 'u_id = s_creator', 'left')
                    ->join('product', 'p_id = s_product_id', 'left');
                if (!empty($Search['product_id'])) {
                    $this->HostDb->where('s_product_id', $Search['product_id']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->like('s_name', $Search['keyword']);
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
                $GLOBALS['error'] = '没有符合搜索条件的规格';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(s_id) as num', FALSE);
        if (!empty($Search['product_id'])) {
            $this->HostDb->where('s_product_id', $Search['product_id']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->like('s_name', $Search['keyword']);
        }
        $this->HostDb->from('speci');

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

    public function is_exists ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('speci')
            ->where_in('s_id', $Vs);

        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            return $Query->result_array();
        } else {
            return false;
        }
    }
    public function is_exist($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('speci')
            ->where('s_id', $V);

        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            return $Query->row_array();
        } else {
            return false;
        }
    }
    /**
     * Insert data to table speci
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('speci', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入规格数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table speci
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('speci', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入规格数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table speci
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('s_id', $Where);
        } else {
            $this->HostDb->where('s_id', $Where);
        }
        $this->HostDb->update('speci', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table speci
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('speci', $Data, 's_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table speci
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('s_id', $Where);
        } else {
            $this->HostDb->where('s_id', $Where);
        }

        $this->HostDb->delete('speci');
        $this->remove_cache($this->_Module);
        return true;
    }
}
