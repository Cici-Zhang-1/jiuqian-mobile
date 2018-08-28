<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-23
 * @author ZhangCC
 * @version
 * @description  
 * 菜单管理
 */
class Menu_model extends MY_Model{
    
	public function __construct(){
		parent::__construct(__DIR__, __CLASS__);
		log_message('debug', 'Model permission/Menu_model Start!');
	}

	public function select($Field, $Con = array(), $Num = 0) {
	    $Item = $this->_Item . __FUNCTION__;
	    $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Con) . $Num;

	    if (!($Return = $this->cache->get($Cache))) {
	        if (count($Field) > 0) {
	            $Sql = $this->_string_untable_as($Field, 'menu');
            }else {
                $Sql = $this->_unformat_as($Item);
            }

            $this->HostDb->select($Sql)->from('menu');
            if (count($Con) > 0) {
                $Con = $this->_untable_as($Con, 'menu');
                $this->HostDb->where($Con);
            }
            if ($Num > 0) {
                $this->HostDb->limit($Num);
            }
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                if ($Num == 1) {
                    $Return = $Query->row_array();
                }else {
                    $Return = $Query->result_array();
                }
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

	public function select_menu(){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item);
	        $Query = $this->HostDb->select($Sql)->from('menu')
	                       ->order_by('m_displayorder')->get();
	        if($Query->num_rows() > 0){
	            $Return = $Query->result_array();
	            $this->cache->save($Cache, $Return, MONTHS);
	        }
	    }
	    return $Return;
	}
	
	/**
	 * 根据菜单名称获取菜单id编号
	 * @param unknown $Name
	 */
	public function select_menu_id($Name){
	    $Query = $this->HostDb->select('m_id')->from('menu')->where('m_name', $Name)->limit(1)->get();
	    if($Query->num_rows()  > 0){
	        $Row = $Query->row_array();
	        return $Row['m_id'];
	    }else{
	        return false;
	    }
	}

    /**
     * 通过用户组编号获取许可的menu
     * @param $Ugid 用户组编号
     * @param boolean $Mobile 是否是移动端
     */
	public function select_allowed_by_ugid($Ugid, $Mobile = false) {
	    return $this->select_by_usergroup_v($Ugid);
    }

    public function select_by_usergroup_v($UsergroupV) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__ . $UsergroupV;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->db->select($Sql)->from('role_menu')
                ->join('menu', 'm_id = rm_menu_id', 'left')
                ->join('page_type', 'pt_name = m_page_type', 'left')
                ->join('boolean_type AS MOBILE', 'MOBILE.bt_name = m_mobile', 'left')
                ->join('boolean_type AS INVISIBLE', 'INVISIBLE.bt_name = m_invisible', 'left')
                ->where("rm_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $UsergroupV)")
                ->group_by('m_id')
                ->order_by('m_displayorder')
                ->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
	/**
	 * 通过用户号获取用户的菜单
	 * @param $Uid
	 */
	public function select_by_uid($Uid) {
		$Item = $this->_Item.__FUNCTION__;
		$Cache = $this->_Cache.__FUNCTION__ . $Uid;
		$Return = false;
		if(!($Return = $this->cache->get($Cache))){
			$Sql = $this->_unformat_as($Item);
			$Query = $this->HostDb->select($Sql)->from('role_menu')
				->join('menu', 'm_id = rm_menu_id', 'left')
				->where("rm_role_id in (SELECT ur_role_id FROM j_usergroup_role LEFT JOIN j_usergroup AS ug ON ug.u_id = ur_usergroup_id
									LEFT JOIN j_user AS u ON u.u_usergroup_id = ug.u_id WHERE u.u_id = $Uid)")
				->group_by('m_id')
                ->order_by('m_displayorder')
				->get();
			if ($Query->num_rows() > 0) {
				$Return = $Query->result_array();
				return $Return;
			}else {
				$Return = false;
			}
		}else {
			$Return = false;
		}
		return $Return;
	}

	/**
	 * 获取显示最大顺序
	 * @return int
	 */
	private function _select_max_displayorder(){
	    $Query = $this->HostDb->select_max('m_displayorder')->get('menu');
	    if($Query->num_rows() > 0){
	        $Row = $Query->row_array();
	        return $Row['m_displayorder'] + 1;
	    }else{
	        return 1;
	    }
	}

    /**
     * METHOD 操作不存在
     * @param $Operation
     * @return bool
     */
	public function select_not_exist_operation($Operation) {
	    $Query = $this->HostDb->select('m_id')
            ->from('menu')
            ->where('m_url', $Operation)
            ->get();
	    if ($Query->num_rows() > 0) {
	        return false;
        }
        return true;
    }
    /**
     * Public
     * METHOD 判断这个操作是否允许
     * @param $Ugid
     * @param $Operation
     * @return bool
     */
	public function select_is_allowed_operation($Ugid, $Operation) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__ . $Ugid . $Operation;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_menu')
                ->join('menu', 'm_id = rm_menu_id', 'left')
                ->where("rm_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Ugid)")
                ->where('m_url', $Operation)
                ->group_by('m_id')
                ->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    /**
     * 判断是否存在
     * @param $V
     * @return bool
     */
    public function is_exist($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('menu')
                ->where('m_id', $V)->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的菜单';
            }
        }
        return $Return;
    }

	public function insert($Data){
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format($Data, $Item);
		if(!empty($Data['m_displayorder'])){
			$this->_update_displayorder($Data['m_displayorder']);
		}else{
		    $Data['m_displayorder'] = $this->_select_max_displayorder();
		}
		if($this->HostDb->insert('menu', $Data)){
			log_message('debug', "Model Menu_model/insert_menu Success!");
			$this->remove_cache('menu');
			return $this->HostDb->insert_id();
		}else{
			log_message('debug', "Model Menu_model/insert_menu Error");
			return false;
		}
	}

	/**
	 * 更新菜单
	 * @param unknown $Data
	 * @param unknown $Where
	 */
	public function update($Data, $Mid){
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format_re($Data, $Item);
	    
		$Query = $this->HostDb->select('m_displayorder')->where(array('m_id' => $Mid))->get('menu');
		if($Query->num_rows() > 0){
			$Row = $Query->row_array();
			$Query->free_result();
			if($Row['m_displayorder'] < $Data['m_displayorder']){
			    $this->_update_displayorder_min($Row['m_displayorder'], $Data['m_displayorder']);
			}elseif ($Row['m_displayorder'] > $Data['m_displayorder']){
			    $this->_update_displayorder_plus($Data['m_displayorder'], $Row['m_displayorder']);
			}
		}else{
		    $GLOBALS['error'] = '您要修改的菜单不存在';
			return false;
		}
		$this->HostDb->where('m_id', $Mid);
		$this->HostDb->update('menu', $Data);
		$this->remove_cache('menu');
		return TRUE;
	}
	
	private function _update_displayorder_min($Min, $Max){
	    $Query = $this->HostDb->query("UPDATE j_menu SET m_displayorder = m_displayorder-1 where m_displayorder > $Min && m_displayorder <= $Max");
	    if($Query){
	        return true;
	    }else{
	        return false;
	    }
	}
	private function _update_displayorder_plus($Min, $Max){
	    $Query = $this->HostDb->query("UPDATE j_menu SET m_displayorder = m_displayorder+1 where m_displayorder >= $Min && m_displayorder < $Max");
	    if($Query){
	        return true;
	    }else{
	        return false;
	    } 
	}
	/**
	 * 更新菜单的显示顺序
	 * @param unknown $DisplayOrder
	 * @return boolean
	 */
	private function _update_displayorder($DisplayOrder){
		$Query = $this->HostDb->query("UPDATE j_menu SET m_displayorder = m_displayorder+1 where m_displayorder >= $DisplayOrder");
		if($Query){
			return true;
		}else{
			return false;
		}
	}
	
	private function _delete_displayorder($DisplayOrder){
	    $Query = $this->HostDb->query("UPDATE j_menu SET m_displayorder = m_displayorder-1 where m_displayorder > $DisplayOrder");
	    if($Query){
	        return true;
	    }else{
	        return false;
	    }
	}

	/**
	 * 删除菜单
	 * @param $Where
	 * @return mixed
	 */
	public function delete($Where){
	    if(is_array($Where)){
	        foreach ($Where as $key => $value){
	            $Query = $this->HostDb->select('m_displayorder')->where(array('m_id' => $value))->get('menu');
	            if($Query->num_rows() > 0){
	                $Row = $Query->row_array();
	                $Query->free_result();
	                $this->_delete_displayorder($Row['m_displayorder']);
	            }
	        }
	    }else{
	        $Query = $this->HostDb->select('m_displayorder')->where(array('m_id' => $Where))->get('menu');
	        if($Query->num_rows() > 0){
	            $Row = $Query->row_array();
	            $Query->free_result();
	            $this->_delete_displayorder($Row['m_displayorder']);
	        }
	    }
		if(is_array($Where)){
		    $this->HostDb->where_in('m_id', $Where);
		}else{
		    $this->HostDb->where('m_id', $Where);
		}
        $this->remove_cache('menu');
		return $this->HostDb->delete('menu');
	}
	
}
