<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Drawing_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Drawing_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model drawing/Drawing_model Start!');
    }

    /**
     * Select from table drawing
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('drawing')
                    ->join('order_product', 'op_id = d_order_product_id', 'left');
                if (!empty($Search['order_product_id'])) {
                    $this->HostDb->where('d_order_product_id', $Search['order_product_id']);
                }
                if (!empty($Search['drawing_id'])) {
                    $this->HostDb->where('d_id', $Search['drawing_id']);
                }
                if (isset($Search['keyword']) && $Search['keyword'] != '') {
                    $this->HostDb->group_start()
                        ->like('op_num', $Search['keyword'])
                        ->group_end();
                }
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的图纸';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(d_id) as num', FALSE)
            ->join('order_product', 'op_id = d_order_product_id', 'left');
        if (!empty($Search['order_product_id'])) {
            $this->HostDb->where('d_order_product_id', $Search['order_product_id']);
        }
        if (!empty($Search['drawing_id'])) {
            $this->HostDb->where('d_id', $Search['drawing_id']);
        }
        if (isset($Search['keyword']) && $Search['keyword'] != '') {
            $this->HostDb->group_start()
                ->like('op_num', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('drawing');

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Search['pagesize']) == 0){
                $Pn = intval($Row['num']/$Search['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * 通过订单产品获得图纸
     * @param $OrderProductId
     * @return array|bool
     */
    public function select_by_order_product_id ($OrderProductId) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $OrderProductId;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('drawing')
                ->join('order_product', 'op_id = d_order_product_id', 'left')
                ->where('d_order_product_id', $OrderProductId)
                ->get();

            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的图纸';
            }
        }
        return $Return;
    }
    /**
     * 判断是否存在图纸
     * @param $Name
     * @return bool
     */
    private function _is_exist($Name){
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
     * Insert data to table drawing
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('drawing', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入图纸数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table drawing
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('drawing', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入图纸数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table drawing
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('d_id', $Where);
        } else {
            $this->HostDb->where('d_id', $Where);
        }
        $this->HostDb->update('drawing', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table drawing
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('drawing', $Data, 'd_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 更新图纸
     * @param $FileName
     * @return bool
     */
    public function update_drawing($FileName){
        if(!!($Drawing = $this->_is_exist($FileName))){
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
    /**
     * Delete data from table drawing
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('d_id', $Where);
        } else {
            $this->HostDb->where('d_id', $Where);
        }

        $this->HostDb->delete('drawing');
        $this->remove_cache($this->_Module);
        return true;
    }
}
