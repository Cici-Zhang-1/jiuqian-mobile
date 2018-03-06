<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月20日
 * @author Administrator
 * @version
 * @des
 */
class Dealer_owner_model extends Base_Model{
    private $_Module = 'dealer';
    private $_Model;
    private $_Item;
    private $_Cache;
    static $Default;
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';

        log_message('debug', 'Model Dealer/Dealer_owner_model Start!');
    }

    /**
     * 获取经销商的属主
     */
    public function select_owner($Did){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Did;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->HostDb->select($Sql, FALSE)
                        ->from('dealer_owner')
                        ->join('user', 'u_id = do_owner_id', 'left')
                        ->where('do_dealer_id', $Did)
                    ->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array()
                );
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '该客户没有指定属主';
            }
        }
        return $Return;
    }
    /**
     * 获取客人可操作的经销商
     */
    public function select(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $this->load->model('dealer/dealer_organization_model');
            $CheckerId = $this->dealer_organization_model->select_doid_by_name('设计师');
            $PayerId = $this->dealer_organization_model->select_doid_by_name('财务');
             
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('dealer_owner')
                            ->join('dealer', 'd_id = do_dealer_id', 'left')
                            ->join('area as d', 'd.a_id = d_area_id', 'left')
                            ->join('payterms', 'p_id = d_payterms_id', 'left')
                            ->join('n9_dealer_linker as A', 'A.dl_dealer_id = d_id && A.dl_primary = 1', 'left', false)
                            ->join('n9_dealer_linker as B', 'B.dl_dealer_id = d_id && B.dl_type = '.$CheckerId, 'left', false)
                            ->join('n9_dealer_linker as C', 'C.dl_dealer_id = d_id && C.dl_type = '.$PayerId, 'left', false)
                            ->join('n9_dealer_delivery', 'dd_dealer_id = d_id && dd_default = 1', 'left', false)
                            ->join('area as dd', 'dd.a_id = dd_area_id', 'left')
                            ->join('logistics', 'l_id = dd_logistics_id', 'left')
                            ->join('out_method', 'om_id = dd_out_method_id', 'left');
             
            $this->HostDb->where('do_owner_id', $this->session->userdata('uid'));
             
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array()
                );
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
    /**
     * 插入经销商
     * @param unknown $Data
     */
    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item, $this->_Module);
        if($this->HostDb->insert('dealer_owner', $Data)){
            log_message('debug', "Model Dealer_owner_model/dealer_owner Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Dealer_owner_model/dealer_owner Error");
            return false;
        }
    }
    
    public function insert_batch($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item, $this->_Module);
        }
        if($this->HostDb->insert_batch('dealer_owner', $Data)){
            log_message('debug', "Model Dealer_owner_model/insert_batch Success!");
            $this->remove_cache($this->_Module);
            return true;
        }else{
            log_message('debug', "Model Dealer_owner_model/insert_batch Error");
            return false;
        }
    }
    
    /**
     * 批量导入
     * @param unknown $Data
     */
    public function replace_batch($Data){
        $Item = $this->_Item.__FUNCTION__;
        
        $Keys = '';
        foreach ($Data as $key => $value){
            if(empty($Keys)){
                $Data[$key] = $this->_format($value, $Item, $this->_Module);
                $Keys = '('.implode(',', array_keys($Data[$key])).')';
                $Data[$key] = '('.implode(',', $Data[$key]).')';
            }else{
                $Data[$key] = '('.implode(',', $Data[$key]).')';
            }
        }
        $Data = implode(',', $Data);
        $Query = $this->HostDb->query("REPLACE INTO n9_dealer_owner$Keys values $Data");
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 更新经销商信息
     * @param unknown $Data
     * @param unknown $Where
     */
    public function update($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item, $this->_Module);
        $this->HostDb->where('d_id', $Where);
        $this->HostDb->update('dealer', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
    
    public function general($Where){
        $this->HostDb->set('do_primary', 0);
        if(is_array($Where)){
            $this->HostDb->where_in('do_id', $Where);
        }else{
            $this->HostDb->where('do_id', $Where);
        }
        $this->HostDb->update('dealer_owner');
        $this->remove_cache($this->_Module);
        return true;
    }
    
    public function primary($Where){
        $this->HostDb->set('do_primary', 1);
        if(is_array($Where)){
            $this->HostDb->where_in('do_id', $Where);
        }else{
            $this->HostDb->where('do_id', $Where);
        }
        $this->HostDb->update('dealer_owner');
        $this->remove_cache($this->_Module);
        return true;
    }

    public function delete_by_did($Did){
        if(is_array($Did)){
            $this->HostDb->where_in('do_dealer_id', $Did);
        }else{
            $this->HostDb->where('do_dealer_id', $Did);
        }
        $this->HostDb->delete('dealer_owner');
        $this->remove_cache($this->_Module);
        return true;
    }
    /**
     * 删除经销商
     * @param unknown $Where
     */
    public function delete_dealer($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('d_id', $Where);
        }else{
            $this->HostDb->where('d_id', $Where);
        }
        $this->HostDb->delete('dealer');
        $this->remove_cache($this->_Module);
        return true;
    }
    
    public function delete($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('do_id', $Where);
        }else{
            $this->HostDb->where('do_id', $Where);
        }
        $this->HostDb->delete('dealer_owner');
        $this->remove_cache($this->_Module);
        return true;
    }
}