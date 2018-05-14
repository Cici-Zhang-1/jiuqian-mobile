<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月24日
 * @author Zhangcc
 * @version
 * @des
 * 在售板材
 */
class Board extends MY_Controller{
    private $_Module;
	private $_Controller;
	private $_Item;
	private $_Cookie;
	
    public function __construct(){
        parent::__construct();
        $this->load->model('product/board_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        
        log_message('debug', 'Controller Product/Board Start!');
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
        if(!!($Data = $this->board_model->select())){
            $this->Success = '获取板材信息成功!';
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合搜索条件的板材信息';
        }
        $this->_return($Data);
    }
    

    public function read_stock(){
        if(!!($Data = $this->board_model->select_stock())){
            $this->Success = '获取板材信息成功!';
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合搜索条件的板材信息';
        }
        $this->_return($Data);
    }
    
    public function add(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Name = $this->input->post('name');
            $Length = $this->input->post('length');
            $Width = $this->input->post('width');
            $ThickId = $this->input->post('thick_id');
            $Thick = explode('-', $ThickId);
            $NatureId = $this->input->post('nature_id');
            $Nature = explode('-', $NatureId);
            $ClassId = $this->input->post('class_id');
            $Class = explode('-', $ClassId);
            $SupplierId = $this->input->post('supplier_id');
            $Supplier = explode('-', $SupplierId);
            $Remark = $this->input->post('remark');
            $ColorId = $this->input->post('color_id');
            if('' != $Name){/*采用自定义命名方式，只能新增一个*/
                $Color = array_shift($ColorId);
                $ColorId = array($Color);
                unset($Color);
            }
            
            foreach ($ColorId as $key => $value){
                $Color = explode('-', $value);
                $Set[$key] = array(
                    'name' => '' == $Name?(intval($Thick[1]).$Nature[1].$Color[1].$Supplier[1]):$Name,
                    'length' => $Length,
                    'width' => $Width,
                    'thick_id' => $Thick[0],
                    'color_id' => $Color[0],
                    'nature_id' => $Nature[0],
                    'class_id' => $Class[0],
                    'supplier_id' => $Supplier[0],
                    'remark' => $Remark
                );
            }
            $Set = gh_escape($Set);
            if(!!($Id = $this->board_model->insert_ignore_batch($Set))){
                $this->Success .= '板材新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'板材信息新增失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    
    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Name = $this->input->post('name');
            $Length = $this->input->post('length');
            $Width = $this->input->post('width');
            $ThickId = $this->input->post('thick_id');
            $Thick = explode('-', $ThickId);
            $NatureId = $this->input->post('nature_id');
            $Nature = explode('-', $NatureId);
            $ClassId = $this->input->post('class_id');
            $Class = explode('-', $ClassId);
            $SupplierId = $this->input->post('supplier_id');
            $Supplier = explode('-', $SupplierId);
            $Remark = $this->input->post('remark');
            $ColorId = $this->input->post('color_id');
            if(count($ColorId) > 1){
                $Color = array_shift($ColorId);
                $ColorId = array($Color);
                unset($Color);
            }
            
            $Color = explode('-', $ColorId[0]);
            $Set = array(
                'name' => '' == $Name?(intval($Thick[1]).$Nature[1].$Color[1].$Supplier[1]):$Name,
                'length' => $Length,
                'width' => $Width,
                'thick_id' => $Thick[0],
                'color_id' => $Color[0],
                'nature_id' => $Nature[0],
                'class_id' => $Class[0],
                'supplier_id' => $Supplier[0],
                'remark' => $Remark
            );
            $Set = gh_escape($Set);
            $where = $this->input->post('selected');
            if(!!($this->board_model->update($Set, $where))){
                $this->Success .= '板材信息修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'板材信息修改失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    
    /**
     * 批量定义板块的单价
     */
    public function unit_price(){
        $Selected = $this->input->post('selected', true);
        $UnitPrice = $this->input->post('unit_price', true);
        $Selected = trim($Selected);
        $Selected = explode(',', $Selected);
        $UnitPrice = floatval(trim($UnitPrice));
        $Set = array();
        foreach ($Selected as $key => $value){
            $value = intval(trim($value));
            if($value > 0){
                $Set[$key] = array(
                    'bid' => $value,
                    'unit_price' => $UnitPrice
                );
            }
        }
        unset($Selected);
        if(!empty($Set)){
            if(!!($this->board_model->update_batch($Set))){
                $this->Success .= '板材单价修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'板材单价修改失败';
            }
        }else{
            $this->Failue = '请选择要修改单价的板材!';
        }
        $this->_return();
    }
    public function edit_amount(){
        $Selected = $this->input->post('selected', true);
        $Amount = $this->input->post('amount', true);
        $Selected = trim($Selected);
        $Selected = explode(',', $Selected);
        $Amount = intval(trim($Amount));
        $Set = array();
        foreach ($Selected as $key => $value){
            $value = intval(trim($value));
            if($value > 0){
                $Set[$key] = array(
                    'bid' => $value,
                    'amount' => $Amount
                );
            }
        }
        unset($Selected);
        if(!empty($Set)){
            if(!!($this->board_model->update_batch($Set))){
                $this->Success .= '板材板材库存修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'板材板材库存修改失败';
            }
        }else{
            $this->Failue = '请选择要修改板材库存的板材!';
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
                if($this->board_model->delete($Where)){
                    $this->Success .= '板材删除成功, 刷新后生效!';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'板材删除失败';
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
