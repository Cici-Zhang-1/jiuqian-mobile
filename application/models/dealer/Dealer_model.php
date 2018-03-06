<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-25
 * @author ZhangCC
 * @version
 * @description
 * 经销商
 */
class Dealer_model extends Base_Model{
    private $_Module = 'dealer';
    private $_Model;
    private $_Item;
    private $_Cache;
	static $Default;
	public function __construct(){
		parent::__construct();
		$this->_Model = strtolower(__CLASS__);
		$this->_Item = $this->_Module.'/'.$this->_Model.'/';
		$this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
		
		log_message('debug', 'Model Dealer/Dealer_model Start!');
	}
	
	public function select_dealer($Con, $Public = true){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con).$Public;
	    if(!($Return = $this->cache->get($Cache))){
	        if(empty($Con['pn'])){
	            $Con['pn'] = $this->_page($Con, $Public);
	        }else{
	            $this->_Num = $Con['num'];
	        }
	        if(!empty($Con['pn'])){
	            $Sql = $this->_unformat_as($Item, $this->_Module);
	            $this->HostDb->select($Sql, FALSE);
	            $this->HostDb->from('dealer');
    	        $this->HostDb->join('area as d', 'd.a_id = d_area_id', 'left');
    	        $this->HostDb->join('dealer_category', 'dc_id = d_company_type_id', 'left');
    	        $this->HostDb->join('user as c', 'c.u_id = d_creator_id', 'left');
    	        $this->HostDb->join('n9_dealer_linker', 'dl_dealer_id = d_id && dl_primary=1', 'left', false);
    	        $this->HostDb->join('payterms', 'p_id = d_payterms_id', 'left');
    	        
    	        if(!$Public){
    	            $this->HostDb->join('n9_dealer_owner', 'do_dealer_id = d_id', 'left', false);
    	            $this->HostDb->join('user as o', 'o.u_id = do_owner_id', 'left');
    	            $this->HostDb->where('do_owner_id', $this->session->userdata('uid'));
    	        }else{
    	            $this->HostDb->join('n9_dealer_owner', 'do_dealer_id = d_id && do_primary=1', 'left', false);
    	            $this->HostDb->join('user as o', 'o.u_id = do_owner_id', 'left');
    	        }
	             
	            if(!empty($Con['keyword'])){
	                $this->HostDb->group_start()
            	                ->like('d_des', $Con['keyword'])
                	                ->or_like('dl_name', $Con['keyword'])
                	                ->or_like('dl_mobilephone', $Con['keyword'])
                	                ->or_like('d_remark', $Con['keyword'])
                	                ->or_like('p_name', $Con['keyword'])
            	                ->group_end();
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
	            $GLOBALS['error'] = '没有符合要求的客户!';
	        }
	    }
	    return $Return;
	}

