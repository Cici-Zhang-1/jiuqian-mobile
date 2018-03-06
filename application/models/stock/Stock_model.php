<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月19日
 * @author Zhangcc
 * @version
 * @des
 * 成品库
 */
class Stock_model extends Base_Model{
    private $_Modular = 'stock';
    private $_Model;
    private $_Item;
    private $_Cache;
    private $_Num;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Order/Order_model start!');
        $this->e_cache->open_cache();
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Modular.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Modular.'_'.$this->_Model.'_'.$this->session->userdata('uid').'_';
    }
}