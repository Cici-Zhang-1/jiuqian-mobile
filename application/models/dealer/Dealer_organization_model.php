<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月27日
 * @author Zhangcc
 * @version
 * @des
 */
class Dealer_organization_model extends MY_Model{
    private $_Module = 'dealer';
    private $_Model;
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        log_message('debug', 'Model Dealer/Dealer_organization_model Start!');
    }

    public function select(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('dealer_organization');
             
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Result = $Query->result_array();
                $Return = array(
                    'content' => $Result,
                    'num' => $Query->num_rows(),
                    'p' => 1,
                    'pn' => 1
                );
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有经销商组织结构信息!';
            }
        }
        return $Return;
    }

    /**
     * 通过名称获取id号
     * @param unknown $Name
     */
    public function select_doid_by_name($Name){
        $Query = $this->HostDb->select('do_id')->from('dealer_organization')->where('do_name', $Name)->limit(1)->get();
        if($Query->num_rows()  > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            return $Row['do_id'];
        }else{
            return false;
        }
    }
    
    public function insert($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('dealer_organization', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }
    
    public function update($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format_re($Set, $Item, $this->_Module);
        $this->HostDb->where('do_id',$Where);
        if($this->HostDb->update('dealer_organization', $Set)){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}
