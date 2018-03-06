<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-5-5
 * @author ZhangCC
 * @version
 * @description  
 */
class Workflow_process extends CWDMS_Controller{
	private $Module = 'manage';
	
	private $RelationType = array(
			'order' => 'financeReceive,produce',
			'produce' => 'stockout'
	);
	
	private $RelationModel = array(
			'order' => 'order/order_model',
			'financeReceive' => 'finance/finance_receive_plan_model',
			'produce' => 'produce/produce_plan_model',
			'stockout' => 'stock/stockout_model'
	);
	
	private $Source = array(
			'order' => FALSE,
			'financeReceive' => TRUE,
			'produce' => TRUE,
			'stockout' => TRUE
	);
	
	private $Des = array(
			'order' => TRUE,
			'financeReceive' => FALSE,
			'produce' => TRUE,
			'stockout' => FALSE
	);
	
	private $_Sets = array();
	
	private $_CurrentName;
	private $_CurrentId;
	
	public function __construct(){
		parent::__construct();
		$this->load->model('manage/workflow_process_model');
	}
	
	public function index(){
		$View = $this->uri->segment(4, 'read');
		if(method_exists(__CLASS__, '_'.$View)){
			$View = '_'.$View;
			$this->$View();
		}else{
			$Item = $this->Module.'/'.strtolower(__CLASS__).'/'.$View;
			$this->data['action'] = site_url($Item);
			$this->load->view($Item, $this->data);
		}
	}
	
	private function _read(){
		$Type = $this->uri->segment(5, 'order');
		$Data = array();
		if($Type){
			$Oid = $this->input->get('oid', true);
			$Oid = intval(trim($Oid));
			if($Oid){
				$Cache = $Oid.'_'.$Type.'_manage_workflow_process_read';
				$this->e_cache->open_cache();
				$Return = array();
				if(!($Return = $this->cache->get($Cache))){
					$this->_CurrentId = $Oid;
					$this->_CurrentName = $Type;
					$this->_get_order();
					$this->_get_des();
					$this->_Sets[] = array('name'=>$this->_CurrentName, 'source_id'=>$this->_CurrentId);
					if(!!($Query = $this->workflow_process_model->select_workflow_process($this->_Sets))){
						$this->config->load('dbview');
						$Dbview = $this->config->item('manage/workflow_process/read');
						foreach ($Query as $key=>$value){
							foreach ($Dbview as $ikey=>$ivalue){
								$Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
							}
						}
						$this->cache->save($Cache, $Return, MONTHS);
					}
				}
				$Data['Process'] = $Return;
				unset($Return);
			}
		}
		$this->load->view('manage/workflow_process/read', $Data);
	}
	
	
	private function _get_order($Id = FALSE, $Name = FALSE){
		if(!$Id){
			$Id = $this->_CurrentId;
		}
		if(!$Name){
			$Name = $this->_CurrentName;
		}
		if(!empty($this->Source[$Name])){
			$this->load->model($this->RelationModel[$Name]);
			$Model = explode('/', $this->RelationModel[$Name]);
			if(!!($Query = $this->$Model[1]->select_source_by_id($Id))){
				foreach ($Query as $key=>$value){
					$this->_get_order($value['source_id'], $value['source_model']);
				}
			}
		}else{
			$this->_CurrentId = $Id;
			$this->_CurrentName = $Name;
		}
	}
	
	private function _get_source($Id = false, $Name = false){
		if(!$Id){
			$Id = $this->_CurrentId;
		}
		if(!$Name){
			$Name = $this->_CurrentName;
		}
		if(!empty($this->Source[$Name])){
			$this->load->model($this->RelationModel[$Name]);
			$Model = explode('/', $this->RelationModel[$Name]);
			if(!!($Query = $this->$Model[1]->select_source_by_id($Id))){
				foreach ($Query as $key=>$value){
					$this->_Sets[] = $this->_get_source($value['source_id'], $value['source_model']);
				}
			}
		}else{
			return array('name'=>$Name, 'source_id' => $Id);
		}
	}
	
	private function _get_des($Id = false, $Name = false){
		if(!$Id){
			$Id = $this->_CurrentId;
		}
		if(!$Name){
			$Name = $this->_CurrentName;
		}
		if(!empty($this->Des[$Name])){
			$RT = explode(',', $this->RelationType[$Name]);
			$Set = array();
			if(count($RT) > 0){
				foreach ($RT as $okey => $ovalue){
					$this->load->model($this->RelationModel[$ovalue]);
					$Model = explode('/', $this->RelationModel[$ovalue]);
					if(!!($Query = $this->$Model[1]->select_id_by_source_id($Id, $Name))){
						foreach ($Query as $key=>$value){
							$this->_Sets[] = $this->_get_des($value, $ovalue);
						}
					}
				}
			}
		}
		return array('name'=>$Name, 'source_id' => $Id);
	}
}