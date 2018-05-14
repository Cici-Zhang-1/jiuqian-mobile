<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-25
 * @author ZhangCC
 * @version
 * @description  
 */
class Company_type_model extends MY_Model{
	public function __construct(){
		log_message('debug', 'Model Data/Company_type_model Start!');
		parent::__construct();
	}

	public function select_company_type(){
		$this->HostDb->select('ct_id, ct_name, ct_des', FALSE);
		$Query = $this->HostDb->get('company_type');
		if($Query->num_rows() > 0){
			return $Query->result_array();
		}else{
			return false;
		}
	}
}
