<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月30日
 * @author Zhangcc
 * @version
 * @des
 */
class Server_model extends MY_Model {
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model Product/Server_model Start!');
    }

    public function select_server() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->X->select($Sql, FALSE)
                ->from('server')
                ->join('product', 'p_id = s_type_id', 'left')
                ->order_by('s_type_id');
        
            $Query = $this->X->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
            }else{
                $GLOBALS['error'] = '无任何服务';
            }
        }
        return $Return;
    }
}