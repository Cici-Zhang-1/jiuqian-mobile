<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月2日
 * @author Zhangcc
 * @version
 * @des
 * 外购，其他
 */
class Order_product_other extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Order_product_other Start !');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
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
    
    private function _read(){
        $this->_Product = $this->uri->segment(5, false);
        $this->_Product = trim($this->_Product);
        $this->_Id = $this->input->get('id', true);
        $this->_Id = intval(trim($this->_Id));
        
        if($this->_Id && $this->_Product){
            $Item = $this->_Item.__FUNCTION__.'_'.$this->_Product;
            $Data = array(
                'Id' => $this->_Id,
                'Product' => $this->_Product
            );
            $this->load->library('d_dismantle');
            $Data['Other'] = $this->d_dismantle->read_detail('other', $this->_Id);
            $this->load->view($Item, $Data);
        }else{
            $this->close_tab('您访问的内容不存在!');
        }
    }

    public function read(){
        $Oid = $this->input->get('id', true);
        $Oid = intval(trim($Oid));
        $Data = array();
        if($Oid){
            $Cache = $Oid.'_order_order_product_other';
            $this->e_cache->open_cache();
            $Return = array();
            if(!($Return = $this->cache->get($Cache))){
                if(!!($Query = $this->order_product_other_model->select_order_product_other($Oid))){
                    $this->config->load('dbview/order');
                    $Dbview = $this->config->item('order/order_product_other/read');
                    foreach ($Query as $key=>$value){
                        foreach ($Dbview as $ikey=>$ivalue){
                            $Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
                        }
                    }
                    $this->cache->save($Cache, $Return, HOURS);
                }else{
                    $this->Failue .= '该订单暂时没有其他信息';
                }
            }
            $Data['content'] = $Return;
            unset($Return);
        }
        $this->_return($Data);
    }
    
    public function edit(){
        $Item = $this->_Module.'/'.strtolower(__CLASS__);
        $Run = $Item.'/'.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $this->config->load('formview/order');
            $FormView = $this->config->item($Item);
            foreach ($FormView as $key=>$value){
                $tmp = $this->input->post($key, true);
                if($tmp !== false){
                    $Set[$value] = $tmp;
                    unset($tmp);
                }
            }
            $where = $this->input->post('selected');
            if(isset($Set) && !!($this->order_product_other_model->update_order_product_other(gh_mysql_string($Set), $where))){
                $this->Success .= '其它信息修改成功, 刷新后生效!';
                $this->load->helper('file');
                delete_cache_files('(.*order.*)');
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'其它信息修改失败&nbsp;&nbsp;';
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
            $this->config->load('formview/order');
            $FormView = $this->config->item($Item);
            foreach ($FormView as $key=>$value){
                $tmp = $this->input->post($key, true);
                if($tmp !== false){
                    $Set[$value] = $tmp;
                    unset($tmp);
                }
            }
            if($Set !== false && is_array($Set) && count($Set) > 0){
                if($this->order_product_other_model->delete_order_product_other_by_id($Set)){
                    $this->Success .= '其他信息删除成功, 刷新后生效!';
                    $this->load->helper('file');
                    delete_cache_files('(.*order.*)');
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'其他信息删除失败&nbsp;&nbsp;';
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
