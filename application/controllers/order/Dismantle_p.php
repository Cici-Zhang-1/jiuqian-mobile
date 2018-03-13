<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月2日
 * @author Zhangcc
 * @version
 * @des
 */
class Dismantle_p extends MY_Controller{
    public function __construct(){
        log_message('debug', 'Controller Order/Dismantle_p eStart!');
        parent::__construct();
        $this->load->library('d_dismantle');
    }
    
    public function read(){
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        $Data = array(
            'fitting' => array()
        );
        if($Id){
            $Data['fitting'] = $this->d_dismantle->read_detail('fitting', $Id);
        }
        $this->_return($Data);
    }
}
