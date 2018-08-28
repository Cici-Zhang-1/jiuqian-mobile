<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Zhangcc
 * @version
 * @des
 */

require_once dirname(__FILE__).'/D_abstract.php';

class D_k extends D_abstract{
    private $_CI;
    private $_Failue = '';
    private $_Code = 'k';
    private $_Save;

    public function __construct(){
        $this->_CI = &get_instance();
        parent::__construct($this->_CI);
    }

    public function edit($Save, $Code){
        $this->_Save = $Save;
        $this->_Code = $Code;
        
        $Order = array(
            'oid' => $this->_CI->input->post('oid', true)
        );
        $Order['oid'] = intval(trim($Order['oid']));

        $Set = $this->_CI->input->post('set', true); /*集*/
        $Set = intval(trim($Set));
        $Set = ($Set <= 0) ? 1: ($Set> 60?60:$Set);

        $OrderProduct = array(
            'opid' => $this->_CI->input->post('opid', true),
            'product' => $this->_CI->input->post('product', true),
            'remark' => $this->_CI->input->post('remarks', true)
        );
        $OrderProduct['opid'] = intval(trim($OrderProduct['opid']));
        
        $BoardWood = $this->_CI->input->post('board_wood', true);

        $Workflow = array(); /*记录工作流*/
        if('dismantled' == $this->_Save && empty($BoardWood)){
            $this->_Failue = '没有添加木框门, 不能确认木框门拆单!';
        }else{
            if($OrderProduct['opid'] > 0){
                /**
                 * 订单产品已经建立
                 */
                !empty($BoardWood)
                && $this->_add_order_product_board_wood($BoardWood, $OrderProduct['opid']);
                $Opid = $OrderProduct['opid'];
                unset($OrderProduct['opid']);
                $this->_edit_order_product($OrderProduct, $Opid);
            
                $Workflow[] = $Opid;
                unset($Opid);
                if(--$Set >= 1){
                    if(!!($Opids = $this->_add_order_product($Order['oid'], $Set, $OrderProduct['product'], $this->_Code))){
                        foreach ($Opids as $value){
                            !empty($BoardWood)
                            && $this->_add_order_product_board_wood($BoardWood, $value);
                            $Workflow[] = $value;
                        }
                    }else{
                        $this->_Failue = '新增订单产品失败!';
                    }
                }
            }else{
                /**
                 * 订单产品需要新建
                 */
                if($Order['oid'] > 0){
                    if(!!($Opids = $this->_add_order_product($Order['oid'], $Set, $OrderProduct['product'], $this->_Code))){
                        foreach ($Opids as $value){
                            !empty($BoardWood)
                            && $this->_add_order_product_board_wood($BoardWood, $value);
                            $Workflow[] = $value;
                        }
                    }
                }else{
                    $this->_Failue .= '请创建订单之后再拆单';
                }
            }
            if(!empty($Workflow)){
                $this->_CI->load->library('workflow/workflow');
                if(!!($this->_CI->workflow->initialize('order_product', $Workflow))){
                    $this->_CI->workflow->{$this->_Save}();
                }else{
                    $this->_Failue = $this->_CI->workflow->get_failue();
                }
            }
        }
    }

    /**
     * 新增订单产品板材板块
     * @param unknown $BoardPlate
     * @param unknown $Opid
     */
    private function _add_order_product_board_wood($BoardWood, $Opid){
        $this->_CI->load->model('order/order_product_board_model');
        $this->_CI->load->model('order/order_product_board_wood_model');
        $Board = array();
        $Opbids = array();
        
        foreach ($BoardWood as $key => $value){
            if(!isset($Board[$value['good']])){
                $Board[$value['good']] = array(
                    'opid' => $Opid,
                    'board' => $value['good'],
                    'amount' => 1,
                    'area' => $value['area']
                );
                if(!($Board[$value['good']]['opbid'] = $this->_CI->order_product_board_model->is_existed($Opid, gh_escape($value['good'])))){
                    $Board[$value['good']] = gh_escape($Board[$value['good']]);
                    $Board[$value['good']]['opbid'] = $this->_CI->order_product_board_model->insert($Board[$value['good']]);
                }/* else{ */
                array_push($Opbids, $Board[$value['good']]['opbid']);
                /* } */
            }else{
                $Board[$value['good']]['amount']++;
                $Board[$value['good']]['area'] += $value['area'];
            }
            $value['opbid'] = $Board[$value['good']]['opbid'];
            $value['thick'] = preg_replace('/^(\d+)(.*)/', '${1}', $value['good']);
            $BoardWood[$key] = $value;
        }
        if(!empty($Opbids)){
            $this->_CI->order_product_board_wood_model->delete_by_opbid($Opbids)
            && $this->_CI->order_product_board_model->delete_not_in($Opid, $Opbids);
        }
        $BoardWood = gh_escape($BoardWood);
        if(!!($this->_CI->order_product_board_wood_model->insert_batch($BoardWood))
            && !!($this->_CI->order_product_board_model->update_batch($Board))){
            return true;
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'保存拆单板块失败!';
            return false;
        }
    }

    public function get_failue(){
        return $this->_Failue;
    }
    public function read(){

    }
    
    public function remove($Id, $OrderProductNum = ''){
        $this->_CI->load->model('order/order_product_board_wood_model');
        $this->_CI->order_product_board_wood_model->delete_relate($Id);
    }
}