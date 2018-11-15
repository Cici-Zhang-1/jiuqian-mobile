<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月22日
 * @author Zhangcc
 * @version
 * @des
 * 上传文件
 */
class Desk_upload extends MY_Controller{
    private $_Created = array();
    private $_New = array(); // 新增的order_product_classify_id
    private $_DataUpdate = array();
    private $_W;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Desk_upload Start!');
        $this->load->model('order/order_product_board_plate_model');
        $this->load->model('order/order_product_classify_model');
        $this->load->model('order/mrp_model');
        $this->load->library('workflow/workflow');
        $this->_W = $this->workflow->initialize('mrp');
    }

    public function saw(){
        $Mat = $this->input->post('mat');
        $_POST['mat'] = explode(',', $Mat);
        $Plate = $this->input->post('plate');
        $_POST['plate'] = explode(',', $Plate);
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Saw = array();
            foreach ($Post['mat'] as $Key => $Value) {
                if (preg_match('/^MAT2.*$/', $Value, $Matches)) {
                    array_push($Saw, $Matches[0]);
                }
            }
            if (!empty($Saw)) {
                if (!empty($Post['plate'])) {
                    if ($this->_parse_order_product_classify($Post['plate'], $Post['batch_num'])) {
                        if ($this->_parse_saw($Saw, $Post['batch_num'])) {
                            $this->_edit_mrp($Post['batch_num']);
                            $this->_remove_mrp();
                            $this->_edit_order_product_classify();
                        }
                    }
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message .= '没有找到相关订单' . $Post['batch_num'];
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message .= '上传数据错误' . $Post['batch_num'];
            }
        }
        $this->_ajax_return();
    }

    /**
     * 解析订单产品板材分类
     * @param $Plate
     * @param $BatchNum
     * @return bool
     */
    private function _parse_order_product_classify ($Plate, $BatchNum) {
        if (!!($OrderProductClassify = $this->order_product_board_plate_model->select_classify_batch_num($Plate))) {
            foreach ($OrderProductClassify as $Key => $Value) {
                if ($Value['status'] == M_ELECTRONIC_SAW) {
                    $this->Code = EXIT_ERROR;
                    $this->Message .= $BatchNum . $Value['board'] . '已经安排下料，请返回后修改!';
                    return false;
                } elseif (in_array($Value['status'], array(M_ELECTRONIC_SAWED))) {
                    $this->Code = EXIT_ERROR;
                    $this->Message .= $BatchNum . $Value['board'] . '已经下料，无法修改!';
                    return false;
                }
                if (!empty($Value['mrp_v'])) { // 已经上传的mrp
                    $this->_Created[$Value['batch_num'] . $Value['board']] = $Value['mrp_v'];
                } else {
                    if (!isset($this->_New[$Value['board']])) {
                        $this->_New[$Value['board']] = array(
                            'v' => array()
                        );
                    }
                    array_push($this->_New[$Value['board']]['v'], $Value['v']);
                }
            }
            return true;
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message .= '没有找到相关订单' . $BatchNum;
            return false;
        }
    }

    /**
     * 解析saw文件
     * @param $Saw
     * @param $BatchNum
     * @return bool
     */
    private function _parse_saw ($Saw, $BatchNum) {
        foreach ($Saw as $Key => $Value) {
            $Item = explode('____', $Value);
            $Board = trim($Item[1]);
            $Num = intval(trim($Item[50])); // 板块数量
            $IKey = $BatchNum . $Board;
            if (isset($this->_Created[$IKey])) { // 如果是已经上传的则更新板块数量
                $this->_DataUpdate[] = array(
                    'v' => $this->_Created[$IKey],
                    'num' => $Num,
                );
                unset($this->_Created[$IKey]);
            } else {
                if ($this->_add_new_mrp($BatchNum, $Board, $Num)) {
                    continue;
                } else {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 添加新的MRP
     * @param $BatchNum
     * @param $Board
     * @param $Num
     * @return bool
     */
    private function _add_new_mrp ($BatchNum, $Board, $Num) {
        $Data = array(
            'batch_num' => $BatchNum,
            'board' => $Board,
            'num' => $Num,
            'status' => M_SHEAR
        );
        if (isset($this->_New[$Board])) {
            if(!!($NewId = $this->mrp_model->insert($Data))) {
                $this->_New[$Board]['mrp_id'] = $NewId;
                return $this->_workflow($NewId, $BatchNum . $Board);
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message .= $BatchNum . $Board . '新建失败!';
                return false;
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message .= $BatchNum . $Board . '没有找到对应订单!';
            return false;
        }
    }

    private function _edit_mrp ($BatchNum) {
        if (!empty($this->_DataUpdate)) { // 已经上传的进行数据更新
            $this->mrp_model->update_batch($this->_DataUpdate);
            $this->Message .= $BatchNum . 'MRP修改成功! <br />';
        }
        return true;
    }

    private function _remove_mrp () {
        if (!empty($this->_Created)) { // 批次号改变的需要删除没用的
            $this->mrp_model->delete($this->_Created);
        }
        return true;
    }

    private function _edit_order_product_classify () {
        if (!empty($this->_New)) {
            foreach ($this->_New as $Key => $Value) {
                if (!empty($Value['mrp_id'])) {
                    if (!($this->order_product_classify_model->update(array('mrp_id' => $Value['mrp_id']), $Value['v']))) {
                        $this->Code = EXIT_ERROR;
                        $this->Message = '更新板材分类MRP时出错!';
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * 执行工作流
     * @param $V
     * @param $Msg
     * @return bool
     */
    private function _workflow ($V, $Msg) {
        $GLOBALS['workflow_msg'] = $Msg;
        if(!!($this->_W->initialize($V))){
            $this->_W->shear();
            $this->Message .= $Msg . '上传成功! <br />';
            return true;
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = $this->workflow_mrp->get_failue();
            return false;
        }
    }
}