<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月14日
 * @author Zhangcc
 * @version
 * @des
 * 图纸库
 */
class Drawing_model extends MY_Model{
    private $_Module = 'drawing';
    private $_Model;
    private $_Item;
    private $_Cache;
    private $_Num;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Drawing/Drawing_model start!');
        $this->e_cache->open_cache();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
    }

    public function select_drawing($Con){
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
                $this->HostDb->from('drawing');
                $this->HostDb->join('order_product', 'op_id = d_order_product_id', 'left');
                 
                if(!empty($Con['keyword'])){
                    $this->HostDb->where("(d_name like '%".$Con['keyword']."%')");
                }
                 
                $this->HostDb->order_by('d_name', 'desc');
                 
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
        $this->HostDb->select('count(d_id) as num', FALSE);
        $this->HostDb->from('drawing');

        if(!empty($Con['keyword'])){
            $this->HostDb->where("(d_name like '%".$Con['keyword']."%')");
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
    public function select_drawing_by_did($Did){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Did;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Query = $this->HostDb->select('d_path')->from('drawing')->where('d_id', $Did)->get();
            if($Query->num_rows() > 0){
                $Row = $Query->row_array();
                $Query->free_result();
                $Return = $Row['d_path'];
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    
    /**
     * 获得某一订单产品下的全部图纸
     * @param unknown $Opid
     */
    public function select_by_opid($Opid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Opid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)
                                ->from('drawing')
                                ->where('d_order_product_id', $Opid)
                                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    /**
     * 是否已经存在
     * @param unknown $Set
     */
    private function _is_exist_drawing($Name){
        $Query = $this->HostDb->select('d_id, d_path')->from('drawing')->where('d_name', $Name)->get();
        if($Query->num_rows() >0){
            $Row = $Query->row_array();
            $Query->free_result();
            return $Row;
        }else{
            return false;
        }
    }
    /**
     * 新健订单
     * @param unknown $Set
     */
    public function insert_drawing($Set){
        log_message('debug', "Model Drawing_model/insert_drawing Start");
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if(!!($Drawing = $this->HostDb->insert('drawing', $Set))){
            log_message('debug', "Model Drawing_model/insert_drawing Success!");
            $this->remove_cache($this->_Module);
            return true;
        }else{
            log_message('debug', "Model Drawing_model/insert_drawing Error");
            return false;
        }
    }
    
     /**
      * 更新订单信息
      * @param unknown $Data
      * @param unknown $Where
      */
     public function update_drawing($FileName){
         if(!!($Drawing = $this->_is_exist_drawing($FileName))){
             $Length = mb_strpos($Drawing['d_path'], '?');
             if($Length){
                 $Path = mb_substr($Drawing['d_path'], 0, $Length).'?_='.time();
             }else{
                 $Path = $Drawing['d_path'].'?_='.time();
             }
             $this->HostDb->set('d_path', $Path);
             $this->HostDb->where('d_id', $Drawing['d_id']);
             $this->HostDb->update('drawing');
             $this->remove_cache($this->_Module);
             return true;
         }else{
             return false;
         }
     }

     public function delete_drawing($Where) {
         if(is_array($Where)){
             $this->HostDb->where_in('d_id', $Where);
         }else{
             $this->HostDb->where('d_id', $Where);
         }
         $this->HostDb->delete('drawing');
         $this->remove_cache($this->_Module);
         return true;
     }
}
