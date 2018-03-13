<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-25
 * @author ZhangCC
 * @version
 * @description
 * 经销商
 */
class Dealer extends MY_Controller{
	private $_Module;
	private $_Controller;
	private $_Item;
	private $_Cookie;
	
	private $_InsertId;
	
	private $Search = array(
	    'keyword' => ''
	);
	
	public function __construct(){
		parent::__construct();
		$this->load->model('dealer/dealer_model');
		$this->load->model('dealer/dealer_linker_model');
		$this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
		$this->_Cookie = str_replace('/', '_', $this->_Item).'_';
		
		log_message('debug', 'Controller Dealer/dealer Start!');
	}

    public function index(){
		$View = $this->uri->segment(4, 'read');
		if(method_exists(__CLASS__, '_'.$View)){
		    $View = '_'.$View;
			$this->$View();
		}else{
			$Item = $this->_Item.$View;
			$Data['action'] = site_url($Item);
			$this->load->view($Item, $Data);
		}
	}
	
	public function read($All=''){
	    $All = trim($All);
	    if('all' != $All){
	        $Cookie = $this->_Cookie.__FUNCTION__;
	        $this->Search = $this->get_page_conditions($Cookie, $this->Search);
	        $Data = array();
	        if(!empty($this->Search)){
	            if(!!($Data = $this->dealer_model->select_dealer($this->Search))){
	                $this->Search['pn'] = $Data['pn'];
	                $this->Search['num'] = $Data['num'];
	                $this->Search['p'] = $Data['p'];
	                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
	            }else{
	                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合搜索条件的经销商';
	            }
	        }else{
	            $this->Failue = '对不起, 没有符合条件的内容!';
	        }
	        $this->_return($Data);
	    }else{
	        $this->_read_all();
	    }
	}
	
	public function _read_all(){
	    if(!!($Data = $this->dealer_model->select_all())){
	        $Content = array();
	        foreach ($Data['content'] as $key => $value){
	            $Content[$key] = $value;
	            if(empty($value['checker'])){
	                $Content[$key]['checker'] = $value['dealer_linker'];
	            }
	            if(empty($value['payer'])){
	                $Content[$key]['payer'] = $value['dealer_linker'];
	            }
	            if(empty($value['checker_phone'])){
	                $Content[$key]['checker_phone'] = $value['dealer_phone'];
	            }
	            if(empty($value['payer_phone'])){
	                $Content[$key]['payer_phone'] = $value['dealer_phone'];
	            }
	        }
	        $Data['content'] = $Content;
	        $this->Success = '获取所有供应商信息成功!';
	    }else{
	        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的供应商';
	    }
	    $this->_return($Data);
	}

	public function add(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Mobilephone = $this->input->post('mobilephone');
	        if($this->dealer_linker_model->is_registed($Mobilephone)){
	            $this->Failue = '手机号码已经注册';
	        }else{
	            if(!!($this->_add_dealer()) && !!($this->_add_dealer_delivery()) && !!($this->_add_dealer_linker())){
	                $this->Success .= '经销商新增成功, 刷新后生效!';
	            }else{
	                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商新增失败!';
	            }
	        }
	    }else{
	        $this->Failue .= validation_errors();
	    }
	    $this->_return();
	}
	private function _add_dealer(){
	    $Set = array(
	        'des' => $this->input->post('des'),
	        'shop' => $this->input->post('shop'),
	        'dcid' => $this->input->post('dcid'),
	        'aid' => $this->input->post('aid'),
	        'pid' => $this->input->post('pid'),
	        'remark' => $this->input->post('remark'),
	        'address' => $this->input->post('address'),
			'name' => $this->input->post('mobilephone')
	    );
	    $Set['password'] = md5($Set['name']);
	    $Set = gh_escape($Set);
	    if(!!($this->_InsertId = $this->dealer_model->insert($Set))){
	        $this->Success .= '经销商信息新增成功, 刷新后生效!';
	        return true;
	    }else{
	        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商信息新增失败';
	        return false;
	    }
	}
	
	private function _add_dealer_delivery(){
	    $Set = array(
	        'dealer_id' => $this->_InsertId,
	        'delivery_linker' => $this->input->post('delivery_linker'),
	        'delivery_phone' => $this->input->post('delivery_phone'),
	        'daid' => $this->input->post('daid'),
	        'lid' => $this->input->post('lid'),
	        'omid' => $this->input->post('omid'),
	        'delivery_address' => $this->input->post('delivery_address')
	    );
	    $Set = gh_escape($Set);
	    $this->load->model('dealer/dealer_delivery_model');
	    if(empty($Set['delivery_linker'])){
	        return TRUE;
	    }
	    if(!!($this->dealer_delivery_model->insert($Set))){
	        $this->Success .= '经销商发货信息新增成功, 刷新后生效!';
	        return true;
	    }else{
	        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商发货信息新增失败';
	        return false;
	    }
	}
	
	private function _add_dealer_linker(){
	    $Set = array(
	        'dealer_id' => $this->_InsertId,
	        'name' => $this->input->post('name'),
	        'mobilephone' => $this->input->post('mobilephone'),
	        'telephone' => $this->input->post('telephone'),
	        'email' => $this->input->post('email'),
	        'qq' => $this->input->post('type'),
	        'fax' => $this->input->post('fax'),
	        'remark' => $this->input->post('remark'),
	        'doid' => $this->input->post('doid')
	    );
	    $Set = gh_escape($Set);
	    if(empty($Set['name'])){
	        return true;
	    }
	    if(!!($this->dealer_linker_model->insert($Set))){
	        $this->Success .= '经销商联系人新增成功, 刷新后生效!';
	        return true;
	    }else{
	        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商联系人新增失败';
	        return false;
	    }
	}
	
	public function edit(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Post = gh_escape($_POST);
	        $Where = $Post['selected'];
	        unset($Post['selected']);
			if (isset($Post['password']) && '' != $Post['password']) {
				$Post['password'] = md5($Post['password']);
			}else {
				unset($Post['password']);
			}
	        if(!!($this->dealer_model->update($Post, $Where))){
	            $this->Success .= '经销商信息修改成功, 刷新后生效!';
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商信息修改失败!';
	        }
	    }else{
	        $this->Failue .= validation_errors();
	    }
	    $this->_return();
	}
	
	/**
	 * 删除
	 */
	public function remove(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Where = $this->input->post('selected', true);
	        if($Where !== false && is_array($Where) && count($Where) > 0){
	            $this->load->model('dealer/dealer_delivery_model');
	            $this->load->model('dealer/dealer_linker_model');
	            if($this->dealer_model->delete_dealer($Where)
	                && $this->dealer_delivery_model->delete_by_did($Where)
	                && $this->dealer_linker_model->delete_by_did($Where)){
	                $this->Success .= '经销商信息删除成功, 刷新后生效!';
	            }else {
	                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商信息删除失败';
	            }
	        }else{
	            $this->Failue .= '没有可删除项!';
	        }
	    }else{
	        $this->Failue .= validation_errors();
	    }
	    $this->_return();
	}
}
