<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian.
 * User: chuangchuangzhang
 * Date: 2018/2/9
 * Time: 15:56
 *
 * Desc: 是否是显示在移动端，这是在移动端显示，否则跳转到桌面端
 */

class Mobile {
    private $_CI;

    public function __construct() {
        log_message('debug', "Mobile Hook Start");

        $this->_CI = &get_instance();
    }

    public function is_mobile() {
        $this->_CI->load->library('user_agent');

        $GLOBALS['MOBILE'] = $this->_CI->agent->is_mobile();
        // $GLOBALS['MOBILE'] = TRUE;
        /* if (ENVIRONMENT === 'production' && !$this->_CI->agent->is_mobile()) {
            redirect($this->_CI->config->item('desktop_url'));
        } */
    }
}
