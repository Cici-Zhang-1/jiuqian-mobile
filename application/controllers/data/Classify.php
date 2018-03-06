<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月12日
 * @author Zhangcc
 * @version
 * @des
 * 板材归类
 */
class Classify extends CWDMS_Controller{
    private $_Module;
    private $_Controller;
    private $_Item ;
    public function __construct(){
        log_message('debug', 'Controller Data/Classify Start!');
        parent::__construct();
        $this->load->model('data/classify_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
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
            if(!($Pid = $this->classify_model->select_classify_id(gh_mysql_string($Parent)))){
                $this->Failue = '您要查找的板块分类不存在';
            }
        }else{
            $Pid = 0;
        }
        $Return = array();
        if(empty($this->Failue)){
            if(!!($Query = $this->classify_model->select())){
                foreach ($Query as $key => $value){
                    $Data[$value['cid']] = $value;
                    $Child[$value['parent']][] = $value['cid'];
                }
                ksort($Child);
                $Child = gh_infinity_category($Child, $Pid);
                while(list($key, $value) = each($Child)){
                    $Return['content'][] = $Data[$value];
                }
            }else{
                $this->Failue = '没有板块分类';
            }
        }
        $this->_return($Return);
    }
    
    public function get($Type){
        $Type = trim($Type);
        $Data = array();
        if('label' == $Type){
            if(!($Data = $this->classify_model->select_label())){
                $this->Failue = '您要获取打标签的板块分类失败!';
            }
        }elseif ('parents' == $Type){
            if(!($Data = $this->classify_model->select_parents())){
                $this->Failue = '您要获取打标签的板块分类失败!';
            }
        }else{
            $this->Failue = '您要获取的类型不存在!';
        }
        $this->_return($Data);
    }

    public function add(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            $Process = $this->input->post('process');
            $Process = explode(',', $Process);
            $Process = array_unique($Process);
            $Post['process'] = implode(',', $Process);
            if(!empty($Post['parent'])){
                if(!!($Parent = $this->classify_model->select_parent($Post['parent']))){
                    $Post = array_merge($Post, $Parent);
                }
            }
            if(!!($this->classify_model->insert($Post))){
                $this->Success .= '板块分类新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'板块分类新增失败!';
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
            $Process = $this->input->post('process');
            $Process = explode(',', $Process);
            $Process = array_unique($Process);
            $Post['process'] = implode(',', $Process);
            if(empty($Post['process'])){
                unset($Post['process']);
            }
            $Selected = $Post['selected'];
            unset($Post['selected']);
            if(!!($this->classify_model->update($Post, $Selected))){
                $this->Success .= '板块分类信息修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'板块分类信息修改失败!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }

    public function act($Type){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Selected = $this->input->post('selected', true);
            if($Selected !== false){
                if('enable' == $Type){
                    $Type = 1;
                }else{
                    $Type = 0;
                }
                if(!!($this->classify_model->able($Selected, $Type))){
                    $this->Success .= '板块分类信息删除成功, 刷新后生效!';
                }else{
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'板块分类信息删除失败';
                }
            }else{
                $this->Failue .= '没有可删除项!';
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
                if(!!($this->classify_model->delete($Where))){
                    $this->Success .= '板块分类信息删除成功, 刷新后生效!';
                }else{
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'板块分类信息删除失败';
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