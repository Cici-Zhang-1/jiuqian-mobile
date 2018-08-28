<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月2日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_product_fitting_model extends MY_Model{
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model Order/Order_product_fitting_model start!');
    }

    public function select ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('order_product_fitting')
                ->join('order_product', 'op_id = opf_order_product_id', 'left')
                ->join('product', 'p_id = op_product_id', 'left')
                ->join('workflow_order_product', 'wop_id = op_status', 'left')
                ->where('op_status > ', OP_REMOVE);

            if (!empty($Search['order_id'])) {
                $this->HostDb->where('op_order_id', $Search['order_id']);
            }

            if (!empty($Search['order_product_id'])) {
                $this->HostDb->where('op_id', $Search['order_product_id']);
            }

            $Query = $this->HostDb->order_by('op_id')->order_by('opf_goods_speci_id')->get();
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
                $GLOBALS['error'] = '没有符合搜索条件的订单配件信息';
            }
        }
        return $Return;
    }

    public function select_for_sure ($OrderProductId) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $OrderProductId;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('order_product_fitting')
                ->where('opf_order_product_id', $OrderProductId);
            $Query = $this->HostDb->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单配件信息';
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
            $Query = $this->HostDb->select($Sql)->from('order_product_fitting')
                ->where('opf_order_product_id', $Search['order_product_id'])->get();
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
                $GLOBALS['error'] = '没有符合搜索条件的订单产品配件信息';
            }
        }
        return $Return;
    }

    public function insert($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	$Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('order_product_fitting', $Set)){
            log_message('debug', "Model Order_product_fitting_model/insert_order_product_fitting Success!");
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Order_product_fitting_model/insert_order_product_fitting Error");
            return false;
        }
    }
    
    public function insert_batch($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	foreach ($Set as $key => $value){
    		$Set[$key] = $this->_format($value, $Item, $this->_Module);
    	}
    	if($this->HostDb->insert_batch('order_product_fitting', $Set)){
    		log_message('debug', "Model Order_product_fitting_model/insert_batch Success!");
    		$this->remove_cache($this->_Module);
    		return true;
    	}else{
    		log_message('debug', "Model Order_product_fitting_model/insert_batch Error");
    		return false;
    	}
    }

    public function update_batch ($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('order_product_fitting', $Data, 'opf_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 通过订单产品Id删除配件信息
     * @param $OrderProductId
     * @return mixed
     */
    public function delete_by_order_product_id ($OrderProductId) {
        $this->HostDb->where_in('opf_order_product_id', is_array($OrderProductId) ? $OrderProductId : array($OrderProductId));
        $this->remove_cache($this->_Module);
        return $this->HostDb->delete('order_product_fitting');
    }
}
