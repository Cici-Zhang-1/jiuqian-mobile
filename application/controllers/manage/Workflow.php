<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-22
 * @author ZhangCC
 * @version
 * @description  
 */
class Workflow extends MY_Controller{
	public function __construct($Alias = ''){
		log_message('debug', 'Controller Manage/Workflow Start!');
		parent::__construct();
		$this->load->model('manage/workflow_model');
	}
	
	public function index(){
		$View = $this->uri->segment(4, 'read');
		if(method_exists(__CLASS__, '_'.$View)){
		    $View = '_'.$View;
			$this->$View();
		}else{
			$Item = $this->_Module.'/'.strtolower(__CLASS__).'/'.$View;
			$this->data['action'] = site_url($Item);
			$this->load->view($Item, $this->data);
		}
	}

	
	public function read(){
        $this->_Item = $this->_Module.'/'.strtolower(__CLASS__).'/read';
        $Cache = 'manage_workflow';
        $this->e_cache->open_cache();
        $Data = array();
        if(!($Data = $this->cache->get($Cache))){
            if(!!($Query = $this->workflow_model->select_workflow())){
                $this->config->load('dbview/manage');
                $Dbview = $this->config->item($this->_Item);
                $Tmp = array_flip($Dbview);
                $Id = $Tmp['wid'];
                foreach ($Query as $key => $value){
                    foreach ($Dbview as $ikey=>$ivalue){
                        $Return[$value[$Id]][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
                    }
                }
                $Pid = 9999;
                foreach ($Return as $key => $value){
                    $Child[$value['parent']][] = $value['wid'];
                    if($value['parent'] < $Pid){
                        $Pid = $value['parent'];
                    }
                }
                ksort($Child);
                $Child = gh_infinity_category($Child, $Pid);
                while(list($key, $value) = each($Child)){
                    $Data['content'][] = $Return[$value];
                }
                $this->cache->save($Cache, $Data, MONTHS);
            }else{
                $this->Failue .= '没有状态类型信息';
            }
        }
        $this->_return($Data);
	}
	
	public function add(){
		$Item = $this->_Module.'/'.strtolower(__CLASS__);
		$Run = $Item.'/'.__FUNCTION__;
		if($this->form_validation->run($Run)){
			$this->config->load('formview/manage');
			$FormView = $this->config->item($Item);
			foreach ($FormView as $key=>$value){
				$tmp = $this->input->post($key, true);
				if(isset($tmp)){
					$Set[$value] = $tmp;
					unset($tmp);
				}
			}
			if(isset($Set) && !!($this->workflow_model->insert_workflow(gh_mysql_string($Set)))){
				$this->Success .= '状态类型新增成功, 刷新后生效!';
				$this->load->helper('file');
				delete_cache_files('(.*workflow.*)');
			}else{
				$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'状态类型创建失败';
			}
		}else{
			$this->Failue .= validation_errors();
		}
		$this->_return();
	}
	
	public function edit(){
		$Item = $this->_Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
		if($this->form_validation->run($Item)){
			$this->config->load('formview/manage');
			$FormView = $this->config->item($Item);
			foreach ($FormView as $key=>$value){
				$tmp = $this->input->post($key, true);
				if(isset($tmp)){
					$Set[$value] = $tmp;
					unset($tmp);
				}
			}
			$where = $this->input->post('selected');
			if(isset($Set) && !!($this->workflow_model->update_workflow(gh_mysql_string($Set), $where))){
				$this->Success .= '状态类型修改成功, 刷新后生效!';
				$this->load->helper('file');
				delete_cache_files('(.*workflow.*)');
			}else{
				$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'状态类型修改失败';
			}
		}else{
			$this->Failue .= validation_errors();
		}
		$this->_return();
	}
	
	public function remove(){
		$Item = $this->_Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
		if($this->form_validation->run($Item)){
			$Where = $this->input->post('selected', true);
			if($Where !== false && is_array($Where) && count($Where) > 0){
				if($this->workflow_model->delete_workflow($Where)){
					$this->Success .= '状态类型删除成功, 刷新后生效!';
					$this->load->helper('file');
					delete_cache_files('(.*workflow.*)');
				}else {
					$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'状态类型删除失败';
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
