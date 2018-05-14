<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/18
 * Time: 14:42
 *
 * Desc:
 */
class Visit_model extends MY_Model {
    public function __construct() {
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Visit_model Start!' . $this->_Module);
    }
    public function select() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('visit');

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有用访问控制信息!';
            }
        }
        return $Return;
    }

    public function select_allowed_by_ugid($Id) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__ . $Id;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_visit')
                ->join('visit', 'v_id = rv_visit_id', 'left')
                ->where("rv_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Id)")
                ->group_by('v_id')
                ->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                return $Return;
            }else {
                $Return = false;
            }
        }else {
            $Return = false;
        }
        return $Return;
    }

    public function select_not_exist_operation($Operation) {
        $Query = $this->HostDb->select('v_id')
            ->from('visit')
            ->where('v_url', $Operation)
            ->get();
        if ($Query->num_rows() > 0) {
            return false;
        }
        return true;
    }

    public function select_is_allowed_operation($Ugid, $Operation) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__ . $Ugid . $Operation;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_visit')
                ->join('visit', 'v_id = rv_visit_id', 'left')
                ->where("rv_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Ugid)")
                ->where('v_url', $Operation)
                ->group_by('v_id')
                ->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('visit', $Data)){
            log_message('debug', "Model Visit_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Role_model/insert Error");
            return false;
        }
    }

    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        $this->HostDb->where('v_id', $Where);
        $this->HostDb->update('visit', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }

    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('v_id', $Where);
        }else{
            $this->HostDb->where('v_id', $Where);
        }
        $this->HostDb->delete('visit');
        $this->remove_cache($this->_Cache);
        return true;
    }
}
