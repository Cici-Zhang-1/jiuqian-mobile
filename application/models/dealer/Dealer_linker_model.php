<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dealer_linker_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Dealer_linker_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model dealer/Dealer_linker_model Start!');
    }

    /**
     * Select from table dealer_linker
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('dealer_linker')
                    ->join('boolean_type', 'bt_name = dl_primary', 'left');
                if (!empty($Search['v'])) {
                    $this->HostDb->where('dl_dealer_id', $Search['v']);
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
                $GLOBALS['error'] = '没有符合搜索条件的经销商联系人的信息';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(dl_id) as num', FALSE);
        if (!empty($Search['v'])) {
            $this->HostDb->where('dl_dealer_id', $Search['v']);
        }
        $this->HostDb->from('dealer_linker');

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

    public function select_primary ($DealerV) {
        $Return = false;
        $Query = $this->HostDb->select('dl_id')->from('dealer_linker')
            ->where('dl_dealer_id', $DealerV)
            ->where('dl_primary', YES)
            ->limit(ONE)
            ->get();
        if ($Query->num_rows() > 0) {
            $Row = $Query->row_array();
            $Return = $Row['dl_id'];
        }
        return $Return;
    }

    /**
     * 获取联系人数量
     * @param $Vs
     * @return mixed
     */
    public function select_dealer_linker_nums ($Vs) {
        $Compiled = $this->HostDb->select('dl_dealer_id')
            ->from('dealer_linker')
            ->where_in('dl_id', $Vs)
            ->group_by('dl_dealer_id')
            ->get_compiled_select();
        $Query = $this->HostDb->select('count(dl_dealer_id) as nums, dl_dealer_id as dealer_id', false)
            ->from('dealer_linker')
            ->where_in('dl_dealer_id', $Compiled, false)
            ->group_by('dl_dealer_id')
            ->get();
        $Return = false;
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        }
        return $Return;
    }
    /**
     * Insert data to table dealer_linker
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        $Data['dl_salt'] = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
        $Data['dl_password'] = crypt($Data['dl_password'], $Data['dl_salt']);
        if ($Data['dl_primary']) {
            $this->_update_primary($Data['dl_dealer_id']);
        }
        if($this->HostDb->insert('dealer_linker', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入经销商联系人的信息数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table dealer_linker
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if(!empty($Data['dl_password'])){
            $Data['dl_salt'] = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
            $Data['dl_password'] = crypt($Data['dl_password'], $Data['dl_salt']);
        }

        if ($Data['dl_primary']) {
            $this->_update_primary($Data['dl_dealer_id']);
        }
        if (is_array($Where)) {
            $this->HostDb->where_in('dl_id', $Where);
        } else {
            $this->HostDb->where('dl_id', $Where);
        }
        $this->HostDb->update('dealer_linker', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table dealer_linker
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('dealer_linker', $Data, 'dl_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * @param $DealerV
     * @return bool
     */
    public function update_primary ($DealerV) {
        $Compiled = $this->HostDb->select('dl_id')
            ->from('dealer_linker')
            ->where('dl_dealer_id', $DealerV)
            ->limit(ONE)
            ->get_compiled_select();
        $Compiled = $this->HostDb->select('dl_id')->from('(' . $Compiled . ') AS A', false)->get_compiled_select();
        $this->HostDb->set('dl_primary', YES);
        $this->HostDb->where_in('dl_id', $Compiled, false);
        $this->HostDb->update('dealer_linker');
        return true;
    }
    /**
     * 切换主要联系人
     * @param $DealerV
     * @return bool
     */
    private function _update_primary ($DealerV) {
        $this->HostDb->set('dl_primary', NO);
        $this->HostDb->where('dl_dealer_id', $DealerV);
        $this->HostDb->update('dealer_linker');
        return true;
    }

    /**
     * Delete data from table dealer_linker
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('dl_id', $Where);
        } else {
            $this->HostDb->where('dl_id', $Where);
        }

        $this->HostDb->delete('dealer_linker');
        $this->remove_cache($this->_Module);
        return true;
    }
}
