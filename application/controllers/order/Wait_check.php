<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月23日
 * @author Zhangcc
 * @version
 * @des
 * 等待核价
 */
 
class Wait_check extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item ;
    private $_Cookie ;
    private $Count;
    private $_Id;
    private $Search = array(
        'status' => '',
        'keyword' => ''
    );
    
    private $_SumDetail = array();  /*核价详情*/
    private $_SumDiff = 0;    /* 差价*/
    private $_OrderProduct = array(); /*核价时订单产品统计*/
    private $_Board = array();  /*核价时统计板材*/
    
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_model');
        
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.strtolower(__CLASS__).'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item);
        
        log_message('debug', 'Controller Order/Wait_check Start!');
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
                foreach ($Data['content'] as $key => $value){
                    $Tmp = json_decode($value['sum_detail'], true);
                    if(is_array($Tmp)){
                        $Data['content'][$key] = array_merge($Data['content'][$key], $Tmp);
                    }
                    unset($Data['content'][$key]['sum_detail']);
                    unset($Tmp);
                }
                
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的订单';;
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }

    private function _edit(){
        if(empty($this->_Id)){
            $this->_Id = $this->input->get('id');
            $this->_Id = intval(trim($this->_Id));
        }
        if($this->_Id){
            if(!!($this->order_model->is_checkable($this->_Id))){
                $Item = $this->_Item.__FUNCTION__;
                $this->load->library('d_money');
                $this->load->model('product/product_model');
                $Data = array(
                    'Id' => $this->_Id
                );
                $Data['Info'] = $this->d_money->read('detail', $this->_Id);
                
                if(!!($Product = $this->product_model->select_undelete())){
                    $Product = $Product['content'];
                    foreach ($Product as $key => $value){
                        $Data[$value['code']] = $this->d_money->read($value['code'], $this->_Id, $value['pid']);
                    }
                }else{
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单产品不存在';
                }
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'该订单已经核价!';
            }
        }else{
            $this->Failue .= '请选择要核价的订单!';
        }
        if(empty($this->Failue)){
            $this->load->view($Item, $Data);
        }else{
            $this->close_tab($this->Failue);
        }
    }
    
    public function edit($Type = ''){
        $Type = trim($Type);
        $this->_Id = $this->input->post('oid', true);
        if($this->_Id){
            $this->_edit_cabinet();
            $this->_edit_wardrobe();
            $this->_edit_door();
            $this->_edit_wood();
            $this->_edit_order_product_board();
            
            $this->_edit_fitting();
            $this->_edit_other();
            $this->_edit_server();

            $this->_edit_order();
            $this->_edit_order_product();
            
            $this->load->library('workflow/workflow');
            if($this->workflow->initialize('order', $this->_Id)){
                $this->workflow->$Type();
            }else{
                $this->Failue = $this->workflow->get_failue();
            }
        }else{
            $this->Failue .= '请先选择要核价的订单';
        }
        $this->_return();
    }
    
    private function _edit_order(){
        $Order = array(
            'sum' => array_sum($this->_SumDetail),
            'sum_detail' => json_encode($this->_SumDetail),
            'sum_diff' => ceil($this->_SumDiff)
        );
        $Order['sum'] = ceil($Order['sum']);
        
        $this->load->model('order/order_model');
        $this->order_model->update_order($Order, $this->_Id);
        
    }
    
    private function _edit_order_product(){
        if(count($this->_OrderProduct) > 0){
            $this->load->model('order/order_product_model');
            $this->order_product_model->update_batch($this->_OrderProduct);
        }
    }

    private function _edit_order_product_board(){
        if(count($this->_Board) > 0 ){   //板块
            $this->load->model('order/order_product_board_model');
            $this->_Board = gh_escape($this->_Board);
            $this->order_product_board_model->update_batch($this->_Board);
        }
    }
    
    private function _edit_cabinet(){
        $Cabinet = $this->input->post('cabinet', true);
        if($Cabinet){/*橱柜核价*/
            $this->_Board = array_merge($this->_Board, $Cabinet);
            $Sum = 0;
            $SumDiff = 0;
            foreach ($Cabinet as $key => $value){
                if(!isset($this->_OrderProduct[$value['opid']])){
                    $this->_OrderProduct[$value['opid']] = array(
                        'opid' => $value['opid'],
                        'sum' => $value['sum'],
                        'sum_diff' => $value['sum_diff']
                    );
                }else{
                    $this->_OrderProduct[$value['opid']]['sum'] += $value['sum'];
                    $this->_OrderProduct[$value['opid']]['sum_diff'] += $value['sum_diff'];
                }
                $Sum += $value['sum'];
                $SumDiff += $value['sum_diff'];
            }
            $this->_SumDetail['cabinet'] = $Sum;
            $this->_SumDiff += $SumDiff;
        }else{
            $this->_SumDetail['cabinet'] = 0;
        }
    }
    
    private function _edit_wardrobe(){
        $Wardrobe = $this->input->post('wardrobe', true);
        if($Wardrobe){/*衣柜核价*/
            $this->_Board = array_merge($this->_Board, $Wardrobe);
            $Sum = 0;
            $SumDiff = 0;
            foreach ($Wardrobe as $key => $value){
                if(!isset($this->_OrderProduct[$value['opid']])){
                    $this->_OrderProduct[$value['opid']] = array(
                        'opid' => $value['opid'],
                        'sum' => $value['sum'],
                        'sum_diff' => $value['sum_diff']
                    );
                }else{
                    $this->_OrderProduct[$value['opid']]['sum'] += $value['sum'];
                    $this->_OrderProduct[$value['opid']]['sum_diff'] += $value['sum_diff'];
                }
                $Sum += $value['sum'];
                $SumDiff += $value['sum_diff'];
            }
            $this->_SumDetail['wardrobe'] = $Sum;
            $this->_SumDiff += $SumDiff;
        }else{
            $this->_SumDetail['wardrobe'] = 0;
        }
    }

    private function _edit_door(){
        $Door = $this->input->post('door', true);
        if($Door){/*门板核价*/
            $this->_Board = array_merge($this->_Board, $Door);
            $Sum = 0;
            $SumDiff = 0;
            foreach ($Door as $key => $value){
                if(!isset($this->_OrderProduct[$value['opid']])){
                    $this->_OrderProduct[$value['opid']] = array(
                        'opid' => $value['opid'],
                        'sum' => $value['sum'],
                        'sum_diff' => $value['sum_diff']
                    );
                }else{
                    $this->_OrderProduct[$value['opid']]['sum'] += $value['sum'];
                    $this->_OrderProduct[$value['opid']]['sum_diff'] += $value['sum_diff'];
                }
                $Sum += $value['sum'];
                $SumDiff += $value['sum_diff'];
            }
            $this->_SumDetail['door'] = $Sum;
            $this->_SumDiff += $SumDiff;
        }else{
            $this->_SumDetail['door'] = 0;
        }
    }
    
    private function _edit_wood(){
        $Wood = $this->input->post('kuang', true);
        if($Wood){/*木框核价*/
            $this->_Board = array_merge($this->_Board, $Wood);
            $Sum = 0;
            $SumDiff = 0;
            foreach ($Wood as $key => $value){
                if(!isset($this->_OrderProduct[$value['opid']])){
                    $this->_OrderProduct[$value['opid']] = array(
                        'opid' => $value['opid'],
                        'sum' => $value['sum'],
                        'sum_diff' => $value['sum_diff']
                    );
                }else{
                    $this->_OrderProduct[$value['opid']]['sum'] += $value['sum'];
                    $this->_OrderProduct[$value['opid']]['sum_diff'] += $value['sum_diff'];
                }
                $Sum += $value['sum'];
                $SumDiff += $value['sum_diff'];
            }
            $this->_SumDetail['kuang'] = $Sum;
            $this->_SumDiff += $SumDiff;
        }else{
            $this->_SumDetail['kuang'] = 0;
        }
    }
    
    private function _edit_fitting(){
        $Fitting = $this->input->post('fitting', true);
        if($Fitting){      //配件
            $Sum = 0;
            foreach ($Fitting as $key => $value){
                if(!isset($this->_OrderProduct[$value['opid']])){
                    $this->_OrderProduct[$value['opid']] = array(
                        'opid' => $value['opid'],
                        'sum' => $value['sum']
                    );
                }else{
                    $this->_OrderProduct[$value['opid']]['sum'] += $value['sum'];
                }
                $Sum += $value['sum'];
            }
            $this->load->model('order/order_product_fitting_model');
            $Fitting = gh_escape($Fitting);
            $this->order_product_fitting_model->update_batch($Fitting);
            $this->_SumDetail['fitting'] = $Sum;
        }else{
            $this->_SumDetail['fitting'] = 0;
        }
    }
    
    private function _edit_other(){
        $Other = $this->input->post('other', true);
        if($Other){       //外购
            $Sum = 0;
            foreach ($Other as $key => $value){
                if(!isset($this->_OrderProduct[$value['opid']])){
                    $this->_OrderProduct[$value['opid']] = array(
                        'opid' => $value['opid'],
                        'sum' => $value['sum']
                    );
                }else{
                    $this->_OrderProduct[$value['opid']]['sum'] += $value['sum'];
                }
                $Sum += $value['sum'];
            }
            $this->load->model('order/order_product_other_model');
            $Other = gh_escape($Other);
            $this->order_product_other_model->update_batch($Other);
            $this->_SumDetail['other'] = $Sum;
        }else{
            $this->_SumDetail['other'] = 0;
        }
    }
    
    private function _edit_server(){
        $Server = $this->input->post('server', true);
        if($Server){       //服务
            $Sum = 0;
            foreach ($Server as $key => $value){
                if(!isset($this->_OrderProduct[$value['opid']])){
                    $this->_OrderProduct[$value['opid']] = array(
                        'opid' => $value['opid'],
                        'sum' => $value['sum']
                    );
                }else{
                    $this->_OrderProduct[$value['opid']]['sum'] += $value['sum'];
                }
                $Sum += $value['sum'];
            }
            $this->load->model('order/order_product_server_model');
            $Server = gh_escape($Server);
            $this->order_product_server_model->update_batch($Server);
            $this->_SumDetail['server'] = $Sum;
        }else{
            $this->_SumDetail['server'] = 0;
        }
    }
    /**
     * 确认核价
     */
    public function edit_asure(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Selected = $this->input->post('selected', true);
            
            if(!!($Order = $this->order_model->is_checking($Selected))){
                $Selected = array();
                foreach ($Order as $key => $value){
                    if($value['sum'] > 0){
                        $Selected[] = $value['oid'];
                    }else{
                        if(preg_match('/^B/', $value['order_num'])){
                            $Selected[] = $value['oid'];
                        }
                    }
                }
                if(!empty($Selected)){
                    $this->load->library('workflow/workflow');
                    if($this->workflow->initialize('order', $Selected)){
                        $this->workflow->checked();
                    }else{
                        $this->Failue = $this->workflow->get_failue();
                    }
                }else{
                    $this->Failue = '订单为正常单，但核价金额为0';
                }
            }else{
                $this->Failue .= '没有可确认项!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    
    
    /**
     * 重新核价
     */
    private function _recheck(){
        $this->_Id = $this->input->post('id', true);
        $this->_Id = trim($this->_Id);
        $this->_Id = empty($this->_Id)?$this->input->get('id', true):$this->_Id;
        $this->_Id = trim($this->_Id);
        if($this->_Id){
            $this->load->model('order/order_model');
            if(!!($Return = $this->order_model->is_recheckable($this->_Id))){
                $Workflow = array();
                $Dealer = array();
                $this->_Id = array();
                foreach ($Return as $key => $value){
                    if($value['status'] > 5){
                        $Workflow[] = $value['oid'];
                    }
                    if($value['status'] > 8){
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
                        $this->workflow->recheck();
                    }
                    /* $this->order_model->update_order_re($Workflow);
                    $this->workflow->action($Workflow, 'order', 'recheck'); */
                }
                $this->_Id = array_shift($this->_Id);
                $this->redirect_tab(site_url('order/wait_check/index/edit?id='.$this->_Id));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您需要重新核价的订单编号不正确, 请确认后再操作!';
                $this->close_tab($this->Failue);
            }
        }else{
            $this->close_tab('您要核价的订单不存在!');
        }
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
