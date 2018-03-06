<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月21日
 * @author Zhangcc
 * @version
 * @des
 */
class Stock_outted_model extends Base_Model{
    private $_Module = 'stock';
    private $_Model;
    private $_Item;
    private $_Cache;
    private $_Num;
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';
        
        log_message('debug', 'Model stock/Stock_outted_model start!');
    }
    
    public function select($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page($Con);
            }else{
                $this->_Num = $Con['num'];
            }
            if(!empty($Con['pn'])){
                $Sql = $this->_unformat_as($Item, $this->_Module);
                $this->HostDb->select($Sql, FALSE);
                $this->HostDb->from('stock_outted');
                $this->HostDb->join('user', 'u_id = so_creator', 'left');
                 
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                                        ->like('so_truck', $Con['keyword'])
                                        ->or_like('so_train', $Con['keyword'])
                                    ->group_end();
                }
                 
                $this->HostDb->order_by('so_end_datetime', 'desc');
                $this->HostDb->order_by('so_truck');
                $this->HostDb->order_by('so_train');
                 
                $this->HostDb->limit($Con['pagesize'], ($Con['p']-1)*$Con['pagesize']);
                 
                $Query = $this->HostDb->get();
                if($Query->num_rows() > 0){
                    $Return = array(
                        'content' => $Query->result_array(),
                        'num' => $this->_Num,
                        'p' => $Con['p'],
                        'pn' => $Con['pn']
                    );
                    $Query->free_result();
                    $this->cache->save($Cache, $Return, HOURS);
                }
            }else{
                $GLOBALS['error'] = '没有符合要求发货记录单!';
            }
        }
        return $Return;
    }

    private function _page($Con, $Public = false){
        $this->HostDb->select('count(so_id) as num', FALSE);
        $this->HostDb->from('stock_outted');

        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                            ->like('so_truck', $Con['keyword'])
                            ->or_like('so_train', $Con['keyword'])
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
     * 通过Id号获取信息
     * @param unknown $Id
     */
    public function select_by_id($Id){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Id;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('stock_outted');
            $this->HostDb->join('user', 'u_id = so_creator', 'left');
            
            $this->HostDb->where('so_id', $Id);
             
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '获取发货记录失败!';
            }
        }
        return $Return;
    }
    /**
     * 新健订单
     * @param unknown $Set
     */
    public function insert($Set){
        log_message('debug', "Model Stock_outted_model/insert_stock_outted Start");
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('stock_outted', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
    }
    
    public function update_stock_outted($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item, $this->_Module);
        if(is_array($Where)){
            $this->HostDb->where_in('so_id', $Where);
        }else{
            $this->HostDb->where(array('so_id' => $Where));
        }
        $this->remove_cache($this->_Module);
        return $this->HostDb->update('stock_outted', $Data);
    }
    
    /**
     * 重新发货时，则删除旧的发货记录
     * @param unknown $Where
     */
    public function delete($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('so_id', $Where);
        }else{
            $this->HostDb->where('so_id', $Where);
        }
        $this->remove_cache($this->_Module);
        return $this->HostDb->delete('stock_outted');
    }
}