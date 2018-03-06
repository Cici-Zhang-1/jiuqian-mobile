<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2014-11-19
 * @author ZhangCC
 * @version
 * @description  
 */
class Order_model extends MY_Model{
	public function __construct(){
		parent::__construct(__DIR__, __CLASS__);
		log_message('debug', 'Model Order/Order_model start!');
	}
	
	public function select($Con, $Sql = '', $OrderBy = array(), $Public = TRUE){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    if(isset($Con['public'])){
	        if(0 == $Con['public']){
	            $Public = FALSE;
	        }else{
	            $Public = TRUE;
	        }
	        unset($Con['public']);
	    }
	    if(!($Return = $this->cache->get($Cache))){
	        if(empty($Con['pn'])){
                $Con['pn'] = $this->_page($Con, $Public);
            }else{
                $this->_Num = $Con['num'];
            }
	        if(!empty($Con['pn'])){
	            if(empty($Sql)){
                    $Item = $this->_Item.__FUNCTION__;
                    $Sql = $this->_unformat_as($Item, $this->_Module);
                }else{
                    $Sql = $this->_unformat_as($Sql, $this->_Module);
                }
	            $this->HostDb->select($Sql, FALSE);
	            $this->HostDb->from('order');
	            $this->HostDb->join('dealer', 'd_id = o_dealer_id', 'left');
	            $this->HostDb->join('user', 'u_id = o_creator', 'left');
	            $this->HostDb->join('task_level', 'tl_id = o_flag', 'left');
	            $this->HostDb->join('workflow', 'w_no = o_status', 'left');

	            $this->HostDb->where('w_type', 'order');
	            
	            if(!$Public){
	                $this->HostDb->where('o_creator', $this->session->userdata('uid'));
	            }

	            if(isset($Con['status']) && '' != $Con['status']){
	                $this->HostDb->where("(o_status in ({$Con['status']}))");
	            }
	            
	            if(!empty($Con['flag'])){
	                $this->HostDb->where("(o_flag in ({$Con['flag']}))");
	            }
	            
	            if(!empty($Con['keyword'])){
	                $this->HostDb->group_start()
                    	                ->like('o_remark', $Con['keyword'])
                    	                ->or_like('o_dealer', $Con['keyword'])
                    	                ->or_like('o_owner', $Con['keyword'])
                    	                ->or_like('o_num', $Con['keyword'])
                	                ->group_end();
	            }
	             
	            if(empty($OrderBy)){
	                $this->HostDb->order_by('o_num', 'desc');
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
	            $GLOBALS['error'] = '没有符合要求的订单!';
	        }
	    }
	    return $Return;
	}
	
	public function _page($Con, $Public){
	    $this->HostDb->select('count(o_id) as num', FALSE);
	    $this->HostDb->from('order');

	    if(!$Public){
	        $this->HostDb->where('(o_creator = '.$this->session->userdata('uid').')');
	    }

	    if(isset($Con['status']) && '' != $Con['status']){
	        $this->HostDb->where("(o_status in ({$Con['status']}))");
	    }
	    
	    if(!empty($Con['flag'])){
	        $this->HostDb->where("(o_flag in ({$Con['flag']}))");
	    }
	    
	    if(!empty($Con['keyword'])){
	        $this->HostDb->group_start()
            	                ->like('o_remark', $Con['keyword'])
            	                ->or_like('o_dealer', $Con['keyword'])
            	                ->or_like('o_owner', $Con['keyword'])
            	                ->or_like('o_num', $Con['keyword'])
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
	        log_message('debug', 'Num is '.$Row['num'].' and Pagesize is'.$Con['pagesize'].' and Page Nums is'.$Pn);
	        return $Pn;
	    }else{
	        return false;
	    }
	}
	/**
	 * 获取当前订单的工作流
	 */
	public function select_current_workflow($Oid, $Type){
	    $Item = $this->_Item.__FUNCTION__;
	    $Sql = $this->_unformat_as($Item, $this->_Module);
	    $Query = $this->HostDb->select($Sql)->from('order')
	               ->join('workflow', 'w_no = o_status', 'left')
	               ->where('o_id', $Oid)
	               ->where('w_type', $Type)->limit(1)->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->row_array();
	        $Query->free_result();
	        return $Return;
	    }else{
	        return false;
	    }
	}
	public function select_order($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.implode('_', $Con).__FUNCTION__;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        if(empty($Con['pn'])){
	            $Con['pn'] = $this->_page_num($Con);
	        }else{
                $this->_Num = $Con['num'];
            }
	        if(!empty($Con['pn'])){
	            $Sql = $this->_unformat_as($Item);
	            $this->HostDb->select($Sql, FALSE);
	            $this->HostDb->from('order');
	            $this->HostDb->join('dealer', 'd_id = o_dealer_id', 'left');
	            $this->HostDb->join('user', 'u_id = o_creator', 'left');
	            $this->HostDb->join('task_level', 'tl_id = o_flag', 'left');
	            $this->HostDb->join('workflow', 'w_no = o_status', 'left');
	            
	            $this->HostDb->where('w_type', 'order');
	            
	            if(!empty($Con['start_date'])){
	                $this->HostDb->where('o_create_datetime > ', $Con['start_date']);
	            }

	            if(!empty($Con['end_date'])){
	                $this->HostDb->where('o_create_datetime < ', $Con['end_date']);
	            }

	            if(isset($Con['status']) && '' != $Con['status']){
	                $this->HostDb->where("(o_status in ({$Con['status']}))");
	            }else{
	                $this->HostDb->where('o_status > ', 0);
	            }
	            
	            if(!empty($Con['keyword'])){
	                $this->HostDb->group_start()
                    	                ->like('o_remark', $Con['keyword'])
                    	                ->or_like('o_dealer', $Con['keyword'])
                    	                ->or_like('o_owner', $Con['keyword'])
                    	                ->or_like('o_num', $Con['keyword'])
                	                ->group_end();
	            }
	    
	            $this->HostDb->order_by('o_create_datetime', 'desc');
	    
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
	            $GLOBALS['error'] = '没有符合要求需要核价的订单!';
	        }
	    }
	    return $Return;
    }
	
	private function _page_num($Con){
	    $this->HostDb->select('count(o_id) as num', FALSE);
        $this->HostDb->from('order');

        if(!empty($Con['start_date'])){
            $this->HostDb->where('o_create_datetime > ', $Con['start_date']);
        }
        
        if(!empty($Con['end_date'])){
            $this->HostDb->where('o_create_datetime < ', $Con['end_date']);
        }
        if(isset($Con['status']) && '' != $Con['status']){
            $this->HostDb->where("(o_status in ({$Con['status']}))");
        }else{
            $this->HostDb->where('o_status > ', 0);
        }
         
        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                            ->like('o_remark', $Con['keyword'])
                            ->or_like('o_dealer', $Con['keyword'])
                            ->or_like('o_owner', $Con['keyword'])
                            ->or_like('o_num', $Con['keyword'])
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
            log_message('debug', 'Num is '.$Row['num'].' and Pagesize is'.$Con['pagesize'].' and Page Nums is'.$Pn);
            return $Pn;
        }else{
            return false;
        }
	}
	
	/**
	 * 获取可发货订单
	 * @param unknown $OutMethod 出厂方式
	 */
	public function select_wait_delivery($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order');
            $this->HostDb->join('dealer', 'd_id = o_dealer_id', true);
            $this->HostDb->join('task_level', 'tl_id = o_flag', 'left');
            
            $this->HostDb->where('o_out_method', $Con['out_method']);
            $this->HostDb->where('o_status', $Con['status']);
            
            $this->HostDb->order_by('o_num', 'desc');
    
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合要求的订单!';
            }
	    }
	    return $Return;
	}
	
	/**
	 * 获得拟定发货的订单
	 * @param unknown $Ids
	 * @param unknown $Status
	 */
	public function select_wait_delivery_by_ids($Ids, $Status){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Ids);
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $this->HostDb->select($Sql, FALSE);
	        $this->HostDb->from('order');
	        $this->HostDb->join('dealer', 'd_id = o_dealer_id', true);
	        $this->HostDb->where('o_status', $Status);
	        
	        $this->HostDb->where_in('o_id', $Ids);
	        $this->HostDb->order_by('o_num', 'desc');
	    
	        $Query = $this->HostDb->get();
	        if($Query->num_rows() > 0){
	            $Return = $Query->result_array();
	            $this->cache->save($Cache, $Return, HOURS);
	        }else{
	            $GLOBALS['error'] = '没有符合要求要发货的订单!';
	        }
	    }
	    return $Return;
	}
	
	/**
	 * 获取物流代收的订单
	 * @param unknown $Con
	 */
	public function select_money_logistics($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        if(empty($Con['pn'])){
	            $Con['pn'] = $this->_page_deliveried($Con);
	        }else{
	            $this->_Num = $Con['num'];
	        }
	        if(!empty($Con['pn'])){
	            $Sql = $this->_unformat_as($Item, $this->_Module);
	            $this->HostDb->select($Sql, FALSE);
	            $this->HostDb->from('order');
	            $this->HostDb->join('stock_outted', 'so_id = o_stock_outted_id', 'left');
	            $this->HostDb->join('user', 'u_id = so_creator', 'left');
	
	            $this->HostDb->where('o_status', $Con['status']);
	
	            $this->HostDb->group_start()
                	            ->like('o_remark', $Con['keyword'])
                	            ->or_like('o_dealer', $Con['keyword'])
                	            ->or_like('o_owner', $Con['keyword'])
                	            ->or_like('o_num', $Con['keyword'])
                	            ->or_like('so_truck', $Con['keyword'])
                	            ->or_like('so_train', $Con['keyword'])
            	            ->group_end();
	             
	            $this->HostDb->order_by('o_end_datetime', 'desc');
	            $this->HostDb->order_by('so_truck');
	            $this->HostDb->order_by('so_train');
	            $this->HostDb->order_by('o_num', 'desc');
	             
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
	            $GLOBALS['error'] = '没有符合要求物流代收的订单!';
	        }
	    }
	    return $Return;
	}
	/**
	 * 获取按月结款的订单
	 * @param unknown $Con
	 * @return Ambigous <boolean, multitype:unknown Ambigous <> NULL >
	 */
	public function select_money_month($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        if(empty($Con['pn'])){
	            $Con['pn'] = $this->_page_deliveried($Con);
	        }else{
	            $this->_Num = $Con['num'];
	        }
	        if(!empty($Con['pn'])){
	            $Sql = $this->_unformat_as($Item, $this->_Module);
	            $this->HostDb->select($Sql, FALSE);
	            $this->HostDb->from('order');
	            $this->HostDb->join('stock_outted', 'so_id = o_stock_outted_id', 'left');
	            $this->HostDb->join('user', 'u_id = so_creator', 'left');
	
	            $this->HostDb->where('o_status', $Con['status']);
	
	            $this->HostDb->group_start()
                    	            ->like('o_remark', $Con['keyword'])
                    	            ->or_like('o_dealer', $Con['keyword'])
                    	            ->or_like('o_owner', $Con['keyword'])
                    	            ->or_like('o_num', $Con['keyword'])
                    	            ->or_like('so_truck', $Con['keyword'])
                    	            ->or_like('so_train', $Con['keyword'])
                	            ->group_end();
	
	            $this->HostDb->order_by('o_end_datetime', 'desc');
	            $this->HostDb->order_by('so_truck');
	            $this->HostDb->order_by('so_train');
	            $this->HostDb->order_by('o_num', 'desc');
	
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
	            $GLOBALS['error'] = '没有符合要求按月结款的订单!';
	        }
	    }
	    return $Return;
	}
	/**
	 * 获取按月结款的订单
	 * @param unknown $Con
	 * @return Ambigous <boolean, multitype:unknown Ambigous <> NULL >
	 */
	public function select_money_factory($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        if(empty($Con['pn'])){
	            $Con['pn'] = $this->_page_deliveried($Con);
	        }else{
	            $this->_Num = $Con['num'];
	        }
	        if(!empty($Con['pn'])){
	            $Sql = $this->_unformat_as($Item, $this->_Module);
	            $this->HostDb->select($Sql, FALSE);
	            $this->HostDb->from('order');
	            $this->HostDb->join('stock_outted', 'so_id = o_stock_outted_id', 'left');
	            $this->HostDb->join('user', 'u_id = so_creator', 'left');
	
	            $this->HostDb->where('o_status', $Con['status']);
	
	            $this->HostDb->group_start()
                	            ->like('o_remark', $Con['keyword'])
                	            ->or_like('o_dealer', $Con['keyword'])
                	            ->or_like('o_owner', $Con['keyword'])
                	            ->or_like('o_num', $Con['keyword'])
                	            ->or_like('so_truck', $Con['keyword'])
                	            ->or_like('so_train', $Con['keyword'])
            	            ->group_end();
	
	            $this->HostDb->order_by('o_end_datetime', 'desc');
	            $this->HostDb->order_by('so_truck');
	            $this->HostDb->order_by('so_train');
	            $this->HostDb->order_by('o_num', 'desc');
	
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
	            $GLOBALS['error'] = '没有符合要求到厂付款的订单!';
	        }
	    }
	    return $Return;
	}
	
	/**
	 * 获得已发货的订单
	 */
	public function select_deliveried($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        if(empty($Con['pn'])){
	            $Con['pn'] = $this->_page_deliveried($Con);
	        }else{
	            $this->_Num = $Con['num'];
	        }
	        if(!empty($Con['pn'])){
	            $Sql = $this->_unformat_as($Item, $this->_Module);
	            $this->HostDb->select($Sql, FALSE);
	            $this->HostDb->from('order');
	            $this->HostDb->join('stock_outted', 'so_id = o_stock_outted_id', 'left');
	            $this->HostDb->join('user', 'u_id = so_creator', 'left');

	            $this->HostDb->where('o_status', $Con['status']);
	            
	            $this->HostDb->group_start()
                	            ->like('o_remark', $Con['keyword'])
                	            ->or_like('o_dealer', $Con['keyword'])
                	            ->or_like('o_owner', $Con['keyword'])
                	            ->or_like('o_num', $Con['keyword'])
                	            ->or_like('so_truck', $Con['keyword'])
                	            ->or_like('so_train', $Con['keyword'])
            	            ->group_end();
	             
	            $this->HostDb->order_by('o_end_datetime', 'desc');
	            $this->HostDb->order_by('so_truck');
	            $this->HostDb->order_by('so_train');
	            $this->HostDb->order_by('o_num', 'desc');
	             
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
	            $GLOBALS['error'] = '没有符合要求已发货的订单!';
	        }
	    }
	    return $Return;
	}
	private function _page_deliveried($Con, $Public = false){
	    $this->HostDb->select('count(o_id) as num', FALSE);
	    $this->HostDb->from('order');
	    $this->HostDb->join('stock_outted', 'so_id = o_stock_outted_id', 'left');
	
	    $this->HostDb->where('o_status', $Con['status']);
	
	    $this->HostDb->group_start()
                	    ->like('o_remark', $Con['keyword'])
                	    ->or_like('o_dealer', $Con['keyword'])
                	    ->or_like('o_owner', $Con['keyword'])
                	    ->or_like('o_num', $Con['keyword'])
                	    ->or_like('so_truck', $Con['keyword'])
                	    ->or_like('so_train', $Con['keyword'])
            	    ->group_end();
	     
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
	 * 获取预警订单，等待之后的订单，发货出厂之前的订单
	 * @param unknown $Con
	 */
	public function select_order_warn($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order');
            $this->HostDb->join('user', 'u_id = o_creator', 'left');
            $this->HostDb->join('task_level', 'tl_id = o_flag', 'left');
            $this->HostDb->join('workflow', 'w_no = o_status', 'left');
            
            $this->HostDb->where('w_type', 'order');
            
            $this->HostDb->where('o_status > 10');
            $this->HostDb->where('o_status < 16');
            
            $this->HostDb->where('o_request_outdate <= ', $Con['end_date']);
            
            if(!empty($Con['area'])){
                $Area = explode(',', $Con['area']);
                if(count($Area) < 2){
                    $Area = array_pop($Area);
                    if(1 == $Area){
                        $this->HostDb->like('o_dealer', '武汉');
                    }else{
                        $this->HostDb->not_like('o_dealer', '武汉');
                    }
                }
            }
    
            if(!empty($Con['keyword'])){
                $this->HostDb->group_start()
                                ->like('o_remark', $Con['keyword'])
                                ->or_like('o_dealer', $Con['keyword'])
                                ->or_like('o_owner', $Con['keyword'])
                                ->or_like('o_num', $Con['keyword'])
                            ->group_end();
            }
            $this->HostDb->order_by('o_request_outdate', 'asc');
    
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Result = $Query->result_array();
                $Return = array(
                    'content' => $Result
                );
                $this->cache->save($Cache, $Return, HOURS);
            }
	    }
	    return $Return;
	}
	/**
	 * 通过stock_outted_id获取订单
	 * @param unknown $Ids
	 * @param unknown $Status
	 */
	public function select_by_soid($Soid, $Status){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.$Soid.$Status;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $this->HostDb->select($Sql, FALSE);
	        $this->HostDb->from('order');
	        $this->HostDb->join('dealer', 'd_id = o_dealer_id', true);
	        $this->HostDb->where('o_stock_outted_id', $Soid);
	        if(is_integer($Status)){
	            $this->HostDb->where('o_status', $Status);
	        }elseif (is_string($Status)){
	            $this->HostDb->where("o_status in ($Status)");
	        }elseif (is_array($Status)){
	            $this->HostDb->where_in('o_status', $Status);
	        }
	        
	        $this->HostDb->order_by('o_num', 'desc');
	         
	        $Query = $this->HostDb->get();
	        if($Query->num_rows() > 0){
	            $Return = $Query->result_array();
	            $this->cache->save($Cache, $Return, HOURS);
	        }else{
	            $GLOBALS['error'] = '没有符合要求要发货的订单!';
	        }
	    }
	    return $Return;
	}
	
	/**
	 * 订单worklfow转换时更改经销商的账目信息
	 * @param unknown $Id
	 */
	public function select_order_dealer_by_id($Id){
	    $Item = $this->_Item.__FUNCTION__;
	    if(is_array($Id)){
	        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Id);
	    }else{
	        $Cache = $this->_Cache.__FUNCTION__.$Id;
	    }
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order');
            if(is_array($Id)){
                $this->HostDb->where_in('o_id', $Id);
            }else{
                $this->HostDb->where('o_id', $Id);
            }
             
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
	            $GLOBALS['error'] = '没有符合要求物流代收的订单!';
	        }
	    }
	    return $Return;
	}
	/**
	 * 获取同一经销商的某个时间段的订单编号
	 * @param unknown $Did 经销商编号
	 * @param unknown $Startdate 开始时间 确认报价时间
	 */
	public function select_order_num($Did, $Startdate){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.'_'.$Did.'_'.$Startdate;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        
            $this->HostDb->select($Sql,  FALSE);
            $this->HostDb->from('order');
            
            $this->HostDb->where('o_dealer_id', $Did);
            $this->HostDb->where('o_status >=', 8);
            $this->HostDb->where('o_quoted_datetime > ', $Startdate);
            
            $this->HostDb->order_by('o_num', 'desc');
             
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合条件的订单编号';
            }
	    }
	    return $Return;
	}
	
	/**
	 * 根据货号，提取某一时间内的订单
	 * @param unknown $CargoNo
	 * @param unknown $Startdate
	 */
	public function select_order_num_by_cargo_no($CargoNo, $Startdate = ''){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.'_'.$CargoNo.$Startdate;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $this->HostDb->select($Sql,  FALSE);
	        $this->HostDb->from('order');
	        
	        $this->HostDb->where('o_cargo_no', $CargoNo);
	        
	        if(!empty($Startdate)){
	            $this->HostDb->where('o_end_datetime >', $Startdate);
	        }
	        
	        $this->HostDb->order_by('o_num', 'desc');
	         
	        $Query = $this->HostDb->get();
	        if($Query->num_rows() > 0){
	            $Return = $Query->result_array();
	            $this->cache->save($Cache, $Return, HOURS);
	        }else{
	            $GLOBALS['error'] = '没有符合条件的订单编号';
	        }
	    }
	    return $Return;
	}
	
	/**
	 * 获取对账的订单
	 * @param unknown $Did
	 * @param unknown $StartDatetime
	 * @param unknown $EndDatetime
	 */
	public function select_for_debt($Did, $StartDatetime, $EndDatetime){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.'_'.$Did.$StartDatetime.$EndDatetime;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $this->HostDb->select($Sql,  FALSE);
	        $this->HostDb->from('order');
	         
	        $this->HostDb->where('o_quoted_datetime >', $StartDatetime);
	        $this->HostDb->where('o_quoted_datetime <', $EndDatetime);
	        $this->HostDb->where('o_dealer_id', $Did);
	        
	        $this->HostDb->where('o_status > 0');
	         
	        $this->HostDb->order_by('o_num', 'desc');
	    
	        $Query = $this->HostDb->get();
	        if($Query->num_rows() > 0){
	            $Return = $Query->result_array();
	            $this->cache->save($Cache, $Return, HOURS);
	        }else{
	            $GLOBALS['error'] = '没有符合条件的对账订单';
	        }
	    }
	    return $Return;
	}
	
	/**
	 * 选择处于新建或者正在拆单的订单
	 * @param unknown $Where
	 */
	public function select_order_is_dismantling($Where) {
	    $Query = $this->HostDb->select('o_id')
	               ->from('order')->where('o_id', $Where)
	               ->where_in('o_status', array(1,2))->limit(1)->get();
	    if($Query->num_rows() > 0){
	        return true;
	    }else{
	        return false;
	    }
	}
	
	/**
	 * 判断订单是否存在
	 * @param unknown $Where
	 * @return multitype:unknown |boolean
	 */
	public function select_order_is_exist($Where) {
	    $Query = $this->HostDb->select('o_id, o_status')->from('order')->where($Where)->limit(1)->get();
	    if($Query->num_rows() > 0){
	        $Row = $Query->row_array();
	        $Query->free_result();
	        $Return = array(
	            'oid' => $Row['o_id'],
	            'status' => $Row['o_status']
	        );
	        return $Return;
	    }else{
	        return false;
	    }
	}
	
	/**
	 * 获取订单详情
	 * @param unknown $Oid
	 */
	public function select_order_detail($Oid) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Oid;
	    $Return = FALSE;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $Query = $this->HostDb->select($Sql)
	                               ->from('order')
	                               ->join('user', 'u_id = o_creator', 'left')
	                               ->join('n9_workflow', 'w_no = o_status && w_type="order"', 'left', false)
	                               ->where('o_id', $Oid)
	                               ->limit(1)
	                               ->get();
	       if($Query->num_rows() > 0){
	           $Return = $Query->row_array();
	           $Query->free_result();
	           $this->cache->save($Cache, $Return, HOURS);
	           log_message('debug', 'Get Order Detail Success!');
	       }else{
	           $GLOBALS['error'] = '没有符合条件的订单';
	       }
	   }
       return $Return;
	}
	
	public function select_order_sorter($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    if(!($Return = $this->cache->get($Cache))){
            $Item = $this->_Item.__FUNCTION__;
            $Sql = $this->_unformat_as($Item, $this->_Module);
            
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order');

            if(!empty($Con['start_date'])){
                $this->HostDb->where('o_asure_datetime > ', $Con['start_date']);
            }

            if(!empty($Con['end_date'])){
                $this->HostDb->where('o_asure_datetime < ', $Con['end_date']);
            }
            $this->HostDb->where('o_status > ', 10);
             
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
                $GLOBALS['error'] = '没有对应下单排行帮';
                $Return = false;
            }
	    }
	    return $Return;
	}
	

	/**
	 * 订单预计
	 * @param unknown $Con
	 */
	public function select_order_predict($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    if(!($Return = $this->cache->get($Cache))){
	        $Item = $this->_Item.__FUNCTION__;
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	
	        $this->HostDb->select($Sql, FALSE);
	        $this->HostDb->from('order');
	
	        if(!empty($Con['start_date'])){
	            $this->HostDb->where('o_quoted_datetime > ', $Con['start_date']);
	        }
	
	        if(!empty($Con['end_date'])){
	            $this->HostDb->where('o_quoted_datetime < ', $Con['end_date']);
	        }
	        $this->HostDb->where('o_status > ', 8);
	
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
	 * 订单确认之后的
	 * @param unknown $Con
	 */
	public function select_order_asured($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    if(!($Return = $this->cache->get($Cache))){
	        $Item = $this->_Item.__FUNCTION__;
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	
	        $this->HostDb->select($Sql, FALSE);
	        $this->HostDb->from('order');
	
	        if(!empty($Con['start_date'])){
	            $this->HostDb->where('o_asure_datetime > ', $Con['start_date']);
	        }
	
	        if(!empty($Con['end_date'])){
	            $this->HostDb->where('o_asure_datetime < ', $Con['end_date']);
	        }
	        $this->HostDb->where('o_status >= 11');  /*状态为等待生产之后*/
	
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
	 * 获取每日确认的订单
	 * @param unknown $Con
	 */
	public function select_everyday_asured($Con){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
	    if(!($Return = $this->cache->get($Cache))){
	        $Item = $this->_Item.__FUNCTION__;
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	
	        $this->HostDb->select($Sql, FALSE);
	        $this->HostDb->from('order');
	        $this->HostDb->join('user', 'u_id = o_creator', 'left');

			$this->HostDb->join('(select o_id as order_id, sum(case bt_name when 18 then opb_area else 0 end) eighteen, sum(case bt_name when 25 then opb_area else 0 end) twentyfive,
sum(case bt_name when 36 then opb_area else 0 end) thirtysix
from n9_order_product_board left join n9_board on b_name = opb_board left join n9_board_thick on bt_id = b_thick
left join n9_order_product on op_id = opb_order_product_id  left join n9_order on o_id = op_order_id
where (bt_name = 18 || bt_name = 25 || bt_name = 36) group by o_id) as Areas', 'order_id = o_id', 'left');

	        if(!empty($Con['start_date'])){
	            $this->HostDb->where('o_asure_datetime > ', $Con['start_date']);
	        }
	
	        if(!empty($Con['end_date'])){
	            $this->HostDb->where('o_asure_datetime < ', $Con['end_date']);
	        }
	        $this->HostDb->where('o_status >= 11');  /*状态为等待生产之后*/
	
	        $Query = $this->HostDb->get();
	        if($Query->num_rows() > 0){
	            $Return = $Query->result_array();
	            $Query->free_result();
	            $this->cache->save($Cache, $Return, HOURS);
	        }else{
	            $GLOBALS['error'] = '该日没有确认的订单!';
	            $Return = false;
	        }
	    }
	    return $Return;
	}
	
	/**
	 * 是否可拆单
	 * @param unknown $Id
	 */
	public function is_dismantlable($Ids){
	    $Item = $this->_Item.__FUNCTION__;
	    $Sql = $this->_unformat_as($Item, $this->_Module);
	    $Query = $this->HostDb->select($Sql)
                    	    ->from('order')
                    	    ->where_in('o_id', $Ids)
                    	    ->where_in('o_status', array(1,2,3,4,5,6,7))
                    	    ->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->result_array();
	        $Query->free_result();
	        return $Return;
	    }else{
	        $GLOBALS['error'] = '当前订单不存在或者已经拆单!';
	        return false;
	    }
	}
	
	/**
	 * 是否可重新拆单
	 * 等待生产前都可以重新拆单
	 * @param unknown $Ids
	 */
	public function is_redismantlable($Ids){
	    $Item = $this->_Item.__FUNCTION__;
	    $Sql = $this->_unformat_as($Item, $this->_Module);
	    $Query = $this->HostDb->select($Sql)
                    	    ->from('order')
                    	    ->where_in('o_id', $Ids)
                    	    ->where_in('o_status', array(1,2,3,4,5,6,7,8,9,10,11))
                    	    ->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->result_array();
	        $Query->free_result();
	        return $Return;
	    }else{
	        $GLOBALS['error'] = '当前订单已经不能重新拆单!';
	        return false;
	    }
	}
	
	/**
	 * 判断是否可以核价
	 * @param unknown $Ids
	 */
	public function is_checkable($Id){
	    $Item = $this->_Item.__FUNCTION__;
	    $Query = $this->HostDb->select('o_id')
	                           ->from('order')
	                           ->where('o_id', $Id)
	                           ->where_in('o_status', array(4, 5))
	                           ->get();
	    if($Query->num_rows() > 0){
	        return true;
	    }else{
	        $GLOBALS['error'] = '当前订单已经确认核价!';
	        return false;
	    }
	}
	
	/**
	 * 判断当前订单是否可以重新核价
	 * @param unknown $Ids
	 */
	public function is_recheckable($Ids){
	    $Item = $this->_Item.__FUNCTION__;
	    $Sql = $this->_unformat_as($Item, $this->_Module);
	    $Query = $this->HostDb->select($Sql)
                            	    ->from('order')
                            	    ->where_in('o_id', $Ids)
                            	    ->where_in('o_status', array(4,5,6,7,8,9,10,11))
                        	    ->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->result_array();
	        $Query->free_result();
	        return $Return;
	    }else{
	        $GLOBALS['error'] = '当前订单已经不能重新核价!';
	        return false;
	    }
	}
	/**
	 * 判断订单是否有正在核价的订单
	 */
	public function is_checking($Ids){
	    $Query = $this->HostDb->select('o_id as oid, o_num as order_num, o_sum as sum')
	                           ->from('order')
	                           ->where_in('o_id', $Ids)
	                           ->where_in('o_status', array(4, 5))
                           ->get();
	    
	    if($Query->num_rows() > 0){
	        return $Query->result_array();
	    }else{
	        $GLOBALS['error'] = '当前订单已经核价!';
	        return false;
	    }
	}
	
	/**
	 * 判断是否可以报价确认
	 */
	public function is_quotable($Ids){
	    $Item = $this->_Item.__FUNCTION__;
	    $Sql = $this->_unformat_as($Item, $this->_Module);
	    $Query = $this->HostDb->select($Sql)
                    	    ->from('order')
                    	    ->join('dealer', 'd_id = o_dealer_id', 'left')
                    	    ->where_in('o_id', $Ids)
                    	    ->where('o_status', 7)
                    	    ->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->result_array();
	        $Query->free_result();
	        return $Return;
	    }else{
	        $GLOBALS['error'] = '当前订单不存在或者已经报价确认!';
	        return false;
	    }
	}
	
	/**
	 * 判断是否可以报价确认
	 */
	public function is_asurable($Ids){
	    $Item = $this->_Item.__FUNCTION__;
	    $Sql = $this->_unformat_as($Item, $this->_Module);
	    $Query = $this->HostDb->select($Sql)
        	    ->from('order')
        	    ->where_in('o_id', $Ids)
        	    ->where('o_status', 10)
        	    ->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->result_array();
	        $Query->free_result();
	        return $Return;
	    }else{
	        $GLOBALS['error'] = '当前订单不存在或者已经确认生产!';
	        return false;
	    }
	}
	
	/**
	 * 是否可以发货
	 */
	public function is_deliveriable($Num){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.$Num;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $this->HostDb->select($Sql, false)
                    	        ->from('order')
                	        ->where('o_num', $Num);
	        $this->HostDb->where('o_status >= 14'); /*只有处于完全入库之后的订单产品才可以包装*/
	        $this->HostDb->where('o_status < 21'); /*只有处于已出厂之前的订单才可以包装*/
	        $this->HostDb->limit(1);
	        $Query = $this->HostDb->get();
	         
	        if($Query->num_rows() > 0){
	            $Return = $Query->row_array();
	            $Query->free_result();
	            $this->cache->save($Cache, $Return, HOURS);
	        }else{
	            $GLOBALS['error'] = '您要打印发货标签的订单不存在';
	        }
	    }
	    return $Return;
	}
	
	/**
	 * 订单是否可以重新发货
	 * @param unknown $Ids
	 * @param unknown $Status
	 * @param boolean $StouckOutted $Ids是否是stockou_outted Id
	 */
	public function is_redeliveriable($Ids, $Status, $StouckOutted = false){
	    $Item = $this->_Item.__FUNCTION__;
	    $Sql = $this->_unformat_as($Item, $this->_Module);
	    $this->HostDb->select($Sql)->from('order');
	    if($StouckOutted){
	        if(is_array($Ids)){
	            $this->HostDb->where_in('o_stock_outted_id', $Ids);
	        }else{
	            $this->HosDb->where('o_stock_outted_id', $Ids);
	        }
	    }else{
	        if(is_array($Ids)){
	            $this->HostDb->where_in('o_id', $Ids);
	        }else{
	            $this->HosDb->where('o_id', $Ids);
	        }
	    }
	    if(is_integer($Status)){
	        $this->HostDb->where('o_status', $Status);
	    }elseif (is_string($Status)){
	        $this->HostDb->where("o_status in ($Status)");
	    }elseif (is_array($Status)){
	        $this->HostDb->where('o_status', $Status);
	    }
	    
        $Query = $this->HostDb->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->result_array();
	        $Query->free_result();
	        return $Return;
	    }else{
	        $GLOBALS['error'] = '当前订单已经出厂, 不可重新发货!';
	        return false;
	    }
	}
	/**
	 * 判断订单是否可编辑
	 * 已经出厂的就不能编辑的了
	 * @param unknown $Ids
	 */
	public function is_editable($Ids){
	    if(!is_array($Ids)){
	        $Ids = array($Ids);
	    }
	    $Query = $this->HostDb->select('o_id as oid, o_payterms as payterms, o_status as status')
	                           ->from('order')
	                           ->where_in('o_id', $Ids)
	                           ->where('o_status > ', 0) /*订单没有删除*/
	                           ->where('o_status < 17')  /*订单没有发货*/
	                           ->get();
	    if($Query->num_rows() > 0){
	        return $Query->result_array();
	    }else{
	        $GLOBALS['error'] = '订单已经删除或者已经发货，不可编辑信息!';
	        return false;
	    }
	}
	
	/**
	 * 判断订单是否可作废
	 * 已经出厂的、已经生产的都不能删除
	 * @param unknown $Ids
	 */
	public function is_removable($Ids){
	    if(!is_array($Ids)){
	        $Ids = array($Ids);
	    }
	    $Query = $this->HostDb->select('o_id as oid, o_status as status, 
	        o_dealer_id as did, o_payed_datetime as payed_datetime, o_sum as sum')
	                           ->from('order')
	                           ->where_in('o_id', $Ids)
	                           ->where('o_status > ', 0)
	                           ->where('o_status < ', 17)
	                           ->get();
	    if($Query->num_rows() > 0){
	        return $Query->result_array();
	    }else{
	        $GLOBALS['error'] = '订单已经删除或者已经发货，不能删除了!';
	        return false;
	    }
	}
	/**
	 * 判断是否只是服务类产品
	 * @param unknown $Id
	 * @return boolean
	 */
	public function only_server($Id){
	    $Query = $this->HostDb->query("SELECT op_id from n9_order_product where op_order_id
	                           = $Id && op_product_id not in (7)");
	    if($Query->num_rows() > 0){
	        return false;
	    }else{
	        return true;
	    }
	}

	/**
	 * 在已知订单编号中区分未付款的订单
	 * @param unknown $OrderNum 订单编号
	 */
	private function _select_by_order_num($OrderNum){
	     $Query = $this->HostDb->select('o_id')->from('order')
    	               ->where_in('o_num', $OrderNum)
    	               ->group_start()
    	                   ->where('o_payed_datetime is null')
    	                   ->or_where('o_payed_datetime', '0000-00-00 00:00:00')
	                   ->group_end()
	               ->get();
	     if($Query->num_rows() > 0){
	         $Return = $Query->result_array();
	         $Query->free_result();
	         return $Return;
	     }
	     return false;
	}
	/**
	 * 新健订单
	 * @param unknown $Set
	 */
	public function insert_order($Set){
	    log_message('debug', "Model Order_model/insert_order Start");
	    $Item = $this->_Item.__FUNCTION__;
	    log_message('debug', 'Model Order_model/insert_order Order Type Id'.$Set['otid']);
	    if(!!($Order = $this->_generate_order_num($Set['otid']))){
	        log_message('debug', "Model Order_model/insert_order Success!");
	        $Set = $this->_format($Set, $Item, $this->_Module);
	        $this->HostDb->set($Set);
	        $this->HostDb->where(array('o_id' => $Order['oid']));
	        $this->HostDb->update('order');
	        $this->remove_cache($this->_Module);
	        return $Order;
	    }else{
	        log_message('debug', "Model Order_model/insert_order Error");
	        return false;
	    }
	}
	
	/**
	 * 调用存储过程，在保存订单后生成订单编号
	 * @param unknown $Oid
	 * @return boolean
	 * generate_order_num3 返回生成的订单编号;
	 * YYYYMM     YYYYMMDD
	 * @type 订单类型
	 * @num 订单前缀位数201512
	 * @suffix 订单后缀位数0001
	 * @newOrderNum 新订单编号
	 * @oid 订单id号
	 * create PROCEDURE `generate_order_num3`(in type varchar(2), in num int(10), in suffix int(10), out newOrderNum varchar(16), out oid int(10))
	 begin
	 declare currentDate varchar(8);
	 declare maxNum int default 0;
	 declare oldOrderNum varchar(16) default '';
	 if(num = 6) then
	 select date_format(now(), '%Y%m') into currentDate;
	 else
	     select date_format(now(), '%Y%m%d') into currentDate;
	     end if;
	     select concat(type, currentDate) into currentDate;
	     select IFNULL(o_num, '') into oldOrderNum
	     from n9_order
	     where o_num REGEXP concat('^',currentDate)
	     order by o_num desc limit 1;
	     if oldOrderNum != '' then
	     set maxNum = convert(substring(oldOrderNum, -1 * suffix), decimal);
	     end if;
	     select concat(currentDate, LPAD((maxNum+1),suffix,'0')) into newOrderNum;
	     insert into n9_order(o_num) values (newOrderNum);
	     select LAST_INSERT_ID() into oid;
	     END
	     */
	 private function _generate_order_num($Type){
	     if($this->HostDb->query("call generate_order_num3('$Type',".ORDER_PREFIX.", ".ORDER_SUFFIX.", @pp, @oid)", false)){
	         if(!!($Query = $this->HostDb->query('select @pp as order_num, @oid as oid', false))){
	             $Row = $Query->row_array();
	             log_message('debug', 'Model order/order_model _generate_order_num on Num '.$Row['order_num']);
	             return $Row;
	         }
	     }
	     return false;
	 }
	 
	/**
	 * 更新订单信息
	 * @param unknown $Data
	 * @param unknown $Where
	 */
	public function update_order($Data, $Where){
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format_re($Data, $Item);
	    if(is_array($Where)){
	        $this->HostDb->where_in('o_id', $Where);
	    }else{
	        $this->HostDb->where(array('o_id' => $Where));
	    }
	    $this->remove_cache($this->_Module);
	    return $this->HostDb->update('order', $Data);
	}
	
	public function update_batch($Data){
	    $Item = $this->_Item.__FUNCTION__;
	    foreach ($Data as $key => $value){
	        $Data[$key] = $this->_format_re($value, $Item, $this->_Module);
	    }
	    $this->remove_cache($this->_Module);
	    return $this->HostDb->update_batch('order', $Data, 'o_id');
	}
	/**
	 * 更新工作流
	 * @param unknown $Set
	 * @param unknown $Where
	 */
	public function update_workflow($Set, $Where){
	    $Item = $this->_Item.__FUNCTION__;
	    $Set = $this->_format_re($Set, $Item, $this->_Module);
	    if(is_array($Where)){
            $this->HostDb->where_in('o_id',$Where);
        }else{
            $this->HostDb->where('o_id',$Where);
        }
	    $this->HostDb->update('order', $Set);
	    log_message('debug', "Model order_product_model/update_workflow");
	    $this->remove_cache($this->_Module);
	    return true;
	}
	/**
	 * 更新订单支付状态
	 */
	public function update_order_payed($OrderNum, $CargoNo = ''){
	    if(!!($Oids = $this->_select_by_order_num($OrderNum))){
	        foreach ($Oids as $key => $value){
	            $Oids[$key] = $value['o_id'];
	        }
	        $this->HostDb->set(array('o_payed_datetime' => date('Y-m-d H:i:s')));
	        if(!empty($CargoNo)){
	            $this->HostDb->set(array('o_cargo_no' => $CargoNo));
	        }
	        $this->HostDb->where_in('o_id', $Oids);
	        $this->HostDb->update('order');
	        $this->remove_cache($this->_Module);
	        return $Oids;
	    }
	    return true;
	}
	
	/**
	 * 物流代收之重新付款
	 * @param unknown $CargoNo
	 */
	public function update_order_repay($CargoNo){
	    if(!!($Oids = $this->select_order_num_by_cargo_no($CargoNo))){
	        foreach ($Oids as $key => $value){
	            $Oids[$key] = $value['oid'];
	        }
	        $this->HostDb->set(array('o_payed_datetime' => null));
	        $this->HostDb->set(array('o_cargo_no' => null));
	        $this->HostDb->where_in('o_id', $Oids);
	        $this->HostDb->update('order');
	        $this->remove_cache($this->_Module);
	        return $Oids;
	    }
	    return true;
	}
	
	/**
	 * 更新货号
	 * @param unknown $OrderNum
	 */
	/* public function update_cargo_no($OrderNum, $CargoNo){
	    if(empty($OrderNum)){
	        $this->HostDb->set('o_cargo_no', '');
	        $this->HostDb->where('o_cargo_no', $CargoNo);
	    }else{
	        $this->HostDb->set('o_cargo_no', $CargoNo);
	        $this->HostDb->where_in('o_num', $OrderNum);
	    }
	    $this->HostDb->update('order');
	    $this->remove_cache($this->_Cache);
	    return true;
	} */
	
	/**
	 * 更新订单的确认时间
	 * @param unknown $Ids
	 */
	public function update_asure_datetime($Ids){
	    $this->HostDb->set(array('o_asure_datetime' => date('Y-m-d H:i:s')));
	    if(is_array($Ids)){
	        $this->HostDb->where_in('o_id', $Ids);
	    }else{
	        $this->HostDb->where('o_id', $Ids);
	    }
	    $this->HostDb->update('order');
	    $this->remove_cache($this->_Module);
	}
	
	/**
	 * 重新核价/重新拆单时这些内容清空
	 * @param unknown $Ids
	 */
	public function update_order_re($Ids){
	    $this->HostDb->set(array('o_asure_datetime' => null, 'o_payed_datetime' => null, 'o_sum' => 0, 'o_sum_detail' => ''));
	    if(is_array($Ids)){
	        $this->HostDb->where_in();
	    }else{
	        $this->HostDb->where('o_id', $Ids);
	    }
	    $this->HostDb->update('order');
	    return true;
	}
    /**
     * 更新订单状态
     * @param unknown $Data
     */
	public function update_status($Data){
	    $Item = $this->_Item.__FUNCTION__;
	    foreach ($Data as $key => $value){
	        $Data[$key] = $this->_format($value, $Item, $this->_Module);
	    }
	    $this->remove_cache($this->_Module);
	    return $this->HostDb->update_batch('order', $Data, 'o_id');
	}
	
	/**
	 * 获取订单当前状态
	 * @param unknown $Ids
	 */
	public function select_status($Ids){
	    $Query = $this->HostDb->select('o_id, o_status')->from('order')->where_in('o_id', $Ids)->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->result_array();
	        $Return = $this->_unformat($Return, $this->_Item.__FUNCTION__, $this->_Module);
	        return $Return;
	    }else{
	        return false;
	    }
	}

	public function delete_order($Where) {
	    if(is_array($Where)){
	        $this->HostDb->where_in('o_id', $Where);
	    }else{
	        $this->HostDb->where('o_id', $Where);
	    }
	    return $this->HostDb->delete('order');
	}

	private function _remove_cache(){
	    $this->load->helper('file');
	    delete_cache_files('(.*'.$this->_Module.'.*)');
	}
}
