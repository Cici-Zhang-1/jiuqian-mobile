<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/12
 * Time: 12:30
 *
 * Desc:
 */
class Role_page_form_model extends MY_Model {
    private $_Module;
    private $_Model;
    private $_Item;
    private $_Cache;

    public function __construct() {
        parent::__construct();

        log_message('debug', 'Model permission/Role_page_form_model Start!');

        $this->_Module = str_replace("\\", "/", dirname(__FILE__));
        $this->_Module = substr($this->_Module, strrpos($this->_Module, '/')+1);
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = str_replace('/', '_', $this->_Item);
    }

    public function select_by_rid($Rid) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_page_form')
                        ->where('rpf_role_id', $Rid)
                        ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('role_page_form', $Data)){
            log_message('debug', "Model Role_page_form_model/insert_role_page_form Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Role_page_form_model/insert_role_page_form Error");
            return false;
        }
    }

    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item, $this->_Module);
        }
        if($this->HostDb->insert_batch('role_page_form', $Data)){
            log_message('debug', "Model Role_page_form_model/insert_batch Success!");
            $this->remove_cache($this->_Module);
            return true;
        }else{
            log_message('debug', "Model Role_page_form_model/insert_batch Error");
            return false;
        }
    }

    /**
     * 删除功能时同时删除相应的角色权限
     * @param $Mid
     * @return bool
     */
    public function delete_by_psid($Mid){
        if(is_array($Mid)){
            $this->HostDb->where_in('rpf_page_form_id', $Mid);
        }else{
            $this->HostDb->where('rpf_page_form_id', $Mid);
        }
        $this->HostDb->delete('role_page_form');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 删除角色时同时删除相关联的功能权限
     * @param $Rid
     * @return boolean
     */
    public function delete_by_rid($Rid) {
        if(is_array($Rid)){
            $this->HostDb->where_in('rpf_role_id', $Rid);
        }else{
            $this->HostDb->where('rpf_role_id', $Rid);
        }
        $this->HostDb->delete('role_page_form');
        $this->remove_cache($this->_Module);
        return true;
    }
}
