<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 21:18
 * Des: 计算工作流
 */
class Compute_workflow {
    private $HostDb;
    public function __construct() {
    }
    public function initialize($HostDb) {
        $this->HostDb = $HostDb;
    }

    public function compute_next ($ProductionLine, $Displayorder) {
        if (!!($NextProcedure = $this->_next_procedure($ProductionLine, $Displayorder))) {
            return $this->_is_exist_procedure($NextProcedure);
        }
        return false;
    }

    private function _next_procedure ($ProductionLine, $Displayorder) {
        $Query = $this->HostDb->select('plp_procedure as procedure')
            ->from('production_line_procedure')
            ->where('plp_production_line', $ProductionLine)
            ->where('plp_displayorder', $Displayorder)
            ->limit(ONE)
            ->get();
        if ($Query->num_rows() > 0) {
            $Row = $Query->row_array();
            return $Row['procedure'];
        }
        return false;
    }
    private function _is_exist_procedure ($Procedure) {
        static $ProcedureWorkflow;
        if (empty($ProcedureWorkflow)) {
            $Query = $this->HostDb->select('pw_procedure as procedure, pw_workflow_procedure_id as workflow_procedure_id, pw_init as init, wp_name as workflow_procedure_name')
                ->from('procedure_workflow')
                ->join('workflow_procedure', 'wp_id = pw_workflow_procedure_id', 'left')
                ->where('pw_init', YES)
                ->get();
            if ($Query->num_rows() > ZERO) {
                $Result = $Query->result_array();
                $Query->free_result();
                foreach ($Result as $Key => $Value) {
                    $ProcedureWorkflow[$Value['procedure']] = $Value;
                }
                unset($Result);
            }
        }
        if (!empty($ProcedureWorkflow) && isset($ProcedureWorkflow[$Procedure])) {
            return $ProcedureWorkflow[$Procedure];
        } else {
            return false;
        }
    }
}