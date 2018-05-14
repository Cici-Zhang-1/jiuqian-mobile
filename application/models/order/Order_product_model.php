<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月24日
 * @author Zhangcc
 * @version
 * @des
 * 订单产品
 */
class Order_product_model extends MY_Model{
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
        
        log_message('debug', 'Model Order/Order_product_model start!');
    }
    /**
     * 获取当前订单的工作流
     */
    public function select_current_workflow($Opid, $Type){
        log_message('debug', 'Library Order/Order_model select_current_workflow Start On $Oid = '.$Opid.'$Type = '.$Type);
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order_product')
            ->join('workflow', 'w_no = op_status', 'left')
            ->where('op_id', $Opid)
            ->where('w_type', $Type)->limit(1)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->row_array();
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }
    
    /**
     * 获得可导出BD文件的订单产品列表
     * @param unknown $Con
     * @return Ambigous <boolean, multitype:unknown NULL >
     */
    public function select_bd($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page_bd($Con);
            }else{
                $this->_Num = $Con['num'];
            }
            if(!empty($Con['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql, FALSE);
                $this->HostDb->from('order_product');
                $this->HostDb->join('order', 'o_id = op_order_id', 'left');
                $this->HostDb->join('user', 'u_id = op_dismantler', 'left');
                
                $this->HostDb->where_in('op_product_id', array(1,2));
                $this->HostDb->where('op_status > 2');
                $this->HostDb->where('o_status >= 11');
                $this->HostDb->where('op_bd', $Con['bd']);
                $this->HostDb->where('op_dismantled_datetime is not null');
                
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                                        ->like('op_remark', $Con['keyword'])
                                        ->or_like('o_remark', $Con['keyword'])
                                        ->or_like('o_dealer', $Con['keyword'])
                                        ->or_like('o_owner', $Con['keyword'])
                                        ->or_like('o_num', $Con['keyword'])
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
                $GLOBALS['error'] = '没有符合要求需要导出BD的订单!';
            }
        }
        return $Return;
    }
    
    private function _page_bd($Con){
        $this->HostDb->select('count(op_id) as num', FALSE);
        $this->HostDb->from('order_product');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
        
        $this->HostDb->where_in('op_product_id', array(1, 2));
        $this->HostDb->where('op_status > 2');
        $this->HostDb->where('o_status >= 11');
        $this->HostDb->where('op_bd', $Con['bd']);
        $this->HostDb->where('op_dismantled_datetime is not null');
        
        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                            ->like('op_remark', $Con['keyword'])
                            ->or_like('o_remark', $Con['keyword'])
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
     * 获取等待拆单订单产品
     * @param unknown $Con
     * @param string $Sql
     * @param string $Public
     */
    public function select_wait_dismantle($Con, $Sql = '', $Public = false){
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        if(isset($Con['public'])){
            if(0 == $Con['public']){
                $Public = FALSE;
            }else{
                $Public = TRUE;
            }
            unset($Con['public']);
        }
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page($Con, $Public);
            }else{
                $this->_Num = $Con['num'];
            }
            if(!empty($Con['pn'])){
                if(empty($Sql)){
                    $Item = $this->_Item.__FUNCTION__;
                    $Sql = $this->_unformat_as($Item);
                }else{
                    $Sql = $this->_unformat_as($Sql, $this->_Module);
                }
                $this->HostDb->select($Sql,  FALSE);
                $this->HostDb->from('order_product');
                $this->HostDb->join('order', 'o_id = op_order_id', 'left');
                $this->HostDb->join('user as B', 'B.u_id = op_dismantler', 'left');
                $this->HostDb->join('user as C', 'C.u_id = o_creator', 'left');
                $this->HostDb->join('workflow', 'w_no = op_status', 'left');
                
                $this->HostDb->where('w_type', 'order_product');
                
                if(!$Public){
                    $this->HostDb->where('op_dismantler', $this->session->userdata('uid'));
                }

                if(isset($Con['status'])){
                    $this->HostDb->where("(op_status in ({$Con['status']}))");
                }

                if(isset($Con['product'])){
                    $this->HostDb->where("op_product_id in ({$Con['product']})");
                }

                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                                        ->like('op_remark', $Con['keyword'])
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
                $GLOBALS['error'] = '没有符合要求需要拆单的订单!';
            }
        }
        return $Return;
    }
    private function _page($Con, $Public){
        $this->HostDb->select('count(op_id) as num', FALSE);
        $this->HostDb->from('order_product');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');

        if(!$Public){
            $this->HostDb->where('op_dismantler', $this->session->userdata('uid'));
        }
        
        if(isset($Con['status'])){
            $this->HostDb->where("(op_status in ({$Con['status']}))");
        }
        
        if(isset($Con['product'])){
            $this->HostDb->where("op_product_id in ({$Con['product']})");
        }
        
        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                        ->like('op_remark', $Con['keyword'])
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
     * 获取待打印生产清单的订单产品列表
     * @param unknown $Con
     */
    public function select_print_list($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page_print_list($Con, true);
            }else{
                $this->_Num = $Con['num'];
            }
            if(!empty($Con['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql,  FALSE);
                $this->HostDb->from('order_product');
                $this->HostDb->join('order', 'o_id = op_order_id', 'left');
                $this->HostDb->join('user as A', 'A.u_id = op_dismantler', 'left');
        
                $this->HostDb->where('op_status >= 3');
                $this->HostDb->where('o_status >= 11');
                //$this->HostDb->where('o_status <= 16');
        
                if('print' == $Con['print']){ /*是否已经打印*/
                    $this->HostDb->where("op_print_datetime is null");
                }else{
                    $this->HostDb->where("op_print_datetime is not null");
                }
                
                if(!empty($Con['product'])){
                    $this->HostDb->where("op_product_id in ({$Con['product']})");
                }
        
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                                    ->like('op_remark', $Con['keyword'])
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
                $GLOBALS['error'] = '没有符合要求的打印清单!';
            }
        }
        return $Return;
    }
    
    private function _page_print_list($Con){
        $this->HostDb->select('count(op_id) as num', FALSE);
        $this->HostDb->from('order_product');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
        
        $this->HostDb->where('op_status >= 3'); /*订单产品已拆单，订单处于等待生产状态之后*/
        $this->HostDb->where('o_status >= 11');
        $this->HostDb->where('o_status <= 16');
        
        if('print' == $Con['print']){ /*是否已经打印*/
            $this->HostDb->where("op_print_datetime is null");
        }else{
            $this->HostDb->where("op_print_datetime is not null");
        }
        
        if(!empty($Con['product'])){
            $this->HostDb->where("op_product_id in ({$Con['product']})");
        }
        
        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                            ->like('op_remark', $Con['keyword'])
                            ->or_like('op_num', $Con['keyword'])
                            ->or_like('o_dealer', $Con['keyword'])
                            ->or_like('o_owner', $Con['keyword'])
                            ->or_like('o_remark', $Con['keyword'])
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
     * 获得包装扫描的订单产品
     * @param unknown $Con
     */
    public function select_pack($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page_pack($Con);
            }else{
                $this->_Num = $Con['num'];
            }
            if(!empty($Con['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql, FALSE);
                $this->HostDb->from('order_product');
                $this->HostDb->join('order', 'o_id = op_order_id', 'left');
                
                $this->HostDb->where_in('op_product_id', array(1, 2)); /*可扫描的产品类型*/
                
                $this->HostDb->where("op_scan_status in ({$Con['scan']})"); /*扫描状态*/
                
                $this->HostDb->where('op_status > 3');  /*已经生产的订单产品才可以扫描*/
                
                if(!empty($Con['start_date'])){ /*扫描开始日期*/
                    $this->HostDb->where('op_scan_start > ', $Con['start_date']);
                }
                
                if(!empty($Con['end_date'])){ /*扫描截止日期*/
                    $this->HostDb->where('op_scan_end < ', $Con['end_date']);
                }

                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                                    ->like('op_remark', $Con['keyword'])
                                    ->or_like('op_num', $Con['keyword'])
                                    ->or_like('o_dealer', $Con['keyword'])
                                    ->or_like('o_owner', $Con['keyword'])
                                    ->or_like('o_remark', $Con['keyword'])
                                ->group_end();
                }
                
                $this->HostDb->order_by('op_num', 'desc');
        
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
                $GLOBALS['error'] = '没有符合要求包装的订单!';
            }
        }
        return $Return;
    }
    
    private function _page_pack($Con){
        $this->HostDb->select('count(op_id) as num', FALSE);
        $this->HostDb->from('order_product');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');

        $this->HostDb->where_in('op_product_id', array(1, 2)); /*可扫描的产品类型*/
        
        $this->HostDb->where("op_scan_status in ({$Con['scan']})"); /*扫描状态*/
        
        $this->HostDb->where('op_status > 3');  /*已经生产的订单产品才可以扫描*/
        
        if(!empty($Con['start_date'])){ /*扫描开始日期*/
            $this->HostDb->where('op_scan_start > ', $Con['start_date']);
        }
        
        if(!empty($Con['end_date'])){ /*扫描截止日期*/
            $this->HostDb->where('op_scan_end < ', $Con['end_date']);
        }
        
        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                            ->like('op_remark', $Con['keyword'])
                            ->or_like('op_num', $Con['keyword'])
                            ->or_like('o_dealer', $Con['keyword'])
                            ->or_like('o_owner', $Con['keyword'])
                            ->or_like('o_remark', $Con['keyword'])
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
     * 获取包装件数统计信息
     * @param unknown $Con
     */
    public function select_pack_statistics($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page_pack_statistics($Con, true);
            }else{
                $this->_Num = $Con['num'];
            }
            if(!empty($Con['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql, FALSE);
                $this->HostDb->from('order_product');
                $this->HostDb->join('order', 'o_id = op_order_id', 'left');
                $this->HostDb->join('user as A', 'A.u_id = op_packer', 'left');

                $this->HostDb->where("op_product_id != 7"); /*把服务类产品除外*/
                $this->HostDb->where("op_status >= 3");  /*所有已经拆单的订单产品， 同时是已经确认生产的订单*/
                $this->HostDb->where('o_status >= 11');  
                
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                                    ->like('op_remark', $Con['keyword'])
                                    ->or_like('op_num', $Con['keyword'])
                                    ->or_like('o_dealer', $Con['keyword'])
                                    ->or_like('o_owner', $Con['keyword'])
                                    ->or_like('o_remark', $Con['keyword'])
                                ->group_end();
                }
                 
                $this->HostDb->order_by('op_num', 'desc');
    
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
    
    private function _page_pack_statistics($Con){
        $this->HostDb->select('count(op_id) as num', FALSE);
        $this->HostDb->from('order_product');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
    
        $this->HostDb->where("op_product_id != 7"); /*把服务类产品除外*/
        $this->HostDb->where("op_status >= 3");  /*所有已经拆单的订单产品， 同时是已经确认生产的订单*/
        $this->HostDb->where('o_status >= 11');  
        
        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                            ->like('op_remark', $Con['keyword'])
                            ->or_like('op_num', $Con['keyword'])
                            ->or_like('o_dealer', $Con['keyword'])
                            ->or_like('o_owner', $Con['keyword'])
                            ->or_like('o_remark', $Con['keyword'])
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
     * 获取订单产品包装详情
     * @param $Opids
     */
    public function select_pack_detail_by_opids($Opids){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode(',', $Opids);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, false)
                                ->from('order_product')
                            ->where_in('op_id', $Opids);
            $Query = $this->HostDb->get();

            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '您要打印包装标签的订单不存在';
            }
        }
        return $Return;
    }


    /**
     * 获取需要预处理的订单
     * @param $Con
     * @return array|bool
     */
    public function select_producing($Con){
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(empty($Sql)){
                $Item = $this->_Item.__FUNCTION__;
                $Sql = $this->_unformat_as($Item);
            }else{
                $Sql = $this->_unformat_as($Sql, $this->_Module);
            }
            $this->HostDb->select($Sql,  FALSE);
            $this->HostDb->from('order_product');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            $this->HostDb->join('user as B', 'B.u_id = op_dismantler', 'left');

            if(isset($Con['status'])){
                $this->HostDb->where("(op_status in ({$Con['status']}))");
            }
/*
            if (isset($Con['o_status']) && '' != $Con['o_status']) {
                $this->HostDb->where("(o_status in ({$Con['status']}))");
            }*/

            if(isset($Con['product'])){
                $this->HostDb->where("op_product_id in ({$Con['product']})");
            }

            if(!empty($Con['keyword'])){
                $this->HostDb->group_start()
                                ->like('op_remark', $Con['keyword'])
                                ->or_like('op_num', $Con['keyword'])
                                ->or_like('o_dealer', $Con['keyword'])
                                ->or_like('o_owner', $Con['keyword'])
                            ->group_end();
            }
            $this->HostDb->order_by('o_create_datetime', 'desc');

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array()
                );
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '系统中暂时没有正在生产的订单';
            }
        }
        return $Return;
    }

    /**
     * 获取需要预处理的订单
     * @param $Con
     * @return array|bool
     */
    public function select_produce_prehandle($Con){
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(empty($Sql)){
                $Item = $this->_Item.__FUNCTION__;
                $Sql = $this->_unformat_as($Item);
            }else{
                $Sql = $this->_unformat_as($Sql, $this->_Module);
            }
            $this->HostDb->select($Sql,  FALSE);
            $this->HostDb->from('order_product');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            $this->HostDb->join('user as B', 'B.u_id = op_dismantler', 'left');

            if(isset($Con['status'])){
                $this->HostDb->where("(op_status in ({$Con['status']}))");
            }

            $this->HostDb->where('o_status > 10');

            if(isset($Con['product'])){
                $this->HostDb->where("op_product_id in ({$Con['product']})");
            }

            if(!empty($Con['keyword'])){
                $this->HostDb->group_start()
                    ->like('op_remark', $Con['keyword'])
                    ->or_like('op_num', $Con['keyword'])
                    ->or_like('o_dealer', $Con['keyword'])
                    ->or_like('o_owner', $Con['keyword'])
                    ->group_end();
            }
            $this->HostDb->order_by('o_create_datetime', 'desc');

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array()
                );
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '系统中暂时没有需要预处理的订单';
            }
        }
        return $Return;
    }

    public function select_clear_fitting($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            
            $this->HostDb->where('op_status != 0');
            $this->HostDb->where('o_asure_datetime > ', $Con['start_date']);
            $this->HostDb->where('o_asure_datetime < ', $Con['end_date']);
            $this->HostDb->where('o_status > ', 10); /*等待生产以后的订单*/
            
            $this->HostDb->where('op_product_id', 5);
             
            $this->HostDb->order_by('o_asure_datetime', 'desc');
    
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Result = $Query->result_array();
                $Query->free_result();
                $Return = array(
                    'content' => $Result
                );
                $this->cache->save($Cache, $Return, HOURS);
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
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            $this->HostDb->join('dealer', 'd_id = o_dealer_id', true);
            $this->HostDb->where('op_status != 0');
            $this->HostDb->where('o_status', $Status);
             
            $this->HostDb->where_in('o_id', $Ids);
            $this->HostDb->order_by('o_num', 'desc');
            $this->HostDb->order_by('op_product_id', 'acs');
             
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
     * 根据发货记录号获取包含的订单产品的产品信息
     * @param unknown $Id
     */
    public function select_by_soid($Soid, $Status){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Soid.$Status;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
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
            $this->HostDb->order_by('op_product_id', 'acs');
             
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合发货单的订单!';
            }
        }
        return $Return;
    }
    /**
     * 根据订单Id号获取包含的订单产品的产品信息
     * @param unknown $Id
     */
    public function select_by_oid($Oid, $Sql = ''){
        if(!empty($Sql)){
            $Item = $Sql;
        }else{
            $Item = $this->_Item.__FUNCTION__;
        }
        if (is_array($Oid)) {
            $Cache = $this->_Cache.__FUNCTION__.'_'.implode(',', $Oid).$Sql;
        }else {
            $Cache = $this->_Cache.__FUNCTION__.'_'.$Oid.$Sql;
        }
        
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql,  FALSE);
            $this->HostDb->from('order_product');
            $this->HostDb->join('product', 'p_id = op_product_id', 'left');
            $this->HostDb->join('workflow', 'w_no = op_status', 'left');
            $this->HostDb->where('w_type', 'order_product');
            if (is_array($Oid)) {
                $this->HostDb->where_in('op_order_id', $Oid);
            }else {
                $this->HostDb->where('op_order_id', $Oid);
            }
            $this->HostDb->where('op_status != ', 0);
            $this->HostDb->order_by('p_id');
            $this->HostDb->order_by('op_num');
            $Query = $this->HostDb->get();
            
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '您提供的订单没有符合要求的订单产品';
            }
        }
        return $Return;
    }
    
    /**
     * 获取已经拆单的订单产品
     * @param unknown $Ids
     * @return boolean
     */
    public function select_dismantled_by_opids($Ids){
        if(is_array($Ids)){
            $Ids = '('.implode(',', $Ids).')';
        }
        $Sql = "SELECT op_order_id as oid, if(min(op_status) = 3, 1, 0) as dismantled FROM n9_order_product
                    WHERE op_order_id in (
                    SELECT op_order_id FROM n9_order_product WHERE op_id in ($Ids) GROUP BY op_order_id
            ) && op_status > 0 group by op_order_id";
        $Query = $this->HostDb->query($Sql, false);
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }

    /**
     * 获取所有兄弟订单
     * @param $Brothers
     * @param $Self
     */
    public function select_brothers($Brothers, $Self){
        $Cache = $this->_Cache.__FUNCTION__.'_'.$Self;
        $Item = $this->_Item.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql,  FALSE);
            $this->HostDb->from('order_product');
            $this->HostDb->like('op_num', $Brothers);
            $this->HostDb->where('op_num != ', $Self);
            $this->HostDb->where('op_status >= 3');
            $this->HostDb->order_by('op_num');
            $Query = $this->HostDb->get();

            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $Return = false;
            }
        }
        return $Return;
    }
    
    /**
     * 获取订单id
     * @param unknown $Ids 订单产品id
     */
    public function select_oids_by_opids($Ids){
        $this->HostDb->select('op_order_id as oid, o_status as status')
                        ->from('order_product')
                        ->join('order', 'o_id = op_order_id', 'left');
        if(is_array($Ids)){
            $this->HostDb->where_in('op_id', $Ids);
        }else{
            $this->HostDb->where('op_id', $Ids);
        }
        $this->HostDb->group_by('o_id');
        $Query = $this->HostDb->get();
        
        if($Query->num_rows() > 0){
            return $Query->result_array();;
        }else{
            $GLOBALS['error'] = '没有符合要求的订单';
            return false;
        }
    }
    
    /**
     * 获取订单入库状态
     * 分为正在入库和已经全部入库
     * @param unknown $Opids
     */
    public function select_ined_status($Opids){
        if(is_array($Opids)){
            $Opids = '('.implode(',', $Opids).')';
        }else{
            $Opids = '('.$Opids.')';
        }
        $Sql = "SELECT op_order_id as oid, if(min(op_status) = 7, 'ined', 'in') as status
                    FROM n9_order_product WHERE op_product_id != 7 && op_status != 0
                    && op_order_id in (SELECT op_order_id FROM n9_order_product WHERE op_id = $Opids)
                    GROUP BY op_order_id";
        $Query = $this->HostDb->query($Sql, FALSE);
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }
    
    /**
     * 判断是否只是配件和外购产品
     * @param unknown $Id
     * @return boolean
     */
    public function only_other_and_fitting($Id){
        $Query = $this->HostDb->query("SELECT op_id from n9_order_product where op_order_id
                                 = $Id && op_product_id not in (5,6,7)");
        if($Query->num_rows() > 0){
            return false;
        }else{
            return true;
        }
    }
    
    /**
     * 判断是否是只有服务类内容
     * @param unknown $Id
     */
    public function only_server($Id){
        $Query = $this->HostDb->select('op_order_id')
                                ->from('n9_order_product')
                            ->where('op_id', $Id)
                            ->limit(1)
                            ->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Oid = $Row['op_order_id'];
            $Query->free_result();
            unset($Row);
            $Query = $this->HostDb->query("SELECT op_id from n9_order_product where op_order_id = $Oid
                                            && op_product_id not in (7)");
            if($Query->num_rows() > 0){
                return false;
            }else{
                return $Oid;
            }
        }
        return false;
    }
    /**
     * 获取已经入库的订单产品的包装数
     */
    public function select_ined_pack($Ids){
        if(!is_array($Ids)){
            $Ids = array($Ids);
        }
        $Query = $this->HostDb->select('op_order_id as oid, op_pack as pack, p_code as code')
                                ->from('order_product')
                                ->join('product', 'p_id = op_product_id', 'left')
                                ->where_in('op_order_id', $Ids)
                                ->where('op_product_id != 7')
                            ->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }

    /**
     * @return bool
     *
     * 获取等待入库到库位的订单
     */
    public function select_wait_position() {
        $Cache = $this->_Cache.__FUNCTION__;
        $Item = $this->_Item.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql);
            $this->HostDb->from('order_product');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            $this->HostDb->where('o_status > ', O_WAIT_ASURE);
            $this->HostDb->where('o_status < ', O_DELIVERIED);
            $this->HostDb->order_by('o_id', 'desc');
            $Query = $this->HostDb->get();

            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $Return = false;
            }
        }
        return $Return;
    }
    
    /**
     * 查看产品订单详情(包含订单详情)
     * @param unknown $OrderProductId
     */
    public function select_order_detail_by_opid($Opid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Opid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql,  FALSE);
            $this->HostDb->from('order_product');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            $this->HostDb->join('product', 'p_id = op_product_id', 'left');
            $this->HostDb->join('user as A', 'A.u_id = op_dismantler', 'left');
            $this->HostDb->join('user as B', 'B.u_id = o_creator', 'left');
            $this->HostDb->join('n9_workflow', 'w_id = o_status && w_type="order"', 'left', false);
            $this->HostDb->where('op_id', $Opid);
            $this->HostDb->limit(1);
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '您提供的订单产品编号不存在或已经删除';
            }
        }
        return $Return;
    }
    
    
    /**
     * 根据搜索条件(订单产品编号)判断是否已经存在
     * @param string $Num
     * @return array 返回订单产品Id和订单产品状态
     */
    public function is_exist_by_num($Num) {
        log_message('debug', 'Model Order/Order_product_model is_exist_by_num Start on Num = '.$Num);
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Num;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Query = $this->HostDb->select('op_id as opid, op_status as status', false)
                                        ->from('order_product')
                                        ->where(array('op_num' => $Num))
                                        ->limit(1)
                                    ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
                log_message('debug', 'Model Order/Order_product_model is_exist_by_num Success!');
            }else{
                $GLOBALS['error'] = '订单产品不存在!';
            }
        }
        return $Return;
    }
    
    /**
     * 判断订单是否可拆单
     * @param unknown $Ids
     */
    public function is_dismantlable($Ids){
	    $Item = $this->_Item.__FUNCTION__;
	    $Sql = $this->_unformat_as($Item);
	    $Query = $this->HostDb->select($Sql)
	                           ->from('order_product')
	                           ->join('product', 'p_id = op_product_id', 'left')
	                           ->join('order', 'o_id = op_order_id', 'left')
	                           ->where_in('op_id', $Ids)
	                           ->where_in('o_status', array(1,2))
	                           ->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->result_array();
	        $Query->free_result();
	        return $Return;
	    }else{
	        $GLOBALS['error'] = '当前订单已经拆单!';
	        return false;
	    }
	}
	
	/**
	 * 判断是否已经有拆单产品存在
	 * @param unknown $Ids
	 */
	public function is_dismantled($Ids){
	    $In = '('.implode(',', $Ids).')';
	    $Query = $this->HostDb->query("(SELECT opb_order_product_id as opid, opb_id as flag FROM n9_order_product_board
	           WHERE opb_order_product_id IN $In) UNION (SELECT opf_order_product_id as opid, opf_id as flag FROM n9_order_product_fitting
	           WHERE opf_order_product_id IN $In) UNION (SELECT opo_order_product_id as opid, opo_id as flag FROM n9_order_product_other
	           WHERE opo_order_product_id IN $In) UNION (SELECT ops_order_product_id as opid, ops_id as flag FROM n9_order_product_server
	           WHERE ops_order_product_id IN $In)
	        ");
	    if($Query->num_rows() > 0){
	        $Return = $Query->result_array();
	        $Query->free_result();
	        return $Return;
	    }else{
	        $GLOBALS['error'] = '当前订单产品没有拆单或已经作废, 不能确认拆单!';
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
	        $Sql = $this->_unformat_as($Item);
	        $this->HostDb->select($Sql, false)
                	        ->from('order_product')
                	        ->join('order', 'o_id = op_order_id', 'left')
                	        ->where('o_num', $Num);
	        $this->HostDb->where('op_status >= 3'); /*只有处于拆单之后的订单产品才可以包装*/
	        $this->HostDb->where('o_status >= 14'); /*只有处于完全入库之后的订单产品才可以包装*/
	        $this->HostDb->where('o_status < 21'); /*只有处于已出厂之前的订单才可以包装*/
	        $this->HostDb->where('op_product_id != 7');
	        $Query = $this->HostDb->get();
	         
	        if($Query->num_rows() > 0){
	            $Return = $Query->result_array();
	            $Query->free_result();
	            $this->cache->save($Cache, $Return, HOURS);
	        }else{
	            $GLOBALS['error'] = '您要打印包装标签的订单不存在';
	        }
	    }
	    return $Return;
	}
	/**
	 * 判断订单是否可重新拆单
	 * @param unknown $Ids
	 */
	public function is_redismantlable($Ids){
	    $Item = $this->_Item.__FUNCTION__;
	    $Sql = $this->_unformat_as($Item);
	    $Query = $this->HostDb->select($Sql)
                        	    ->from('order_product')
                        	    ->join('order', 'o_id = op_order_id', 'left')
                        	    ->where_in('op_id', $Ids)
                        	    ->where_in('op_status', array(1,2,3))
                        	    ->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->result_array();
	        $Query->free_result();
	        return $Return;
	    }else{
	        $GLOBALS['error'] = '当前订单产品已经不能重新拆单!';
	        return false;
	    }
	}
	
	/**
	 * 判断订单产品的拆单是否可清除
	 * @param unknown $Id
	 */
	public function is_dismantle_removable($Id){
	    $Query = $this->HostDb->select('op_num')
                        	    ->from('order_product')
                        	    ->where('op_id', $Id)
                        	    ->where('op_status', 2)
                    	    ->get();
	    if($Query->num_rows() > 0){
	        $Result = $Query->row_array();
	        return $Result['op_num'];
	    }else{
	        $GLOBALS['error'] = '当前订单产品没有拆单或者已经确认!';
	        return false;
	    }
	}
	/**
	 * 判断订单是否在售后服务范围内
	 * @param unknown $Id
	 */
	public function is_post_salable($Id){
	    $Item = $this->_Item.__FUNCTION__;
	    $Sql = $this->_unformat_as($Item);
	    $Query = $this->HostDb->select($Sql)
                	    ->from('order_product')
                	    ->join('product', 'p_id = op_product_id', 'left')
                	    ->join('order', 'o_id = op_order_id', 'left')
                	    ->where_in('op_id', $Id)
                	    ->where('o_status >= ', 10)
                	    ->where('o_status < ', 21)
                	    ->get();
	    if($Query->num_rows() > 0){
	        $Return = $Query->result_array();
	        $Query->free_result();
	        return $Return;
	    }else{
	        $GLOBALS['error'] = '当前订单已经不可安排售后服务!';
	        return false;
	    }
	}
	/**
	 * 是否可以包装
	 * @param unknown $Num
	 */
	public function is_packable($Num){
	    $Item = $this->_Item.__FUNCTION__;
	    $Cache = $this->_Cache.__FUNCTION__.$Num;
	    $Return = false;
	    if(!($Return = $this->cache->get($Cache))){
	        $Sql = $this->_unformat_as($Item);
	        $this->HostDb->select($Sql, false)
                	        ->from('order_product')
                	        ->join('order', 'o_id = op_order_id', 'left')
                	        ->where('op_num', $Num);
	        $this->HostDb->where('op_status >= 3'); /*只有处于拆单之后的订单产品才可以包装*/
	        $this->HostDb->where('o_status >= 11'); /*只有处于生产之后的订单才可以包装*/
	        $this->HostDb->limit(1);
	        $Query = $this->HostDb->get();
	        
	        if($Query->num_rows() > 0){
	            $Return = $Query->row_array();
	            $Query->free_result();
	            $this->cache->save($Cache, $Return, HOURS);
	        }else{
	            $GLOBALS['error'] = '您要打印包装标签的订单不存在';
	        }
	    }
	    return $Return;
	}
	/**
	 * 判断订单产品是否可以删除
	 * @param unknown $Id
	 */
	public function is_removable($Id){
	    $Query = $this->HostDb->select('op_id')
	                           ->from('order_product')
	                           ->where('op_id', $Id)
	                           ->where_in('op_status', array(1,2))
	                           ->get();
	    if($Query->num_rows() > 0){
	        return true;
	    }else{
	        $GLOBALS['error'] = '当前订单已经不能清除!';
	        return false;
	    }
	}

    /**
     * @param $Opid
     * @des  是否可以重新分类
     */
    public function is_produce_rehandlable($Opid, $Status){
        $Query = $this->HostDb->select('op_id')
                                ->from('order_product')
                                ->where('op_id', $Opid)
                                ->where('op_status', $Status)
                            ->get();
        if($Query->num_rows() > 0){
            return true;
        }else{
            $GLOBALS['error'] = '当前没有可以重新分类的订单产品!';
            return false;
        }
    }
    
    /**
     * 批量插入订单产品
     * @param unknown $Data
     */
    public function insert_batch_order_product($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Set[$key] = $this->_format($value, $Item, $this->_Module);
        }
        if($this->HostDb->insert_batch('order_product', $Set)){
            log_message('debug', "Model order_product_model/insert_batch_order_product Success!");
            $this->remove_cache($this->_Module);
            return $this->_select_latest_insert_ids($Set);
        }else{
            log_message('debug', "Model order_product_model/insert_batch_order_product Error");
            return false;
        }
    }
    
    public function insert($Product, $Order){
        if(is_array($Order) && 2 == count($Order)){
            $Oid = $Order['oid'];
            $OrderNum = $Order['order_num'];
        }
        $Return = array();
        foreach ($Product as $key => $value){
            if(!!($this->HostDb->query("call generate_order_product_num($Oid,1, '{$value['code']}', '{$value['name']}', 0, @opid, @order_product_num)", FALSE))){
                if(!!($Query = $this->HostDb->query('select @opid as opid, @order_product_num as order_product_num', FALSE))){
                    $Row = $Query->row_array();
                    $Query->free_result();
                    $Opids = explode(',', trim($Row['opid'], ','));
                    $OrderProductNum = explode(',', trim($Row['order_product_num'], ','));
                    foreach ($Opids as $ikey => $ivalue){
                        $Return[] = array(
                            'opid' => $ivalue,
                            'order_product_num' => $OrderProductNum[$ikey]
                        );
                    }
                }else{
                    $GLOBALS['error'] = '获得新建订单产品编号失败!';
                    break;
                }
            }else{
                $GLOBALS['error'] = '订单产品新建失败!';
                break;
            }
        }
        $this->remove_cache($this->_Module);
        return $Return;
    }
    
    /**
     * 生成订单的产品编号，
     * @id 订单id号
     * @num 生成的数量
     * @code 订单产品类型代号(Y,W...)
     * @product 产品名称
     * @parent 父类编号
     * @opid 新产生的订单产品的id号
     * @orderProductNum 新产生的订单产品的编号
 create PROCEDURE generate_order_product_num(in id int(10), in num int(5), in code varchar(2), in product varchar(64), in parent int(10), out opid varchar(1024), out orderProductNum varchar(1024))
 begin
 declare pid int(10);
 declare maxNum int default 0;
 declare step int default 1;
 declare orderNum varchar(16) default '';
 declare prefix varchar(64) default '';
 declare old varchar(64) default '';
 declare new varchar(64) default '';
 
 select p_id into pid from n9_product where p_code = code && p_delete = 0;
 select o_num into orderNum from n9_order where o_id = id;
 select concat(orderNum,'-',code) into prefix;
 select ifnull(op_num, '') into old
 from n9_order_product where op_num REGEXP concat('^',prefix)
 order by substring(op_num,CHAR_LENGTH(prefix) + 1)+0 desc limit 1;
 if old != '' then
 set maxNum = convert(substring(old, CHAR_LENGTH(prefix) + 1), decimal);
 end if;
 
 set opid = '';
 set orderProductNum = '';
 label1: LOOP
    select concat(prefix, LPAD((maxNum+step), 2, '0')) into new;
     * select concat(prefix, (maxNum+step)) into new;
    insert into n9_order_product(op_order_id, op_num,op_product_id, op_product, op_parent) values (id, new, pid, product, parent);
    select concat(orderProductNum, ',', new) into orderProductNum;
    select concat(opid, ',', LAST_INSERT_ID()) into opid;
    if step < num then 
        set step = step + 1;
        ITERATE label1;
    END IF;
    LEAVE label1;
 END LOOP label1;
 END 
     * @param unknown $Oid
     * @param unknown $Code
     * @param unknown $Product
     * @example generate_order_product_num(1, 5, 'Y', '衣柜', @opid, @order_product_num);
     */
    public function insert_procedure_order_product($Oid, $Code, $Set = 1, $Product = '', $Parent=0){
        $Code = strtoupper($Code);
        if($this->HostDb->query("call generate_order_product_num($Oid,$Set, '$Code', '$Product', $Parent, @opid, @order_product_num)", false)){
            if(!!($Query = $this->HostDb->query('select @opid as opid, @order_product_num as order_product_num', false))){
                $Row = $Query->row_array();
                $Query->free_result();
                $Return = array();
                $Opids = explode(',', trim($Row['opid'], ','));
                $OrderProductNum = explode(',', trim($Row['order_product_num'], ','));
                unset($Row);
                foreach ($Opids as $key => $value){
                    $Return[$key] = array(
                        'opid' => $value,
                        'order_product_num' => $OrderProductNum[$key]
                    );
                }
                unset($Opids);
                unset($OrderProductNum);
                log_message('debug', 'Model order/order_model _generate_order_num on Num ');
                return $Return;
            }
        }
        $this->remove_cache($this->_Module);
        return false;
    }
    /**
     * 获取批量插入的id号
     * @param unknown $Insert
     */
    private function _select_latest_insert_ids($Insert){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Insert as $key => $value){
            $Where[$key] = $value['op_num'];
        }
        $Query = $this->HostDb->select('op_id, op_num')->from('order_product')->where_in('op_num', $Where)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->result_array();
            $Return = $this->_unformat($Return, $Item, $this->_Module);
            $Query->free_result();
            return $Return;
        }
        return false;
    }
    
    /**
     * 删除订单时，清除订单产品
     * @param unknown $Where
     */
    public function delete_order_product_from_order($Where){
        if(!!($Return = $this->_select_order_product_ids('op_order_id', $Where))){
            if(is_array($Where)){
                $this->HostDb->where_in('op_order_id', $Where);
            }else{
                $this->HostDb->where('op_order_id', $Where);
            }
            if(!!($this->HostDb->delete('order_product'))){
                foreach ($Return as $key =>$value){
                    $Opids[$key] = $value['op_id'];
                }
                return $Opids;
            }
        }
        return false;
    }
    
    private function _select_order_product_ids($Key, $Value){
        $this->HostDb->select('op_id');
        $this->HostDb->from('order_product');
        if(is_array($Value)){
            $this->HostDb->where_in($Key, $Value);
        }else{
            $this->HostDb->where($Key, $Value);
        }
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }
    
    /**
     * 更新订单产品
     * @param unknown $Set
     * @param unknown $Where
     */
    public function update($Set, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format_re($Set, $Item, $this->_Module);
        if(is_array($Where)){
            $this->HostDb->where_in('op_id', $Where);
        }else{
            $this->HostDb->where('op_id',$Where);
        }
        
        $this->HostDb->update('order_product', $Set);
        log_message('debug', "Model order_product_model/update");
        $this->remove_cache($this->_Module);
        return true;
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
            $this->HostDb->where_in('op_id',$Where);
        }else{
            $this->HostDb->where('op_id',$Where);
        }
        
        $this->HostDb->update('order_product', $Set);
        log_message('debug', "Model order_product_model/update_workflow");
        $this->remove_cache($this->_Module);
        return true;
    }
    
    /**
     * 批量更新订单产品信息
     * @param unknown $Data
     * @return boolean
     */
    public function update_batch($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item, $this->_Module);
        }
        $this->HostDb->update_batch('order_product', $Data, 'op_id');
        log_message('debug', "Model Order_product_model/update_batch!");
        $this->remove_cache($this->_Module);
        return true;
    }

    
    /**
     * 更新订单产品的扫描状态
     * @param unknown $Bid 板块的id号
     */
    public function update_scan($Bid){
        /*判断订单产品的当前扫描状态*/
        $Sql = "SELECT opb_order_product_id, if(min(opbp_scan_datetime) = '0000-00-00 00:00:00', 1, 2) as bb
                    FROM n9_order_product_board_plate
                    LEFT JOIN n9_order_product_board on opb_id = opbp_order_product_board_id
                    WHERE opb_order_product_id in(
                    SELECT opb_order_product_id FROM n9_order_product_board_plate AS B
                    LEFT JOIN n9_order_product_board on opb_id = opbp_order_product_board_id
                    WHERE opbp_id = $Bid)  GROUP BY opb_order_product_id";
        
        $Query = $this->HostDb->query($Sql);
        if($Query->num_rows() > 0){
            $Status = $Query->row_array();
            $Query->free_result();
            
            /*计算最初一次和最近一次扫描时间*/
            $Sql = "SELECT opb_order_product_id, min(opbp_scan_datetime) as op_scan_start, max(opbp_scan_datetime) as op_scan_end
                        FROM n9_order_product_board_plate left join n9_order_product_board on opb_id = opbp_order_product_board_id
                        WHERE opb_order_product_id = {$Status['opb_order_product_id']} 
                        && (opbp_scan_datetime is not null && opbp_scan_datetime != '0000-00-00 00:00:00')
                        GROUP BY opb_order_product_id";
            $Query = $this->HostDb->query($Sql);
            if($Query->num_rows() > 0){
                $Datetime = $Query->row_array();
                $Query->free_result();
                $Set = array(
                    'op_scan_status' => $Status['bb'],
                    'op_scan_start' => $Datetime['op_scan_start'],
                    'op_scan_end' => $Datetime['op_scan_end']
                );
                $this->HostDb->where(array('op_id'=>$Status['opb_order_product_id']));
                $this->remove_cache($this->_Module);
                return $this->HostDb->update('order_product', $Set);
            }
        }
        return false;
    }
    /**
     * 获取订单产品的当前状态
     * @param unknown $Ids
     */
    public function select_status($Ids){
        $Item = $this->_Item.__FUNCTION__;
        $Query = $this->HostDb->select('op_id, op_status')->from('order_product')->where_in('op_id', $Ids)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->result_array();
            $Return = $this->_unformat($Return, $Item, $this->_Module);
            return $Return;
        }else{
            return false;
        }
    }
    /**
     * 更新订单产品的当前状态
     * @param unknown $Data
     */
    public function update_status($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item, $this->_Module);
        }
        $this->_remove_cache();
        log_message('debug', "Model order_product_model/update_status");
        return $this->HostDb->update_batch('order_product', $Data, 'op_id');
    }
    
    /**
     * 删除订单产品，订单状态变为0;
     * @param unknown $Oids
     */
    public function delete_by_oid($Oids){
        $this->HostDb->set('op_status', 0);
        $this->HostDb->where_in('op_order_id', $Oids);
        $this->HostDb->update('order_product');
        $this->remove_cache($this->_Module);
        return true;
    }
    
    protected function _default($name, $tmp=''){
        switch ($name){
            case 'dismantler':
            case 'creator':
                $Return = $this->session->userdata('uid');
                break;
            case 'create_datetime':
                $Return = date('Y-m-d H:i:s');
                break;
            default:
                $Return = $tmp;
        }
        return $Return;
    }
    
    private function _remove_cache(){
        $this->load->helper('file');
        delete_cache_files('(.*'.$this->_Module.'.*)');
    }
}
