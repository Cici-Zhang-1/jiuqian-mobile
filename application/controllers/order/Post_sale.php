<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月19日
 * @author zhangcc
 * @version
 * @des
 * 送装修改
 */
class Post_sale extends MY_Controller {
    private $__Search = array(
        'order_id' => ZERO
    );
    private $_Id; // 订单产品编号
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/PostSale __construct Start!');
        $this->load->model('order/order_model');
        $this->load->model('order/order_product_model');
        $this->load->model('product/product_model');
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['order_id'])) {
            $OrderId = $this->input->get('v', true);
            $this->_Search['order_id'] = intval($OrderId);
        }
        $Data = array();
        if (empty($this->_Search['order_id'])) {
            $this->Code = EXIT_ERROR;
            $this->Message = '请选择需要送装的订单';
        } else {
            if (!($Data['order_info'] = $this->order_model->is_order_post_salable($this->_Search['order_id']))) {
                $this->Code = EXIT_ERROR;
                $this->Message = '订单当前状态不可送装!';
            } else {
                $Data['order_info']['code'] = SERVER_NUM;
            }
        }
        if ($this->Code == EXIT_SUCCESS) {
            $OrderProducts = array();
            if (!!($Query = $this->order_product_model->select_post_sale($Data['order_info']['order_id'], array(SERVER)))) {
                foreach ($Query as $Key => $Value) {
                    if (!isset($OrderProducts[$Value['product_id']])) {
                        $OrderProducts[$Value['product_id']] = array();
                    }
                    array_push($OrderProducts[$Value['product_id']], $Value);
                }
            }
            if (!!($Product = $this->product_model->select(array('undelete' => YES, 'code' => array(SERVER_NUM), 'p' => ONE, 'pn' => ONE, 'pagesize' => ALL_PAGESIZE)))) {
                foreach ($Product['content'] as $Key => $Value) {
                    $Value['remark'] = ''; // 备注清空
                    $Value['set'] = ONE; // 默认设置一套
                    $Data[$Value['code']] = array();
                    $Data[$Value['code']]['product'] = $Value;
                    if (isset($OrderProducts[$Value['v']])) {
                        $Data[$Value['code']]['order_product'] = $OrderProducts[$Value['v']];
                    }
                }
            }
        }
        $this->_ajax_return($Data);
    }
    /*public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['order_product_id'])) {
            $OrderProductId = $this->input->get('v', true);
            $OrderProductId = intval($OrderProductId);
            if (!empty($OrderProductId)) {
                $this->_Search['order_product_id'] = $OrderProductId;
            }
        }
        $Data = array();
        if (empty($this->_Search['order_product_id'])) {
            $this->Code = EXIT_ERROR;
            $this->Message = '请选择需要售后的订单';
        } else {
            if (!($Data['order_info'] = $this->order_product_model->is_order_post_salable($this->_Search['order_product_id']))) {
                $this->Code = EXIT_ERROR;
                $this->Message = '订单当前状态不可售后!';
            }
        }
        if ($this->Code == EXIT_SUCCESS) {
            $OrderProducts = array();
            if (!!($Query = $this->order_product_model->select_post_sale($Data['order_info']['order_id'], array(OTHER, FITTING, SERVER)))) {
                foreach ($Query as $Key => $Value) {
                    if (!isset($OrderProducts[$Value['product_id']])) {
                        $OrderProducts[$Value['product_id']] = array();
                    }
                    array_push($OrderProducts[$Value['product_id']], $Value);
                }
            }
            if (!!($Product = $this->product_model->select(array('undelete' => YES, 'code' => array(OTHER_NUM, FITTING_NUM, SERVER_NUM), 'p' => ONE, 'pn' => ONE, 'pagesize' => ALL_PAGESIZE)))) {
                foreach ($Product['content'] as $Key => $Value) {
                    $Value['remark'] = ''; // 备注清空
                    $Value['set'] = ONE; // 默认设置一套
                    $Data[$Value['code']] = array();
                    $Data[$Value['code']]['product'] = $Value;
                    if (isset($OrderProducts[$Value['v']])) {
                        $Data[$Value['code']]['order_product'] = $OrderProducts[$Value['v']];
                    }
                }
            }
        }
        $this->_ajax_return($Data);
    }*/

    public function add () {
        $Product = $this->input->post('product', true);
        $_POST['product'] = explode(',', $Product);
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $ProductId = $Post['product_id'];
            $OrderId = $Post['order_id'];
            $Set = $Post['set'];
            unset($Post['product_id'], $Post['order_product_id'], $Post['set']);
            $this->load->model('product/product_model');
            $this->load->model('order/order_model');
            if (!($Product = $this->product_model->is_exist($ProductId))) {
                $this->Code = EXIT_ERROR;
                $this->Message .= '产品不存在!';
            } elseif (!($this->order_model->is_order_post_salable($OrderId))) {
                $this->Code = EXIT_ERROR;
                $this->Message .= '订单不存在或者当前状态不能送装!';
            } elseif (!!($Query = $this->order_product_model->insert(array($Product), $OrderId, $Set, $Post))) {
                $this->load->library('workflow/workflow');
                $W = $this->workflow->initialize('order_product');
                foreach ($Query as $key => $value){
                    $Query[$key] = $value['v'];
                }
                $W->initialize($Query);
                if($W->init_post_sale()){
                    $this->Message = '新建送装成功!';
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单送装失败!';
            }
        }
        $this->_ajax_return();
    }
    /**
     * 拆单
     * @param number $this->_Type
     */
    public function edit(){
        $Save = $this->input->post('save', true);
        if (empty($Save)) {
            $Save = 'post_sale';
        }
        if (!!($OrderProduct = $this->_is_post_salable())) {
            $this->load->library('p/p');
            if (!!($P = $this->p->initialize($OrderProduct['code']))) {
                if (!($P->edit($Save, $OrderProduct))) {
                    $this->Code = EXIT_ERROR;
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单产品送装出错!';
                } else {
                    $this->Message = '送装成功处理!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您送装的订单产品类型不存在!';
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单产品不可送装!';
        }
        $this->_ajax_return();
//        $this->_Code = $this->input->post('code', true);
//        $this->_Code = trim($this->_Code);
//        switch($this->_Code){
//            case 'p':
//                $this->load->library('post_sale/p_p');
//                $this->p_p->edit();
//                $this->Failue = $this->p_p->get_failue();
//                $this->_edit_check();
//                break;
//            case 'g':
//                $this->load->library('post_sale/p_g');
//                $this->p_g->edit();
//                $this->Failue = $this->p_g->get_failue();
//                $this->_edit_check();
//                break;
//            case 'f':
//                $this->load->library('post_sale/p_f');
//                $this->p_f->edit();
//                $this->Failue = $this->p_f->get_failue();
//                $this->_edit_check();
//                break;
//            default:
//                $this->Failue .= '您访问的内容不存在';
//        }
//        $this->_return();
    }

    /**
     * 是否可以送装
     * @return bool
     */
    private function _is_post_salable () {
        $this->_Id = $this->input->post('order_product_id', true);
        $this->_Id = intval($this->_Id);
        if (empty($this->_Id)) {
            $this->_Id = $this->input->post('v', true);
            if (is_array($this->_Id)) {
                $this->_Id = array_map('intval', $this->_Id);
            } else {
                $this->_Id = intval($this->_Id);
            }
        }
        if (empty($this->_Id)) {
            $GLOBALS['error'] = '请选择需送装的订单!';
        } else {
            if (!!($OrderProduct = $this->order_product_model->is_order_post_salable($this->_Id))) {
                return $OrderProduct;
            } else {
                $GLOBALS['error'] = '订单还未确认，不能送装!';
            }
        }
        return false;
    }
    
    /**
     * 更新订单后要更新核价
     */
    private function _edit_check(){
        $Oid = $this->input->post('oid', true);
        $this->load->library('d_money');
        $this->load->model('product/product_model');
        
        $Info = $this->d_money->read('detail', $Oid);
        
        if(!!($Product = $this->product_model->select_undelete())){
            $Product = $Product['content'];
            $Opid = array();
            $SumDetail = array(
                    "cabinet" => 0,"wardrobe" => 0,"door" => 0,"kuang" => 0,"fitting" => 0,"other" => 0,"server" => 0);
            foreach ($Product as $key => $value){
                if(!!($Money = $this->d_money->read($value['code'], $Oid, $value['pid']))){
                    foreach ($Money as $ikey => $ivalue){
                        if(empty($Opid[$ivalue['opid']])){
                            $Opid[$ivalue['opid']] = array(
                                'opid' => $ivalue['opid'],
                                'sum' => $ivalue['sum']
                            );
                        }else{
                            $Opid[$ivalue['opid']]['sum'] += $ivalue['sum'];
                        }
                        if('W' == $value['code']){
                            $SumDetail['cabinet'] += $ivalue['sum'];
                        }elseif ('Y' == $value['code']){
                            $SumDetail['wardrobe'] += $ivalue['sum'];
                        }elseif ('M' == $value['code']){
                            $SumDetail['door'] += $ivalue['sum'];
                        }elseif ('K' == $value['code']){
                            $SumDetail['kuang'] += $ivalue['sum'];
                        }elseif ('P' == $value['code']){
                            $SumDetail['fitting'] += $ivalue['sum'];
                        }elseif ('G' == $value['code']){
                            $SumDetail['other'] += $ivalue['sum'];
                        }elseif ('F' == $value['code']){
                            $SumDetail['server'] += $ivalue['sum'];
                        }
                    }
                }
            }
            $this->_edit_order($SumDetail, $Oid);
            $this->_edit_order_product($Opid);
            $this->_edit_dealer($SumDetail, $Info);
        }
    }

    private function _edit_order($SumDetail, $Oid){
        $Order = array(
            'sum' => array_sum($SumDetail),
            'sum_detail' => json_encode($SumDetail)
        );
        $Order['sum'] = ceil($Order['sum']);
    
        $this->load->model('order/order_model');
        $this->order_model->update_order($Order, $Oid);
    
    }
    
    private function _edit_order_product($OrderProduct){
        $this->load->model('order/order_product_model');
        $this->order_product_model->update_batch($OrderProduct);
    }
    
    private function _edit_dealer($SumDetail, $Info){
        $Diff = $Info['sum'] - ceil(array_sum($SumDetail));
        $this->load->model('dealer/dealer_model');
        if($Info['status'] >= 10 && $Info['status'] <= 11){
            $this->dealer_model->update_dealer_debt1_post_sale($Diff, $Info['did'], 'debt1');
        }elseif ($Info['status'] > 11 && $Info['status'] < 21){
            $this->dealer_model->update_dealer_debt1_post_sale($Diff, $Info['did'], 'debt2');
        }
    }

//    private function _redismantle(){
//        $this->_Type = $this->uri->segment(5, 'order');
//        $this->_Type = trim($this->_Type);
//        $this->_Id = $this->input->post('id', true);
//        $this->_Id = trim($this->_Id);
//        $this->_Id = empty($this->_Id)?$this->input->get('id', true):$this->_Id;
//        $this->_Id = trim($this->_Id);
//        if($this->_Id && $this->_Type){
//            $Method = __FUNCTION__.'_'.$this->_Type;
//            if(method_exists(__CLASS__, $Method)){
//                if($this->$Method()){
//                    $this->_Id = array_shift($this->_Id);
//                    $this->redirect_tab(site_url('order/dismantle/index/read/'.$this->_Type.'?id='.$this->_Id));
//                }else{
//                    $this->close_tab($this->Failue);
//                }
//            }else{
//                show_error('您要访问的内容不存在');
//            }
//        }else{
//            show_error('您要重新拆单的订单不存在!');
//        }
//    }
    /**
     * 重新拆单
     */
//    public function redismantle(){
//        $this->_Type = $this->uri->segment(4, 'order');
//        $this->_Type = trim($this->_Type);
//        $this->_Id = $this->input->post('id');
//        if($this->_Id && $this->_Type){
//            $Method = '_'.__FUNCTION__.'_'.$this->_Type;
//            if(method_exists(__CLASS__, $Method)){
//                if($this->$Method()){
//                    $this->Success = '重新拆单成功!';
//                    $this->_return();
//                }else{
//                    $this->close_tab($this->Failue);
//                }
//            }else{
//                show_error('您要访问的内容不存在');
//            }
//        }else{
//            show_error('您要重新拆单的订单不存在!');
//        }
//    }
//    private function _redismantle_order(){
//        if(!is_array($this->_Id)){
//            $this->_Id = array($this->_Id);
//        }
//        foreach ($this->_Id as $key => $value){
//            $value = intval(trim($value));
//            if($value < 0){
//                unset($this->_Id[$key]);
//            }else{
//                $this->_Id[$key] = $value;
//            }
//        }
//        if(!empty($this->_Id)){
//            $this->load->model('order/order_model');
//            if(!!($Return = $this->order_model->is_redismantlable($this->_Id))){
//                $Workflow = array();
//                $Dealer = array();
//                $this->_Id = array();
//                foreach ($Return as $key => $value){
//                    if($value['status'] > 2){
//                        /*如果已经确认拆单，则需要返工*/
//                        $Workflow[] = $value['oid'];
//                    }
//                    if(10 == $value['status']){
//                        /*如果订单已经等待生产，则需要重新发挥经销商的账目信息*/
//                        if(!isset($Dealer[$value['did']])){
//                            $Dealer[$value['did']] = $value['sum'];
//                        }else{
//                            $Dealer[$value['did']] += $value['sum'];
//                        }
//                    }
//                    $this->_Id[] = $value['oid'];
//                }
//                if(!empty($Dealer)){
//                    $this->load->model('dealer/dealer_model');
//                    $this->dealer_model->update_dealer_re($Dealer);
//                }
//                if(!empty($Workflow)){
//                    $this->load->library('workflow/workflow');
//                    if($this->workflow->initialize('order', $Workflow)){
//                        $this->workflow->redismantle();
//                    }
//                }
//                return true;
//            }else{
//                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您需要重新拆单的订单编号不正确, 请确认后再操作!';
//                return false;
//            }
//        }else{
//            $this->Failue = '您需要重新拆单的订单编号不正确, 请确认后再操作!';
//            return false;
//        }
//    }
//    private function _redismantle_order_product(){
//        $this->_Id = intval(trim($this->_Id));
//        if($this->_Id > 0){
//            $this->_Id = array($this->_Id);
//            $this->load->model('order/order_product_model');
//            if(!!($Return = $this->order_product_model->is_redismantlable($this->_Id))){
//                $Workflow = array();
//                $Order = array();
//                $Dealer = array();
//                $this->_Id = array();
//                foreach ($Return as $key => $value){
//                    if($value['status'] > 2){
//                        $Workflow[] = $value['opid'];
//                    }
//                    if(!in_array($value['oid'], $Order)){
//                        if($value['ostatus'] > 2){
//                            /*如果已经确认拆单，则需要返工*/
//                            $Order[] = $value['oid'];
//                        }
//                        if(10 == $value['ostatus']){
//                            if(!isset($Dealer[$value['did']])){
//                                $Dealer[$value['did']] = $value['sum'];
//                            }else{
//                                $Dealer[$value['did']] += $value['sum'];
//                            }
//                        }
//                    }
//                    $this->_Id[] = $value['opid'];
//                }
//                if(!empty($Dealer)){
//                    $this->load->model('dealer/dealer_model');
//                    $this->dealer_model->update_dealer_re($Dealer);
//                }
//                $this->load->library('workflow/workflow');
//                if(!empty($Order)){
//                    $this->load->model('order/order_model');
//                    if($this->workflow->initialize('order', $Order)){
//                        $this->workflow->redismantle();
//                    }
//                }
//                if(!empty($Workflow)){
//                    if($this->workflow->initialize('order_product', $Workflow)){
//                        $this->workflow->redismantle();
//                    }
//                }
//                return true;
//            }else{
//                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您需要重新拆单的订单编号不正确, 请确认后再操作!';
//                return false;
//            }
//        }else{
//            $this->Failue = '您需要重新拆单的订单产品编号不正确, 请确认后再操作!';
//            return false;
//        }
//    }

    /**
     * 清除当前的拆单数据
     */
//    public function remove(){
//        $this->_Id = $this->input->post('id', true);
//        $this->_Id = intval(trim($this->_Id));
//        $this->_Code = $this->input->post('code', true);
//        $this->_Code = trim($this->_Code);
//        $this->load->model('order/order_product_model');
//        if($this->_Id > 0 && $this->order_product_model->is_dismantle_removable($this->_Id)){
//            switch ($this->_Code){
//                case 'w':
//                    $this->load->library('dismantle/d_w');
//                    $this->d_w->remove($this->_Id);
//                    $this->Failue = $this->d_w->get_failue();
//                    break;
//                case 'y':
//                    $this->load->library('dismantle/d_y');
//                    $this->d_y->remove($this->_Id);
//                    $this->Failue = $this->d_y->get_failue();
//                    break;
//                case 'm':
//                    $this->load->library('dismantle/d_m');
//                    $this->d_m->remove($this->_Id);
//                    $this->Failue = $this->d_m->get_failue();
//                    break;
//                case 'k':
//                    $this->load->library('dismantle/d_k');
//                    $this->d_k->remove($this->_Id);
//                    $this->Failue = $this->d_k->get_failue();
//                    break;
//                case 'p':
//                    $this->load->library('dismantle/d_p');
//                    $this->d_p->remove($this->_Id);
//                    $this->Failue = $this->d_p->get_failue();
//                    break;
//                case 'g':
//                    $this->load->library('dismantle/d_g');
//                    $this->d_g->remove($this->_Id);
//                    $this->Failue = $this->d_g->get_failue();
//                    break;
//                case 'f':
//                    $this->load->library('dismantle/d_f');
//                    $this->d_f->remove($this->_Id);
//                    $this->Failue = $this->d_f->get_failue();
//                    break;
//                default:
//                    $this->Failue = '您要清除的订单类型不存在';
//            }
//        }else{
//            $this->Failue = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要清除的订单不存在';
//        }
//        $this->_return();
//    }
}