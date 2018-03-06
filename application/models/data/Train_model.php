<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-5-13
 * @author ZhangCC
 * @version
 * @description  
 */
class Train_model extends Base_Model{
    private $_Module = 'data';
    private $_Model = 'train_model';
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        
        log_message('debug', 'Model Data/train_model Start!');
    }

    public function select_train() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql);
            $this->HostDb->from('train');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '无任何车次';
            }
        }
        return $Return;
    }

    public function insert_train($Set) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('train', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }

    public function update_train($Set, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format_re($Set, $Item, $this->_Module);
        $this->HostDb->where('t_id',$Where);
        if($this->HostDb->update('train', $Set)){
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
    public function delete_train($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('t_id', $Where);
        }else{
            $this->HostDb->where('t_id', $Where);
        }
        if($this->HostDb->delete('train')){
            $this->remove_cache($this->_Cache);
            return true;
        }else{
            return false;
        }
    }
}