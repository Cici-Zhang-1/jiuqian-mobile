<?php
/**
 *  2015-4-23
 * @author ZhangCC
 * @version
 * @description  
 */
class Workflow_node extends MY_Controller{
	private $Module = 'manage';
	
	public function __construct(){
		log_message('debug', 'Controller Workflow_node Start!');
		parent::__construct();
		$this->load->model('manage/workflow_node_model');
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
	    $Id = $this->input->get('id', true);
	    $Id = intval(trim($Id));
	    $this->load->view('manage/workflow_node/_read', array('Id' => $Id));
	}
	
	public function read(){
	    
	}
	
	public function read_by_name(){
	    $Name = $this->input->get('name');
	    $Item = $this->Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
	    $Cache = $Name.'_workflow_node_read_by_name';
	    if($Name){
	        $this->e_cache->open_cache();
	        if(!($Return = $this->cache->get($Cache))){
	            if(!!($Query = $this->workflow_node_model->select_workflow_node_by_name($Name))){
	                $this->config->load('manage/dbview');
	                $Dbview = $this->config->item($Item);
                    foreach ($Dbview as $ikey=>$ivalue){
                        $Return[$ivalue] = isset($Query[$ikey])?$Query[$ikey]:'';
                    }
	                $this->cache->save($Cache, $Return, MONTHS);
	            }
	        }
	    }
	    $this->_return($Return);
	}
	
	public function read_by_id(){
		$Id = intval($_GET['wid']);
		$Return = array();
		if($Id){
			$Item = $this->Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
			$Cache = $Id.'_workflow_node_read_by_id';
			$this->e_cache->open_cache();
			if(!($Return = $this->cache->get($Cache))){
				if(!!($Query = $this->workflow_node_model->select_workflow_node_by_id($Id))){
					$this->config->load('dbview');
					$Dbview = $this->config->item($Item);
					foreach ($Query as $key => $value){
						foreach ($Dbview as $ikey=>$ivalue){
							$Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
						}
					}
					$this->cache->save($Cache, $Return, MONTHS);
				}
			}
		}
		$this->_return($Return);
	}
	
	public function add(){
		$Item = $this->Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
		if($this->form_validation->run($Item)){
			$PrevNodeId = $this->input->post('prev_node_id', true);
			if($PrevNodeId !== false && is_array($PrevNodeId)){
				unset($_POST['prev_node_id']);
				$_POST['prev_node_id'] = implode(',', $PrevNodeId);
			}else{
				$_POST['prev_node_id'] = 0;
			}
			$NextNodeId = $this->input->post('next_node_id', true);
			if($NextNodeId !== false && is_array($NextNodeId)){
				unset($_POST['next_node_id']);
				$_POST['next_node_id'] = implode(',', $NextNodeId);
			}else{
				$_POST['next_node_id'] = 0;
			}
			$this->config->load('formview', TRUE);
			$FormView = $this->config->item($Item, 'formview');
			foreach ($FormView as $key=>$value){
				$tmp = $this->input->post($key, true);
				if(isset($tmp)){
					$Set[$value] = $tmp;
					unset($tmp);
				}
			}
			if(isset($Set) && !!($this->workflow_node_model->insert_workflow_node(gh_mysql_string($Set)))){
				$this->Success .= '工作流程节点新增成功, 刷新后生效!';
				$this->load->helper('file');
				delete_cache_files('(.*manage_workflow.*)');
			}else{
				$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单创建失败&nbsp;&nbsp;';
			}
		}else{
			$this->Failue .= validation_errors();
		}
		$this->_return();
	}
	
	public function edit(){
		$Item = $this->Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
		if($this->form_validation->run($Item)){
			$PrevNodeId = $this->input->post('prev_node_id', true);
			if($PrevNodeId !== false && is_array($PrevNodeId)){
				unset($_POST['prev_node_id']);
				$_POST['prev_node_id'] = implode(',', $PrevNodeId);
			}else{
				$_POST['prev_node_id'] = 0;
			}
			$NextNodeId = $this->input->post('next_node_id', true);
			if($NextNodeId !== false && is_array($NextNodeId)){
				unset($_POST['next_node_id']);
				$_POST['next_node_id'] = implode(',', $NextNodeId);
			}else{
				$_POST['next_node_id'] = 0;
			}
			$this->config->load('formview', TRUE);
			$FormView = $this->config->item($Item, 'formview');
			foreach ($FormView as $key=>$value){
				$tmp = $this->input->post($key, true);
				if(isset($tmp)){
					$Set[$value] = $tmp;
					unset($tmp);
				}
			}
			$where = $this->input->post('selected');
			if(isset($Set) && !!($this->workflow_node_model->update_workflow_node(gh_mysql_string($Set), $where))){
				$this->Success .= '工作流程节点修改成功, 刷新后生效!';
				$this->load->helper('file');
				delete_cache_files('(.*manage_workflow.*)');
			}else{
				$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单创建失败&nbsp;&nbsp;';
			}
		}else{
			$this->Failue .= validation_errors();
		}
		$this->_return();
	}
	
	public function remove(){
		$Item = $this->Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
		if($this->form_validation->run($Item)){
			$Where = $this->input->post('selected', true);
			if($Where !== false && is_array($Where) && count($Where) > 0){
				if($this->workflow_node_model->delete_workflow_node($Where)){
					$this->Success .= '工作流程节点删除成功, 刷新后生效!';
					$this->load->helper('file');
					delete_cache_files('(.*manage_workflow.*)');
				}else {
					$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单创建失败&nbsp;&nbsp;';
				}
			}else{
				$this->Failue .= '没有可删除项!';
			}
		}else{
			$this->Failue .= validation_errors();
		}
		$this->_return();
	}
}
