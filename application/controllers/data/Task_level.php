<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月23日
 * @author Administrator
 * @version
 * @des
 * 任务等级
 */
class Task_level extends MY_Controller{
    private $_Module;
	private $_Controller;
	private $_Item;
	
    public function __construct(){
        parent::__construct();
        $this->load->model('data/task_level_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        log_message('debug', 'Controller Data/Task_level Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view($Item, $Data);
        }
    }

    public function read(){
        $Data = array();
        if(!!($Data = $this->task_level_model->select())){
            $this->Success = '获取任务等级成功';
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合搜索条件的任务等级';
        }
        $this->_return($Data);
    }

    public function add(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            $Post['icon'] = $this->input->post('icon', true);
            if(!!($Mid = $this->task_level_model->insert($Post))){
                $this->Success .= '任务等级新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'任务等级新增失败!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    
    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            $Post['icon'] = $this->input->post('icon', true);
            $Where = $Post['selected'];
            unset($Post['selected']);
            if(!!($this->task_level_model->update($Post, $Where))){
                $this->Success .= '任务等级修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'任务等级修改失败!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    /**
     * 删除
     */
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false && is_array($Where) && count($Where) > 0){
                if($this->task_level_model->delete($Where)){
                    $this->Success .= '任务等级删除成功, 刷新后生效!';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'任务等级删除失败';
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
