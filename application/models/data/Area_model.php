<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-25
 * @author ZhangCC
 * @version
 * @description
 * 地区  
 */
class Area_model extends MY_Model{
    private $_Module = 'data';
    private $_Model;
    private $_Item;
    private $_Cache;
	public function __construct(){
		parent::__construct();
		$this->_Model = strtolower(__CLASS__);
		$this->_Item = $this->_Module.'/'.$this->_Model.'/';
		$this->_Cache = $this->_Module.'_'.$this->_Model.'_';
		
		log_message('debug', 'Model Data/Area_model Start!');
	}
	
	public function select($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    $Sql = $this->_unformat_as($Item);
	    $this->HostDb->select($Sql, FALSE);
	    $this->HostDb->from('area');
	     
	    if(!empty($Con['keyword'])){
	        $this->HostDb->group_start()
        	        ->like('a_province', $Con['keyword'])
        	        ->or_like('a_city', $Con['keyword'])
        	        ->or_like('a_county', $Con['keyword'])
    	        ->group_end();
	    }
	    
	    $Query = $this->HostDb->get();
	    if($Query->num_rows() > 0){
	        $Result = $Query->result_array();
	        $Return = array(
	            'content' => $Result
	        );
	        $this->cache->save($Cache, $Return, HOURS);
	        return $Return;
	    }else{
	        return false;
	    }
	}
	/* public function select($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    if(!($Return = $this->cache->get($Cache))){
	        if(empty($Con['pn'])){
	            $Con['pn'] = $this->_page($Con);
	        }else{
	            $this->_Num = $Con['num'];
	        }
	        if(!empty($Con['pn'])){
	            $Sql = $this->_unformat_as($Item);
	            $this->HostDb->select($Sql, FALSE);
	            $this->HostDb->from('area');
	    
	            if(!empty($Con['keyword'])){
	                $this->HostDb->group_start()
            	                ->like('a_province', $Con['keyword'])
            	                ->or_like('a_city', $Con['keyword'])
            	                ->or_like('a_county', $Con['keyword'])
        	                ->group_end();
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
	            $GLOBALS['error'] = '没有符合要求的客户!';
	        }
	    }
	    return $Return;
	} */

	public function _page($Con){
	    $this->HostDb->select('count(a_id) as num', FALSE);
	    $this->HostDb->from('area');
	     
	    if(!empty($Con['keyword'])){
	        $this->HostDb->group_start()
	                        ->like('a_province', $Con['keyword'])
        	                ->or_like('a_city', $Con['keyword'])
        	                ->or_like('a_county', $Con['keyword'])
	                       ->group_end();
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

	public function insert($Data) {
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format($Data, $Item);
	    if($this->HostDb->insert('area', $Data)){
	        log_message('debug', "Model Area_model/insert Success!");
	        $this->remove_cache($this->_Cache);
	        return $this->HostDb->insert_id();
	    }else{
	        log_message('debug', "Model Area_model/insert Error");
	        return false;
	    }
	}
	
	public function update($Data, $Where) {
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format_re($Data, $Item);
	    $this->HostDb->where('a_id', $Where);
	    $this->HostDb->update('area', $Data);
	    $this->remove_cache($this->_Cache);
	    return TRUE;
	}
	
	/**
	 * 删除
	 * @param unknown $Where
	 */
	public function delete($Where){
	    if(is_array($Where)){
	        $this->HostDb->where_in('a_id', $Where);
	    }else{
	        $this->HostDb->where('a_id', $Where);
	    }
	    $this->HostDb->delete('area');
	    $this->remove_cache($this->_Cache);
	    return true;
	}
}
