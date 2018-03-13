<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2014-8-15
 * @author ZhangCC
 * @version
 * @description  
 */
class MY_Controller extends CI_Controller
{
    protected $_Module;
    protected $_Controller;
    protected $_Item;
    protected $_Cookie;
    protected $_Validation;

	protected $Code = EXIT_SUCCESS;	// 返回码，默认是成功返回
	protected $Message = ''; // 返回时，携带的信息
	protected $Location = '';
	public function __construct()
	{
		parent::__construct();
		log_message('debug','Controller MY_Controller/__construct Start');
		$this->_init();
	}

    private function _init() {
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Validation = 'validation/' . $this->_Module . $this->_Controller . '_validation';
        $this->_Cookie = str_replace('/', '_', $this->_Item);
    }

    /**
     * 实施表单验证
     * @param string $Item
     * @param string $File
     */
    protected function _do_form_validation($Item = '', $File = '') {
        if (!(isset($File) && $File != false)) {
            $File = $this->_Validation;
        }

        if (isset($Item) && $Item != false) {
            $Item = $this->_Item . $Item;
        } else {
            $Item = $this->_Item . $this->router->method;
        }
        $this->config->load($File, true, true);
        if (!!($Rules = $this->config->item($Item, $File))) {
            $this->form_validation->set_rules($Rules);
            if($this->form_validation->run()){
                return true;
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = validation_errors();
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '没有找到对应验证配置!';
        }
        return false;
    }
    /**
     * Load Index View
     * @param $View
     */
    protected function _index($View) {
        $Item = $this->_Item.$View . $this->session->userdata('ugid');
        if (!file_exists(VIEWPATH . $Item . '.php') || file_expired(VIEWPATH . $Item . '.php', VIEW_EXPIRED)) {
            $this->load->library('permission');
            $Data['funcs'] = $this->permission->get_allowed_func();
            $Data['page_searches'] = $this->permission->get_allowed_page_search();
            $Data['cards'] = $this->permission->get_allowed_card();
            $this->load->library('generate/generate');
            $this->generate->create_view($Item, $Data);
        }
        $this->load->view($Item);
    }

    /**
     * Alias of _ajax_return
     * @param array $Data
     */
	public function _return($Data = array()) {
	    $this->_ajax_return($Data);
    }
	/**
	 * @param array $Data
	 */
	public function _ajax_return($Data = array()) {
		$this->Message .= isset($GLOBALS['message'])?(is_array($GLOBALS['message'])?implode(',', $GLOBALS['message']):$GLOBALS['message']):'';
		if (isset($_GET['callback'])) {
            exit($_GET['callback'] . '(' .json_encode(array('code'=>$this->Code, 'message'=> $this->Message, 'contents' => $Data)) . ')');
        }else {
            exit(json_encode(array('code'=>$this->Code, 'message'=> $this->Message, 'contents' => $Data)));
        }
	}

	/**
	 * 获得收索分页条件
	 * @param unknown $Cookie
	 * @param unknown $Con
	 */
	protected function get_page_conditions($Cookie, $Con){
	    $Return = array();
	    $Flag = false;
	    foreach ($Con as $key=>$value){
	        $Return[$key] = $this->input->get($key, true);
	        $Return[$key] = trim($Return[$key]);
	        if(false === $Return[$key] || '' == $Return[$key]){
	            $Return[$key] = $value;
	        }else{
	            $Flag = true;
	        }
	    }
	    if(!$Flag){
	        $P = $this->input->get('p', true);
	        $Page = $this->input->get('page', true);  //缓存页面
	        $Table = $this->input->get('table', true);  //缓存表格
	        $P = intval(trim($P));
	        if($P){
	            if((!!($Cookies = $this->input->cookie($Cookie, true))
	                   && !!($Condition = json_decode($Cookies, true)))
	                   || ($Page != false && !!($Cookies = $this->input->cookie($Page, true))
	                    && !!($Cookies = json_decode($Cookies, true))
	                    && isset($Cookies[$Table])
	                    && !!($Condition = $Cookies[$Table]))){
	                unset($Cookies);
	                $Return = array_merge($Return, $Condition);
	            }else{
	                $Return = $Con;
	            }
	            $Return['p'] = $P;
	            $Flag = true;
	        }
	    }
	    if(empty($Return['pn'])){
	        $Return['pn'] = 0;
	    }
	    if(empty($Return['pagesize']) || $Return['pagesize'] < MIN_PAGESIZE || $Return['pagesize'] > MAX_PAGESIZE){
	        $Return['pagesize'] = MIN_PAGESIZE;
	    }
	    if(empty($Return['p']) || $Return['p'] < 1){
	        $Return['p'] = 1;
	    }elseif (!empty($Return['pn']) && $Return['p'] > $Return['pn']){
	        $Return['p'] = $Return['pn'];
	    }
	    return $Return;
	}
	
}//end Base_Controller
