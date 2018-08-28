<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Application_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model order/Application_model Start!');
    }

    /**
     * Select from table application
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('application')
                    ->join('application_status', 'as_name = a_status', 'left')
                    ->join('order', 'o_id = a_source_id', 'left')
                    ->join('user AS C', 'C.u_id = a_creator', 'left')
                    ->join('user AS R', 'R.u_id = a_replyer', 'left');
                $this->HostDb->where_in('a_status', $Search['status']);
                if (!empty($Search['order_id'])) {
                    $this->HostDb->where('o_id', $Search['order_id']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('o_num', $Search['keyword'])
                        ->or_like('o_dealer', $Search['keyword'])
                        ->group_end();
                }
                $Query = $this->HostDb->order_by('a_id', 'desc')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的申请';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(a_id) as num', FALSE)
            ->join('order', 'o_id = a_source_id', 'left');
        $this->HostDb->where_in('a_status', $Search['status']);
        if (!empty($Search['order_id'])) {
            $this->HostDb->where('o_id', $Search['order_id']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('o_num', $Search['keyword'])
                ->or_like('o_dealer', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('application');

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
     * 判断申请是否通过
     * @param $Type
     * @param $SourceId
     * @param $Des
     */
    public function is_passed ($Type, $SourceId, $Des) {
        $Query = $this->HostDb->select('a_id')
            ->from('application')
            ->where('a_type', $Type)
            ->where('a_source_id', $SourceId)
            ->where('a_des', $Des)
            ->where('a_status', PASSED)
            ->limit(ONE)
            ->get();
        return $Query->num_rows() > 0;
    }
    /**
     * Insert data to table application
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('application', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入申请数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table application
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('application', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入申请数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table application
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
            $this->HostDb->where('a_id', $Where);
        }
        $this->HostDb->update('application', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table application
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('application', $Data, 'a_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table application
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('a_id', $Where);
        } else {
            $this->HostDb->where('a_id', $Where);
        }

        $this->HostDb->delete('application');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 清除申请
     * @param $OrderId
     * @param $Type
     * @return bool
     */
    public function clear ($OrderId, $Type) {
        if(is_array($OrderId)){
            $this->HostDb->where_in('a_source_id', $OrderId);
        } else {
            $this->HostDb->where('a_source_id', $OrderId);
        }
        $this->HostDb->where('a_type', $Type);

        $this->HostDb->delete('application');
        $this->remove_cache($this->_Module);
        return true;
    }
}
