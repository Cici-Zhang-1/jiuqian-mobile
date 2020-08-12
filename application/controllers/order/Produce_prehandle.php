<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 16/6/1
 * Time: 09:30
 * Des: 生产前的预处理,可以重新分类清单,可以一起导出给优化, 也可以重新划分分类
 */

class Produce_prehandle extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $Search = array(
        'product' => '1,2',
        'status' => '3',
        'keyword' => ''
    );

    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';

        log_message('debug', 'Controller Order/Produce_prehandle Start!');
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
            if(!!($Data = $this->order_product_model->select_produce_prehandle($this->Search))){
                $this->Success = '获取预处理订单成功!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要预处理的订单';
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }

    /**
     * 重新预处理
     */
    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Selected = $this->input->post('selected', true);

            $this->load->model('order/order_product_classify_model');
            foreach ($Selected as $key => $value){
                $Select = intval(trim($value));

                if($Select > 0 && $this->order_product_model->is_produce_rehandlable($Select, $this->Search['status'])
                    && $this->order_product_classify_model->delete_produce_prehandle($Select)
                    && $this->_edit_classify($Select)){
                    continue;
                }else{
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'重新预处理失败!';
                    break;
                }
            }
        }else{
            $this->Failue = validation_errors();
        }
        if(empty($this->Failue)){
            $this->Success = '重新预处理成功!';
        }
        $this->_return();
    }

    private function _edit_classify($Opid){
        $this->load->model('order/order_product_board_plate_model');
        if(!!($Qrcode = $this->order_product_board_plate_model->select_qrcode_by_opid($Opid))){
            $OrderProductClassify = array();
            $Set = array();
            $this->load->model('data/classify_model');
            $this->load->model('order/order_product_classify_model');
            foreach($Qrcode as $key => $value){
                $Classify = $this->_get_classify($value);
                $Classify['board'] = $value['board'];
                $Classify['opid'] = $value['opid'];
                $Key = implode('', $Classify);
                $Classify = gh_escape($Classify);
                if (isset($OrderProductClassify[$Key])) {
                    $OrderProductClassify[$Key]['amount'] += $value['amount'];
                    $OrderProductClassify[$Key]['area'] = bcadd($OrderProductClassify[$Key]['area'], $value['area'], 3);
                    // $OrderProductClassify[$Key]['area'] += $value['area'];
                }else{
                    $OrderProductClassify[$Key] = $Classify;
                    $OrderProductClassify[$Key]['amount'] = $value['amount'];
                    $OrderProductClassify[$Key]['area'] = $value['area'];
                }
                if(!($OrderProductClassify[$Key]['opcid'] = $this->order_product_classify_model->is_existed($Classify))){
                    $OrderProductClassify[$Key]['opcid'] = $this->order_product_classify_model->insert($Classify);
                }
                $Set[] = array(
                    'opbpid' => $value['opbpid'],
                    'opcid' => $OrderProductClassify[$Key]['opcid']
                );
            }
            unset($Qrcode, $Classify);
            if(!empty($Set)){
                if(!!($this->order_product_board_plate_model->update_batch($Set))
                    && !!($this->order_product_classify_model->update_batch($OrderProductClassify))){
                    return true;
                }else{
                    $this->Failue = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'生成板块编号时失败';
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 区分板块
     * @param unknown $Data
     */
    private function _get_classify($Data){
        if(empty($this->_Classify)){
            $this->_Classify = $this->classify_model->select_children();
        }
        $Flag = true;
        $Return = array(
            'classify_id' => 0,
            'optimize' => 0,
            'status' => 0
        );
        $Parent = 1;
        $Optimize = 0;
        $Process = '1,2,3,7,6';
        if($this->_Classify){
            foreach ($this->_Classify as $key => $value){
                if($value['plate_name'] != '' && $value['plate_name'] != $Data['plate_name']){
                    $Flag = false;
                }
                if($value['width_min'] < $value['width_max'] && $value['length_min'] < $value['length_max']){   /*Length + Width*/
                    if(!(($Data['width'] >= $value['width_min'] && $Data['width'] < $value['width_max']) ||
                        ($Data['length'] >= $value['length_min'] && $Data['length'] < $value['length_max']))){
                        $Flag = false;
                    }
                }elseif ($value['width_min'] < $value['width_max'] && $value['length_min'] == $value['length_max']){    /*Width*/
                    if(!($Data['width'] >= $value['width_min'] && $Data['width'] < $value['width_max'])){
                        $Flag = false;
                    }
                }elseif ($value['width_min'] == $value['width_max'] && $value['length_min'] < $value['length_max']){    /*Length*/
                    if(!($Data['length'] >= $value['length_min'] && $Data['length'] < $value['length_max'])){
                        $Flag = false;
                    }
                }

                if($value['thick'] != 0 && $value['thick'] != $Data['thick']){
                    $Flag = false;
                }
                if($value['edge'] != '' && $value['edge'] != $Data['edge']){
                    $Flag = false;
                }
                if($value['slot'] != '' && $value['slot'] != $Data['slot']){
                    $Flag = false;
                }
                if($value['remark'] != '' && !(preg_match('/'.$value['remark'].'/', $Data['remark']))){
                    $Flag = false;
                }
                if(true == $Flag){
                    $Parent = $value['parent'];
                    $Process = $value['process'];
                    $Optimize = $value['flag'];
                    break;
                }else{
                    $Flag = true;
                }
            }
        }
        $Return['classify_id'] = $Parent;
        $Return['optimize'] = $Optimize;
        $Process = explode(',', $Process);
        $Return['status'] = array_shift($Process);
        return $Return;
    }
}
