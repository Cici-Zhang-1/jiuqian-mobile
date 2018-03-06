<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月30日
 * @author Zhangcc
 * @version
 * @des
 */
class Wardrobe_edge_model extends Base_Model{
    private $_Module = 'data';
    private $_Model = 'wardrobe_edge_model';
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        
        log_message('debug', 'Model Data/Wardrobe_edge_model Start!');
    }

    public function select_wardrobe_edge() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('wardrobe_edge');
        
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有衣柜封边名称!';
            }
        }
        return $Return;
    }

    /**
     * @param $Name
     * @return bool
     * 根据封边名称获取四周封边信息
     */
    public function select_wardrobe_edge_by_name($Name) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Name;

        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('wardrobe_edge');
            $this->HostDb->where('we_name', $Name);

            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else {
                $GLOBALS['error'] = '没有对应的封边信息';
            }
        }
        return $Return;
    }

    public function insert_wardrobe_edge($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('wardrobe_edge', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }

    public function update_wardrobe_edge($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format_re($Set, $Item, $this->_Module);
        $this->HostDb->where('we_id',$Where);
        if($this->HostDb->update('wardrobe_edge', $Set)){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
    /**
     * 删除异形
     * @param unknown $Where
     */
    public function delete_wardrobe_edge($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('we_id', $Where);
        }else{
            $this->HostDb->where('we_id', $Where);
        }
        if($this->HostDb->delete('wardrobe_edge')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}