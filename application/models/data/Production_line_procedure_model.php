<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Production_line_procedure_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Production_line_procedure_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model data/Production_line_procedure_model Start!');
    }

    /**
     * Select from table production_line_procedure
     * @param $Search array
     * @return array
     */
    public function select($Search = array()) {
        if (empty($Search)) {
            return $this->_select();
        } else {
            $Item = $this->_Item . __FUNCTION__;
            $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
            $Return = false;
            if (!($Return = $this->cache->get($Cache))) {
                $Search['pn'] = $this->_page_num($Search);
                if(!empty($Search['pn'])){
                    $Sql = $this->_unformat_as($Item);
                    $this->HostDb->select($Sql)->from('production_line_procedure')
                        ->join('production_line', 'pl_id = plp_production_line', 'left')
                        ->join('procedure', 'p_id = plp_procedure', 'left')
                        ->where('pl_id', $Search['production_line']);
                    if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    }
                    $Query = $this->HostDb->order_by('plp_displayorder')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                    $Return = array(
                        'content' => $Query->result_array(),
                        'num' => $this->_Num,
                        'p' => $Search['p'],
                        'pn' => $Search['pn'],
                        'pagesize' => $Search['pagesize']
                    );
                    $this->cache->save($Cache, $Return, MONTHS);
                } else {
                    $GLOBALS['error'] = '没有符合搜索条件的生产线工序';
                }
            }
            return $Return;
        }
    }

    private function _page_num($Search){
        $this->HostDb->select('count(plp_id) as num', FALSE)
            ->where('plp_production_line', $Search['production_line']);
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
        }
        $this->HostDb->from('production_line_procedure');

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Search['pagesize']) == 0){
                $Pn = intval($Row['num']/$Search['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * 通过生产线获取数据
     * @param $ProductionLine
     */
    private function _select () {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('production_line_procedure')
                ->join('procedure', 'p_id = plp_procedure', 'left')
                ->join('j_procedure_workflow', 'pw_procedure = p_id && pw_init = ' . YES,  'left', false)
                ->where('plp_displayorder', ONE);
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的生产线工序';
            }
        }
        return $Return;
    }
    /**
     * 获取显示最大顺序
     * @return int
     */
    private function _select_max_displayorder($PlId){
        $Query = $this->HostDb->select_max('plp_displayorder')
            ->where('plp_production_line', $PlId)
            ->get('production_line_procedure');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            return $Row['plp_displayorder'] + 1;
        }else{
            return 1;
        }
    }
    /**
     * Insert data to table production_line_procedure
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if(!empty($Data['plp_displayorder'])){
            $this->_update_displayorder($Data['plp_displayorder'], $Data['plp_production_line']);
        }else{
            $Data['plp_displayorder'] = $this->_select_max_displayorder($Data['plp_production_line']);
        }
        if($this->HostDb->insert('production_line_procedure', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入生产线工序数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table production_line_procedure
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);

        $Query = $this->HostDb->select('plp_displayorder')->where(array('plp_id' => $Where))->get('production_line_procedure');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            if($Row['plp_displayorder'] < $Data['plp_displayorder']){
                $this->_update_displayorder_min($Row['plp_displayorder'], $Data['plp_displayorder'], $Data['plp_production_line']);
            }elseif ($Row['plp_displayorder'] > $Data['plp_displayorder']){
                $this->_update_displayorder_plus($Data['plp_displayorder'], $Row['plp_displayorder'], $Data['plp_production_line']);
            }
        }else{
            $GLOBALS['error'] = '您要修改的表单不存在';
            return false;
        }
        $this->HostDb->where('plp_id', $Where);
        $this->HostDb->update('production_line_procedure', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    private function _update_displayorder_min($Min, $Max, $Mid){
        $Query = $this->HostDb->query("UPDATE j_production_line_procedure SET plp_displayorder = plp_displayorder-1 where plp_displayorder > $Min && plp_displayorder <= $Max && plp_production_line = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }
    private function _update_displayorder_plus($Min, $Max, $Mid){
        $Query = $this->HostDb->query("UPDATE j_production_line_procedure SET plp_displayorder = plp_displayorder+1 where plp_displayorder >= $Min && plp_displayorder < $Max && plp_production_line = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 更新菜单的显示顺序
     * @param unknown $DisplayOrder
     * @return boolean
     */
    private function _update_displayorder($DisplayOrder, $Mid){
        $Query = $this->HostDb->query("UPDATE j_production_line_procedure SET plp_displayorder = plp_displayorder+1 where plp_displayorder >= $DisplayOrder && plp_production_line = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }

    private function _delete_displayorder($DisplayOrder, $Mid){
        $Query = $this->HostDb->query("UPDATE j_production_line_procedure SET plp_displayorder = plp_displayorder-1 where plp_displayorder > $DisplayOrder && plp_production_line = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Delete data from table production_line_procedure
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            foreach ($Where as $key => $value){
                $Query = $this->HostDb->select('plp_displayorder, plp_production_line')->where(array('plp_id' => $value))->get('production_line_procedure');
                if($Query->num_rows() > 0){
                    $Row = $Query->row_array();
                    $Query->free_result();
                    $this->_delete_displayorder($Row['plp_displayorder'], $Row['plp_production_line']);
                }
            }
        }else{
            $Query = $this->HostDb->select('plp_displayorder, plp_production_line')->where(array('plp_id' => $Where))->get('production_line_procedure');
            if($Query->num_rows() > 0){
                $Row = $Query->row_array();
                $Query->free_result();
                $this->_delete_displayorder($Row['plp_displayorder'], $Row['plp_production_line']);
            }
        }
        if(is_array($Where)){
            $this->HostDb->where_in('plp_id', $Where);
        } else {
            $this->HostDb->where('plp_id', $Where);
        }

        $this->HostDb->delete('production_line_procedure');
        $this->remove_cache($this->_Module);
        return true;
    }
}
