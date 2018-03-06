<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月19日
 * @author zhangcc
 * @version
 * @des
 * 售后修改
 */
class Post_sale extends CWDMS_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;

    private $_Id;
    private $_Type;

    private $_Save;
    /**
     * 选中的订单产品信息
     * @var ArrayAccess
     */
    private $_Select = array('opid' => 0, 'product' => '', 'code' => '', 'remark' => '');
    private $_EditParam;
    private $_Code;
    public function __construct(){
        parent::__construct();
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

        log_message('debug', 'Controller Order/Dismantle __construct Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view($Item, $$Data);
        }
    }

    private function _read(){
        if(empty($this->_Id)){
            $Id = $this->input->get('id');
            $this->_Id = intval(trim($Id));
        }
        if($this->_Id){
            if(!!($this->_is_post_salable())){
                $Data = array();
                $Data['Id'] = $this->_Id;
                $this->load->library('d_order');
                if(!!($Data['Info'] = $this->d_order->read('detail', $this->_Id))){
                    $Data['Product'] = $this->_read_product();
                    $Data['Select'] = '' == $this->_Select['code']?'w':strtolower($this->_Select['code']);
                }else{
                    $this->Failue = $this->d_order->get_failue();
                }
            }
        }else{
            $this->Failue = '请选择您要拆单的订单!';
            show_error('您要访问的订单不存在!');
        }
        if(empty($this->Failue)){
            $this->load->view($this->_Item.__FUNCTION__, $Data);
        }else{
            $this->close_tab($this->Failue);
        }
    }

    private function _is_post_salable(){
        $this->load->model('order/order_product_model');
        if(!!($this->_Select = $this->order_product_model->is_post_salable(array($this->_Id)))){
            $this->_Select = array_shift($this->_Select);
            $this->_Id = $this->_Select['oid'];
            return true;
        }else{
            $this->Failue = '您要拆的订单产品不存在或者已经拆了..';
        }
        return false;
    }

    /**
     * 按产品分类载入不同页面
     */
    private function _read_product(){
        $Return = false;
        $this->load->model('product/product_model');
        $this->load->model('order/order_product_model');
        if(!!($Product = $this->product_model->select_post_salable())){
            $Product = $Product['content'];
            if(!!($OrderProductQuery = $this->order_product_model->select_by_oid($this->_Id))){
                $Dismantle = array();
                $Dismantled = array();
                foreach ($OrderProductQuery  as $key=>$value){
                    /**
                     * 按同一产品类型下的订单产品分类，同时分未已拆单和未拆单(不包含已经删除的订单产品)
                     */
                    if(1 == $value['status'] || 2 == $value['status']){
                        if(empty($Dismantle[$value['pid']])){
                            $Dismantle[$value['pid']] = array($value);
                        }else{
                            array_push($Dismantle[$value['pid']], $value);
                        }
                    }else{
                        if(empty($Dismantled[$value['pid']])){
                            $Dismantled[$value['pid']] = array($value);
                        }else{
                            array_push($Dismantled[$value['pid']], $value);
                        }
                    }
                }
                $Data = array_merge(array('OrderProductQuery'=> $OrderProductQuery), $this->_Select);
            }else{
                $Data = $this->_Select;
            }
            /**
             * 按产品类型载入对应页面
             */
            foreach ($Product as $key => $value){
                $Return[$key] = array(
                    'Pid' => $value['pid'],
                    'Name' => $value['name'],
                    'Code' => strtolower($value['code']),
                    'Dismantle' => isset($Dismantle[$value['pid']])?$Dismantle[$value['pid']]:0,
                    'Dismantled' => isset($Dismantled[$value['pid']])?$Dismantled[$value['pid']]:0
                );
                $Data = array_merge($Data, $Return[$key]);
                $Return[$key]['content'] = $this->load->view($this->_Item.strtolower($value['code']), $Data, TRUE);
                if('' == $this->_Select['code'] && isset($Dismantle[$value['pid']])){
                    $this->_Select['code'] = $value['code'];
                }
            }
            return $Return;
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要访问的订单产品不存在!';
            return false;
        }
    }

    /**
     * 拆单
     * @param number $this->_Type
     */
    public function edit(){
        $this->_Code = $this->input->post('code', true);
        $this->_Code = trim($this->_Code);
        switch($this->_Code){
            case 'p':
                $this->load->library('post_sale/p_p');
                $this->p_p->edit();
                $this->Failue = $this->p_p->get_failue();
                $this->_edit_check();
                break;
            case 'g':
                $this->load->library('post_sale/p_g');
                $this->p_g->edit();
                $this->Failue = $this->p_g->get_failue();
                $this->_edit_check();
                break;
            case 'f':
                $this->load->library('post_sale/p_f');
                $this->p_f->edit();
                $this->Failue = $this->p_f->get_failue();
                $this->_edit_check();
                break;
            default:
                $this->Failue .= '您访问的内容不存在';
        }
        $this->_return();
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

    private function _redismantle(){
        $this->_Type = $this->uri->segment(5, 'order');
        $this->_Type = trim($this->_Type);
        $this->_Id = $this->input->post('id', true);
        $this->_Id = trim($this->_Id);
        $this->_Id = empty($this->_Id)?$this->input->get('id', true):$this->_Id;
        $this->_Id = trim($this->_Id);
        if($this->_Id && $this->_Type){
            $Method = __FUNCTION__.'_'.$this->_Type;
            if(method_exists(__CLASS__, $Method)){
                if($this->$Method()){
                    $this->_Id = array_shift($this->_Id);
                    $this->redirect_tab(site_url('order/dismantle/index/read/'.$this->_Type.'?id='.$this->_Id));
                }else{
                    $this->close_tab($this->Failue);
                }
            }else{
                show_error('您要访问的内容不存在');
            }
        }else{
            show_error('您要重新拆单的订单不存在!');
        }
    }
    /**
     * 重新拆单
     */
    public function redismantle(){
        $this->_Type = $this->uri->segment(4, 'order');
        $this->_Type = trim($this->_Type);
        $this->_Id = $this->input->post('id');
        if($this->_Id && $this->_Type){
            $Method = '_'.__FUNCTION__.'_'.$this->_Type;
            if(method_exists(__CLASS__, $Method)){
                if($this->$Method()){
                    $this->Success = '重新拆单成功!';
                    $this->_return();
                }else{
                    $this->close_tab($this->Failue);
                }
            }else{
                show_error('您要访问的内容不存在');
            }
        }else{
            show_error('您要重新拆单的订单不存在!');
        }
    }
    private function _redismantle_order(){
        if(!is_array($this->_Id)){
            $this->_Id = array($this->_Id);
        }
        foreach ($this->_Id as $key => $value){
            $value = intval(trim($value));
            if($value < 0){
                unset($this->_Id[$key]);
            }else{
                $this->_Id[$key] = $value;
            }
        }
        if(!empty($this->_Id)){
            $this->load->model('order/order_model');
            if(!!($Return = $this->order_model->is_redismantlable($this->_Id))){
                $Workflow = array();
                $Dealer = array();
                $this->_Id = array();
                foreach ($Return as $key => $value){
                    if($value['status'] > 2){
                        /*如果已经确认拆单，则需要返工*/
                        $Workflow[] = $value['oid'];
                    }
                    if(10 == $value['status']){
                        /*如果订单已经等待生产，则需要重新发挥经销商的账目信息*/
                        if(!isset($Dealer[$value['did']])){
                            $Dealer[$value['did']] = $value['sum'];
                        }else{
                            $Dealer[$value['did']] += $value['sum'];
                        }
                    }
                    $this->_Id[] = $value['oid'];
                }
                if(!empty($Dealer)){
                    $this->load->model('dealer/dealer_model');
                    $this->dealer_model->update_dealer_re($Dealer);
                }
                if(!empty($Workflow)){
                    $this->load->library('workflow/workflow');
                    if($this->workflow->initialize('order', $Workflow)){
                        $this->workflow->redismantle();
                    }
                }
                return true;
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您需要重新拆单的订单编号不正确, 请确认后再操作!';
                return false;
            }
        }else{
            $this->Failue = '您需要重新拆单的订单编号不正确, 请确认后再操作!';
            return false;
        }
    }
    private function _redismantle_order_product(){
        $this->_Id = intval(trim($this->_Id));
        if($this->_Id > 0){
            $this->_Id = array($this->_Id);
            $this->load->model('order/order_product_model');
            if(!!($Return = $this->order_product_model->is_redismantlable($this->_Id))){
                $Workflow = array();
                $Order = array();
                $Dealer = array();
                $this->_Id = array();
                foreach ($Return as $key => $value){
                    if($value['status'] > 2){
                        $Workflow[] = $value['opid'];
                    }
                    if(!in_array($value['oid'], $Order)){
                        if($value['ostatus'] > 2){
                            /*如果已经确认拆单，则需要返工*/
                            $Order[] = $value['oid'];
                        }
                        if(10 == $value['ostatus']){
                            if(!isset($Dealer[$value['did']])){
                                $Dealer[$value['did']] = $value['sum'];
                            }else{
                                $Dealer[$value['did']] += $value['sum'];
                            }
                        }
                    }
                    $this->_Id[] = $value['opid'];
                }
                if(!empty($Dealer)){
                    $this->load->model('dealer/dealer_model');
                    $this->dealer_model->update_dealer_re($Dealer);
                }
                $this->load->library('workflow/workflow');
                if(!empty($Order)){
                    $this->load->model('order/order_model');
                    if($this->workflow->initialize('order', $Order)){
                        $this->workflow->redismantle();
                    }
                }
                if(!empty($Workflow)){
                    if($this->workflow->initialize('order_product', $Workflow)){
                        $this->workflow->redismantle();
                    }
                }
                return true;
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您需要重新拆单的订单编号不正确, 请确认后再操作!';
                return false;
            }
        }else{
            $this->Failue = '您需要重新拆单的订单产品编号不正确, 请确认后再操作!';
            return false;
        }
    }

    /**
     * 清除当前的拆单数据
     */
    public function remove(){
        $this->_Id = $this->input->post('id', true);
        $this->_Id = intval(trim($this->_Id));
        $this->_Code = $this->input->post('code', true);
        $this->_Code = trim($this->_Code);
        $this->load->model('order/order_product_model');
        if($this->_Id > 0 && $this->order_product_model->is_dismantle_removable($this->_Id)){
            switch ($this->_Code){
                case 'w':
                    $this->load->library('dismantle/d_w');
                    $this->d_w->remove($this->_Id);
                    $this->Failue = $this->d_w->get_failue();
                    break;
                case 'y':
                    $this->load->library('dismantle/d_y');
                    $this->d_y->remove($this->_Id);
                    $this->Failue = $this->d_y->get_failue();
                    break;
                case 'm':
                    $this->load->library('dismantle/d_m');
                    $this->d_m->remove($this->_Id);
                    $this->Failue = $this->d_m->get_failue();
                    break;
                case 'k':
                    $this->load->library('dismantle/d_k');
                    $this->d_k->remove($this->_Id);
                    $this->Failue = $this->d_k->get_failue();
                    break;
                case 'p':
                    $this->load->library('dismantle/d_p');
                    $this->d_p->remove($this->_Id);
                    $this->Failue = $this->d_p->get_failue();
                    break;
                case 'g':
                    $this->load->library('dismantle/d_g');
                    $this->d_g->remove($this->_Id);
                    $this->Failue = $this->d_g->get_failue();
                    break;
                case 'f':
                    $this->load->library('dismantle/d_f');
                    $this->d_f->remove($this->_Id);
                    $this->Failue = $this->d_f->get_failue();
                    break;
                default:
                    $this->Failue = '您要清除的订单类型不存在';
            }
        }else{
            $this->Failue = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要清除的订单不存在';
        }
        $this->_return();
    }
}