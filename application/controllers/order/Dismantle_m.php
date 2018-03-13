<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月2日
 * @author Zhangcc
 * @version
 * @des
 */
class Dismantle_m extends MY_Controller{
    private $_EditParam;
    public function __construct(){
        log_message('debug', 'Controller Order/Dismantle_m eStart!');
        parent::__construct();
        $this->load->library('d_dismantle');
    }
    
    public function read(){
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        $Data = array(
            'struct' => array(), 'board_door' => array()
        );
        if($Id){
            $Data['struct'] = $this->d_dismantle->read_detail('door', $Id);
            $Data['board_door'] = $this->d_dismantle->read_detail('board_door', $Id);
        }
        $this->_return($Data);
    }
}