	public function _page($Con, $Public){
	    $this->HostDb->select('count(d_id) as num', FALSE);
	    $this->HostDb->from('dealer');
	    $this->HostDb->join('n9_dealer_linker', 'dl_dealer_id = d_id && dl_primary=1', 'left', false);
	    $this->HostDb->join('payterms', 'p_id = d_payterms_id', 'left');
	    //$this->HostDb->join('n9_dealer_owner', 'do_dealer_id = d_id && do_primary=1', 'left', false);
	    /* 
	    $this->HostDb->where('dl_primary=1'); */
	    if(!$Public){
	        $this->HostDb->join('n9_dealer_owner', 'do_dealer_id = d_id', 'left', false);
	        $this->HostDb->where('do_owner_id', $this->session->userdata('uid'));
	    }else{
	        $this->HostDb->join('n9_dealer_owner', 'do_dealer_id = d_id && do_primary=1', 'left', false);
	    }
	    
	    if(!empty($Con['keyword'])){
	        $this->HostDb->group_start()
                	        ->like('d_des', $Con['keyword'])
                	        ->or_like('dl_name', $Con['keyword'])
                	        ->or_like('dl_mobilephone', $Con['keyword'])
                	        ->or_like('d_remark', $Con['keyword'])
                	        ->or_like('p_name', $Con['keyword'])
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
	
	public function select_dealer_money(){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $this->HostDb->select($Sql, FALSE);
	        $this->HostDb->from('dealer');
	        $this->HostDb->join('area as d', 'd.a_id = d_area_id', 'left');
	        $this->HostDb->join('dealer_category', 'dc_id = d_company_type_id', 'left');
	        $this->HostDb->join('user', 'u_id = d_creator_id', 'left');
	        $this->HostDb->join('n9_dealer_linker', 'dl_dealer_id = d_id && dl_primary=1', 'left', false);
	        $this->HostDb->join('payterms', 'p_id = d_payterms_id', 'left');
	         
	        $Query = $this->HostDb->get();
	        if($Query->num_rows() > 0){
	            $Return = array(
	                'content' => $Query->result_array()
	            );
	            $Query->free_result();
	            $this->cache->save($Cache, $Return, MONTHS);
	        }
	    }
	    return $Return;
	}
	
	public function select_all(){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__;
	    if(!($Return = $this->cache->get($Cache))){
	        $this->load->model('dealer/dealer_organization_model');
	        $CheckerId = $this->dealer_organization_model->select_doid_by_name('设计师');
	        $PayerId = $this->dealer_organization_model->select_doid_by_name('财务');
	        
	        $Sql = $this->_unformat_as($Item, $this->_Module);
	        $this->HostDb->select($Sql, FALSE);
	        $this->HostDb->from('dealer');
	        $this->HostDb->join('area as d', 'd.a_id = d_area_id', 'left');
	        $this->HostDb->join('payterms', 'p_id = d_payterms_id', 'left');
	        $this->HostDb->join('n9_dealer_linker as A', 'A.dl_dealer_id = d_id && A.dl_primary = 1', 'left', false);
	        $this->HostDb->join('n9_dealer_linker as B', 'B.dl_dealer_id = d_id && B.dl_type = '.$CheckerId, 'left', false);
	        $this->HostDb->join('n9_dealer_linker as C', 'C.dl_dealer_id = d_id && C.dl_type = '.$PayerId, 'left', false);
	        $this->HostDb->join('n9_dealer_delivery', 'dd_dealer_id = d_id && dd_default = 1', 'left', false);
	        $this->HostDb->join('area as dd', 'dd.a_id = dd_area_id', 'left');
	        $this->HostDb->join('logistics', 'l_id = dd_logistics_id', 'left');
	        $this->HostDb->join('out_method', 'om_id = dd_out_method_id', 'left');
	        
	        $Query = $this->HostDb->get();
	        if($Query->num_rows() > 0){
	            $Return = array(
	                'content' => $Query->result_array()
	            );
	            $Query->free_result();
	            $this->cache->save($Cache, $Return, MONTHS);
	        }
	    }
	    return $Return;
	}

    /**
     * 获取经销商的信息
     * @param integer $Id
     */
    public function select_dealer_detail($Id = null){
        $this->HostDb->select('d_id, d_des, 
		          d.a_id, concat(d.a_province, d.a_city, ifnull(d.a_county, \'\'), \'-\', ifnull(d_address,\'\')) as area,
				dc_id, dc_name,d_remark,from_unixtime(d_create_datetime) as d_create_datetime,
				d_status, u_truename, dl_name, ifnull(dl_mobilephone, ifnull(dl_telephone, \'\')) as way',  FALSE);
        $this->HostDb->from('dealer');
        $this->HostDb->join('area as d', 'd.a_id = d_area_id', 'left');
        $this->HostDb->join('dealer_category', 'dc_id = d_company_type_id', 'left');
        $this->HostDb->join('user', 'u_id = d_creator_id', 'left');
        $this->HostDb->join('dealer_linker', 'dl_dealer_id = d_id && dl_type=1', 'left');
        if(!is_null($Id)){
            if(is_array($Id)){
                $this->HostDb->where_in('d_id', $Id);
            }elseif (is_int($Id)){
                $this->HostDb->where('d_id', $Id);
            }
        }
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }
	
	public function select_default_value($Key, $Did){
		if(empty(self::$Default[$Did])){
			$this->HostDb->select('d_logistics_id, d_payterms_id, r.a_id as ra_id,
				concat(r.a_city, ifnull(r.a_county, \'\')) as receiver_area,
				d_receiver_address as receiver_address',  FALSE);
			$this->HostDb->from('dealer');
			$this->HostDb->join('area as r', 'r.a_id = d_receiver_area_id', 'left');
			$this->HostDb->where('d_id', $Did);
			$Query = $this->HostDb->get();
			if($Query->num_rows() > 0){
				self::$Default[$Did] = $Query->row_array();
			}
		}
		if(!empty(self::$Default[$Did])){
			return self::$Default[$Did][$Key];
		}else{
			return false;
		}
	}
	
	public function select_dealer_json(){
	    $this->HostDb->select('d_id, concat(d_name, \'|\', d_des, \'|\', a_province, a_city, ifnull(a_county, \'\'), ifnull(d_address,\'\'),
					\'|\', dl_name, \'|\', dl_mobilephone, \'|\', dl_telephone, \'|\', dl_qq,
				\'|\', dl_fax, \'|\', dl_email) as dealer', FALSE);
	    $this->HostDb->from('dealer');
	    $this->HostDb->join('area', 'a_id = d_area_id', 'left');
	    $this->HostDb->join('dealer_linker', 'dl_dealer_id = d_id', 'left');
	    $this->HostDb->where(array('d_status' => 1, 'dl_primary' => 1));
	    $Query = $this->HostDb->get();
	    if($Query->num_rows() > 0){
	        return $Query->result_array();
	    }else{
	        return false;
	    }
	}
	
	/**
	 * 计算发货欠款
	 * @param unknown $Did
	 */
	public function select_deliveried_debt($Did) {
	    $Query = $this->HostDb->select('d_deliveried, d_received')->from('dealer')->where(array('d_id' => $Did))->get();
	    if($Query->num_rows() > 0){
	        $Row = $Query->row_array();
	        $Return = $Row['d_received'] - $Row['d_deliveried'];
	        return $Return;
	    }else{
	        return false;
	    }
	}
	
	public function is_valid($Did){
	    $Sql = $this->_unformat_as($this->_Item.__FUNCTION__, $this->_Module);
	    $this->HostDb->select($Sql, false);
	    $this->HostDb->from('dealer');
	    $this->HostDb->join('area as d', 'd.a_id = d_area_id', 'left');
	    $this->HostDb->join('n9_dealer_linker as A', 'A.dl_dealer_id = d_id && A.dl_primary = 1', 'left', false);
	    $this->HostDb->where('d_id', $Did);
	    
	    $Query = $this->HostDb->get();
	    if($Query->num_rows() > 0){
	        return $Query->row_array();;
	    }else{
	        $GLOBALS[] = '该经销商不存在!';
	        return false;
	    }
	}
    /**
     * 判断是否为有效的经销商，并返回信息
     * @param unknown $Did
     */
	private function _is_valid_dealer($Did, $Sql = ''){
	     $Mutiple = false;
	     if(is_array($Did)){
	         $this->HostDb->where_in('d_id', $Did);
	         $Mutiple = true;
	     }else{
	         $this->HostDb->where('d_id', $Did);
	     }
	     if(!empty($Sql)){
	         $Sql = $this->_unformat_as($Sql, $this->_Module);
	         $this->HostDb->select($Sql, false);
	     }
	     $Query = $this->HostDb->get('dealer');
	     if($Query->num_rows() > 0){
	         if($Mutiple){
	             $Return = $Query->result_array();
	         }else{
	             $Return = $Query->row_array();
	         }
	         $Query->free_result();
	         log_message('debug', 'Model Dealer/Dealer_model _is_valid_dealer on success');
	         return $Return;
	     }
	     log_message('debug', 'Model Dealer/Dealer_model _is_valid_dealer on failue');
	     return false;
	}

	/**
	 * 插入经销商
	 * @param unknown $Data
	 */
	public function insert($Data){
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format($Data, $Item, $this->_Module);
	    if($this->HostDb->insert('dealer', $Data)){
	        log_message('debug', "Model Dealer_model/insert Success!");
	        $this->remove_cache($this->_Module);
	        return $this->HostDb->insert_id();
	    }else{
	        log_message('debug', "Model Dealer_model/insert Error");
	        return false;
	    }
	}
	
	/**
	 * 更新经销商信息
	 * @param unknown $Data
	 * @param unknown $Where
	 */
	public function update($Data, $Where){
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format_re($Data, $Item, $this->_Module);
	    $this->HostDb->where('d_id', $Where);
	    $this->HostDb->update('dealer', $Data);
	    $this->remove_cache($this->_Module);
	    return TRUE;
	}
	
	/**
	 * 批量自改经销商信息
	 */
	public function update_batch($Data){
	    $Item = $this->_Item.__FUNCTION__;
	    foreach ($Data as $key => $value){
	        $Data[$key] = $this->_format_re($value, $Item, $this->_Module);
	    }
	    $this->remove_cache($this->_Module);
	    return $this->HostDb->update_batch('dealer', $Data, 'd_id');
	}
	
	/**
	 * 更新经销商属主
	 * @param unknown $Where
	 * @param number $Set
	 */
	public function update_owner($Where, $Set = 0){
	    if(is_array($Where)){
	        $this->HostDb->where_in('d_id', $Where);
	    }else{
	        $this->HostDb->where(array('d_id' => $Where));
	    }
	    $this->HostDb->set(array('d_owner' => $Set));
	    $this->HostDb->update('dealer');
	    $this->remove_cache($this->_Module);
	    return true;
	}
	
	/**
	 * 更新经销商进账
	 * @param unknown $Data
	 * @param unknown $Where
	 */
	public function update_dealer_received($Data, $Where){
	    if(!!($Dealer = $this->_is_valid_dealer($Where))){
	        $Received = $Dealer['d_received'] + $Data;
	        $Balance = $Received - $Dealer['d_deliveried'];
	        if($this->HostDb->query("UPDATE n9_dealer SET d_received = $Received
	                   , d_balance = $Balance WHERE d_id = $Where")){
                $this->remove_cache($this->_Module);
                log_message('debug', 'Model Dealer/Dealer_model update_dealer_received on $Balance = '.$Balance);
	            return $Balance;
	        }else{
	            $GLOBALS['error'] = '更新经销商进账时出错';
	            return false;
	        }
	    }else{
	        $GLOBALS['error'] = '不是有效的经销商';
	        return false;
	    }
	}
	
	/**
	 * 更新经销商的发货欠款
	 * @param unknown $Data
	 * @param unknown $Where
	 */
	public function update_dealer_deliveried($Data){
	    if(!!($Dealer = $this->_is_valid_dealer(array_keys($Data)))){
	        $Set = array();
	        foreach ($Dealer as $key=>$value){
	            $Set[] = array(
	                'd_id' => $value['d_id'],
	                'd_deliveried' => $value['d_deliveried'] + $Data[$value['d_id']],
	                'd_debt2' => $value['d_debt2'] - $Data[$value['d_id']],
	                'd_balance' => $value['d_balance'] - $Data[$value['d_id']]
	            );
	        }
	        $this->HostDb->update_batch('dealer',$Set, 'd_id');
	        $this->remove_cache($this->_Module);
	        return true;
	    }else{
	        $GLOBALS['error'] = '不是有效的经销商';
	        return false;
	    }
	}
	
	/**
	 * 重新发货时需要重置经销商账目
	 * @param unknown $Data
	 */
	public function update_dealer_redelivery($Data){
	    if(!!($Dealer = $this->_is_valid_dealer(array_keys($Data)))){
	        $Set = array();
	        foreach ($Dealer as $key=>$value){
	            $Set[] = array(
	                'd_id' => $value['d_id'],
	                'd_deliveried' => $value['d_deliveried'] - $Data[$value['d_id']],
	                'd_debt2' => $value['d_debt2'] + $Data[$value['d_id']],
	                'd_balance' => $value['d_balance'] + $Data[$value['d_id']]
	            );
	        }
	        $this->HostDb->update_batch('dealer',$Set, 'd_id');
	        $this->remove_cache($this->_Module);
	        return true;
	    }else{
	        $GLOBALS['error'] = '不是有效的经销商';
	        return false;
	    }
	}
	
	/**
	 * 重新核价/重新拆单时要时要清除账目
	 * @param unknown $Debt
	 */
	public function update_dealer_re($Debt){
	    $Item = $this->_Item.__FUNCTION__;
	    foreach ($Debt as $key => $value){
	        $this->HostDb->set('d_debt1', 'd_debt1 - '.$value, false);
	        $this->HostDb->where('d_id', $key);
	        $this->HostDb->update('dealer');
	        log_message('debug', 'Model Dealer/Dealer_model update_dealer_re on $Debt = '.$value);
	    }
	    $this->remove_cache($this->_Module);
	    return true;
	}
	
	/**
	 * 售后服务修改价格
	 */
	public function update_dealer_debt1_post_sale($Data, $Did, $Type){
	    if(!!($Dealer = $this->_is_valid_dealer($Did))){
	        if('debt1' == $Type){
	            $this->HostDb->set('d_debt1', 'd_debt1 - '.$Data, false);
	        }else{
	            $this->HostDb->set('d_debt2', 'd_debt2 - '.$Data, false);
	        }
	        $this->HostDb->where('d_id', $Did);
	        $this->HostDb->update('dealer');
	        $this->remove_cache($this->_Module);
	        return true;
	    }else{
	        $GLOBALS['error'] = '不是有效的经销商';
	        return false;
	    }
	}
	/**
	 * 更新经销商的正在生产金额
	 */
	public function update_dealer_debt2($Data){
	    if(!!($Dealer = $this->_is_valid_dealer(array_keys($Data)))){
	        $Set = array();
	        foreach ($Dealer as $key=>$value){
	            $Set[] = array(
	                'd_id' => $value['d_id'],
	                'd_debt1' => $value['d_debt1'] - $Data[$value['d_id']],
	                'd_debt2' => $value['d_debt2'] + $Data[$value['d_id']]
	            );
	            log_message('debug', __FILE__.__LINE__.'zhangcc On dealer = '.$value['d_id'].' d_debt1 = '.($value['d_debt1'] - $Data[$value['d_id']]).'d_debt2='.($value['d_debt2'] + $Data[$value['d_id']]));
	        }
	        $this->HostDb->update_batch('dealer',$Set, 'd_id');
	        $this->remove_cache($this->_Module);
	        return true;
	    }else{
	        $GLOBALS['error'] = '不是有效的经销商';
	        return false;
	    }
	}
	
	/**
	 * 订单删除时更新经销商的财务
	 * @param unknown $Debt
	 * @param unknown $Type
	 */
	public function update_dealer_remove($Debt, $Type){
	    $Item = $this->_Item.__FUNCTION__;
	    foreach ($Debt as $key => $value){
	        if('debt1' == $Type){
	            $this->HostDb->set('d_debt1', 'd_debt1 - '.$value, false);
	        }else{
	            $this->HostDb->set('d_debt2', 'd_debt2 - '.$value, false);
	        }
	        $this->HostDb->where('d_id', $key);
	        $this->HostDb->update('dealer');
	    }
	    $this->remove_cache($this->_Module);
	    return true;
	}

	/**
	 * 删除经销商
	 * @param unknown $Where
	 */
	public function delete_dealer($Where){
	    if(is_array($Where)){
	        $this->HostDb->where_in('d_id', $Where);
	    }else{
	        $this->HostDb->where('d_id', $Where);
	    }
	    $this->remove_cache($this->_Module);
	    $this->HostDb->delete('dealer');
		return true;
	}
}