<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月18日
 * @author Zhangcc
 * @version
 * @des
 */
class Pack_label extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    private $_Cookie;
    private $_Type;
    private $_Id;
    private $_Code;

    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';
        
        log_message('debug', 'Controller Order/Pack_label Start !');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view('header2');
            $this->load->view($Item, $Data);
        }
    }

    /**
     * 读取订单的相关信息
     */
    public function read(){
        $Year = $this->input->get('year', true);
        $Month = $this->input->get('month', true);
        $Prefix = $this->input->get('prefix', true);
        $Middle = $this->input->get('middle', true);
        $Code = $this->input->get('code', true); /*产品类型*/
        $Type = $this->input->get('type', true); /*正常单、增补单*/
        $Year = intval(trim($Year));
        $Month = intval(trim($Month));
        $Code = strtoupper(trim($Code));
        $Type = strtoupper(trim($Type));
        
        $Data = array();
        if(preg_match('/^[\d]{4,4}$/', $Year) && preg_match('/^[\d]{1,2}$/', $Month)
            && preg_match('/^[X]|[B]$/', $Type) && preg_match('/^[\d]{1,4}$/', $Prefix)
            && preg_match('/^[\d]{1,10}$/', $Middle)){
            $OrderProductNum = sprintf('%s%d%02d%04d-%s%d',$Type,$Year,$Month,$Prefix,$Code,$Middle);
            if(!!($Data = $this->order_product_model->is_packable($OrderProductNum))){
                $Tmp = json_decode($Data['pack_detail'], true);
                if(is_array($Tmp)){
                    $Data = array_merge($Data, $Tmp);
                }
                if('W' == $Code || 'Y' == $Code){
                    $Data['unscaned'] = $this->_read_unscaned($Data['opid']);
                    $OrderProductBrothers = sprintf('%s%d%02d%04d-%s',$Type,$Year,$Month,$Prefix,$Code);
                    $Data['brothers'] = $this->_read_brothers($OrderProductBrothers, $OrderProductNum);
                }
                $this->Success = '成功获得要打印标签的订单';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要打印的清单';
            }
        }else{
            $this->Failue = '您提供的信息不正确';
        }
        $this->_return($Data);
    }

    /**
     * 获得未扫描的板块的信息
     */
    private function _read_unscaned($Opid){
        $this->load->model('order/order_product_board_plate_model');
        if(!!($Unscaned = $this->order_product_board_plate_model->select_scan_lack(array(),$Opid))){
            return $Unscaned;
        }
        return false;
    }


    /**
     * 获取同一大订单编号下大小订单
     */
    private function _read_brothers($OrderProductBrothers, $OrderProductNum){
        if(!!($Data = $this->order_product_model->select_brothers($OrderProductBrothers, $OrderProductNum))){
            foreach ($Data as $key => $value){
                $Tmp = json_decode($value['pack_detail'], true);
                if (is_array($Tmp)) {
                    $Data[$key] = array_merge($Data[$key], $Tmp);
                 }
            }
            return $Data;
        }
        return false;
    }
    
    private function _print(){
        $OrderProductNum = $this->input->get('order_product_num', true);
        $Pack = $this->input->get('pack', true);   /*获得包装件数*/
        $Classify = $this->input->get('classify', true);    /*获得包装类型*/
        if(empty($Classify)){
            $Classify = 'other';
        }
//        $Together = $this->input->get('together', true);    /*获得包装类型*/
//        $Together = intval(trim($Together));
//        if(1 !== $Together){
//            $Together = 0;
//        }
        $Brothers = $this->input->get('brothers', true);
        $Brothers = trim($Brothers);

        $OrderProductNum = trim($OrderProductNum);
        $Data = array();
        $Data['Pack'] = intval(trim($Pack)); /*包装件数*/
        $Length = ORDER_PREFIX + ORDER_SUFFIX;
        if(preg_match("/^(X|B)[\d]{{$Length},{$Length}}\-[A-Z][\d]{1,10}$/", $OrderProductNum) && $Data['Pack'] > 0){
            $Item = $this->_Item.__FUNCTION__;
            if(!!($Data['Data'] = $this->order_product_model->is_packable($OrderProductNum))){
                $Tmp = json_decode($Data['Data']['pack_detail'], true);
                $Cookie = $this->_Cookie.'pack_detail';
                if(is_array($Tmp)){
                    $Data['Data'] = array_merge($Data['Data'], $Tmp);
                    $this->input->set_cookie(array('name' => $Cookie, 'value' => $Data['Data']['pack_detail'], 'expire' => HOURS));
                }else{
        	        $this->load->helper('cookie');
                    delete_cookie($Cookie);
                }
                $Cookie = $this->_Cookie.'classify';
                $this->input->set_cookie(array('name' => $Cookie, 'value' => $Classify, 'expire' => HOURS));
                $Cookie = $this->_Cookie.'brothers';
                $this->input->set_cookie(array('name' => $Cookie, 'value' => $Brothers, 'expire' => HOURS));
                $this->load->view('header2');
                $this->load->view($Item,$Data);
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要打印的清单';
                gh_alert_back($this->Failue);
            }
        }else{
            gh_alert_back('您要打印的标签内容不存在');
        }
    }
    
    public function edit(){
        $Id = $this->input->post('id', true);
        $Id = intval(trim($Id));
        $Pack = $this->input->post('pack', true);
        $Pack = intval(trim($Pack));
        $this->load->helper('cookie');
        $Cookie = $this->_Cookie.'classify';
        $Classify = $this->input->cookie($Cookie);
        $Cookie = $this->_Cookie.'brothers';
        $Brothers = $this->input->cookie($Cookie);
        $Brothers = trim($Brothers);
        if(!empty($Brothers)){
            $Together = true;       /*说明是合包*/
        }else{
            $Together = false;      /*没有合包*/
        }
        $Cookie = $this->_Cookie.'pack_detail';
        $PackDetail = $this->input->cookie($Cookie); 
        $PackDetail = json_decode($PackDetail, true);
        unset($Cookie);
        
        if('both' == $Classify){   /*如果选择两个一起包, 则重置原先的*/
            unset($PackDetail);
        }

//        if(1 === $Together){            /*当是合包的时候记录为-1*/
//            $PackDetail[$Classify] = -1;
//        }else{
//            $PackDetail[$Classify] = $Pack; /*当是非合包的时候正常记录*/
//        }
        $PackDetail[$Classify] = $Pack;

        if(in_array(0, $PackDetail)){
            $Keep = true;
        }else{
            $Keep = false;
        }
        if($Id > 0){
            $Sum = $PackDetail;
            foreach (array_keys($Sum, -1) as $ivalue){
                unset($Sum[$ivalue]);
            }
            $Set = array(
                'pack' => array_sum($Sum),
                'pack_detail' => json_encode($PackDetail),
                'packer' => $this->session->userdata('uid'),
                'pack_datetime' => date('Y-m-d H:i:s')
            );
            unset($Sum, $PackDetail);

            if(!!($this->order_product_model->update($Set, $Id))){
                $this->load->library('workflow/workflow');
                if($this->workflow->initialize('order_product', $Id)){
                    if('both' == $Classify){
                        $GLOBALS['workflow_msg'] = '打包所有, 入库'.$Pack.'件, 总件数'.$Set['pack'].'件';
                    }elseif ('thick' == $Classify){
                        $GLOBALS['workflow_msg'] = '打包厚板, 入库'.$Pack.'件, 总件数'.$Set['pack'].'件';
                    }elseif ('thin' == $Classify){
                        $GLOBALS['workflow_msg'] = '打包薄板, 入库'.$Pack.'件, 总件数'.$Set['pack'].'件';
                    }else{
                        $GLOBALS['workflow_msg'] = '打包入库'.$Pack.'件, 总件数'.$Set['pack'].'件';
                    }
                    
                    if($Together){
                        $GLOBALS['workflow_msg'] .= ', 这次为合包, 且打印包标签';
                    }

                    if($Keep){
                        $this->workflow->pack();
                    }else{
                        $this->workflow->packed();
                    }

                    if ($Together){
                        $this->_edit_brothers($Brothers, $Classify, $Pack);
                    }
                    $this->Success = '包装标签打印保存成功!';
                }else{
                    $this->Failue = $this->workflow->get_failue();
                }
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要打印的清单';
            }
        }else{
            $this->Failue = '您要保存的包装标签不正确, 请检查!';
        }
        $this->_return();
    }

    /**
     * 当出现合包时,兄弟类的都一起处理了
     * @param $Brothers
     * @param $Classify
     * @param $Pack
     * @return bool
     */
    private function _edit_brothers($Brothers, $Classify, $Pack){
        if(!is_array($Brothers)){
            $Brothers = explode(',', $Brothers);
        }
        if(!!($PackDetails = $this->order_product_model->select_pack_detail_by_opids($Brothers))){
            foreach ($PackDetails as $key => $value){
                $PackDetail = json_decode($value['pack_detail'], true);
                $PackDetail[$Classify] = -1;
                if(in_array(0, $PackDetail)){
                    $Keep = true;
                }else{
                    $Keep = false;
                }
                $Sum = $PackDetail;
                foreach (array_keys($Sum, -1) as $ivalue){
                    unset($Sum[$ivalue]);
                }
                $Set = array(
                    'pack' => array_sum($Sum),
                    'pack_detail' => json_encode($PackDetail),
                    'packer' => $this->session->userdata('uid'),
                    'pack_datetime' => date('Y-m-d H:i:s')
                );
                unset($Sum, $PackDetail);

                if(!!($this->order_product_model->update($Set, $value['opid']))){
                    $this->load->library('workflow/workflow');
                    if($this->workflow->initialize('order_product', $value['opid'])){
                        if('both' == $Classify){
                            $GLOBALS['workflow_msg'] = '打包所有, 入库'.$Pack.'件, 总件数'.$Set['pack'].'件';
                        }elseif ('thick' == $Classify){
                            $GLOBALS['workflow_msg'] = '打包厚板, 入库'.$Pack.'件, 总件数'.$Set['pack'].'件';
                        }elseif ('thin' == $Classify){
                            $GLOBALS['workflow_msg'] = '打包薄板, 入库'.$Pack.'件, 总件数'.$Set['pack'].'件';
                        }else{
                            $GLOBALS['workflow_msg'] = '打包入库'.$Pack.'件, 总件数'.$Set['pack'].'件';
                        }

                        $GLOBALS['workflow_msg'] .= ', 这次为合包, 未打印包标签';

                        if($Keep){
                            $this->workflow->pack();
                        }else{
                            $this->workflow->packed();
                        }
                    }
                }
            }
        }
        return true;
    }
}
