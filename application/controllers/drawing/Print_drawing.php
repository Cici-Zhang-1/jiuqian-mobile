<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author Zhangcc
 * @version
 * @des
 * 打印图纸
 */
class Print_drawing extends MY_Controller{
    private $_Module = 'drawing';
    private $_Controller;
    private $_Item ;
    public function __construct(){
        parent::__construct();
        $this->load->model('drawing/drawing_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';

        log_message('debug', 'Controller Drawing/Print_drawing Start!');
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

    private function _read(){
        $Type = $this->uri->segment('5', false);
        $Id = $this->input->get('id', true);
        $Id = intval(trim($Id));
        if($Id){
            if('preview' == $Type){
                $this->_read_preview($Id);
            }elseif('print' == $Type){
                $this->_read_print($Id);
            }else{
                $this->_read_single($Id);
            }
        }else{
            show_error('没有内容可以预览');
        }
    }

    /**
     * 查看单个图纸
     * @param unknown $Id
     */
    private function _read_single($Id){
        $Return = array();
        if(!!($Return['PrintDrawing'] = $this->drawing_model->select_drawing_by_did($Id))){
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
    private function _read_preview($Id){
        $Return = array();
        if(!!($Return['PrintDrawing'] = $this->drawing_model->select_by_opid($Id))){
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
    private function _read_print($Id){
        $Return = array();
        if(!!($Return['PrintDrawing'] = $this->drawing_model->select_by_opid($Id))){
            $this->load->view('header2');
            $this->load->view($this->_Item.__FUNCTION__, $Return);
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有图纸可以预览';
            show_error($this->Failue);
        }
    }
}
