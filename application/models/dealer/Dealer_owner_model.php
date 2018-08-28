<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dealer_owner_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Dealer_owner_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model dealer/Dealer_owner_model Start!');
    }

    /**
     * Select from table dealer_owner
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('dealer_owner')
                    ->join('boolean_type', 'bt_name = do_primary', 'left')
                    ->join('user', 'u_id = do_owner_id', 'left');
                if (!empty($Search['v'])) {
                    $this->HostDb->where('do_dealer_id', $Search['v']);
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
                $GLOBALS['error'] = '没有符合搜索条件的客户属主';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(do_id) as num', FALSE);
        if (!empty($Search['v'])) {
            $this->HostDb->where('do_dealer_id', $Search['v']);
        }
        $this->HostDb->from('dealer_owner');

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
     * Insert data to table dealer_owner
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if ($Data['do_primary']) {
            $this->_update_primary($Data['do_dealer_id']);
        }
        if($this->HostDb->insert('dealer_owner', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入客户属主数据失败!';
            return false;
        }
    }

    public function insert_batch ($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('dealer_owner', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入客户属主数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table dealer_owner
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if ($Data['do_primary']) {
            $this->_update_primary($Data['do_dealer_id']);
        }
        if (is_array($Where)) {
            $this->HostDb->where_in('do_id', $Where);
        } else {
            $this->HostDb->where('do_id', $Where);
        }
        $this->HostDb->update('dealer_owner', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table dealer_owner
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('dealer_owner', $Data, 'do_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * @param $DealerV
     * @return bool
     */
    public function update_primary ($DealerV) {
        $Compiled = $this->HostDb->select('do_id')
            ->from('dealer_owner')
            ->where('do_dealer_id', $DealerV)
            ->limit(ONE)
            ->get_compiled_select();
        $Compiled = $this->HostDb->select('do_id')->from('(' . $Compiled . ') AS A', false)->get_compiled_select();
        $this->HostDb->set('do_primary', YES);
        $this->HostDb->where_in('do_id', $Compiled, false);
        $this->HostDb->update('dealer_owner');
        return true;
    }
    /**
     * 切换主要联系人
     * @param $DealerV
     * @return bool
     */
    private function _update_primary ($DealerV) {
        $this->HostDb->set('do_primary', NO);
        $this->HostDb->where('do_dealer_id', $DealerV);
        $this->HostDb->update('dealer_owner');
        return true;
    }
    /**
     * Delete data from table dealer_owner
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('do_id', $Where);
        } else {
            $this->HostDb->where('do_id', $Where);
        }

        $this->HostDb->delete('dealer_owner');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 释放或者丢弃客户如公海池
     * @param $Where
     * @param int $Owner
     * @return bool
     */
    public function delete_by_dealer_v ($Where, $Owner = 0) {
        $this->HostDb->where('do_primary', YES);
        if (is_array($Where)) {
            $this->HostDb->where_in('do_dealer_id', $Where);
        } else {
            $this->HostDb->where('do_dealer_id', $Where);
        }
        if (!empty($Owner)) { // 删除对应属主的
            $this->HostDb->where('do_owner_id', $this->session->userdata('uid'));
        }

        $this->HostDb->delete('dealer_owner');
        $this->remove_cache($this->_Module);
        return true;
    }
}
