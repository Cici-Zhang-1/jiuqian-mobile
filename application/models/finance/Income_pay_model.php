<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月19日
 * @author Zhangcc
 * @version
 * @des
 */
class Income_pay_model extends Base_Model{
    private $_Module = 'finance';
    private $_Model;
    private $_Item;
    private $_Cache;
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        
        log_message('debug', 'Model Data/Income_pay_model Start!');
    }

    public function select($Type) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode(',', $Type);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('income_pay');
            
            $this->HostDb->where_in('ip_type', $Type); /*选择类型*/

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $Query->num_rows(),
                    'p' => 1,
                    'pn' => 1
                );
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有收支类型信息!';
            }
        }
        return $Return;
    }

    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item, $this->_Module);
        if($this->HostDb->insert('income_pay', $Data)){
            log_message('debug', "Model Income_pay_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Income_pay_model/insert Error");
            return false;
        }
    }

    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item, $this->_Module);
        $this->HostDb->where('ip_id', $Where);
        $this->HostDb->update('income_pay', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
}
 