<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月19日
 * @author Administrator
 * @version
 * @des
 * 售后服务中外购
 */
class Post_sale_g extends MY_Controller{
    public function __construct(){
        log_message('debug', 'Controller Order/Post_sale_g eStart!');
        parent::__construct();
        $this->load->library('d_dismantle');
    }

    public function read(){
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        $Data = array(
            'other' => array()
        );
        if($Id){
            $Data['other'] = $this->d_dismantle->read_detail('other', $Id);
        }
        $this->_return($Data);
    }
}
