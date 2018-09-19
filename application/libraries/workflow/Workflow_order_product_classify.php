<?php namespace Wopc;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:26
 */
require_once 'Workflow_order_product_classify_abstract.php';

class Workflow_order_product_classify {
    static $AllWorkflow = NULL;
    private $_Workflow;
    private $_V;
    private $_Type = 'Order_product_classify'; // order_product_classify
    private $_Model = 'order_product_classify_model';
    private $_Source_id;
    private $_Source_ids;
    private $_Dir;
    private $_CI;
    private $_Failue;

    private $_Data = array();

    public function __construct() {
        $this->_CI = &get_instance();
        log_message('debug', 'Library Workflow/Workflow_procedure __construct Start');
        $this->_Dir = dirname(__FILE__);
        $this->_CI->load->model('workflow/workflow_procedure_model');
        $this->_CI->load->model('workflow/workflow_order_product_classify_msg_model');
    }

    /**
     * 初始化，设置工作流节点类型(订单，订单产品，订单产品板材，订单产品板块...)，根据类型设置模型(Model)
     * 设置对应的源Id，获取所有的工作流节点
     * @param unknown $Type
     * @param unknown $Source_id
     * @param string $Model
     */
    public function initialize($Source_id, $Data = array()) {
        $this->_Source_ids = $Source_id;
        if(is_array($this->_Source_ids)){
            $this->_Source_id = $this->_Source_ids[array_rand($this->_Source_ids, 1)];
        }else{
            $this->_Source_id = $this->_Source_ids;
        }
        if(is_null(self::$AllWorkflow)){
            if(!!($Query = $this->_CI->workflow_procedure_model->select(array('pagesize' => ALL_PAGESIZE, 'p' => ONE)))) {
                $Query = $Query['content'];
                foreach ($Query as $key => $value) {
                    self::$AllWorkflow[$value['name']] = $value;
                }
            } else {
                $this->_Failue = '获取OPC工作流失败!';
                return false;
            }
        }
        if (!empty($Data)) { // 提交的时候同时修改数据
            $this->_Data = $Data;
        }
        return $this->read_current_workflow();
    }

    /**
     * 获取当前工作流类型的工作流，以及设置上下文环境
     */
    public function read_current_workflow() {
        log_message('debug', 'Library read_current_workflow Start On V = ' . $this->_Source_id . '$Type = ' . $this->_Type);
        if(!!($Query = $this->_CI->{$this->_Model}->select_current_workflow($this->_Source_id))){
            return $this->_set_context($Query);
        } else {
            log_message('debug', 'Library Workflow/Workflow read_current_workflow Failue');
            $this->_Failue = '获取当前OPC的工作流失败';
            return false;
        }
    }

    /**
     * 编辑当前订单的工作流
     * @param unknown $Workflow
     */
    public function edit_current_workflow($Workflow, $Other = array()) {
        $Other['status'] = $Workflow['v'];
        $Other = array_merge($this->_Data, $Other);
        $this->_Data = array();
        if(!!($Query = $this->_CI->{$this->_Model}->update($Other,$this->_Source_ids))) {
            return $this->_set_context($Workflow);
        } else {
            $this->_Failue = '设置当前订单的工作流失败';
            return false;
        }
    }

    /**
     * 设置当前环境，引入执行文件，设置执行文件的工作流
     * @param unknown $Workflow
     */
    private function _set_context($Workflow) {
        $this->_V = $Workflow['v'];

        $File = $this->_Dir . '/workflow_order_product_classify/' . $Workflow['file'].'.php';
        if (file_exists($File)) {
            require_once $File;
            log_message('debug', 'Library Workflow/workflow_order_product_classify _set_content on File = '.$File.' And Source_id'.$this->_Source_id);
            $Class = '\\Wopc\\' . $Workflow['file'];
            $this->_Workflow = new $Class($this->_Source_id);
            $this->_Workflow->set_workflow($this);
            return true;
        }else{
            $this->_Failue = '您要执行的工作流文件不存在!' . $File;
            return false;
        }
    }

    /**
     * 在修改订单状态之前可以编辑$Data, 提交的时候一次性提交
     * @param $Data
     */
    public function edit_data ($Data) {
        $this->_Data = array_merge($this->_Data, $Data);
    }
    /**
     * 更新数据, 仅仅是更新数据，在修改订单状态之后单独修改数据
     * @param $Data
     * @return mixed
     */
    public function set_data ($Data) {
        return $this->_CI->{$this->_Model}->update($Data, $this->_Source_ids);
    }

    /**
     * 存储信息
     */
    public function store_message($Msg) {
        $GLOBALS['workflow_msg'] = isset($GLOBALS['workflow_msg']) ? $GLOBALS['workflow_msg'] : '';
        if(is_array($this->_Source_ids)){
            $Set = array();
            foreach ($this->_Source_ids as $value){
                $Set[] = array(
                    'order_product_classify_id' => $value,
                    'msg' => $Msg . $GLOBALS['workflow_msg'],
                    'workflow_procedure_id' => $this->_V
                );
            }
            $this->_CI->workflow_order_product_classify_msg_model->insert_batch($Set);
        } else {
            $Set = array(
                'order_product_classify_id' => $this->_Source_id,
                'msg' => $Msg . $GLOBALS['workflow_msg'],
                'workflow_procedure_id' => $this->_V
            );
            $this->_CI->workflow_order_product_classify_msg_model->insert($Set);
        }
        return true;
    }

    public function set_failue($Failue) {
        $GLOBALS['error'] = $Failue;
        $this->_Failue = $Failue;
    }
    public function get_failue() {
        return $this->_Failue;
    }

    public function get_source_ids() {
        return $this->_Source_ids;
    }

    public function get_type() {
        return $this->_Type;
    }

    public function __call($name, $arguments) {
        $Methods = array(
            'create', 'optimize', 'print_list', 'printed_list', 'shear', 'shearing', 'sheared', 'electronic_saw', 'electronic_sawing', 'electronic_sawed', 're_electronic_saw', 're_shear', 'edge', 'edging', 'edged', 're_edge', 'punch', 'punched', 'punching', 're_punch', 'scan',  'scanning', 'scanned', 're_scan', 'pack', 'packing', 'packed', 're_pack'
        );
        if (in_array($name, $Methods)) {
            return $this->_Workflow->{$name}($arguments);
        }
    }
}