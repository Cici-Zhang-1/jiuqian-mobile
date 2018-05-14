<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月23日
 * @author Zhangcc
 * @version
 * @des
 */
class Task_level_model extends MY_Model{
    private $_Module = 'data';
    private $_Model;
    private $_Item;
    private $_Cache;
    
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        
        log_message('debug', 'Model Data/Task_level_model Start!');
    }

    public function select() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('task_level');
             
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
                $GLOBALS['error'] = '没有任务等级信息!';
            }
        }
        return $Return;
    }

    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('task_level', $Data)){
            log_message('debug', "Model Task_level_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Task_level_model/insert Error");
            return false;
        }
    }

    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        $this->HostDb->where('tl_id', $Where);
        $this->HostDb->update('task_level', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
    /**
     * 删除异形
     * @param unknown $Where
     */
    public function delete($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('tl_id', $Where);
        }else{
            $this->HostDb->where('tl_id', $Where);
        }
        if($this->HostDb->delete('task_level')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}
