<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月24日
 * @author zhangcc
 * @version
 * @des
 */
class Order_product_board_plate_model extends MY_Model{
	private $_Module = 'order';
	private $_Model;
	private $_Item;
	private $_Cache;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Order/Order_product_board_plate_model start!');
        $this->e_cache->open_cache();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
    }
    /**
     * 选择Bd文件
     * @param unknown $Ids 订单产品Id号
     */
    public function select_bd_files($Ids){
        $Query = $this->HostDb->select('opbp_qrcode as qrcode, opbp_bd_file as bd_file', false)
                                ->from('order_product_board_plate')
                                ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
                                ->where_in('opb_order_product_id', $Ids)
                                ->where('opbp_bd_file is not null')
                                ->where('opbp_bd_file != ""')
                                ->get();
        if($Query->num_rows() > 0){
            $Return = $Query->result_array();
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 获取优化的板块
     * @param $Ids
     * @return bool
     */
    public function select_optimize($Ids){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Ids);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql,false)
                                    ->from('order_product_board_plate')
                                    ->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left')
                                    ->join('order_product', 'op_id = opc_order_product_id', 'left')
                                    ->join('order','o_id = op_order_id', 'left')
                                    ->join('dealer','d_id = o_dealer_id', 'left')
                                    ->where_in('opbp_order_product_classify_id', $Ids)
                                    ->order_by('opc_sn', 'acs')->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合要求需要优化的订单清单!';
            }
        }
        return $Return;
    }

    /**
     *
     * 通过opid获取预处理清单
     */
    public function select_optimize_produce_prehandle($Ids){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Ids);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql,false)
                ->from('order_product_board_plate')
                ->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left')
                ->join('order_product', 'op_id = opc_order_product_id', 'left')
                ->join('order','o_id = op_order_id', 'left')
                ->where_in('op_id', $Ids)
                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合要求需要优化的订单清单!';
            }
        }
        return $Return;
    }

    /**
     * @param $Ids
     * @return bool
     * @des 通过opbpid获取预处理清单
     */
    public function select_optimize_produce_prehandled($Ids){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Ids);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql,false)
                ->from('order_product_board_plate')
                ->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left')
                ->join('order_product', 'op_id = opc_order_product_id', 'left')
                ->join('order','o_id = op_order_id', 'left')
                ->where_in('opbp_id', $Ids)
                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合要求需要优化的订单清单!';
            }
        }
        return $Return;
    }
    /**
     * 通过order_product_id 获取板块信息
     * @param unknown $Opid
     */
    public function select_order_product_board_plate_by_opid($Where){
        $Item = $this->_Item.__FUNCTION__;
        if(is_array($Where)){
            $Cache = $this->_Cache.implode('_', $Where).__FUNCTION__;
        }else{
            $Cache = $this->_Cache.$Where.__FUNCTION__;
        }
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $this->HostDb->select('opbp_qrcode, opbp_cubicle_name, opbp_cubicle_num, opbp_plate_name,
                        opbp_plate_num, opbp_width, opbp_length, opbp_thick, opbp_amount,
                        opbp_area, opbp_slot, opbp_punch, opbp_edge, opbp_remark, opbp_decide_size,
                        opbp_abnormity, opbp_right_edge, opbp_left_edge, opbp_up_edge, 
                        opbp_down_edge, opb_board, opbp_bd_file', false);
            $this->HostDb->from('order_product_board_plate');
            $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
            if(is_array($Where)){
                $this->HostDb->where_in('opb_order_product_id', $Where);
            }else{
                $this->HostDb->where('opb_order_product_id', $Where);
            }
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $Return = $this->_unformat($Return, $Item, $this->_Module);
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    
    /**
     * 读取异形板块信息
     * @param unknown $Id
     */
    public function select_order_product_board_plate_abnormity_by_opid($Opid){
        $Item = $this->_Item.__FUNCTION__;
        if(is_array($Opid)){
            $Cache = $this->_Cache.implode('_', $Opid).__FUNCTION__;
        }else{
            $Cache = $this->_Cache.$Opid.__FUNCTION__;
        }
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, false);
            $this->HostDb->from('order_product_board_plate');
            $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
            if(is_array($Opid)){
                $this->HostDb->where_in('opb_order_product_id', $Opid);
            }else{
                $this->HostDb->where('opb_order_product_id', $Opid);
            }
            $this->HostDb->where('opbp_abnormity', 1);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    /**
     * 读取板块标签信息
     */
    public function select_label($OrderProductNum) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$OrderProductNum;

        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, false);
            $this->HostDb->from('order_product_board_plate');
            $this->HostDb->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left');
            $this->HostDb->join('order_product', 'op_id = opc_order_product_id', 'left');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');

            $this->HostDb->where('op_num', $OrderProductNum);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }

        return $Return;
    }
    
    /**
     * 获取分类需要打印清单
     * @param unknown $Opid
     * @param unknown $Cid
     */
    public function select_classify_print_list($Opid, $Cid){
        $Item = $this->_Item.__FUNCTION__;
        if(is_array($Opid)){
            $Cache = $this->_Cache.__FUNCTION__.implode('_', $Opid).$Cid;
        }else{
            $Cache = $this->_Cache.__FUNCTION__.$Opid.$Cid;
        }
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, false);
            $this->HostDb->from('order_product_board_plate');
            $this->HostDb->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left');
            if(is_array($Opid)){
                $this->HostDb->where_in('opc_order_product_id', $Opid);
            }else{
                $this->HostDb->where('opc_order_product_id', $Opid);
            }
            $this->HostDb->where('opc_classify_id', $Cid);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    
    /**
     * 获取需要打印的板块分类标签
     * @param unknown $OrderProductNum
     */
    public function select_classify_label($OrderProductNum, $Cid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$OrderProductNum.$Cid;
        
        $Return = array();
        if(!($Return = $this->cache->get($this->_Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, false);
            $this->HostDb->from('order_product_board_plate');
            $this->HostDb->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left');
            $this->HostDb->join('order_product', 'op_id = opc_order_product_id', 'left');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            
            $this->HostDb->where('op_num', $OrderProductNum);
            $this->HostDb->where('opc_classify_id', $Cid);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    
    public function select_order_product_board_opid($Ids){
        $this->HostDb->select('op_id', false);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
        $this->HostDb->where_in('opbp_id', $Ids);
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            return $Row['op_id'];
        }
        return false;
    }
    
    /**
     * 获取板材面积和
     */
    public function select_order_product_board_plate_area($Where){
        $this->HostDb->select('opb_id, sum(opbp_area) as opb_area, count(opbp_id) as opb_amount', false);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        if(is_array($Where)){
            $this->HostDb->where_in('op_order_id', $Where);
        }else{
            $this->HostDb->where(array('op_order_id' => $Where));
        }
        $this->HostDb->group_by('opb_id');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }
    
    /**
     * 获取需要设置qrcode的板块清单
     * @param unknown $Oids
     */
    public function select_qrcode($Oids){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql, false);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        if(is_array($Oids)){
            $this->HostDb->where_in('op_order_id', $Oids);
        }else{
            $this->HostDb->where(array('op_order_id' => $Oids));
        }
        $this->HostDb->order_by('op_num');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }

    /**
     * @param $Opid
     * @des 通过opid获取板块信息
     */
    public function select_qrcode_by_opid($Opid){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql, false);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        if(is_array($Opid)){
            $this->HostDb->where_in('op_id', $Opid);
        }else{
            $this->HostDb->where(array('op_id' => $Opid));
        }
        $this->HostDb->order_by('op_num');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }
        return false;
    }
    
    /**
     * 获取当前扫描的某一板块
     * @param unknown $Qrcode 扫描的二维码
     * @return boolean
     */
    public function select_scan($Qrcode){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql, false);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
        $this->HostDb->where('opbp_qrcode', $Qrcode);
        $this->HostDb->limit(1);
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->row_array();
        }else{
            return false;
        }
    }
    /**
     * 获取当前订单产品的所有板块的扫描信息
     * @param unknown $Opid 订单产品Id
     * @return boolean
     */
    public function select_scan_list($Opid){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql, false);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('user', 'u_id = opbp_scanner', 'left');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        $this->HostDb->where(array('op_id' => $Opid));
        $this->HostDb->order_by('opbp_thick');
        $this->HostDb->order_by('opbp_qrcode');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }
    
    /**
     * 获取扫描差板块订单产品列表
     * @param unknown $Con
     */
    public function select_scan_lack($Con, $Opid = 0){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con).$Opid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product_board_plate');
            $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
            $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            
            if(!empty($Opid)){
                $this->HostDb->where('op_id', $Opid);
            }
    
            $this->HostDb->where('op_scan_status', 1); /*正在扫描的板块*/
            $this->HostDb->where_in('op_product_id', array(1,2)); /*只有橱柜和衣柜有扫描*/
            
            $this->HostDb->where('(opbp_scan_datetime = "0000-00-00 00:00:00" || opbp_scan_datetime is null)'); /*还未扫描的板块*/
    
            $this->HostDb->group_by('opb_id');
            
            $this->HostDb->order_by('op_num');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    /**
     * 获取扫描差板块
     */
    public function select_scan_lack_detail($Opid){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql, false);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left');
        $this->HostDb->where(array('opb_order_product_id' => $Opid));
        $this->HostDb->where('(opbp_scan_datetime = "0000-00-00 00:00:00" || opbp_scan_datetime is null)');
        $Query = $this->HostDb->get();
        
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }

    /**
     * 获取已经预处理的订单的板块详情
     * @param $Con
     */
    public function select_produce_prehandled($Opid){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql, false);
        $this->HostDb->from('order_product_board_plate');
        $this->HostDb->join('user', 'u_id = opbp_scanner', 'left');
        $this->HostDb->join('order_product_classify', 'opc_id = opbp_order_product_classify_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opc_order_product_id', 'left');
        $this->HostDb->join('classify', 'c_id = opc_classify_id', 'left');
        $this->HostDb->where(array('op_id' => $Opid));
        $this->HostDb->order_by('opc_classify_id');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            $GLOBALS['error'] = '获取生产预处理清单失败!';
            return false;
        }
    }
    
    /**
     * 判断是否已经上传
     * @param unknown $Qrcode
     */
    public function is_uploaded($Qrcode){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Qrcode;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)
                                    ->from('order_product_board_plate')
                                    ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
                                    ->where('opbp_qrcode', $Qrcode)
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
     * 插入一行
     * @param unknown $Set
     */
    public function insert($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	$Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('order_product_board_plate', $Set)){
            log_message('debug', "Model Order_product_board_plate_model/insert Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Order_product_board_plate_model/insert Error");
            return false;
        }
    }
    
    /**
     * 如果存在冲突则替换插入
     * @param unknown $Set
     */
    public function replace_order_product_board_plate($Set){
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->replace('order_product_board_plate', $Set)){
            log_message('debug', "Model Order_product_board_plate_model/replate_order_product_board_plate Success!");
            return true;
        }else{
            log_message('debug', "Model Order_product_board_plate_model/replate_order_product_board_plate Error");
            return false;
        }
    }
    
    /**
     * 批量插入
     * @param unknown $Set
     */
    public function insert_batch($Set){
    	$Item = $this->_Item.__FUNCTION__;
    	foreach ($Set as $key => $value){
    		$Set[$key] = $this->_format($value, $Item, $this->_Module);
    	}
    	if(!!($this->HostDb->insert_batch('order_product_board_plate', $Set))){
    		log_message('debug', "Model Order_product_board_plate_model/insert_batch Success!");
    		$this->remove_cache($this->_Module);
    		return true;
    	}else{
    		log_message('debug', "Model Order_product_board_plate_model/insert_batch Error");
    		return false;
    	}
    }    

    public function update($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if(is_array($Where)){
            $this->HostDb->where_in('opbp_id', $Where);
        }else{
            $this->HostDb->where(array('opbp_id' => $Where));
        }
        $this->HostDb->update('order_product_board_plate', $Data);
        $this->remove_cache($this->_Module);
        return TRUE;
    }
    /**
     * 批量修改板块清单
     */
    public function update_batch($Set){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Set as $key => $value){
            $Set[$key] = $this->_format_re($value, $Item, $this->_Module);
        }
        $this->HostDb->update_batch('order_product_board_plate', $Set, 'opbp_id');
        $this->remove_cache($this->_Module);
        return true;
    }
    
    public function update_order_product_board_plate_board($Data, $Where){
        if(is_array($Where)){
            $this->HostDb->where_in('opbp_id', $Where);
        }else{
            $this->HostDb->where(array('opbp_id' => $Where));
        }
        return $this->HostDb->update('order_product_board_plate', array('opbp_order_product_board_id' => $Data));
    }

    /**
     * @param $Oid
     * @return bool
     * 清除Qrcode
     */
    public function update_clear_qrcode($Oids) {
        if (is_array($Oids)) {
            $Oids = '(' . implode(',', $Oids) . ')';
        }else {
            $Oids = '(' . $Oids . ')';
        }
        $Query = $this->HostDb->query("update n9_order_product_board_plate  left join n9_order_product_board on opb_id = opbp_order_product_board_id 
                                          left join n9_order_product on op_id = opb_order_product_id left join n9_order on o_id = op_order_id 
                                          set opbp_qrcode = null where o_id in $Oids && (opbp_bd_file = '' || opbp_bd_file is null)");
        if($Query){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 更新板块扫描状态
     */
    public function update_scan($Where){
        $Set = array(
            'opbp_scanner' => $this->session->userdata('uid'),
            'opbp_scan_datetime' => date('Y-m-d H:i:s')
        );
        $this->HostDb->where(array('opbp_scanner' => 0, 'opbp_scan_datetime' => '0000-00-00 00:00:00'));
        $this->HostDb->where_in('opbp_id',$Where);
        $this->remove_cache($this->_Module);
        return $this->HostDb->update('order_product_board_plate', $Set);;
    }

    /**
     * 更新Bd文件所在位置
     * @param $Data
     * @return bool
     */
    public function update_bd_file($Data) {
        $this->HostDb->set('opbp_bd_file', $Data['bd_file']);
        $this->HostDb->where('opbp_qrcode', $Data['qrcode']);
        $this->HostDb->update('order_product_board_plate');
        $this->remove_cache($this->_Module);
        return true;
    }
    
    /**
     * 删除之前的板块(修改后重新生成)
     * @param unknown $Opbid 订单产品板材Id
     */
    public function delete_by_opbid($Opbid){
        if(is_array($Opbid)){
            $Opbid = implode(',', $Opbid);
        }
        $this->HostDb->query("DELETE n9_order_product_board_plate
            FROM n9_order_product_board_plate WHERE opbp_order_product_board_id in ($Opbid)");
        $this->remove_cache($this->_Module);
        return true;
    }
    /**
     * 删除之前的板块(修改后重新生成)
     * @param unknown $Opbid 订单产品板材Id
     */
    public function delete_by_opid($Opid){
        if(is_array($Opid)){
            $Opbid = implode(',', $Opid);
        }
        $this->HostDb->query("DELETE n9_order_product_board_plate
            FROM n9_order_product_board_plate left join n9_order_product_board on opb_id = opbp_order_product_board_id
            WHERE opb_order_product_id in ($Opid)");
        $this->remove_cache($this->_Module);
        return true;
    }
    
    /**
     * 删除板块相关内容
     */
    public function delete_relate($Opid, $OrderProductNum = ''){
        $this->remove_cache($this->_Module);
        if(is_array($Opid)){
            $Where = implode(',', $Opid);
        }else{
            $Where = $Opid;
        }
        if(empty($OrderProductNum)){
            /*通过产品Id号删除*/
            return $this->HostDb->query("DELETE n9_order_product_board_plate, n9_order_product_board
                FROM n9_order_product_board_plate LEFT JOIN n9_order_product_board ON opb_id = opbp_order_product_board_id
                WHERE opb_order_product_id in ($Where)");
        }else{
            /*通过订单编号删除*/
            return $this->HostDb->query("DELETE n9_order_product_board_plate, n9_order_product_board
                FROM n9_order_product_board_plate LEFT JOIN n9_order_product_board ON opb_id = opbp_order_product_board_id
                WHERE (opb_order_product_id in ($Where) or opbp_qrcode like '{$OrderProductNum}%') ");
        }
    }
}
