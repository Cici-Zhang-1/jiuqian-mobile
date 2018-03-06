<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-23
 * @author ZhangCC
 * @version
 * @description  
 * 菜单管理
 */
class Classify_model extends Base_Model{
    private $_Module;
    private $_Model;
    private $_Item;
    private $_Cache;
    private $_Num;
    
	public function __construct(){
		parent::__construct();
		log_message('debug', 'Model Data/Classify_model Start!');
		
		$this->_Module = str_replace("\\", "/", dirname(__FILE__));
		$this->_Module = substr($this->_Module, strrpos($this->_Module, '/')+1);
		$this->_Model = strtolower(__CLASS__);
		$this->_Item = $this->_Module.'/'.$this->_Model.'/';
		$this->_Cache = str_replace('/', '_', $this->_Item);
	}

	public function select(){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $Query = $this->HostDb->select($Sql)->from('classify')->get();
	        if($Query->num_rows() > 0){
	            $Return = $Query->result_array();
	            $this->cache->save($Cache, $Return, MONTHS);
	        }
	    }
	    return $Return;
	}

    /**
     * 获取所有一级分类的信息
     * @return bool
     */
	public function select_parents(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->HostDb->select($Sql)
                ->from('classify')
                ->where('c_class', 0)
                ->where('c_status', 1)
                ->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array()
                );
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有可用一级分类';
            }
        }
        return $Return;
    }
	public function select_children(){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $Query = $this->HostDb->select($Sql)
	                               ->from('classify')
	                               ->where('c_class > 0')
	                               ->where('c_status',1)
                               ->get();
	        if($Query->num_rows() > 0){
	            $Return = $Query->result_array();
	            $this->cache->save($Cache, $Return, MONTHS);
	        }
	    }
	    return $Return;
	}
	
	/**
	 * 获取需要打印清单的父类标签
	 */
	public function select_print_list(){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $Query = $this->HostDb->select($Sql)
	                       ->from('classify')
	                       ->where('c_class', 0)
	                       ->where('c_print_list', 1)
	                       ->where('c_status', 1)
                       ->get();
	        if($Query->num_rows() > 0){
	            $Return = $Query->result_array();
	            $this->cache->save($Cache, $Return, MONTHS);
	        }
	    }
	    return $Return;
	}
	
	public function select_label(){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $Query = $this->HostDb->select($Sql)
                            	        ->from('classify')
                            	        ->where('c_class', 0)
                            	        ->where('c_label', 1)
                            	        ->where('c_status', 1)
                    	           ->get();
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
	 * 获取父类 信息
	 * @param unknown $Pid
	 */
	public function select_parent($Pid){
	    $Return = array();
	    $Query = $this->HostDb->select('c_flag as flag, c_print_list as print_list, 
	                   c_label as label, c_optimize as optimize, c_process as process')
	       ->from('classify')->where('c_id', $Pid)->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->row_array();
	        $Query->free_result();
	    }
	    return $Return;
	}
	
	/**
	 * 根据菜单名称获取菜单id编号
	 * @param unknown $Name
	 */
	public function select_classify_id($Name){
	    $Query = $this->HostDb->select('c_id')->from('classify')->where('c_name', $Name)->limit(1)->get();
	    if($Query->num_rows() > 0){
	        $Row = $Query->row_array();
	        return $Row['c_id'];
	    }else{
	        return false;
	    }
	}
	
	public function insert($Data){
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format($Data, $Item, $this->_Module);
		if($this->HostDb->insert('classify', $Data)){
			$this->remove_cache($this->_Module);
			return $this->HostDb->insert_id();
		}else{
			log_message('debug', "Model Menu_model/insert_classify Error");
			return false;
		}
	}

	/**
	 * 更新菜单
	 * @param unknown $Data
	 * @param unknown $Where
	 */
	public function update($Data, $Cid){
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format_re($Data, $Item, $this->_Module);
		$this->HostDb->where('c_id', $Cid);
		$this->HostDb->update('classify', $Data);
		$this->remove_cache('classify');
		return TRUE;
	}
	
	public function able($Where, $Type){
	    $this->HostDb->set('c_status', $Type);
	    if(is_array($Where)){
	        $this->HostDb->where_in('c_id', $Where);
	    }else{
	        $this->HostDb->where('c_id', $Where);
	    }
	    $this->HostDb->update('classify');
	    $this->remove_cache('classify');
	    return true;
	}
	public function delete($Where){
		if(is_array($Where)){
		    $this->HostDb->where_in('c_id', $Where);
		}else{
		    $this->HostDb->where('c_id', $Where);
		}
		$this->HostDb->delete('classify');
		$this->remove_cache('classify');
		return true;
	}
	
}