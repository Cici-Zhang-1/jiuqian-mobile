<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-27
 * @author ZhangCC
 * @version
 * @description  
 */
class Order extends MY_Controller {
	private $Insert; // 新增orderid
	private $Search = array(
        'status' => '',
        'start_create_date' => '',
        'end_create_date' => '',
        'keyword' => '',
        'all' => NO,
        'dealer_id' => ZERO
	);
	private $_Order = array(); // orderdata
    private $_OrderProduct = array();
	private $_ShopPrimaryInfo = array(); // dealer_shop_primary_info
	public function __construct(){
		parent::__construct();
        log_message('debug', 'Controller Order/Order __construct Start!');
		$this->load->model('order/order_model');
	}
	
	public function index(){
		$View = $this->uri->segment(4, 'read');
		if(method_exists(__CLASS__, '_'.$View)){
			$View = '_'.$View;
			$this->$View();
		}else{
			$this->_index($View);
		}
	}
	
	public function read() {
		$this->_Search = array_merge($this->_Search, $this->Search);
        $this->get_page_search();
        $this->Search = $this->_Search;
        if ($this->_Search['start_create_date'] == '') {
            $this->_Search['start_create_date'] = date('Y-m-d', strtotime('-30 days'));
        }
        if (empty($this->_Search['owner'])) { // 默认只看自己的订单
            $this->_Search['owner'] = $this->session->userdata('uid');
        }
        if (empty($this->_Search['dealer_id'])) {
            $DealerId = $this->input->get('v');
            $DealerId = intval($DealerId);
            if (!empty($DealerId)) {
                $this->_Search['dealer_id'] = $DealerId;
            }
        }
        if (is_array($this->_Search['status'])) {
            foreach ($this->_Search['status'] as $Key => $Value) {
                if ($Value == '') {
                    unset($this->_Search['status'][$Key]);
                }
            }
        }
		$Data = array();
        if(!($Data = $this->order_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        if (!empty($this->_Search['dealer_id'])) {
            $Data['query']['dealer_id'] = $this->_Search['dealer_id'];
        }
		$this->_ajax_return($Data);
	}

    /**
     * 详细信息
     */
	public function detail () {
	    $this->_Search['order_id'] = ZERO;
        $this->get_page_search();
        if (empty($this->_Search['order_id'])) {
            $OrderId = $this->input->get('v', true);
            $OrderId = intval($OrderId);
            if (!empty($OrderId)) {
                $this->_Search['order_id'] = $OrderId;
            } else {
                $this->_get_order_product();
            }
        }

        $Data = array();
        if (!empty($this->_Search['order_id'])) {
            if(!($Data = $this->order_model->select_detail($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单详情不存在';
                $this->Code = EXIT_ERROR;
            } else {
                $this->load->helper('json_helper');
                $Data['content']['warehouse_num'] = discode_warehouse_v($Data['content']['warehouse_num']);
                $Data['content']['order_product'] = $this->_get_order_product_num();
            }
            $Data['query']['order_id'] = $this->_Search['order_id'];
        } else {
            $this->Message = '请选择订单获取订单详情';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    private function _get_order_product () {
        $OrderProductId = $this->input->get('order_product_id', true);
        $OrderProductId = intval($OrderProductId);
        if (!empty($OrderProductId)) {
            $this->load->model('order/order_product_model');
            if (!!($this->_OrderProduct = $this->order_product_model->is_exist('', $OrderProductId))) {
                $this->_Search['order_id'] = $this->_OrderProduct['order_id'];
            }
        }
        return true;
    }

    private function _get_order_product_num () {
	    $this->load->model('order/order_product_model');
	    $Data = array();
        if ($Query = $this->order_product_model->select_by_order_id($this->_Search['order_id'])) {
            foreach ($Query as $Key => $Value) {
                $Tmp = explode('-', $Value['num']);
                $Data[] = array_pop($Tmp);
            }
        }
        return implode(',', $Data);
    }
	
	/**
	 * 根据经销商信息获取订单编号(登帐时使用)
	 */
	public function read_order_num(){
	    $Did = $this->input->get('dealer_id', true);
	    $Days = $this->input->get('days', true);
	    $Did = intval(trim($Did));
	    $Days = intval(trim($Days));
	    $StartDatetime = $Days <= 0?date('Y-m-d H:i:s', strtotime('-30 days')):date('Y-m-d H:i:s', strtotime('-'.$Days.' days'));
	    $Data = array();
	    if(!($Data = $this->order_model->select_order_num($Did, $StartDatetime))){
	        $this->Code = EXIT_ERROR;
	        $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'获取订单编号失败!';
	    }
	    $this->_ajax_return($Data);
	}

	public function read_wait_position() {
		$this->_Item = $this->_Item.__FUNCTION__;

		$Data = array();
		if(!($Query = $this->position_model->select_wait_position())){
			$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有de';
		}else{
			$Data['content'] = $Query;
		}
		$this->_return($Data);
	}
	
	/**
	 * 新建订单
	 */
	public function add(){
	    $Product = $this->input->post('product', true);
	    $_POST['product'] = is_array($Product) ? $Product : explode(',', $Product);
		if ($this->_do_form_validation()) {
            $this->_Order = array(
                'order_type' => $this->input->post('order_type', true),
                'task_level' => $this->input->post('task_level', true),
                'dealer_id' => $this->input->post('dealer_id', true),
                'dealer' => $this->input->post('dealer', true),
                'shop_id' => $this->input->post('shop_id', true),
                'checker' => $this->input->post('checker', true),
                'checker_phone' => $this->input->post('checker_phone', true),
                'payer' => $this->input->post('payer', true),
                'payer_phone' => $this->input->post('payer_phone', true),
                'logistics' => $this->input->post('logistics', true),
                'out_method' => $this->input->post('out_method', true),
                'delivery_area' => $this->input->post('delivery_area', true),
                'delivery_address' => $this->input->post('delivery_address', true),
                'delivery_linker' => $this->input->post('delivery_linker', true),
                'delivery_phone' => $this->input->post('delivery_phone', true),
                'owner' => $this->input->post('owner', true),
                'request_outdate' => $this->input->post('request_outdate', true),
                'remark' => $this->input->post('remark', true),
                'dealer_remark' => $this->input->post('out_remark', true),
                'down_payment' => ONE
            );
            $this->load->model('dealer/shop_model');
            if ($this->_ShopPrimaryInfo = $this->shop_model->select_primary_info($this->_Order['shop_id'])) {
                $this->_Order['down_payment'] = $this->_ShopPrimaryInfo['down_payment']; // 可能首付会不同
                $this->_Order['payterms'] = $this->_ShopPrimaryInfo['payterms'];
                $this->_set_checker();
                $this->_set_payer();
                $this->_set_dealer_delivery();
            }
            $this->_Order = gh_escape($this->_Order);
            if(!!($this->Insert = $this->order_model->insert($this->_Order))){
                $this->load->library('workflow/workflow');
                $W = $this->workflow->initialize('order');

                if(!!($W->initialize($this->Insert['v']))){
                    $W->create();
                    $this->_add_order_product();
                    $this->Confirm = '新建订单' . $this->Insert['order_num'] . '是否拆单?';
                    $this->Location = '/order/dismantle?order_id=' . $this->Insert['v'];
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单新增失败!';
            }
        }
		$this->_return($this->Insert);
	}
	private function _set_checker() {
	    $this->load->model('dealer/dealer_linker_shop_model');
	    $Checker = $this->dealer_linker_shop_model->select_position($this->_Order['shop_id'], '设计师');
	    $this->_Order['checker'] = $this->_Order['checker'] == '' ? (isset($Checker['linker']) ? $Checker['linker'] : $this->_ShopPrimaryInfo['primary_linker']) : $this->_Order['checker'];
	    $this->_Order['checker_phone'] = $this->_Order['checker_phone'] == '' ? (isset($Checker['phone']) ? $Checker['phone'] : $this->_ShopPrimaryInfo['primary_phone']) : $this->_Order['checker_phone'];
    }
    private function _set_payer () {
        $this->load->model('dealer/dealer_linker_shop_model');
        $Payer = $this->dealer_linker_shop_model->select_position($this->_Order['shop_id'], '财务');
        $this->_Order['payer'] = $this->_Order['payer'] == '' ? (isset($Payer['linker']) ? $Payer['linker'] : $this->_ShopPrimaryInfo['primary_linker']) : $this->_Order['payer'];
        $this->_Order['payer_phone'] = $this->_Order['payer_phone'] == '' ? (isset($Payer['phone']) ? $Payer['phone'] : $this->_ShopPrimaryInfo['primary_phone']) : $this->_Order['payer_phone'];
    }
    private function _set_dealer_delivery () {
        $this->_Order['logistics'] = $this->_Order['logistics'] == '' ? $this->_ShopPrimaryInfo['logistics'] : $this->_Order['logistics'];
        $this->_Order['out_method'] = $this->_Order['out_method'] == '' ? $this->_ShopPrimaryInfo['out_method'] : $this->_Order['out_method'];
        $this->_Order['delivery_linker'] = $this->_Order['delivery_linker'] == '' ? $this->_ShopPrimaryInfo['delivery_linker'] : $this->_Order['delivery_linker'];
        $this->_Order['delivery_phone'] = $this->_Order['delivery_phone'] == '' ? $this->_ShopPrimaryInfo['delivery_phone'] : $this->_Order['delivery_phone'];
        $this->_Order['delivery_area'] = $this->_Order['delivery_area'] == '' ? $this->_ShopPrimaryInfo['delivery_area'] : $this->_Order['delivery_area'];
        $this->_Order['delivery_address'] = $this->_Order['delivery_address'] == '' ? $this->_ShopPrimaryInfo['delivery_address'] : $this->_Order['delivery_address'];
    }

    /**
     * 添加订单备注
     * @return bool
     */
    /*private function _add_order_remark () {
	    $Set = array(
	        'remark' => $this->input->post('remark', true)
        );
	    if ($Set['remark'] != '') {
            $this->load->model('order/order_remark_model');
            $Set['for'] = NO;
            $Set['status'] = O_MINUS;
            $Set['order_id'] = $this->Insert['v'];
            return $this->order_remark_model->insert($Set);
        }
	    return true;
    }*/

    /**
     * 添加客户备注
     * @return bool
     */
    /*private function _add_dealer_remark () {
        $Set = array(
            'remark' => $this->input->post('dealer_remark', true)
        );
        if ($Set['remark'] != '') {
            $this->load->model('order/order_remark_model');
            $Set['for'] = YES;
            $Set['status'] = O_MINUS;
            $Set['order_id'] = $this->Insert['v'];
            return $this->order_remark_model->insert($Set);
        }
        return true;
    }*/

    /**
     * 新建订单时新建订单产品
     */
	private function _add_order_product(){
	    $Product = $this->input->post('product', true);
	    $this->load->model('product/product_model');
	    if(!!($Product = $this->product_model->select_product_code_by_id($Product))){
	        $this->load->model('order/order_product_model');
            $this->load->library('workflow/workflow');
            $W = $this->workflow->initialize('order_product');
	        if(!!($Query = $this->order_product_model->insert($Product, $this->Insert))){
	            foreach ($Query as $key => $value){
	                if(!!($W->initialize($value['v']))){
                        $W->create();
	                }else{
	                    $this->Message = $W->get_failue();
	                    break;
	                }
	            }
	        }else{
	            $this->Code = EXIT_ERROR;
	            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单产品新增失败!';
	        }
	    }else{
            $this->Code = EXIT_ERROR;
	        $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'获取产品类型失败!';
	    }
	}
	
	public function edit(){
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $V = intval(trim($this->input->post('v', true)));
            if(!!($Return = $this->order_model->is_editable($V))){
                if(!!($this->order_model->update($Post, $V))){
                    $this->load->library('workflow/workflow');
                    $W = $this->workflow->initialize('order');
                    if($W->initialize($V)) {
                        $W->store_message('修改了订单基本信息', O_RECORD);
                    }
                    $this->Message .= '订单修改成功, 刷新后生效!';
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单修改失败';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message .= '订单已经作废或者发货, 不能修改基本信息';
            }
        }
		$this->_return();
	}

    /**
     * 代收金额
     */
	public function collection () {
        $V = $this->input->post('v', true);
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $V = $Post['v'];
            unset($Post['v']);
            if(!!($this->order_model->update($Post, $V))){
                $this->load->library('workflow/workflow');
                $W = $this->workflow->initialize('order');
                $W->initialize($V);
                if(!$W->store_message('订单添加了代收金额' . $Post['collection'], O_RECORD)) {
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                } else {
                    $this->Message .= '代收金额添加成功, 刷新后生效!';
                }
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'代收金额添加失败';
            }
        }
        $this->_ajax_return();
    }
	
	
    /**
     * 发货前的订单【1-16】，都可以作废
     */
	public function remove(){
	    $V = $this->input->post('v', true);
	    if (!is_array($V)) {
	        $_POST['v'] = explode(',', $V);
        }
	    if ($this->_do_form_validation()) {
            $V = $_POST['v'];
            $this->load->library('workflow/workflow');
            $W = $this->workflow->initialize('order');
            $W->initialize($V);
            if ($W->remove()) {
                $this->Message = '订单作废成功!';
            } else {
                $this->Message = $W->get_failue();
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
	}

    /**
     * 直接出厂
     */
    public function direct_out(){
        $V = $this->input->post('v', true);
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $V = $_POST['v'];
            $this->load->library('workflow/workflow');
            $W = $this->workflow->initialize('order');
            $W->initialize($V);
            if ($W->direct_out()) {
                $this->Message = '订单直接出厂成功!';
            } else {
                $this->Message = $W->get_failue();
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    /**
     * 从新拆单
     */
	public function re_dismantle () {
	    if (empty($_POST['v'])) {
	        $_POST['v'] = $_POST['order_id'];
        }
        if ($this->_do_form_validation()) {
            $V = $this->input->post('v', true);
            if (!!($this->order_model->is_re_dismantlable($V))) {
                $this->load->library('workflow/workflow');
                $W = $this->workflow->initialize('order');
                if ($W->initialize($V)) {
                    $W->re_dismantle();
                    $this->Message = '订单产品重新拆单成功!';
                    $this->Location = '/order/dismantle?order_id=' . $V;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '这个订单不能已经不能重新拆单!';
            }
        }
        $this->_ajax_return();
    }
}
