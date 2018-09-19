<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月2日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_product_other_model extends MY_Model{
	private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model Order/Order_product_other_model start!');
    }
    public function select ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('order_product_other')
                ->join('order_product', 'op_id = opo_order_product_id', 'left')
                ->join('product', 'p_id = op_product_id', 'left')
                ->join('workflow_order_product', 'wop_id = op_status', 'left')
                ->join('workflow_procedure', 'wp_id = opo_status', 'left')
                ->where('op_status > ', OP_REMOVE);

            if (!empty($Search['order_id'])) {
                $this->HostDb->where('op_order_id', $Search['order_id']);
            }

            if (!empty($Search['order_product_id'])) {
                $this->HostDb->where('op_id', $Search['order_product_id']);
            }

            $Query = $this->HostDb->order_by('op_id')->order_by('opo_goods_speci_id')->get();
            if ($Query->num_rows() > 0) {
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $Query->num_rows(),
                    'p' => ONE,
                    'pn' => ONE,
                    'pagesize' => ALL_PAGESIZE
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单外购信息';
            }
        }
        return $Return;
    }

    /**
     * 根据订单产品编号获取在确认时需要的信息
     * @param $OrderProductId
     * @return bool
     */
    public function select_for_sure ($OrderProductId) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $OrderProductId;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('order_product_other')
                ->where('opo_order_product_id', $OrderProductId);
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单外购产品信息';
            }
        }
        return $Return;
    }

    /**
     * 通过订单产品编号获取信息
     * @param $Search
     * @return array|bool
     */
    public function select_by_order_product_id ($Search) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('order_product_other')
                ->where('opo_order_product_id', $Search['order_product_id'])->get();
            if ($Query->num_rows() > 0) {
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $Query->num_rows(),
                    'p' => ONE,
                    'pn' => ONE,
                    'pagesize' => ALL_PAGESIZE
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单产品外购信息';
            }
        }
        return $Return;
    }

    public function select_sales($Con) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.array_to_string($Con);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);

            $this->HostDb->select($Sql, FALSE)
                ->from('order_product_other')
                ->join('order_product', 'op_id = opo_order_product_id', 'left')
                ->join('product', 'p_id = op_product_id', 'left')
                ->join('order', 'o_id = op_order_id', 'left')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->where('op_status > ', OP_DISMANTLING)
                ->where('o_status > ', O_WAIT_SURE)
                ->where('od_sure_datetime > ', $Con['start_date']);
            if(!empty($Con['end_date'])) {
                $this->HostDb->where('od_sure_datetime < ', $Con['end_date']);
            }
            if (!empty($Con['product_id'])) {
                $this->HostDb->where_in('p_id', $Con['product_id']);
            }

            if(!empty($Con['keyword'])){
                $this->HostDb->group_start()
                    ->like('o_dealer', $Con['keyword'])
                    ->group_end();
            }

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有对应销售记录';
                $Return = false;
            }
        }
        return $Return;
    }

    /**
     * 获取配件是否可以打包
     * @param $OrderProductId
     * @return bool
     */
    public function select_packable_by_order_product_id ($OrderProductId) {
        $Item = $this->_Item . __FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product_other')
            ->where('opo_order_product_id', $OrderProductId)
            ->where('opo_status >= ', WP_PACK)
            ->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }

    /**
     * 获取订单产品Id
     * @param $Vs
     * @return bool
     */
    public function select_order_product_id ($Vs) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__ . array_to_string($Vs);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product_other')
                ->where_in('opo_id', $Vs)
                ->group_by('opo_order_product_id');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    /**
     * 下一工作流
     * @param $Vs
     * @return bool
     */
    public function select_workflow_next ($Vs) {
        if ($Now = $this->_select_production_line($Vs)) {
            $this->load->library('workflow/compute_workflow');
            $this->compute_workflow->initialize($this->HostDb);
            foreach ($Now as $Key => $Value) {
                $Value['displayorder']++; // 下一执行工序
                $Next = $this->compute_workflow->compute_next($Value['production_line'], $Value['displayorder']);
                if ($Next !== false) {
                    $Now[$Key] = array_merge($Now[$Key], $Next);
                    continue;
                }

                unset($Now[$Key]);
            }
        }
        return $Now;
    }
    private function _select_production_line ($Vs) {
        $Query = $this->HostDb->select('opo_id as v, opo_production_line as production_line, opo_procedure as procedure, plp_displayorder as displayorder')
            ->from('order_product_other')
            ->join('j_production_line_procedure', 'plp_production_line = opo_production_line && plp_procedure = opo_procedure', 'left', false)
            ->where_in('opo_id', $Vs)
            ->get();
        $Return = false;
        if ($Query->num_rows() > ZERO) {
            $Return = $Query->result_array();
            $Query->free_result();
        } else {
            $GLOBALS['error'] = '您选择的订单产品配件不存在!';
        }
        return $Return;
    }

    /**
     * 当前工作流
     * @param $V
     * @return bool
     */
    public function select_current_workflow($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('order_product_other')
            ->join('workflow_procedure', 'wp_id = opo_status', 'left')
            ->where('opo_id', $V)->limit(1)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->row_array();
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }

    public function insert($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	$Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('order_product_other', $Set)){
            log_message('debug', "Model Order_product_other_model/insert_order_product_other Success!");
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Order_product_other_model/insert_order_product_other Error");
            return false;
        }
    }
    
    public function insert_batch($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	foreach ($Set as $key => $value){
    		$Set[$key] = $this->_format($value, $Item, $this->_Module);
    	}
    	if($this->HostDb->insert_batch('order_product_other', $Set)){
    		log_message('debug', "Model Order_product_other_model/insert_batch Success!");
    		$this->remove_cache($this->_Module);
    		return true;
    	}else{
    		log_message('debug', "Model Order_product_other_model/insert_batch Error");
    		return false;
    	}
    }

    /**
     * 更新数据
     * @param $Data
     * @param $Where
     * @return bool
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('opo_id', $Where);
        } else {
            $this->HostDb->where('opo_id', $Where);
        }
        $this->HostDb->update('order_product_other', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    public function update_batch ($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('order_product_other', $Data, 'opo_id');
        $this->remove_cache($this->_Module);
        return true;
    }
    /**
     * 通过订单产品Id删除配件信息
     * @param $OrderProductId
     * @return mixed
     */
    public function delete_by_order_product_id ($OrderProductId) {
        $this->HostDb->where_in('opo_order_product_id', is_array($OrderProductId) ? $OrderProductId : array($OrderProductId));
        $this->remove_cache($this->_Module);
        return $this->HostDb->delete('order_product_other');
    }
}
