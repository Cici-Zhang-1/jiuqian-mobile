<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月24日
 * @author zhangcc
 * @version
 * @des
 */
class Order_product_board_model extends Base_Model{
	private $_Module = 'order';
	private $_Model;
	private $_Item;
	private $_Cache;
	private $_Num;
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
        
        log_message('debug', 'Model Order/Order_product_board_model start!');
    }
    
    /**
     * 获取需要优化的订单产品板材
     */
    public function select_optimize($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Con['optimize'] = explode(',', $Con['optimize']);
            
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page_optimize($Con);
            }else{
                $this->_Num = $Con['num'];
            }
            if(!empty($Con['pn'])){
                $Sql = $this->_unformat_as($Item, $this->_Module);
                $this->HostDb->select($Sql, FALSE);
                $this->HostDb->from('order_product_board');
                $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
                $this->HostDb->join('order', 'o_id = op_order_id', 'left');
                $this->HostDb->join('user as A', 'A.u_id = opb_optimizer', 'left');
                $this->HostDb->join('user as B', 'B.u_id = op_dismantler', 'left');

                $this->HostDb->where('o_asure_datetime is not null'); /*已经确认的订单才可以导出优化文件*/
		$this->HostDb->where('op_status >= 3');
        	$this->HostDb->where('o_status >= 11');

                $this->HostDb->where("op_product_id in ({$Con['product']})"); /*对应产品*/
                
                $this->HostDb->where(array('opb_amount > ' => 0)); /*板块数量大于0*/
                
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                                        ->like('op_remark', $Con['keyword'])
                                        ->or_like('o_remark', $Con['keyword'])
                                        ->or_like('o_dealer', $Con['keyword'])
                                        ->or_like('o_owner', $Con['keyword'])
                                        ->or_like('op_num', $Con['keyword'])
                                        ->or_like('opb_board', $Con['keyword'])
                                        ->or_like('opb_optimize_datetime', $Con['keyword'])
                                    ->group_end();
                }
        
                if(2 !== count($Con['optimize'])){ /*优化状态*/
                    if(in_array(0, $Con['optimize'])){
                        $this->HostDb->where("(opb_optimize is null || opb_optimize = '')");
                    }else{
                        $this->HostDb->where("(opb_optimize is not null && opb_optimize != '')");
                    }
                }
                
                if('num' == $Con['sort']){
                    $this->HostDb->order_by('op_num');
                    $this->HostDb->order_by('opb_board');
                }elseif ('board' == $Con['sort']){
                    $this->HostDb->order_by('opb_board');
                    $this->HostDb->order_by('op_num');
                }elseif ('datetime' == $Con['sort']){
                    $this->HostDb->order_by('opb_optimize_datetime', 'desc');
                    $this->HostDb->order_by('op_num');
                    $this->HostDb->order_by('opb_board');
                }
                
                $this->HostDb->limit($Con['pagesize'], ($Con['p']-1)*$Con['pagesize']);
                $Query = $this->HostDb->get();
                if($Query->num_rows() > 0){
                    $Result = $Query->result_array();
                    $Return = array(
                        'content' => $Result,
                        'num' => $this->_Num,
                        'p' => $Con['p'],
                        'pn' => $Con['pn']
                    );
                    $this->cache->save($Cache, $Return, HOURS);
                }
            }else{
                $GLOBALS['error'] = '没有符合要求需要优化的订单!';
            }
        }
        return $Return;
    }
    
    private function _page_optimize($Con){
        $this->HostDb->select('count(opb_id) as num', FALSE);
        $this->HostDb->from('order_product_board');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');

        $this->HostDb->where('o_asure_datetime is not null');
	$this->HostDb->where('op_status >= 3');
        $this->HostDb->where('o_status >= 11');

        $this->HostDb->where("op_product_id in ({$Con['product']})");
        
        $this->HostDb->where(array('opb_amount > ' => 0));
        
        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
            ->like('op_remark', $Con['keyword'])
            ->or_like('o_remark', $Con['keyword'])
            ->or_like('o_dealer', $Con['keyword'])
            ->or_like('o_owner', $Con['keyword'])
            ->or_like('op_num', $Con['keyword'])
            ->or_like('opb_board', $Con['keyword'])
            ->or_like('opb_optimize_datetime', $Con['keyword'])
            ->group_end();
        }
        
        if(2 !== count($Con['optimize'])){
            if(in_array(0, $Con['optimize'])){
                $this->HostDb->where("(opb_optimize is null || opb_optimize = '')");
            }else{
                $this->HostDb->where("(opb_optimize is not null && opb_optimize != '')");
            }
        }
        
        if('num' == $Con['sort']){
            $this->HostDb->order_by('op_num');
            $this->HostDb->order_by('opb_board');
        }elseif ('board' == $Con['sort']){
            $this->HostDb->order_by('opb_board');
            $this->HostDb->order_by('op_num');
        }elseif ('datetime' == $Con['sort']){
            $this->HostDb->order_by('opb_optimize_datetime', 'desc');
            $this->HostDb->order_by('op_num');
            $this->HostDb->order_by('opb_board');
        }
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Con['pagesize']) == 0){
                $Pn = intval($Row['num']/$Con['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Con['pagesize'])+1;
            }
            log_message('debug', 'Num is '.$Row['num'].' and Pagesize is'.$Con['pagesize'].' and Page Nums is'.$Pn);
            return $Pn;
        }else{
            return false;
        }
    }
    
    /**
     * 生产中用清单
     * @param unknown $Con
     */
    public function select_produce($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            /* if(empty($Con['pn'])){
                $Con['pn'] = $this->_page($Con);
            }else{
                $this->_Num = $Con['num'];
            }
            if(!empty($Con['pn'])){ */
                $Sql = $this->_unformat_as($Item, $this->_Module);
                $this->HostDb->select($Sql, FALSE);
                $this->HostDb->from('order_product_board');
                $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
                $this->HostDb->join('order', 'o_id = op_order_id', 'left');
                
                if(!empty($Con['start_date'])){  /*已确认时间为标准*/
                    $this->HostDb->where('o_asure_datetime > ', $Con['start_date']);
                }
                if(!empty($Con['end_date'])){
                    $this->HostDb->where('o_asure_datetime < ', $Con['end_date']);
                }
                
                $this->HostDb->where('op_product_id', $Con['product']); /*对应订单产品*/
                $this->HostDb->where('o_status > ', 10);   /*对应订单状态*/

                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                    ->like('op_remark', $Con['keyword'])
                    ->or_like('o_remark', $Con['keyword'])
                    ->or_like('o_dealer', $Con['keyword'])
                    ->or_like('o_owner', $Con['keyword'])
                    ->or_like('op_num', $Con['keyword'])
                    ->or_like('opb_board', $Con['keyword'])
                    ->group_end();
                }
                //$this->HostDb->limit($Con['pagesize'], ($Con['p']-1)*$Con['pagesize']);
                $Query = $this->HostDb->get();
                if($Query->num_rows() > 0){
                    $Result = $Query->result_array();
                    $Return = array(
                        'content' => $Result
                        /* 'num' => $this->_Num,
                        'p' => $Con['p'],
                        'pn' => $Con['pn'] */
                    );
                    $this->cache->save($Cache, $Return, HOURS);
                }
            /* }else{
                $GLOBALS['error'] = '没有符合要求板块统计!';
            } */
        }
        return $Return;
    }
    
    private function _page($Con){
        $this->HostDb->select('count(opb_id) as num', FALSE);
        $this->HostDb->from('order_product_board');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
        if(!empty($Con['start_date'])){  /*已确认时间为标准*/
            $this->HostDb->where('o_asure_datetime > ', $Con['start_date']);
        }
        if(!empty($Con['end_date'])){
            $this->HostDb->where('o_asure_datetime < ', $Con['end_date']);
        }
        
        $this->HostDb->where('op_product_id', $Con['product']); /*对应订单产品*/
        $this->HostDb->where('o_status > ', 10);   /*对应订单状态*/
    
        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
            ->like('op_remark', $Con['keyword'])
            ->or_like('o_remark', $Con['keyword'])
            ->or_like('o_dealer', $Con['keyword'])
            ->or_like('o_owner', $Con['keyword'])
            ->or_like('op_num', $Con['keyword'])
            ->or_like('opb_board', $Con['keyword'])
            ->group_end();
        }
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Con['pagesize']) == 0){
                $Pn = intval($Row['num']/$Con['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Con['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }
    
    public function select_order_product_board_by_opid($Opid){
        $Item = $this->_Item.__FUNCTION__;
        if(is_array($Opid)){
            $Cache = $this->_Cache.__FUNCTION__.implode('_', $Opid);
        }else{
            $Cache = $this->_Cache.__FUNCTION__.$Opid;
        }
        if(!($Return = $this->cache->get($Cache))){
            $this->HostDb->select('opb_id, opb_board, opb_amount, opb_area, opb_unit_price, opb_sum',  FALSE);
            $this->HostDb->from('order_product_board');
            if(is_array($Opid)){
                $this->HostDb->where_in('opb_order_product_id', $Opid);
            }else{
                $this->HostDb->where('opb_order_product_id', $Opid);
            }
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Return = $this->_unformat($Return, $Item, $this->_Module);
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合要求的订单产品板材';
            }
        }
        return $Return;
    }
    
    /**
     * 通过订单编号获取板材
     * @param unknown $Oids
     * @return Ambigous <multitype:, string, unknown>
     */
    public function select_by_oid($Oids){
        $Item = $this->_Item.__FUNCTION__;
        if(is_array($Oids)){
            $Cache = $this->_Cache.__FUNCTION__.implode('_', $Oids);
        }else{
            $Cache = $this->_Cache.__FUNCTION__.$Oids;
        }
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, false);
            $this->HostDb->from('order_product_board');
            $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
            $this->HostDb->join('product', 'p_id = op_product_id', 'left');
            if(is_array($Oids)){
                $this->HostDb->where_in('op_order_id', $Oids);
            }else{
                $this->HostDb->where('op_order_id', $Oids);
            }
            $this->HostDb->where('op_status > 0');
            
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '该订单下没有符合要求的订单产品板材';
            }
        }
        return $Return;
    }
    
    /**
     * 选择需要核价的板块
     * @param unknown $Id   订单Id
     * @param unknown $Pid  产品类型Id
     */
    public function select_check_by_opid($Id, $Pid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Id.$Pid;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql,  FALSE);
            $this->HostDb->from('order_product_board');
            $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
            $this->HostDb->join('board', 'b_name = opb_board', 'left');
            $this->HostDb->where(array('op_order_id' => $Id, 'op_product_id' => $Pid));
            $this->HostDb->where('op_status != 0');
            $this->HostDb->order_by('op_num');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合要求的订单产品板材';
            }
        }
        return $Return;
    }

    /**
     * 板块销售统计
     * @param unknown $Con
     */
    public function select_board_predict($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        if(!($Return = $this->cache->get($Cache))){
            $Item = $this->_Item.__FUNCTION__;
            $Sql = $this->_unformat_as($Item, $this->_Module);
    
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product_board');
            $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
    
            if(!empty($Con['start_date'])){
                $this->HostDb->where('o_quoted_datetime > ', $Con['start_date']);
            }
    
            if(!empty($Con['end_date'])){
                $this->HostDb->where('o_quoted_datetime < ', $Con['end_date']);
            }
            $this->HostDb->where('op_status > 2'); /*订单产品已经拆单*/
            $this->HostDb->where('o_status > 8'); /*订单产品已经确认报价*/
    
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '本月还没有开始销售!';
                $Return = false;
            }
        }
        return $Return;
    }

    /**
     * 拆单面积统计
     * @param $Con
     * @return bool
     */
    public function select_dismantle_area($Con) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        if(!($Return = $this->cache->get($Cache))){
            $Item = $this->_Item.__FUNCTION__;
            $Sql = $this->_unformat_as($Item, $this->_Module);

            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product_board');
            $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            $this->HostDb->join('product', 'p_id = op_product_id', 'left');

            $this->HostDb->where('op_dismantler', $Con['self']);
            if(!empty($Con['start_date'])){
                $this->HostDb->where('o_quoted_datetime > ', $Con['start_date']);
            }

            if(!empty($Con['end_date'])){
                $this->HostDb->where('o_quoted_datetime < ', $Con['end_date']);
            }

            $this->HostDb->where('op_status > 2'); /*已拆订单产品*/
            $this->HostDb->where('o_status > 6'); /*已拆订单产品*/

            $this->HostDb->group_by('board, op_product_id');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '在此期间段您没有分解!';
                $Return = false;
            }
        }
        return $Return;
    }

    /**
     * 拆单面积统计详细
     * @param $Con
     * @return bool
     */
    public function select_dismantle_area_detail($Con) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        if(!($Return = $this->cache->get($Cache))){
            $Item = $this->_Item.__FUNCTION__;
            $Sql = $this->_unformat_as($Item, $this->_Module);

            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product_board');
            $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            $this->HostDb->join('product', 'p_id = op_product_id', 'left');

            $this->HostDb->where('op_dismantler', $Con['self']);
            if(!empty($Con['start_date'])){
                $this->HostDb->where('o_quoted_datetime > ', $Con['start_date']);
            }

            if(!empty($Con['end_date'])){
                $this->HostDb->where('o_quoted_datetime < ', $Con['end_date']);
            }

            $this->HostDb->where('op_status > 2'); /*已拆订单产品*/
            $this->HostDb->where('o_status > 6'); /*已拆订单产品*/

            $this->HostDb->order_by('op_product_id, board');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '在此期间段您没有分解!';
                $Return = false;
            }
        }
        return $Return;
    }

    /**
     * 判断是否存在
     * @param unknown $Opid
     * @param unknown $Board
     */
    public function is_exist($Opid, $Board){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Opid.$Board;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->HostDb->select($Sql)
                                        ->from('order_product_board')
                                        ->where('opb_order_product_id', $Opid)
                                        ->where('opb_board', $Board)
                                        ->limit(1)
                                    ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    /**
     * 判断板材是否已经统计
     * @param unknown $Opid order_product_id
     * @param unknown $Board  board
     * @return boolean|Ambigous <unknown>
     */
    public function is_existed($Opid, $Board) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Opid.$Board;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Query = $this->HostDb->select('opb_id')
                                ->from('order_product_board')
                                ->where('opb_order_product_id', $Opid)
                                ->where('opb_board', $Board)
                                ->limit(1)
                            ->get();
            if($Query->num_rows() > 0){
                $Row = $Query->row_array();
                $Query->free_result();
                $Return = $Row['opb_id'];
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
    	return $Return;
    }

    public function select_status($Ids){
    	$Query = $this->HostDb->select('opb_id, opb_status')->from('order_product_board')->where_in('opb_id', $Ids)->get();
    	if($Query->num_rows() > 0){
    		$Return = $Query->result_array();
    		$Return = $this->_unformat($Return, $this->_Item.__FUNCTION__, $this->_Module);
    		return $Return;
    	}else{
    		return false;
    	}
    }
    
    public function insert($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	$Set = $this->_format($Set, $Item, $this->_Module);
    	if($this->HostDb->insert('order_product_board', $Set)){
    		log_message('debug', "Model $Item Success!");
    		$this->remove_cache($this->_Module);
    		return $this->HostDb->insert_id();
    	}else{
    		log_message('debug', "Model $Item Error");
    		return false;
    	}
    }
    
    /**
     * 删除无用的板材统计(之前有过统计，但现在又修改)
     * @param unknown $Opid
     * @param unknown $Opbids
     */
    public function delete_not_in($Opid, $Opbids){
    	$this->HostDb->where('opb_order_product_id', $Opid);
    	$this->HostDb->where_not_in('opb_id', $Opbids);
    	$this->remove_cache($this->_Module);
    	return $this->HostDb->delete('order_product_board');
    }
    
    public function update($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item, $this->_Module);
        if(is_array($Where)){
            $this->HostDb->where('opb_id', $Where);
        }else{
            $this->HostDb->where('opb_id', $Where);
        }
        $this->HostDb->update('order_product_board', $Data);
        log_message('debug', "Model Order_product_board_model/update_batch!");
        $this->remove_cache($this->_Module);
        return true;
    }
    /**
     * 更新已统计的板材的信息
     * @param unknown $Data
     * @return boolean
     */
    public function update_batch($Data){
    	$Item = $this->_Item.__FUNCTION__;
    	foreach ($Data as $key => $value){
    		$Data[$key] = $this->_format_re($value, $Item, $this->_Module);
    	}
    	$this->HostDb->update_batch('order_product_board', $Data, 'opb_id');
    	log_message('debug', "Model Order_product_board_model/update_batch!");
    	$this->remove_cache($this->_Module);
    	return true;
    }

    /**
     * 由于优化进行更新标志
     * @param unknown $Ids 订单产品板材id
     * @param unknown $Time 优化批次
     * @return array $Return 订单产品编号
     */
    public function update_optimize($Ids, $Time){
        $OrderProductBoard = array();
        $Shift = array(); /*不同的订单产品进行区分-标志*/
        $Return = array();
        $Query = $this->HostDb->select('opb_id, op_id')
                            ->from('order_product_board')
                            ->join('order_product', 'op_id = opb_order_product_id', 'left')
                            ->order_by('op_num')
                            ->where_in('opb_id', $Ids)
                            ->get();
        if($Query->num_rows() > 0){
            $Oids = $Query->result_array();
            $Query->free_result();
            
            $Num = 1;
            $Uid = $this->session->userdata('uid');
            foreach ($Oids as $key => $value){
                if(!isset($Shift[$value['op_id']])){
                    $Shift[$value['op_id']] = $Num++;
                }
                $OrderProductBoard[] = array(
                    'opb_id' => $value['opb_id'],
                    'opb_optimize' => $Shift[$value['op_id']],
                    'opb_optimizer' => $Uid,
                    'opb_optimize_datetime' => $Time
                );
                $Return[] = $value['op_id'];
            }
            $this->HostDb->update_batch('order_product_board', $OrderProductBoard, 'opb_id');
            $this->remove_cache($this->_Module);
            $Return = array_unique($Return);
            return $Return;
        }else{
            $GLOBALS['error'] = '您要查看优化的订单不存在';
            return false;
        }
    }
    /**
     * 增加数据, BD文件导入时，需要一条一条的导入
     * 如果需要重新导入，则需要清除数据
     * @param unknown $Data
     * @param unknown $Where
     */
    public function update_increase($Data, $Where){
        return $this->HostDb->query("UPDATE n9_order_product_board set opb_amount = opb_amount + {$Data['amount']}
                                        , opb_area = opb_area + {$Data['area']} where opb_id = {$Where}");
    }
    
    public function update_status($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item, $this->_Module);
        }
        $this->_remove_cache();
        return $this->HostDb->update_batch('order_product_board', $Data, 'opb_id');
    }

    private function _remove_cache(){
    	$this->load->helper('file');
    	delete_cache_files('(.*'.$this->_Module.'.*)');
    }
}