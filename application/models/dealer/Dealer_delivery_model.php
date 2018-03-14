<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月19日
 * @author Zhangcc
 * @version
 * @des
 * 发货
 */
class Dealer_delivery_model extends MY_Model{
    /**
     * 主要联系人
     */
    private $_Default = 1;
    /**
     * 非主要联系人
     */
    private $_UDefault = 0;
    private $_Module = 'dealer';
    private $_Model;
    private $_Item;
    private $_Cache;
    
    public function __construct(){
        parent::__construct();
        
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        log_message('debug', 'Model Dealer/Dealer_delivery_model Start!');
        
    }

    public function select($Did){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Did;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('dealer_delivery');
            $this->HostDb->join('area', 'a_id = dd_area_id', 'left');
            $this->HostDb->join('logistics', 'l_id = dd_logistics_id', 'left');
            $this->HostDb->join('out_method', 'om_id = dd_out_method_id', 'left');
            
            $this->HostDb->where('dd_dealer_id', $Did);
    
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '该经销商没有联系人信息!';
            }
        }
        return $Return;
    }
    
    /**
     * 判断是否有主要联系人
     * @param unknown $Did
     */
    private function _select_dealer_delivery_default($Did){
        $Query = $this->HostDb->select('dd_id')
                            ->from('dealer_delivery')
                            ->where(array('dd_dealer_id' => $Did, 'dd_default' => $this->_Default))
                            ->get();
        if($Query->num_rows() > 0){
            $Query->free_result();
            return true;
        }else{
            return false;
        }
    }

    public function select_dealer_delivery_by_dealer($Rid){
        try {
            $this->HostDb->where('dd_dealer_id', $Rid);
            $Query = $this->HostDb->get('dealer_delivery');
            if($Query->num_rows() > 0){
                return $Query->result_array();
            }else{
                return false;
            }
        } catch (Exception $e) {
            $GLOBALS['error'] = $e->message();
            return false;
        }
    }

    /**
     * 插入经销商 联系人
     * @param unknown $Data
     */
    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if(!isset($Data['dd_default']) || ($this->_Default == $Data['dd_default'])){
            log_message('debug', '新建时设为默认发货信息');
            $Data['dd_default'] = $this->_Default;
            $this->_update_dealer_delivery_undefault($Data['dd_dealer_id']);
        }elseif(!$this->_select_dealer_delivery_default($Data['dd_dealer_id'])){
            /**
             * 判断是否有默认的发货地址，如果没有则设为默认
             */
            $Data['dd_default'] = $this->_Default;
        }else{
            $Data['dd_default'] = $this->_UDefault;
        }
        
        if($this->HostDb->insert('dealer_delivery', $Data)){
            log_message('debug', "Model Dealer_delivery_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Dealer_delivery_model/insert Error");
            return false;
        }
    }

    /**
     * 更新经销商 联系人
     * @param unknown $Data
     * @param unknown $Where
     */
    public function update($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        
        if($this->_Default == $Data['dd_default']){
            $this->_update_dealer_delivery_undefault($Data['dd_dealer_id']);
        }elseif(!$this->_select_dealer_delivery_default($Data['dd_dealer_id'])){
            $Data['dd_default'] = $this->_Default;
        }
        $this->HostDb->where('dd_id',$Where);
        $this->HostDb->update('dealer_delivery', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }

    /**
     * 更改经销商发货未非默认的发货
     * @param unknown $Did
     */
    private function _update_dealer_delivery_undefault($Did){
        $this->HostDb->where(array('dd_dealer_id' => $Did));
        return $this->HostDb->update('dealer_delivery', array('dd_default' => $this->_UDefault));
    }

    public function delete($Where){
        $this->HostDb->where_in('dd_id', $Where);
        $this->HostDb->delete('dealer_delivery');
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
    
    /**
     * 通过经销商Id号删除
     * @param unknown $Where
     */
    public function delete_by_did($Where){
        $this->HostDb->where_in('dd_dealer_id', $Where);
        $this->HostDb->delete('dealer_delivery');
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
}
