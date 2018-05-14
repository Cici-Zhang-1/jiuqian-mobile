<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月13日
 * @author Zhangcc
 * @version
 * @des
 * 工作流
 */
class Workflow extends MY_Controller{
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Data/Workflow Start!');

        $this->load->model('data/workflow_model');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read($Type = ''){
        $Type = trim($Type);
        $Data = array();
        if(empty($Type)){
            if(!($Query = $this->workflow_model->select())){
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有工作流节点';
            }else{
                $Data['content'] = $Query;
            }
        }else{
            $Method = '_'.__FUNCTION__.'_'.$Type;
            if(method_exists(__CLASS__, $Method)){
                $Data['content'] = $this->$Method();
                $Data['length'] = count($Data['content']);
            }
        }
        $this->_return($Data);
    }
    
    private function _read_wait_dismantle(){
        $Data = array();
        if(!($Query = $this->workflow_model->select())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有工作流节点';
        }else{
            foreach ($Query as $key => $value){
                if('order_product' == $value['type']){
                    if('create' == $value['name_en']){
                        $Data[0] = array(
                            'no' => $value['no'],
                            'name' => '未拆单'
                        );
                    }elseif ('dismantling' == $value['name_en']){
                        $Data[1] = array(
                            'no' => $value['no'],
                            'name' => '正在拆单'
                        );
                    }else{
                        if(!isset($Data['3'])){
                            $Data[3] = array(
                                'no' => array($value['no']),
                                'name' => '已拆单'
                            );
                        }else{
                            array_push($Data[3]['no'], $value['no']);
                        }
                    }
                }else{
                    continue;
                }
            }
            $Data[3]['no'] = implode(',', $Data[3]['no']);
        }
        return $Data;
    }
    
    private function _read_order(){
        $Data = array();
        if(!($Query = $this->workflow_model->select())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有工作流节点';
        }else{
            foreach ($Query as $key => $value){
                if('order' == $value['type']){
                    $Data[] = array(
                        'no' => $value['no'],
                        'v' => $value['no'],
                        'name' => $value['name']
                    );
                }else{
                    continue;
                }
            }
        }
        return $Data;
    }
    
    private function _read_classify(){
        $Data = array();
        if(!($Query = $this->workflow_model->select())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有工作流节点';
        }else{
            foreach ($Query as $key => $value){
                if('order_product_classify' == $value['type']){
                    $Data[] = array(
                        'no' => $value['no'],
                        'name' => $value['name']
                    );
                }else{
                    continue;
                }
            }
        }
        return $Data;
    }
    
    private function _read_wait_check(){
        $Data = array();
        if(!($Query = $this->workflow_model->select())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有工作流节点';
        }else{
            foreach ($Query as $key => $value){
                if('order' == $value['type']){
                    if('check' == $value['name_en']){
                        $Data[0] = array(
                            'no' => $value['no'],
                            'name' => '未核价'
                        );
                    }elseif ('checking' == $value['name_en']){
                        $Data[1] = array(
                            'no' => $value['no'],
                            'name' => '正在核价'
                        );
                    }
                }else{
                    continue;
                }
            }
        }
        return $Data;
    }
    
    private function _read_wait_quote(){
        $Data = array();
        if(!($Query = $this->workflow_model->select())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有工作流节点';
        }else{
            foreach ($Query as $key => $value){
                if('order' == $value['type']){
                    if('quote' == $value['name_en']){
                        $Data[0] = array(
                            'no' => $value['no'],
                            'name' => '未报价'
                        );
                    }
                }else{
                    continue;
                }
            }
        }
        return $Data;
    }

    private function _read_wait_asure(){
        $Data = array();
        if(!($Query = $this->workflow_model->select())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有工作流节点';
        }else{
            foreach ($Query as $key => $value){
                if('order' == $value['type']){
                    if('wait_asure' == $value['name_en']){
                        $Data[0] = array(
                            'no' => $value['no'],
                            'name' => '等待确认'
                        );
                    }elseif('produce' == $value['name_en']){
                        $Data[1] = array(
                            'no' => $value['no'],
                            'name' => '等待生产'
                        );
                    }
                }else{
                    continue;
                }
            }
        }
        return $Data;
    }

    private function _read_money_produce(){
        $Data = array();
        if(!($Query = $this->workflow_model->select())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有工作流节点';
        }else{
            foreach ($Query as $key => $value){
                if('order' == $value['type']){
                    if('money_produce' == $value['name_en']){
                        $Data[0] = array(
                            'no' => $value['no'],
                            'name' => '款到生产'
                        );
                    }
                }else{
                    continue;
                }
            }
        }
        return $Data;
    }
    
    public function add(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($Id = $this->workflow_model->insert($Post))){
                $this->Success .= '工作流节点新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'工作流节点新增失败';
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
            $Where = $this->input->post('selected');
            if(!!($this->workflow_model->update($Post, $Where))){
                $this->Success .= '工作流节点修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'工作流节点修改失败';
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
                if($this->workflow_model->delete($Where)){
                    $this->Success .= '工作流节点删除成功, 刷新后生效!';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'工作流节点删除失败';
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
