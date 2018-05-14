<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月14日
 * @author Zhangcc
 * @version
 * @des
 */
class Drawing extends MY_Controller{
    private $_Module = 'drawing';
    private $_Controller;
    private $_Item ;
    private $_Cookie;
    private $Search = array(
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('drawing/drawing_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
        
        log_message('debug', 'Controller drawing/Drawing Start!');
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
        $this->Search = $this->get_page_conditions($Cookie, $this->Search);
        $Data = array();
        if(!empty($this->Search)){
            if(!!($Data = $this->drawing_model->select_drawing($this->Search))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要核价的订单';
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的图纸!';
        }
        $this->_return($Data);
    }
    
    private function _preview(){
        $Type = $this->uri->segment('5', false);
        $Id = $this->input->get('id', true);
        $Id = intval(trim($Id));
        if($Id){
            if('list' == $Type){
                $this->_preview_list($Id);
            }elseif('print' == $Type){
                $this->_preview_print($Id);
            }else{
                $this->_preview_single($Id);
            }
        }else{
            show_error('没有内容可以预览');
        }
    }
    
    /**
     * 查看单个图纸
     * @param unknown $Id
     */
    private function _preview_single($Id){
        $Return = array();
        if(!!($Return['Drawing'] = $this->drawing_model->select_drawing_by_did($Id))){
            $this->load->view('header2');
            $this->load->view($this->_Item.__FUNCTION__, $Return);
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有图纸可以预览';
            show_error($this->Failue);
        }
    }
    
    /**
     * 在查看清单的时候查看图纸，不需要打印
     * @param unknown $Id
     */
    private function _preview_list($Id){
        $Return = array();
        if(!!($Return['Drawing'] = $this->drawing_model->select_by_opid($Id))){
            $this->load->view('header2');
            $this->load->view($this->_Item.__FUNCTION__, $Return);
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有图纸可以预览';
            show_error($this->Failue);
        }
    }
    
    /**
     * 在打印的时候查看图纸
     * @param unknown $Id
     */
    private function _preview_print($Id){
        $Return = array();
        if(!!($Return['Drawing'] = $this->drawing_model->select_by_opid($Id))){
            $this->load->view('header2');
            $this->load->view($this->_Item.__FUNCTION__, $Return);
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有图纸可以预览';
            show_error($this->Failue);
        }
    }

    /**
     * 删除
     */
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false && is_array($Where) && count($Where) > 0){
                $this->drawing_model->delete_drawing($Where);
                $this->Success = '作废成功';
            }else{
                $this->Failue .= '没有可作废项!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
