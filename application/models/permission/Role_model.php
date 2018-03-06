<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/12
 * Time: 11:02
 *
 * Desc:
 */
class Role_model extends MY_Model{

    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Role_model Start!');
    }

    public function select() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('role');

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有用户角色信息!';
            }
        }
        return $Return;
    }

    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('role', $Data)){
            log_message('debug', "Model Role_model/insert_role Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Role_model/insert_role Error");
            return false;
        }
    }

    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        $this->HostDb->where('r_id', $Where);
        $this->HostDb->update('role', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
    /**
     * @param unknown $Where
     */
    public function delete($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('r_id', $Where);
        }else{
            $this->HostDb->where('r_id', $Where);
        }
        if($this->HostDb->delete('role')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}
