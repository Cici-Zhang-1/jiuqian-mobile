<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月5日
 * @author Administrator
 * @version
 * @des
 */
class Usergroup extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item ;
    public function __construct(){
        parent::__construct();
        $this->load->model('manage/usergroup_model');
		$this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
		$this->_Cookie = str_replace('/', '_', $this->_Item).'_';
		
		log_message('debug', 'Controller Manage/Usergroup Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $this->data['action'] = site_url($Item);
            $this->load->view($Item, $this->data);
        }
    }

    public function read(){
        $Parent = $this->input->get('parent', true);
        $Parent = trim($Parent);
        if(preg_match('/\d{1,10}/', $Parent)){
            $Pid = $Parent;
        }elseif(is_string($Parent) && !empty($Parent)){
            if(!($Pid = $this->usergroup_model->select_usergroup_id($Parent))){
                $this->Failue = '您要查找的用户组不存在';
            }
        }else{
            $Pid = $this->session->userdata('ugid');
        }
        if(empty($this->Failue)){
            if(!!($Query = $this->usergroup_model->select())){
                foreach ($Query as $key => $value){
                    $Data[$value['uid']] = $value;
                    $Child[$value['parent']][] = $value['uid'];
                }
                ksort($Child);
                $Child = gh_infinity_category($Child, $Pid);
                while(list($key, $value) = each($Child)){
                    $Return['content'][] = $Data[$value];
                }
                $Return['id'] = $Pid;
                $Return['class'] = $Data[$Pid]['class'];
            }else{
                $this->Failue = '没有用户组!';
            }
        }
        $this->_return($Return);
    }

    public function add(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($Mid = $this->usergroup_model->insert($Post))){
                $this->Success .= '用户组新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户组新增失败!';
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
            $Where = $Post['selected'];
            unset($Post['selected']);
            if(!!($this->usergroup_model->update($Post, $Where))){
                $this->Success .= '用户组信息修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户组信息修改失败!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }

    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false){
                $this->load->model('manage/usergroup_model');
                if(!!($this->usergroup_model->delete($Where))){
                    $this->Success .= '用户组信息删除成功, 刷新后生效!';
                }else{
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户组信息删除失败';
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
