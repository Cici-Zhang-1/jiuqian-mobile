<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月18日
 * @author Zhangcc
 * @version
 * @des
 * 包装统计
 */
class Pack_statistics_model extends MY_Model{
    private $_Modular = 'pack';
    private $_Model;
    private $_Item;
    private $_Cache;
    private $_Num;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Produce/Pack_statistics_model start!');
        $this->e_cache->open_cache();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Modular.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Modular.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
    }

    public function select_pack_statistics($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page_num($Con);
            }else{
                $this->_Num = $Con['num'];
            }
            if(!empty($Con['pn'])){
                $Sql = $this->_unformat_as($Item, $this->_Modular);
                $this->HostDb->select($Sql, FALSE);
                $this->HostDb->from('order_product');
                $this->HostDb->join('order', 'o_id = op_order_id', 'left');
                $this->HostDb->join('user as A', 'A.u_id = op_packer', 'left');

                if(!empty($Con['keyword'])){
                    $this->HostDb->where("(o_remark like '%".$Con['keyword']."%'
                        || o_dealer like '%".$Con['keyword']."%'
                        || op_num like '%".$Con['keyword']."%')");
                }

                if(isset($Con['product']) && '' != $Con['product']){
                    $this->HostDb->where("op_product_id in ({$Con['product']})");
                }
            
                if(isset($Con['status']) && '' != $Con['status']){
                    $this->HostDb->where("op_status in ({$Con['status']})");
                }
                 
                if(isset($Con['ostatus']) && '' != $Con['ostatus']){
                    $this->HostDb->where("o_status in ({$Con['ostatus']})");
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

    private function _page_num($Con){
        $this->HostDb->select('count(op_id) as num', FALSE);
        $this->HostDb->from('order_product');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');

        if(!empty($Con['keyword'])){
            $this->HostDb->where("(o_remark like '%".$Con['keyword']."%'
                || o_dealer like '%".$Con['keyword']."%'
                || op_num like '%".$Con['keyword']."%')");
        }

        if(isset($Con['product']) && '' != $Con['product']){
            $this->HostDb->where("op_product_id in ({$Con['product']})");
        }
        if(isset($Con['status']) && '' != $Con['status']){
            $this->HostDb->where("op_status in ({$Con['status']})");
        }
         
        if(isset($Con['ostatus']) && '' != $Con['ostatus']){
            $this->HostDb->where("o_status in ({$Con['ostatus']})");
        }
        $this->HostDb->order_by('op_num', 'desc');
        
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

    public function select_produce_optimize_download($Id) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Id);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Modular);
            $Query = $this->HostDb->select($Sql,false)->from('order_product_board_plate')
            ->join('order_product_board', 'opb_id = opbp_order_product_board_id', 'left')
            ->join('order_product', 'op_id = opb_order_product_id', 'left')
            ->join('order','o_id = op_order_id', 'left')
            ->where_in('opbp_order_product_board_id', $Id)->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合要求需要优化的订单清单!';
            }
        }
        return $Return;
    }

    public function update_produce_optimize($Id, $Time){
        $OPB = array();
        $Shift = array();
        $Return = array();
        if(!!($Oids = $this->_select_produce_optimize_order_id($Id))){
            foreach ($Oids as $key => $value){
                if(!isset($Shift[$value['op_id']])){
                    $Shift[$value['op_id']] = array_shift($this->_Flag);
                }
                $OPB[] = array(
                    'opb_id' => $value['opb_id'],
                    'opb_optimize' => $Shift[$value['op_id']],
                    'opb_optimizer' => $this->session->userdata('uid'),
                    'opb_optimize_datetime' => $Time
                );
                $Return[] = $value['op_id'];
            }
            $this->HostDb->update_batch('order_product_board', $OPB, 'opb_id');
            $this->_Module();
            $Return = array_unique($Return);
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 生产优化
     * @param unknown $Id
     */
    private function _select_produce_optimize_order_id($Id){
        $this->HostDb->select('opb_id, op_id');
        $this->HostDb->from('order_product_board');
        $this->HostDb->join('order_product', 'op_id = opb_order_product_id', 'left');
        $this->HostDb->order_by('op_num');
        $this->HostDb->where_in('opb_id', $Id);
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Return = $Query->result_array();
            $Query->free_result();
            return $Return;
        }else{
            $GLOBALS['error'] = '您要查看优化的订单不存在';
            return false;
        }
    }

    /**
     * 移除缓存
     */
    private function _remove_cache(){
        $this->load->helper('file');
        delete_cache_files('(.*'.$this->_Cache.'.*)');
    }
}
