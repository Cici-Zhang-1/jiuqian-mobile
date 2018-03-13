<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月19日
 * @author Administrator
 * @version
 * @des
 */
class Post_sale_f extends MY_Controller{
    public function __construct(){
        log_message('debug', 'Controller Order/Post_sale_f eStart!');
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
