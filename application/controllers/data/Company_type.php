<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-25
 * @author ZhangCC
 * @version
 * @description  
 */
class Company_type extends MY_Controller{
	public function __construct(){
		log_message('debug', 'Controller Data/Company_type Start!');
		parent::__construct();
		$this->load->model('data/company_type_model');
	}

	public function read_json(){
		$this->_Item = $this->_Module.'/'.strtolower(__CLASS__).'/read';
		$Cache = 'company_type';
		$this->e_cache->open_cache();
		$Return = array();
		if(!($Return = $this->cache->get($Cache))){
			if(!!($Query = $this->company_type_model->select_company_type())){
				$this->config->load('dbview');
				$Dbview = $this->config->item($this->_Item);
				foreach ($Query as $key => $value){
					foreach ($Dbview as $ikey=>$ivalue){
						$Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
					}
				}
				$this->cache->save($Cache, $Return, MONTHS);
			}
		}
		$this->_return($Return);
	}
}
