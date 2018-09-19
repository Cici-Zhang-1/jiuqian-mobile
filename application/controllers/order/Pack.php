<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 2016年3月16日
 * @author Administrator
 * @version
 * @des
 * 打包
 */
class Pack extends MY_Controller{
    private $__Search = array(
        'pack' => 0,
        'start_date' => '',
        'end_date' => '',
        'status' => WP_PACK
    );
    private $_PackGroupMethod = 0;
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Ppack __construct Start!');
        $this->load->model('order/pack_model');
        $this->load->model('data/configs_model');
        $this->_PackGroupMethod = intval($this->configs_model->select_by_name('pack_group_method')); // 分组方法
    }

    public function index() {
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_' . $View)){
            $View = '_' . $View;
            $this->$View();
        } else {
            $this->_index($View);
        }
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['pack'])) {
            if ($this->_is_pack_group()) {
                $this->_Search['pack'] = $this->session->userdata('uid');
            }
        }
        if ($this->_Search['status'] == WP_PACKED && $this->_Search['start_date'] == '') {
            $this->_Search['start_date'] = date('Y-m-01');
        }
        $Data = array();
        if(!($Data = $this->pack_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取打包任务信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_return($Data);
    }

    private function _is_pack_group () {
        $this->load->model('permission/usergroup_model');
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('打包'))) {
            return $this->session->userdata('ugid') == $UsergroupV;
        }
        return false;
    }
}