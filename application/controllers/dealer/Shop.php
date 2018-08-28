<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Shop Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Shop extends MY_Controller {
    private $__Search = array(
        'v' => 0
    );
    private $_DealerV;
    private $_ShopV;
    private $_DealerLinkerV = 0;
    private $_DealerDeliveryV = 0;
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller dealer/Shop __construct Start!');
        $this->load->model('dealer/dealer_model');
        $this->load->model('dealer/shop_model');
        $this->load->model('dealer/dealer_linker_model');
        $this->load->model('dealer/dealer_linker_shop_model');
        $this->load->model('dealer/dealer_delivery_model');
        $this->load->model('dealer/dealer_delivery_shop_model');
    }

    /**
    *
    * @return void
    */
    public function index() {
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $DealerId = $this->input->get('dealer_id');
        $DealerId = intval($DealerId);
        if (empty($this->_Search['v'])) {
            if (!empty($DealerId)) {
                $this->_Search['v'] = $DealerId;
            }
        }
        $Data = array();
        if(!($Data = $this->shop_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $Data['query']['dealer_id'] = $this->_Search['v'];
        $this->_ajax_return($Data);
    }

    /**
     * 我的店面
     */
    public function my_shop () {
        $this->_Search['paging'] = false;
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $DealerId = $this->input->get('dealer_id');
        $DealerId = intval($DealerId);
        if (empty($this->_Search['v'])) {
            if (!empty($DealerId)) {
                $this->_Search['v'] = $DealerId;
            }
        }
        $this->_Search['owner'] = $this->session->userdata('uid');
        $Data = array();
        if(!($Data = $this->shop_model->select_my_shop($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {

        }
        $Data['query']['dealer_id'] = $this->_Search['v'];
        $this->_ajax_return($Data);
    }

    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $this->load->model('dealer/dealer_model');
            if (!!($this->dealer_model->is_exist($Post['dealer_id']))) {
                $this->_DealerV = $Post['dealer_id'];
                if ($this->_check_dealer_linker() && $this->_check_dealer_delivery()) {
                    if(!!($this->_ShopV = $this->shop_model->insert($Post))) {
                        $this->_add_dealer_linker();
                        $this->_add_dealer_delivery();
                        $this->Message = '新建成功, 刷新后生效!';
                    }else{
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                        $this->Code = EXIT_ERROR;
                    }
                }
            } else {
                $this->Message = '客户不存在, 请先新建客户再建店面!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    private function _check_dealer_delivery() {
        $OutMethod = $this->input->post('dealer_delivery_out_method');
        $DealerDeliveryLinker = $this->input->post('dealer_delivery_linker');
        $DealerDeliveryPhone = $this->input->post('dealer_delivery_phone');
        if ($OutMethod == '' && $DealerDeliveryPhone == '' && '' == $DealerDeliveryLinker) {
            $this->_DealerDeliveryV = $this->dealer_delivery_model->select_primary($this->_DealerV);
        } elseif ($OutMethod == '') {
            $this->Message .= '请填写发货出厂方式';
            return false;
        } elseif ($DealerDeliveryLinker == '') {
            $this->Message .= '请填写发货联系人';
            return false;
        } elseif ($DealerDeliveryPhone == '') {
            $this->Message .= '请填写发货联系方式';
            return false;
        }
        return true;
    }
    private function _add_dealer_delivery(){
        if ($this->_DealerDeliveryV) {
            $this->_add_dealer_delivery_shop();
        } else {
            $DealerDeliveryArea = $this->input->post('dealer_delivery_area_id');
            $DealerDeliveryArea = intval($DealerDeliveryArea);
            $DealerDeliveryAddress = $this->input->post('dealer_delivery_address');
            $Set = array(
                'dealer_id' => $this->_NewDealerV,
                'shop_id' => $this->_NewShopV,
                'linker' => $this->input->post('dealer_delivery_truename'),
                'phone' => $this->input->post('dealer_delivery_phone'),
                'area_id' => ($DealerDeliveryArea != 0 || $DealerDeliveryAddress != '') ? $DealerDeliveryArea : $this->input->post('area_id'),
                'address' => ($DealerDeliveryArea != 0 || $DealerDeliveryAddress != '') ? $DealerDeliveryAddress : $this->input->post('address'),
                'out_method' => $this->input->post('dealer_delivery_out_method'),
                'primary' => NO
            );
            $Set = gh_escape($Set);
            $this->load->model('dealer/dealer_delivery_model');
            if(!!($this->_DealerDeliveryV = $this->dealer_delivery_model->insert($Set))){
                $this->_add_dealer_delivery_shop();
                $this->Message .= '经销商发货信息新增成功, 刷新后生效!';
            }else{
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商发货信息新增失败';
            }
        }
        return true;
    }
    private function _add_dealer_delivery_shop () {
        $Set = array(
            'dealer_delivery_id' => $this->_DealerDeliveryV,
            'shop_id' => $this->_ShopV,
            'primary' => YES
        );
        $this->load->model('dealer/dealer_delivery_shop_model');
        return $this->dealer_delivery_shop_model->insert($Set);
    }

    private function _check_dealer_linker () {
        $DealerLinkerName = $this->input->post('dealer_linker_name');
        $DealerLinkerTruename = $this->input->post('dealer_linker_truename');
        $DealerLinkerMobilephone = $this->input->post('dealer_linker_mobilephone');
        if ($DealerLinkerName == '' && $DealerLinkerTruename == '' && $DealerLinkerMobilephone == '') {
            $this->_DealerLinkerV = $this->dealer_linker_model->select_primary($this->_DealerV);
        } elseif ($DealerLinkerName == '') {
            $this->Message .= '请填写联系人用户名';
            return false;
        } elseif ($DealerLinkerTruename == '') {
            $this->Message .= '请填写联系人真实姓名';
            return false;
        } elseif ($DealerLinkerMobilephone == '') {
            $this->Message .= '请填写联系人移动电话';
            return false;
        }
        return true;
    }
    private function _add_dealer_linker(){
        if ($this->_DealerLinkerV) {
            return $this->_add_dealer_linker_shop();
        } else {
            $Set = array(
                'dealer_id' => $this->_NewDealerV,
                'name' => $this->input->post('dealer_linker_name'),
                'truename' => $this->input->post('dealer_linker_truename'),
                'password' => '90009000',
                'mobilephone' => $this->input->post('dealer_linker_mobilephone'),
                'telephone' => $this->input->post('dealer_linker_telephone'),
                'email' => $this->input->post('dealer_linker_email'),
                'qq' => $this->input->post('dealer_linker_qq'),
                'fax' => $this->input->post('dealer_linker_fax'),
                'position' => $this->input->post('dealer_linker_position'),
                'primary' => NO
            );
            $Set = gh_escape($Set);

            if (!!($this->_DealerLinkerV = $this->dealer_linker_model->insert($Set))) {
                $this->_add_dealer_linker_shop();
                $this->Message .= '经销商联系人新增成功, 刷新后生效!';
            } else {
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商联系人新增失败';
            }
        }
        return true;
    }
    private function _add_dealer_linker_shop () {
        $Set = array(
            'dealer_linker_id' => $this->_DealerLinkerV,
            'shop_id' => $this->_ShopV,
            'primary' => YES
        );
        $this->load->model('dealer/dealer_linker_shop_model');
        return $this->dealer_linker_shop_model->insert($Set);
    }

    /**
    *
    * @return void
    */
    public function edit() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->shop_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
    }

    /**
     *
     * @param  int $id
     * @return void
     */
    public function remove() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->shop_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
