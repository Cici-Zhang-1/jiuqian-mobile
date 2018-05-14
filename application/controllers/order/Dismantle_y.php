<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月2日
 * @author Zhangcc
 * @version
 * @des
 */
class Dismantle_y extends MY_Controller{
    private $_EditParam;
    public function __construct(){
        log_message('debug', 'Controller Order/Dismantle_y eStart!');
        parent::__construct();
        $this->load->library('d_dismantle');
    }
    
    public function read(){
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        $Data = array(
            'struct' => array(), 'plate_board' => array()
        );
        if($Id){
            $Data['struct'] = $this->d_dismantle->read_detail('wardrobe_struct', $Id);
            $Data['board_plate'] = $this->d_dismantle->read_detail('board_plate', $Id);
        }
        $this->_return($Data);
    }
}
