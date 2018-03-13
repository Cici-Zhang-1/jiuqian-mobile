<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Administrator
 * @version
 * @des
 */
class Wait_asure extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item ;
    private $_Cookie;
    private $_Classify;
    
    private $Search = array(
        'status' => '10',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
        
        log_message('debug', 'Controller Order/Wait_asure Start!');
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
            if(!!($Data = $this->order_model->select($this->Search, $this->_Item.__FUNCTION__))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要报价的订单';
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }


    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Selected = $this->input->post('selected', true);
            $RequestOutdate = $this->input->post('request_outdate', TRUE);
            
            $Selected = explode(',', $Selected);
            foreach ($Selected as $key => $value){
                $Selected = intval(trim($value));
            }
            if(!!($Query = $this->order_model->is_asurable($Selected))){
                $Workflow = array();
                foreach ($Query as $key => $value){
                    $Workflow[] = $value['oid'];
                }
                $RequestOutdate = gh_escape($RequestOutdate);
                $this->load->library('workflow/workflow');
                if($this->order_model->update_order(array('request_outdate' => $RequestOutdate), $Workflow)
                    && $this->_edit_pack_detail($Workflow)
                    && $this->_edit_qrcode($Workflow)
                    && $this->_edit_classify($Workflow)
                    && $this->workflow->initialize('order', $Workflow)){
                    $this->workflow->produce();
                }else{
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:$this->workflow->get_failue();
                }
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要报价确认的订单';
            }
        }else{
            $this->Failue = validation_errors();
        }
        $this->_return();
    }

    /**
     * 更新订单板块的条形码
     */
    private function _edit_qrcode($Oids){
        $Count = array();
        $Set = array();
        $Bd = array();
        $this->load->model('order/order_product_board_plate_model');
        if(!!($Qrcode = $this->order_product_board_plate_model->select_qrcode($Oids))){
            foreach($Qrcode as $key => $value){
                if(empty($value['cubicle_num'])){
                    //$value['cubicle_num'] = '00';
                    $value['cubicle_num'] = 0;
                }
                if(0 == $value['bd']){
                    /*不是BD文件*/
                    if(!isset($Count[$value['opid']])){
                        $Count[$value['opid']] = 1;
                    }else{
                        $Count[$value['opid']]++;
                    }
                    $No = sprintf('%0'.QRCODE_SUFFIX.'d', $Count[$value['opid']]);
                    $Set[] = array(
                        'opbpid' => $value['opbpid'],
                        'qrcode' => $value['order_product_num'].'-'.$value['cubicle_num'].$No,
                        'plate_num' => $No,
                        'cubicle_num' => $value['cubicle_num']
                    );
                }else{
                    /*BD文件*/
                    /*BD文件上传后的手工板块的后缀命名不包含柜号,从1开始*/
                    /*已经有Qrcode的则不需要改动*/
                    if(empty($value['qrcode'])){
                        /*BD文件上传之后手动添加新的板块*/
                        $Bd[] = array(
                            'opbpid' => $value['opbpid'],
                            'qrcode' => $value['order_product_num'] . '-',
                            //'qrcode' => $value['order_product_num'] . '-' . $value['cubicle_num'],
			                'opid' => $value['opid'],
                            //'qrcode' => $value['order_product_num'].'-'.$value['cubicle_num'],
                            'plate_num' => 0,
                            'cubicle_num' => $value['cubicle_num']
                        );
                        if(!isset($Count[$value['opid']])){
                            $Count[$value['opid']] = 1;
                        }
                    }else{
                        if(!isset($Count[$value['opid']])){
                            $Count[$value['opid']] = 1;
                        }
                        $Tmp = explode('-', $value['qrcode']);
                        $Last = array_pop($Tmp);
                        if(QRCODE_SUFFIX == strlen($Last)){
                            if(intval($Last) > $Count[$value['opid']]){
                                $Count[$value['opid']] = intval($Last) + 1;
                            }
                        }
                    }
                }
            }
            if(!empty($Bd)){
                foreach ($Bd as $key => $value){
                    $No = sprintf('%0'.QRCODE_SUFFIX.'d', $Count[$value['opid']]);
                    $value['qrcode'] = $value['qrcode'].$No;
                    $value['plate_num'] = $No;
                    $Set[] = $value;
                    $Count[$value['opid']]++;
                }
                unset($Bd);
            }
            if(!empty($Set)){
                if(!!($this->order_product_board_plate_model->update_batch($Set))){
                    return true;
                }else{
                    $this->Failue = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'生成板块编号时失败';
                    return false;
                }
            }
        }
        return true;
    }
    
    private function _edit_pack_detail($Oids){
        $this->load->model('order/order_product_board_model');
        if(!!($Board = $this->order_product_board_model->select_by_oid($Oids))){
            $Set = array();
            foreach ($Board as $key => $value){
                $Thick = intval($value['board']);
                if(5 == $Thick || 9 == $Thick){
                    $Type = 'thin';
                }else{
                    $Type = 'thick';
                }
                if('Y' == $value['code'] || 'W' == $value['code']){
                    if(!isset($Set[$value['opid']][$Type])){
                        $Set[$value['opid']][$Type] = 0;
                    }
                }else{
                    $Type = 'other';
                    if(!isset($Set[$value['opid']])){
                        $Set[$value['opid']][$Type] = 0;
                    }
                }
            }
            unset($Board);
            if(!empty($Set)){
                $Update = array();
                foreach ($Set as $key => $value){
                    $Update[] = array(
                        'opid' => $key,
                        'pack_detail' => json_encode($value)
                    );
                }
                unset($Set);
                $this->load->model('order/order_product_model');
                if(!!($this->order_product_model->update_batch($Update))){
                    return true;
                }else{
                    $this->Failue = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'生成包装初始信息时失败';
                    return false;
                }
            }
        }
        return true;
    }
    /**
     * 板块分类
     * @param unknown $Oids
     */
    private function _edit_classify($Oids){
        $this->load->model('order/order_product_board_plate_model');
        if(!!($Qrcode = $this->order_product_board_plate_model->select_qrcode($Oids))){
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
                    $OrderProductClassify[$Key]['area'] += $value['area'];
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

    /**
     * 废止
     */
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false && is_array($Where) && count($Where) > 0){
                $this->workflow->action($Where, 'order', 2);
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
