<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-25
 * @author ZhangCC
 * @version
 * @description  
 */
class Supplier_model extends Base_Model{
    private $_Module = 'supplier';
    private $_Model;
    private $_Item;
    private $_Cache;
    public function __construct(){
		parent::__construct();
		
		$this->_Model = strtolower(__CLASS__);
		$this->_Item = $this->_Module.'/'.$this->_Model.'/';
		$this->_Cache = $this->_Module.'_'.$this->_Model.'_';
		log_message('debug', 'Model Supplier/Supplier_model Start!');
	}

	public function select($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    if(!($Return = $this->cache->get($Cache))){
	        if(empty($Con['pn'])){
	            $Con['pn'] = $this->_page($Con);
	        }else{
	            $this->_Num = $Con['num'];
	        }
	        if(!empty($Con['pn'])){
	            $Sql = $this->_unformat_as($Item, $this->_Module);
	            $this->HostDb->select($Sql, FALSE);
	            $this->HostDb->from('supplier');
	
	            if(!empty($Con['keyword'])){
	                $this->HostDb->like('s_name', $Con['keyword']);
	            }
	             
	            $this->HostDb->limit($Con['pagesize'], ($Con['p']-1)*$Con['pagesize']);
	             
	            $Query = $this->HostDb->get();
	            if($Query->num_rows() > 0){
	                $Result = $Query->result_array();
	                $Return = array(
	                    'content' => $Result,
	                    'num' => $this->_Num,
	                    'p' => $Con['p'],
	                    'pn' => $Con['pn']
	                );
	                $this->cache->save($Cache, $Return, HOURS);
	            }
	        }else{
	            $GLOBALS['error'] = '没有符合要求的供应商!';
	        }
	    }
	    return $Return;
	}

	public function _page($Con){
	    $this->HostDb->select('count(s_id) as num', FALSE);
	    $this->HostDb->from('supplier');
	     
	    if(!empty($Con['keyword'])){
	        $this->HostDb->like('s_name', $Con['keyword']);
	    }
	     
	    $Query = $this->HostDb->get();
	    if($Query->num_rows() > 0){
	        $Row = $Query->row_array();
	        $Query->free_result();
	        $this->_Num = $Row['num'];
	        if(intval($Row['num']%$Con['pagesize']) == 0){
	            $Pn = intval($Row['num']/$Con['pagesize']);
	        }else{
	            $Pn = intval($Row['num']/$Con['pagesize'])+1;
	        }
	        log_message('debug', 'Num is '.$Row['num'].' and Pagesize is'.$Con['pagesize'].' and Page Nums is'.$Pn);
	        return $Pn;
	    }else{
	        return false;
	    }
	}
	
	/**
	 * 获得所有供应商信息
	 * @return multitype:NULL
	 */
	public function select_all(){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__;
	    if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('supplier');
    
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
	 * 插入供应商
	 * @param unknown $Data
	 */
	public function insert($Data){
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format($Data, $Item, $this->_Module);
	    if($this->HostDb->insert('supplier', $Data)){
	        log_message('debug', "Model Supplier_model/insert Success!");
	        $this->remove_cache($this->_Cache);
	        return $this->HostDb->insert_id();
	    }else{
	        log_message('debug', "Model Supplier_model/insert Error");
	        return false;
	    }
	}
	
	/**
	 * 更新供应商信息
	 * @param unknown $Data
	 * @param unknown $Where
	 */
	public function update($Data, $Where){
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format_re($Data, $Item, $this->_Module);
	    $this->HostDb->where('s_id', $Where);
	    $this->HostDb->update('supplier', $Data);
	    $this->remove_cache($this->_Cache);
	    return TRUE;
	}
	
	/**
	 * 删除供应商
	 * @param unknown $Where
	 */
	public function delete($Where){
	    if(is_array($Where)){
	        $this->HostDb->where_in('s_id', $Where);
	    }else{
	        $this->HostDb->where('s_id', $Where);
	    }
	    $this->HostDb->delete('supplier');
	    $this->remove_cache($this->_Cache);
		return true;
	}
}