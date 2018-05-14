<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 2017/10/14
 * Time: 08:54
 *
 * Desc: 库位与订单对应
 */

class Position_order_product extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;
    private $_In = 1;
    private $_Out = 0;
    private $_Empty = 0;
    private $_Cookie;
    private $Search = array(
        'id' => '',
        'keyword' => ''
    );

    private $_PositionStatus = false;
    private $_Pid = false;
    public function __construct(){
        parent::__construct();
        $this->load->model('position/position_order_product_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

        log_message('debug', 'Controller Position/Position_order_product Start!');
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
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        if($Id){
            $Data['id'] = $Id;
            $this->load->view($this->_Item.__FUNCTION__, $Data);
        }else{
            show_error('您要访问的库位历史不存在!');
        }
    }

    public function read(){
        $Cookie = $this->_Cookie.__FUNCTION__;
        $this->Search = $this->get_page_conditions($Cookie, $this->Search);
        $Data = array();
        if(!empty($this->Search)){
            if(!!($Data = $this->position_order_product_model->select($this->Search))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的库位订单历史';;
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }

    public function add(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            $Post['status'] = $this->_In;
            if(!!($Id = $this->position_order_product_model->insert($Post)) && !!($this->_edit_position())){
                $this->Success .= '订单入库位成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单入库位失败';
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
            if ($Post['status'] == $this->_In) {
                $Post['destroy'] = 0;
                $Post['destroy_datetime'] = NULL;
            }else {
                $Post['destroy'] = $this->session->userdata('uid');
                $Post['destroy_datetime'] = date('Y-m-d H:i:s');
            }
            $Where = $this->input->post('selected');
            if(!!($this->position_order_product_model->update($Post, $Where))){
                $this->Success .= '订单库位修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单库位修改失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }

    /**
     * 出库
     */
    public function edit_out() {
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            $Post['status'] = $this->_Out;
            $Post['destroy'] = $this->session->userdata('uid');
            $Post['destroy_datetime'] = date('Y-m-d H:i:s');
            $Where = $this->input->post('selected');
            if(!!($this->position_order_product_model->update($Post, $Where))){
                $Query = $this->position_order_product_model->select_pid_by_popid($Where);
                if (!!$Query) {
                    $Pids = array();
                    foreach ($Query as $key => $value) {
                        $Pids[] = $value['pid'];
                    }
                    $Unfull = array();
                    if (!!($Query = $this->position_order_product_model->select_unfull_pid($Pids))) {
                        foreach ($Query as $key=> $value){
                            $Unfull[] = $value['pid'];
                        }
                    }
                    $Empty = array_diff($Pids, $Unfull);
                    if (is_array($Empty) && count($Empty) > 0) {
                        $this->_Pid = $Empty;
                        $this->_PositionStatus = $this->_Empty;
                        $this->_edit_position();
                    }
                }
                $this->Success .= '订单库位出库成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单库位出库失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }

    /**
     * @return bool
     * 修改库位状态
     */
    private function _edit_position() {
        $this->load->model('position/position_model');
        if (false === $this->_PositionStatus) {
            $Post['status'] = $_POST['status'];
        }else {
            $Post['status'] = $this->_PositionStatus;
        }

        if (false === $this->_Pid) {
            $Where = $_POST['selected'];
        }else {
            $Where = $this->_Pid;
        }

        if(!!($this->position_model->update_position($Post, $Where))){
            return true;
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'库位状态修改失败';
        }
        return false;
    }
    /**
     * 删除
     */
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false){
                $Query = $this->position_order_product_model->select_pid_by_popid($Where);
                if(!!($this->position_order_product_model->delete($Where))){
                    if (!!$Query) {
                        $Pids = array();
                        foreach ($Query as $key => $value) {
                            $Pids[] = $value['pid'];
                        }
                        $Unfull = array();
                        if (!!($Query = $this->position_order_product_model->select_unfull_pid($Pids))) {
                            foreach ($Query as $key=> $value){
                                $Unfull[] = $value['pid'];
                            }
                        }
                        $Empty = array_diff($Pids, $Unfull);
                        if (is_array($Empty) && count($Empty) > 0) {
                            $this->_Pid = $Empty;
                            $this->_PositionStatus = $this->_Empty;
                            $this->_edit_position();
                        }
                    }


                    $this->Success .= '订单库位删除成功, 刷新后生效!';
                }else{
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单库位删除失败';
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
