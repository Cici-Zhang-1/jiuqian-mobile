<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月15日
 * @author Zhangcc
 * @version
 * @des
 * 
 */
class Order_product_classify_model extends Base_Model{
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

        log_message('debug', 'Model Order/Order_product_classify_model start!');
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
                $this->HostDb->from('order_product_classify');
                $this->HostDb->join('classify', 'c_id = opc_classify_id', 'left');
                $this->HostDb->join('order_product', 'op_id = opc_order_product_id', 'left');
                $this->HostDb->join('order', 'o_id = op_order_id', 'left');
                $this->HostDb->join('user as A', 'A.u_id = opc_optimizer', 'left');
                $this->HostDb->join('user as B', 'B.u_id = op_dismantler', 'left');

                $this->HostDb->where('c_optimize', 1);
                $this->HostDb->where('o_asure_datetime is not null'); /*已经确认的订单才可以导出优化文件*/
                $this->HostDb->where('op_status >= 3');
                $this->HostDb->where('o_status >= 11');
                $this->HostDb->where("op_product_id in ({$Con['product']})"); /*对应产品*/

                $this->HostDb->where(array('opc_amount > ' => 0)); /*板块数量大于0*/

                if(2 !== count($Con['optimize'])){ /*优化状态*/
                    if(in_array(0, $Con['optimize'])){
                        $this->HostDb->where("(opc_optimize_datetime is null || opc_optimize_datetime = '')");
                    }else{
                        $this->HostDb->where("(opc_optimize_datetime is not null && opc_optimize_datetime != '')");
                    }
                }
                
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                                    ->like('op_remark', $Con['keyword'])
                                    ->or_like('o_remark', $Con['keyword'])
                                    ->or_like('o_dealer', $Con['keyword'])
                                    ->or_like('o_owner', $Con['keyword'])
                                    ->or_like('op_num', $Con['keyword'])
                                    ->or_like('opc_board', $Con['keyword'])
                                    ->or_like('opc_optimize_datetime', $Con['keyword'])
                                ->group_end();
                }


                if('num' == $Con['sort']){
                    $this->HostDb->order_by('op_num');
                    $this->HostDb->order_by('opc_board');
                }elseif ('board' == $Con['sort']){
                    $this->HostDb->order_by('opc_board');
                    $this->HostDb->order_by('op_num');
                }elseif ('datetime' == $Con['sort']){
                    $this->HostDb->order_by('opc_optimize_datetime', 'desc');
                    $this->HostDb->order_by('op_num');
                    $this->HostDb->order_by('opc_board');
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
        $this->HostDb->select('count(opc_id) as num', FALSE);
        $this->HostDb->from('order_product_classify');
        $this->HostDb->join('classify', 'c_id = opc_classify_id', 'left');
        $this->HostDb->join('order_product', 'op_id = opc_order_product_id', 'left');
        $this->HostDb->join('order', 'o_id = op_order_id', 'left');
        
        $this->HostDb->where('c_optimize', 1);
        $this->HostDb->where('o_asure_datetime is not null');
        $this->HostDb->where('op_status >= 3');
        $this->HostDb->where('o_status >= 11');
        
        $this->HostDb->where("op_product_id in ({$Con['product']})");

        $this->HostDb->where(array('opc_amount > ' => 0));

        if(2 !== count($Con['optimize'])){
            if(in_array(0, $Con['optimize'])){
                $this->HostDb->where("(opc_optimize_datetime is null || opc_optimize_datetime = '')");
            }else{
                $this->HostDb->where("(opc_optimize_datetime is not null && opc_optimize_datetime != '')");
            }
        }
        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
            ->like('op_remark', $Con['keyword'])
            ->or_like('o_remark', $Con['keyword'])
            ->or_like('o_dealer', $Con['keyword'])
            ->or_like('o_owner', $Con['keyword'])
            ->or_like('op_num', $Con['keyword'])
            ->or_like('opc_board', $Con['keyword'])
            ->or_like('opc_optimize_datetime', $Con['keyword'])
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

    public function select($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product_classify');
            $this->HostDb->join('order_product', 'op_id = opc_order_product_id', 'left');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            $this->HostDb->join('classify', 'c_id = opc_classify_id', 'left');
        
            $this->HostDb->where('opc_status', $Con['status']);
            $this->HostDb->where('op_status > 3');
            $this->HostDb->where('o_status > 11');
        
            $this->HostDb->order_by('opc_optimize_datetime');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Result = $Query->result_array();
                $Return = array(
                    'content' => $Result,
                );
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    public function select_electric_saw($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order_product_classify');
            $this->HostDb->join('order_product', 'op_id = opc_order_product_id', 'left');
            $this->HostDb->join('order', 'o_id = op_order_id', 'left');
            $this->HostDb->join('classify', 'c_id = opc_classify_id', 'left');
            
            $this->HostDb->where('opc_status', $Con['status']);
            $this->HostDb->where('op_status > 3');
            $this->HostDb->where('o_status > 11');
            
            $this->HostDb->order_by('opc_optimize_datetime');
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Result = $Query->result_array();
                $Return = array(
                    'content' => $Result,
                );
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }
    
    /**
     * 通过订单产品分类id获取同一批次的订单产品分类编号
     * @param unknown $Opcids
     */
    public function select_batch($Opcids){
        $Opcids = implode(',', $Opcids);
        $Query = $this->HostDb->query("select opc_id as opcid from n9_order_product_classify where opc_optimize_datetime in 
            (select opc_optimize_datetime from n9_order_product_classify where opc_id in ($Opcids) group by opc_optimize_datetime)");
        if($Query->num_rows() > 0){
            $Return = $Query->result_array();
        }else{
            $Return = false;
        }
        return $Return;
        
    }

    /**
     * 获取同一订单产品编号中的批次号
     * @param $OrderProductNum
     */
    public function select_sn($OrderProductNum){
        $Query = $this->HostDb->query("SELECT c_name as name, opc_sn as sn from n9_order_product_classify left join n9_classify
                                          ON c_id = opc_classify_id LEFT JOIN n9_order_product ON op_id = opc_order_product_id
                                          WHERE op_num = '{$OrderProductNum}' && opc_sn IS NOT NULL ");
        if ($Query->num_rows() > 0){
            $Return = array(
                'content' => $Query->result_array()
            );
        }else{
            $Return = false;
        }
        return $Return;
    }
    /**
     * 获取当前订单中板块分类的工作流
     */
    public function select_current_workflow($Opcid, $Type){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item, $this->_Module);
        $Query = $this->HostDb->select($Sql)
                            ->from('order_product_classify')
                            ->join('workflow', 'w_no = opc_status', 'left')
                            ->where('opc_id', $Opcid)
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
     * 获取当前状态的下一状态
     * @param unknown $Id
     */
    public function select_next_status($Id){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Id;
        
        if(!($Return = $this->cache->get($Cache))){
            $this->HostDb->select('opc_id, opc_status, c_name, c_process',  FALSE);
            $this->HostDb->from('order_product_classify');
            $this->HostDb->join('classify', 'c_id = opc_classify_id', 'left');
            
            $this->HostDb->where('opc_id', $Id);
            
            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $Query->free_result();
                $Process = $Return['c_process'];
                $Process = explode(',', $Process);
                foreach ($Process as $key => $value){
                    if($value == $Return['opc_status']){
                        $Next = $Process[$key + 1];
                    }
                }
                $Return = array(
                    'classify' => $Return['c_name'],
                    'status' => $Next
                );
                unset($Next, $Process);
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合要求的下一状态';
            }
        }
        return $Return;
    }
    
    /**
     * 获取订单产品id
     * @param unknown $Ids
     */
    public function select_opids_by_opcids($Ids){
        $Return = false;
        $this->HostDb->select('opc_order_product_id as opid')
                        ->from('order_product_classify');
        if(is_array($Ids)){
            $this->HostDb->where_in('opc_id', $Ids);
        }else{
            $this->HostDb->where('opc_id', $Ids);
        }
        $this->HostDb->group_by('opid');
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Return = $Query->result_array();
            $Query->free_result();
        }else{
            $GLOBALS['error'] = '您要通过订单产品分类获取订单产品编号不存在';
        }
        return $Return;
    }
    
    /**
     * 通过订单产品id获取订单产品板块分类编号
     * @param unknown $Ids
     */
    public function select_opcids_by_opids($Ids){
        $Return = false;
        $this->HostDb->select('opc_id as opcid')->from('order_product_classify');
        if(is_array($Ids)){
            $this->HostDb->where_in('opc_order_product_id', $Ids);
        }else{
            $this->HostDb->where('opc_order_product_id', $Ids);
        }
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Return = $Query->result_array();
            $Query->free_result();
        }else{
            $GLOBALS['error'] = '您要查看订单产品板块分类记录不存在';
        }
        return $Return;
    }
    /**
     * 判断板材是否已经统计
     * @param unknown $Opid order_product_id
     * @param unknown $Board  board
     * @return boolean|Ambigous <unknown>
     */
    public function is_existed($Classify) {
        $Cache = $this->_Cache.__FUNCTION__.implode(',', $Classify);
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Query = $this->HostDb->select('opc_id')
                                    ->from('order_product_classify')
                                    ->where('opc_order_product_id', $Classify['opid'])
                                    ->where('opc_board', $Classify['board'])
                                    ->where('opc_optimize', $Classify['optimize'])
                                    ->where('opc_classify_id', $Classify['classify_id'])
                                    ->where('opc_status', $Classify['status'])
                                    ->limit(1)
                                ->get();
            if($Query->num_rows() > 0){
                $Row = $Query->row_array();
                $Query->free_result();
                $Return = $Row['opc_id'];
                $this->cache->save($Cache, $Return, HOURS);
            }
        }
        return $Return;
    }

    public function insert($Set){
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format($Set, $Item, $this->_Module);
        if($this->HostDb->insert('order_product_classify', $Set)){
            log_message('debug', "Model $Item Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model $Item Error");
            return false;
        }
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
        log_message('debug', "Model Order_product_classify_model/update_batch!");
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
        $this->HostDb->update_batch('order_product_classify', $Data, 'opc_id');
        log_message('debug', "Model Order_product_classify_model/update_batch!");
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
        $Except = array(9);
        $Subs = array(0);
        $Return = array();
        $Query = $this->HostDb->select('opc_id, op_id')
                                ->from('order_product_classify')
                                ->join('order_product', 'op_id = opc_order_product_id', 'left')
                                ->order_by('op_num')
                                ->where_in('opc_id', $Ids)
                            ->get();
        if($Query->num_rows() > 0){
            $Oids = $Query->result_array();
            $Query->free_result();
        
            $Num = 1;
            $Uid = $this->session->userdata('uid');
            foreach ($Oids as $key => $value){
                if(!isset($Shift[$value['op_id']])){
                    if (in_array($Num, $Except)) {
                        $Shift[$value['op_id']] = array_pop($Subs);
                        $Num++;
                    }else {
                        $Shift[$value['op_id']] = $Num++;
                    }
                }
                $OrderProductBoard[] = array(
                    'opc_id' => $value['opc_id'],
                    'opc_sn' => $Shift[$value['op_id']],
                    'opc_optimizer' => $Uid,
                    'opc_optimize_datetime' => $Time
                );
                $Return[] = $value['op_id'];
            }
            $this->HostDb->update_batch('order_product_classify', $OrderProductBoard, 'opc_id');
            $this->remove_cache($this->_Module);
            $Return = array_unique($Return);
            return $Return;
        }else{
            $GLOBALS['error'] = '您要查看优化的订单不存在';
            return false;
        }
        /* 
        $this->HostDb->set('opc_optimizer', $this->session->userdata('uid'));
        $this->HostDb->set('opc_optimize_datetime', $Time);
        $this->HostDb->where_in('opc_id', $Ids);
        $this->HostDb->update('order_product_classify');
        $this->remove_cache($this->_Module);
        return true; */
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
            $this->HostDb->where_in('opc_id',$Where);
        }else{
            $this->HostDb->where('opc_id',$Where);
        }
        
        $this->HostDb->update('order_product_classify', $Set);
        log_message('debug', "Model order_product_classify/update_workflow");
        $this->remove_cache($this->_Module);
        return true;
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

    /**
     * 删除预先处理的清单
     * @param $Opid
     * @return bool
     */
    public function delete_produce_prehandle($Opid){
        $this->HostDb->where('opc_order_product_id', $Opid);
        $this->HostDb->where("(opc_optimize_datetime is null || opc_optimize_datetime = '')");
        $this->HostDb->delete('order_product_classify');
        $this->remove_cache($this->_Module);
        return true;
    }
}