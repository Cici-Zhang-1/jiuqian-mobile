<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-23
 * @author ZhangCC
 * @version
 * @description  
 */
class Usergroup_role_model extends MY_Model{
	public function __construct(){
		parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Usergroup_role_model Start!');
	}

	public function select_by_usergroup_v($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('usergroup_role')
                ->join('role', 'r_id = ur_role_id', 'left')
                ->where('ur_usergroup_id', $V)
                ->order_by('r_name')
                ->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的用户组角色';
            }
        }
        return $Return;
    }



	public function insert($Data){
		$Item = $this->_Item.__FUNCTION__;
		$Data = $this->_format($Data, $Item);
		if($this->HostDb->insert('usergroup_role', $Data)){
			log_message('debug', "Model Usergropu_role_model/insert Success!");
			$this->remove_cache($this->_Module);
			return $this->HostDb->insert_id();
		}else{
			log_message('debug', "Model Usergropu_role_model/insert Error");
			return false;
		}
	}

	/**
	 * 批量输入
	 * @param $Data
	 * @return bool
	 */
	public function insert_batch($Data) {
		$Item = $this->_Item.__FUNCTION__;
		foreach ($Data as $key => $value){
			$Data[$key] = $this->_format($value, $Item);
		}
		if($this->HostDb->insert_batch('usergroup_role', $Data)){
			log_message('debug', "Model Usergropu_role_model/insert_batch Success!");
			$this->remove_cache($this->_Module);
			return true;
		}else{
			log_message('debug', "Model Usergropu_role_model/insert_batch Error");
			return false;
		}
	}

	public function update($Data, $Where){
		$this->HostDb->where('ur_id',$Where);
		return $this->HostDb->update('usergroup_role', $Data);
	}

	public function delete($Where){
		$this->HostDb->where_in('ur_id', $Where);
		return $this->HostDb->delete('usergroup_role');
	}

	/**
	 * 在删除用户组时，删除冗余的用户组角色信息
	 * 在设置用户组包含角色时，也需要删除冗余信息
	 * @param $Where
	 * @return bool
	 */
	public function delete_by_usergroup_v($Where) {
		if (is_array($Where)) {
			$this->HostDb->where_in('ur_usergroup_id', $Where);
		}else {
			$this->HostDb->where('ur_usergroup_id', $Where);
		}

		$this->HostDb->delete('usergroup_role');
		return true;
	}
}
