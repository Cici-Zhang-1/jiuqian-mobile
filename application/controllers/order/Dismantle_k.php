<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月2日
 * @author Zhangcc
 * @version
 * @des
 */
class Dismantle_k extends MY_Controller{
    private $_EditParam;
    public function __construct(){
        log_message('debug', 'Controller Order/Dismantle_k eStart!');
        parent::__construct();
        $this->load->library('d_dismantle');
    }
    
    public function read(){
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        $Data = array(
            'struct' => array(), 'board_wood' => array()
        );
        if($Id){
            $Data['board_wood'] = $this->d_dismantle->read_detail('board_wood', $Id);
        }
        $this->_return($Data);
    }
    
    private function _read_board_wood($Id){
        $this->load->model('order/order_product_board_wood_model');
        return $this->order_product_board_wood_model->select_order_product_board_wood_by_opid($Id);
    }
}
