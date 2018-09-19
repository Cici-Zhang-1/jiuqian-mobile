<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Remark_list_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Remark_list_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model order/Remark_list_model Start!');
    }

    /**
     * Select from table remark_list
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('order')
                    ->join('remark_list', 'rl_order_id = o_id', 'left')
                    ->join('user', 'u_id = rl_creator', 'left')
                    ->where('o_status >= ', O_PRODUCE);
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('o_num', $Search['keyword'])
                        ->or_like('o_dealer', $Search['keyword'])
                        ->or_like('o_owner', $Search['keyword'])
                        ->group_end();
                }
                if (empty($Search['status'])) {
                    $this->HostDb->where('rl_id is null');
                    $this->HostDb->order_by('o_id');
                } else {
                    $this->HostDb->order_by('rl_id', 'desc');
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
                $GLOBALS['error'] = '没有符合搜索条件的备忘单';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(o_id) as num', FALSE)->from('order')
            ->join('remark_list', 'rl_order_id = o_id', 'left')
            ->where('o_status >= ', O_PRODUCE);
        if (empty($Search['status'])) {
            $this->HostDb->where('rl_id is null');
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('o_num', $Search['keyword'])
                ->or_like('o_dealer', $Search['keyword'])
                ->or_like('o_owner', $Search['keyword'])
                ->group_end();
        }

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
     * Insert data to table remark_list
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('remark_list', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入备忘单数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table remark_list
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('remark_list', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入备忘单数据失败!';
            return false;
        }
    }

    public function insert_batch_update ($Data) {
        $Values = '(' .implode('), (', $Data) . ')';

        $this->HostDb->query("INSERT INTO j_remark_list (rl_order_id) VALUES $Values ON DUPLICATE KEY UPDATE rl_order_id = VALUES(rl_order_id)");
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Update the data of table remark_list
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('rl_id', $Where);
        } else {
            $this->HostDb->where('rl_id', $Where);
        }
        $this->HostDb->update('remark_list', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table remark_list
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('remark_list', $Data, 'rl_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table remark_list
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('rl_id', $Where);
        } else {
            $this->HostDb->where('rl_id', $Where);
        }

        $this->HostDb->delete('remark_list');
        $this->remove_cache($this->_Module);
        return true;
    }
}
