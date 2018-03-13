<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月20日
 * @author Zhangcc
 * @version
 * @des
 * 用户组菜单权限
 */
class Usergroup_menu extends MY_Controller{
    private $_Module = 'manage';
    private $_Controller;
    private $_Item = '';
    public function __construct(){
        parent::__construct();
        $this->load->model('manage/usergroup_priviledge_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        
        log_message('debug', 'Controller Manage/Usergroup_menu Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, false);
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view($Item, $Data);
        }
    }

    private function _read(){
        $Id = $this->input->get('id', true);
        $Id = intval(trim($Id));
        $Ugid = $this->session->userdata('ugid');
        $MyMenu = $this->_read_menu($Ugid); /*当前用户所在用户组的菜单权限*/
        $UsergroupMenu = $this->_read_menu($Id);  /*需要获取的用户组的菜单权限*/
        if(empty($MyMenu)){
            $Data['Error'] = '您无权设置权限!';
        }else{
            $Data['Id'] = $Id;
            
            foreach($MyMenu['content'] as $key => $value){
                if(!empty($UsergroupMenu) && in_array($value['pid'], $UsergroupMenu['child'])){
                    $MyMenu['content'][$key]['checked'] = 1;
                }else{
                    $MyMenu['content'][$key]['checked'] = 0;
                }
            }
            $Data['content'] = $MyMenu['content'];
            unset($MyMenu);
            unset($UsergroupMenu);
        }
        $this->load->view($this->_Item.__FUNCTION__, $Data);
    }

    private function _read_menu($Ugid){
        if(!!($Query = $this->usergroup_priviledge_model->select_menu($Ugid))){
            $Pid = 9999;
            foreach ($Query as $key => $value){
                $Data[$value['id']] = $value;
                $Child[$value['parent']][] = $value['id'];
                if($value['parent'] < $Pid){
                    $Pid = $value['parent'];
                }
            }
            ksort($Child);
            $Child = gh_infinity_category($Child, $Pid);
            while(list($key, $value) = each($Child)){
                $Return['content'][] = $Data[$value];
                $Child[$key] = $Data[$value]['pid'];
            }
            $Return['child'] = $Child;  /*这是当前已有权限的priviledgeId*/
        }else{
            $Return = false;
        }
        return $Return;
    }

    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Pid = $this->input->post('pid', true);
            $Ugid = $this->input->post('ugid', true);
            $Insert = array();
            if(!!($Menu = $this->_read_menu($Ugid))){
                $UsergroupMenuIds = $Menu['child'];
                unset($Menu);
                foreach ($Pid as $key => $value){
                    if(!in_array($value, $UsergroupMenuIds)){
                        $Insert[] = array(
                            'pid' => $value,
                            'ugid' => $Ugid
                        );
                    }else{
                        unset($UsergroupMenuIds[array_search($value,$UsergroupMenuIds)]);
                    }
                }
            }else{
                foreach ($Pid as $key => $value){
                    $Insert[] = array(
                        'pid' => $value,
                        'ugid' => $Ugid
                    );
                }
            }

            if(!empty($UsergroupMenuIds)){
                if(!($this->usergroup_priviledge_model->delete($UsergroupMenuIds, $Ugid))){
                    $this->Failue = '删除旧的权限失败!';
                }
            }
            if(!empty($Insert)){
                if(!($this->usergroup_priviledge_model->insert_batch($Insert))){
                    $this->Failue = '添加新的权限失败!';
                }
            }
        }else{
            $this->Failue .= validation_errors();
        }
        if(empty($this->Failue)){
            $this->Success = '用户组权限信息修改成功, 刷新后生效!';
        }
        $this->_return();
    }
}
