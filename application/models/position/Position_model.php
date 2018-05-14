<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 2017/10/14
 * Time: 09:17
 *
 * Desc:
 */

class Position_model extends MY_Model{
    private $_Module = 'position';
    private $_Model;
    private $_Item;
    private $_Cache;
    private $_Table = 'position';
    private $_TableOne = 'position_order_product';
    private $_TableTwo = 'order_product';
    public function __construct(){
        parent::__construct();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Module.'_'.$this->_Model.'_';

        log_message('debug', 'Model Data/Position_model Start!');
    }

    public function select_position(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);

        if(!($Return = $this->cache->get($Cache))) {
            $Query = $this->HostDb->query("select $Sql from n9_position as b left join 
                              (select pop_position_id, group_concat(concat(op_num, '___', pop_count) SEPARATOR ', ') as op_num from n9_position_order_product 
                              left join n9_order_product on op_id = pop_order_product_id where pop_status > 0 
                              group by pop_position_id) as a on a.pop_position_id =  b.p_id");
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else {
                $Return = false;
            }
        }
        return $Return;
    }

    public function insert_position($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert($this->_Table, $Data)){
            log_message('debug', "Model Position_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Position_model/insert Error");
            return false;
        }
    }

    public function update_position($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('p_id', $Where);
        }else {
            $this->HostDb->where('p_id', $Where);
        }
        $this->HostDb->update($this->_Table, $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }

    /**
     * 删除
     * @param unknown $Where
     */
    public function delete_position($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('p_id', $Where);
        }else{
            $this->HostDb->where('p_id', $Where);
        }
        $this->HostDb->delete($this->_Table);
        $this->remove_cache($this->_Cache);
        return true;
    }
}
