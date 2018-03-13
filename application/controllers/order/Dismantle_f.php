<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月2日
 * @author Zhangcc
 * @version
 * @des
 */
class Dismantle_f extends MY_Controller{
    public function __construct(){
        log_message('debug', 'Controller Order/Dismantle_f eStart!');
        parent::__construct();
        $this->load->library('d_dismantle');
    }
    
    public function read(){
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        $Data = array(
            'server' => array()
        );
        if($Id){
            $Data['server'] = $this->d_dismantle->read_detail('server', $Id);
        }
        $this->_return($Data);
    }
}
