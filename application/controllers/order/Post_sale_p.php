<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月19日
 * @author Administrator
 * @version
 * @des
 * 配件清单
 */
class Post_sale_p extends CWDMS_Controller{
    public function __construct(){
        log_message('debug', 'Controller Order/Post_sale_p eStart!');
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