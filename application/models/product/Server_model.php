<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月30日
 * @author Zhangcc
 * @version
 * @des
 */
class Server_model extends Base_Model{
    private $_Module = 'product';
    private $_Model = 'server_model';
    private $_Item;
    private $_Cache;
    public function __construct(){
        log_message('debug', 'Model Product/Server_model Start!');
        parent::__construct();
        $this->e_cache->open_cache();
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
    }

    public function select_server() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('server');
            $this->HostDb->join('product', 'p_id = s_type_id', 'left');
            $this->HostDb->order_by('s_type_id');
        
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array()
                );
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何服务';
            }
        }
        return $Return;
    }

    public function insert_server($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('server', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }

    public function update_server($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        $this->HostDb->where('s_id',$Where);
        if($this->HostDb->update('server', $Set)){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
    /**
     * 批量更新
     * @param unknown $Set
     */
    public function update_batch($Set){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Set as $key => $value){
            $Set[$key] = $this->_format_re($value, $Item, $this->_Module);
        }
        $this->HostDb->update_batch('server',$Set,'s_id');
        $this->remove_cache($this->_Cache);
        return true;
    }
    /**
     * 删除异形
     * @param unknown $Where
     */
    public function delete_server($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('s_id', $Where);
        }else{
            $this->HostDb->where('s_id', $Where);
        }
        if($this->HostDb->delete('server')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}