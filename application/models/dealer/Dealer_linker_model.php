<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*Program: JIUQIAN
*============================
* 
*============================
*Author: Zhangcc
*Date:2015-4-26
**/
class Dealer_linker_model extends MY_Model{
	/**
	 * 主要联系人
	 */
	private $_Primary = 1;
	/**
	 * 非主要联系人
	 */
	private $_UPrimary = 0; 
	private $_Module = 'dealer';
	private $_Model;
	private $_Item;
	private $_Cache;
	
	public function __construct(){
		parent::__construct();

		$this->_Model = strtolower(__CLASS__);
		$this->_Item = $this->_Module.'/'.$this->_Model.'/';
		$this->_Cache = $this->_Module.'_'.$this->_Model.'_';
		log_message('debug', 'Model Dealer/Dealer_linker_model Start!');
	}
	
	public function select($Did){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.$Did;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item);
	        $this->HostDb->select($Sql, FALSE);
	        $this->HostDb->from('dealer_linker');
	        $this->HostDb->join('dealer_organization', 'do_id = dl_type', 'left');
	        $this->HostDb->where('dl_dealer_id', $Did);
	         
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
	
	public function is_registed($Mobilephone){
	    $Query = $this->HostDb->select('dl_id')->from('dealer_linker')->where('dl_mobilephone', $Mobilephone)->get();
	    if($Query->num_rows() > 0){
	        return true;
	    }else{
	        return false;
	    }
	}
	
	/**
	 * 判断是否有主要联系人
	 * @param unknown $Did
	 */
	private function _select_dealer_linker_primary($Did){
	    $Query = $this->HostDb->select('dl_id')->from('dealer_linker')->where(array('dl_dealer_id' => $Did, 'dl_primary' => $this->_Primary))->get();
	    if($Query->num_rows() > 0){
	        $Query->free_result();
	        return true;
	    }else{
	        return false;
	    }
	}
	
	/**
	 * 插入经销商 联系人
	 * @param unknown $Data
	 */
	public function insert($Data){
	    $Item = $this->_Item.__FUNCTION__;
	    if(isset($Data['primary'])){
	        $Primary = $Data['primary'];
	    }else{
	        $Primary = false;
	    }
	    $Data = $this->_format($Data, $Item);
	    
	    if(false === $Primary){
	        $Data['dl_primary'] = $this->_Primary;
	    }else{
	        if($this->_Primary == $Primary){
	            $Data['dl_primary'] = $this->_Primary;
	            $this->_update_dealer_linker_unprimary($Data['dl_dealer_id']);
	        }elseif(!$this->_select_dealer_linker_primary($Data['dl_dealer_id'])){
	            $Data['dl_primary'] = $this->_Primary;
	        }else{
	            $Data['dl_primary'] = $this->_UPrimary;
	        }
	    }
	    
		if($this->HostDb->insert('dealer_linker', $Data)){
			log_message('debug', "Model Dealer_linker_model/insert_dealer_linker Success!");
			$this->remove_cache($this->_Cache);
			return $this->HostDb->insert_id();
		}else{
			log_message('debug', "Model Dealer_linker_model/insert_dealer_linker Error");
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
	    
	    if($this->_Primary == $Data['dl_primary']){
	        $this->_update_dealer_linker_unprimary($Data['dl_dealer_id']);
	    }elseif(!$this->_select_dealer_linker_primary($Data['dl_dealer_id'])){
	        $Data['dl_primary'] = $this->_Primary;
	    }
	    $this->HostDb->where('dl_id',$Where);
	    $this->HostDb->update('dealer_linker', $Data);
	    $this->remove_cache($this->_Cache);
	    return TRUE;
	}
	
	/**
	 * 更新经销商联系人未非默认联系人
	 * @param unknown $Did
	 */
	private function _update_dealer_linker_unprimary($Did){
	    $this->HostDb->where(array('dl_dealer_id' => $Did));
	    return $this->HostDb->update('dealer_linker', array('dl_primary' => $this->_UPrimary));
	}

	public function delete($Where){
		$this->HostDb->where_in('dl_id', $Where);
		$this->HostDb->delete('dealer_linker');
		$this->remove_cache($this->_Cache);
		return TRUE;
	}
	
	/**
	 * 通过经销商编号
	 * @param unknown $Where
	 */
	public function delete_by_did($Where){
	    $this->HostDb->where_in('dl_dealer_id', $Where);
	    $this->HostDb->delete('dealer_linker');
	    $this->remove_cache($this->_Cache);
	    return TRUE;
	}
}
