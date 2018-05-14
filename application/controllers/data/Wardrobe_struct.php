<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月21日
 * @author Zhangcc
 * @version
 * @des
 */
class Wardrobe_struct extends MY_Controller{
    public function __construct(){
        parent::__construct();
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
        $Cache = 'data_wardrobe_struct';
        $this->e_cache->open_cache();
        $Data = array();
        /* if(!($Data = $this->cache->get($Cache))){
            if(!!($Query = $this->wardrobe_struct_model->select_wardrobe_struct())){
                $this->config->load('dbview/data');
                $Dbview = $this->config->item($this->_Item);
                foreach ($Query as $key => $value){
                    foreach ($Dbview as $ikey=>$ivalue){
                        $Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
                    }
                }
                $Data = array(
                    'content' => $Return
                );
                $this->cache->save($Cache, $Data, MONTHS);
            }else{
                $this->Failue .= '没有异形信息';
            }
        } */
        $Data['content'] = array(
            array(
                'wsid' => 1,
                'name' => '板块名称',
                'parent' => 0,
                'class' => 0
            ),
            array(
                'wsid' => 2,
                'name' => '立板',
                'parent' => 3,
                'class' => 1
            ),
            array(
                'wsid' => 4,
                'name' => '中立板',
                'parent' => 1,
                'class' => 1
            ),
            array(
                'wsid' => 5,
                'name' => '顶板',
                'parent' => 1,
                'class' => 1
            ),
            array(
                'wsid' => 6,
                'name' => '底板',
                'parent' => 1,
                'class' => 1
            ),
            array(
                'wsid' => 7,
                'name' => '顶底板',
                'parent' => 1,
                'class' => 1
            ),
            array(
                'wsid' => 8,
                'name' => '封边',
                'parent' => 0,
                'class' => 0
            ),
            array(
                'wsid' => 9,
                'name' => 'bbbb',
                'parent' => 8,
                'class' => 1
            ),
            array(
                'wsid' => 10,
                'name' => 'Hbbb',
                'parent' => 8,
                'class' => 1
            ),
            array(
                'wsid' => 11,
                'name' => 'Hb',
                'parent' => 8,
                'class' => 1
            ),
            array(
                'wsid' => 12,
                'name' => 'HHHH',
                'parent' => 8,
                'class' => 1
            ),
            array(
                'wsid' => 13,
                'name' => '开槽边',
                'parent' => 0,
                'class' => 0
            ),
            array(
                'wsid' => 14,
                'name' => '单开',
                'parent' => 13,
                'class' => 1
            ),
            array(
                'wsid' => 15,
                'name' => '双开',
                'parent' => 13,
                'class' => 1
            ),
            array(
                'wsid' => 16,
                'name' => '开槽',
                'parent' => 13,
                'class' => 1
            ),
            array(
                'wsid' => 17,
                'name' => '打孔',
                'parent' => 0,
                'class' => 0
            )
        );
        $this->_return($Data);
    }
    public function add(){
        $Item = $this->_Module.'/'.strtolower(__CLASS__);
        $Run = $Item.'/'.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $this->config->load('formview/data');
            $FormView = $this->config->item($Item);
            foreach ($FormView as $key=>$value){
                $tmp = $this->input->post($key)?$this->input->post($key):$this->_default($key);
                if($tmp !== false){
                    $Set[$value] = $tmp;
                    unset($tmp);
                }
            }
            if(isset($Set) && !!($Id = $this->wardrobe_struct_model->insert_wardrobe_struct(gh_mysql_string($Set)))){
                $this->Success .= '异形新增成功, 刷新后生效!';
                $this->load->helper('file');
                delete_cache_files('(.*wardrobe_struct.*)');
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'异形信息新增失败&nbsp;&nbsp;';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    public function edit(){
        $Item = $this->_Module.'/'.strtolower(__CLASS__);
        $Run = $Item.'/'.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $this->config->load('formview/data');
            $FormView = $this->config->item($Item);
            foreach ($FormView as $key=>$value){
                $tmp = $this->input->post($key, true);
                if($tmp !== false){
                    $Set[$value] = $tmp;
                    unset($tmp);
                }
            }
            $where = $this->input->post('selected');
            if(isset($Set) && !!($this->wardrobe_struct_model->update_wardrobe_struct(gh_mysql_string($Set), $where))){
                $this->Success .= '异形信息修改成功, 刷新后生效!';
                $this->load->helper('file');
                delete_cache_files('(.*wardrobe_struct.*)');
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'异形信息修改失败&nbsp;&nbsp;';
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
        $Item = $this->_Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false && is_array($Where) && count($Where) > 0){
                if($this->wardrobe_struct_model->delete_wardrobe_struct($Where)){
                    $this->Success .= '异形信息删除成功, 刷新后生效!';
                    $this->load->helper('file');
                    delete_cache_files('(.*wardrobe_struct.*)');
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'异形信息删除失败&nbsp;&nbsp;';
                }
            }else{
                $this->Failue .= '没有可删除项!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }

    private function _default($name){
        switch ($name){
            case 'creator':
                $Return = $this->session->userdata('uid');
                break;
            case 'create_datetime':
                $Return = time();
                break;
            default:
                $Return = false;
        }
        return $Return;
    }

}
