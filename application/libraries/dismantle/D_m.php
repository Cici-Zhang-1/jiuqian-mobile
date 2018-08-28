<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Zhangcc
 * @version
 * @des
 */

require_once dirname(__FILE__).'/D_abstract.php';

class D_m extends D_abstract{
    private $_CI;
    private $_Failue = '';
    private $_Code = 'm';
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

        $Door = array(
            'opdid' => $this->_CI->input->post('opdid', true),
            'struct' => $this->_CI->input->post('struct', true)
        );
        
        $BoardDoor = $this->_CI->input->post('board_door', true);

        $Workflow = array(); /*记录工作流*/
        if('dismantled' == $this->_Save && empty($BoardDoor)){
            $this->_Failue = '没有添加门板, 不能确认门板拆单!';
        }else{
            if($OrderProduct['opid'] > 0){
                /**
                 * 订单产品已经建立
                 */
                if(empty($Door['opdid'])){
                    !empty($Door['struct'])
                    && $Opdid = $this->_add_order_product_door($Door['struct'], $OrderProduct['opid']);
                }else{
                    !empty($Door['struct'])
                    && $Opdid = $this->_edit_order_product_door($Door);
                }
                !empty($BoardDoor)
                && $this->_add_order_product_board_door($BoardDoor, $OrderProduct['opid'], $Door['struct']);
                $Opid = $OrderProduct['opid'];
                unset($OrderProduct['opid']);
                $this->_edit_order_product($OrderProduct, $Opid);
            
                $Workflow[] = $Opid;
                if(--$Set >= 1){
                    if(!!($Opids = $this->_add_order_product($Order['oid'], $Set, $OrderProduct['product'], $this->_Code))){
                        foreach ($Opids as $value){
                            if(!empty($Door['struct'])
                                && !!($Opcsid = $this->_add_order_product_door($Door['struct'], $value))){
                                !empty($BoardDoor)
                                && $this->_add_order_product_board_door($BoardDoor, $value, $Door['struct']);
                            }else{
                                break;
                            }
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
                            if(!empty($Door['struct'])
                                && !!($this->_add_order_product_door($Door['struct'], $value))){
                                !empty($BoardDoor)
                                && $this->_add_order_product_board_door($BoardDoor, $value, $Door['struct']);
                            }else{
                                break;
                            }
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
     * 新建订单产品结构
     * @param unknown $Struct
     * @param unknown $Opid
     */
    private function _add_order_product_door($Struct, $Opid){
        $this->_CI->load->model('order/order_product_door_model');
        $Struct['opid'] = $Opid;
        if(!!($Opdid = $this->_CI->order_product_door_model->insert($Struct))){
            return $Opdid;
        }else{
            $this->_Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建柜体结构失败!';
            return false;
        }
    }
    /**
     * 编辑订单产品结构
     * @param unknown $CabinetStruct
     * @param unknown $Opid
     */
    private function _edit_order_product_door($Door){
        $this->_CI->load->model('order/order_product_door_model');
        $Door = gh_escape($Door);
        if(!!($this->_CI->order_product_door_model->update($Door['struct'], $Door['opdid']))){
            return $Door['opdid'];
        }else{
            $this->_Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'修改柜体结构失败!';
            return false;
        }
    }

    /**
     * 新增订单产品板材板块
     * @param unknown $BoardPlate
     * @param unknown $Opid
     */
    private function _add_order_product_board_door($BoardDoor, $Opid, $Struct){
        $this->_CI->load->model('order/order_product_board_model');
        $this->_CI->load->model('order/order_product_board_door_model');
        $Board = array();
        $Opbids = array();
        
        foreach ($BoardDoor as $key => $value){
            if(!isset($Board[$value['good']])){
                $Board[$value['good']] = array(
                    'opid' => $Opid,
                    'board' => $value['good'],
                    'amount' => 1,
                    'area' => $value['area'],
                    'open_hole' => $value['open_hole'],
                    'invisibility' => $value['invisibility']
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
                $Board[$value['good']]['open_hole'] += $value['open_hole'];
                $Board[$value['good']]['invisibility'] += $value['invisibility'];
            }
            $value = array_merge($value, $this->_get_edge_thick($Struct['edge'], $value['handle'], $value['invisibility']));
            $value['opbid'] = $Board[$value['good']]['opbid'];
            $value['thick'] = preg_replace('/^(\d+)(.*)/', '${1}', $value['good']);
            $BoardDoor[$key] = $value;
        }
        
        if(!empty($Opbids)){
            $this->_CI->order_product_board_door_model->delete_by_opbid($Opbids)
                && $this->_CI->order_product_board_model->delete_not_in($Opid, $Opbids);
        }
        $BoardDoor = gh_escape($BoardDoor);
        if(!!($this->_CI->order_product_board_door_model->insert_batch($BoardDoor))
            && !!($this->_CI->order_product_board_model->update_batch($Board))){
            return true;
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'保存拆单板块失败!';
            return false;
        }
    }

    private function _get_edge_thick($Value, $Handle, $Invisibility){
        if(preg_match('/双色/',$Value) || preg_match('/同色/', $Value)){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1;
            if('定尺拉手' == $Handle){
                $Return['left_edge'] = $Return['right_edge'] = 1.5;
            }
            if($Invisibility > 0){
                $Return['left_edge'] = $Return['right_edge'] = 18.5;
            }
        }elseif (preg_match('/哑光窄边/',$Value) || preg_match('/碰角/',$Value)){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 2;
            if($Invisibility > 0){
                $Return['left_edge'] = $Return['right_edge'] = 19;
            }
        }else{
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 0;
        }
        return $Return;
    }

    public function get_failue(){
        return $this->_Failue;
    }
    public function read(){

    }
    
    public function remove($Id, $OrderProductNum = ''){
        $this->_CI->load->model('order/order_product_board_door_model');
        $this->_CI->order_product_board_door_model->delete_relate($Id);
    }
}