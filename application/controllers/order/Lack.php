<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月6日
 * @author Zhangcc
 * @version
 * @des
 * 差板件查询
 */

class Lack extends CWDMS_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $Search = array(
        'status' => '4,5',
        'sstatus' => '1',
        'keyword' => ''
    );
    
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_board_plate_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
        
        log_message('debug', 'Controller order/Lack Start!');
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
        $Cookie = $this->_Cookie.__FUNCTION__;
        $Data = array();
        if(!empty($this->Search)){
            $Data = array();
            if(!!($Data['content'] = $this->order_product_board_plate_model->select_scan_lack($this->Search))){
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要拆单的订单';;
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }
    
    public function edit($Type){
        $Func = '_edit_'.$Type;
        if(method_exists(__CLASS__, $Func)){
            $this->$Func();
        }else{
            $this->Failue = '您访问的内容不存在';
        }
        $this->_return();
    }
    
    private function _edit_flag(){
        $Item = $this->_Module.'/'.strtolower(__CLASS__);
        $Run = $Item.'/'.__FUNCTION__;
        $Flag = $this->input->post('flag', true);
        $Flag = trim($Flag);
        $Where = $this->input->post('selected', true);
        $Where = intval($Where);
        if(false != $Where){
            if(strlen($Flag) <= 64){
                if(!!($OldFlag = $this->pack_model->select_order_product_flag($Where))){
                    $OldFlag = json_decode($OldFlag, true);
                }else{
                    $OldFlag = array();
                }
                $OldFlag[$this->_Module.'_'.__CLASS__] = $Flag;
                $NewFlag = json_encode($OldFlag);
                if(!!($this->pack_model->update_order_product_flag($NewFlag, $Where))){
                    $this->Success .= '标记信息修改成功, 刷新后生效!';
                    $this->load->helper('file');
                    delete_cache_files('(.*pack_lack.*)');
                }else{
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'标记信息修改失败&nbsp;&nbsp;';
                }
            }else{
                $this->Failue .= '不好意思, 每次标记不能超过64个字符';
            }
        }else{
            $this->Failue .= '您要标记的订单不存在!';
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