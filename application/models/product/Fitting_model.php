<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月30日
 * @author Zhangcc
 * @version
 * @des
 */
class Fitting_model extends MY_Model {
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model Product/Fitting_model Start!');
    }
    
    public function select_fitting() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $this->X->select($Sql, FALSE)
                ->from('fitting')
                ->join('product', 'p_id = f_type_id', 'left')
                ->join('supplier', 's_id = f_supplier_id', 'left')
                ->order_by('f_type_id');
            
            $Query = $this->X->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
            }else{
                $GLOBALS['error'] = '无任何配件';
            }
        }
        return $Return;
    }
}