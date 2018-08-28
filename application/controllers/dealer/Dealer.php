<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dealer Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Dealer extends MY_Controller {
    private $_NewDealerV;
    private $_NewShopV;
    private $__Search = array(
        'owner' => ZERO,
        'status' => ONE,
        'public' => YES
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller dealer/Dealer __construct Start!');
        $this->load->model('dealer/dealer_model');
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
        if ($this->_Search['public'] == NO) {
            $this->_Search['owner'] = $this->session->userdata('uid');
        }
        $Data = array();
        if(!($Data = $this->dealer_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if(!!($this->_NewDealerV = $this->dealer_model->insert($Post))) {
                $this->_add_shop();
                $this->_add_dealer_delivery();
                $this->_add_dealer_linker();
                if (empty($Post['public'])) {
                    $this->_add_owner();
                }
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    private function _add_shop () {
        $Shop = $this->input->post('shop');
        $Set = array(
            'num' => ONE,
            'dealer_id' => $this->_NewDealerV,
            'name' => $Shop != '' ? $Shop : $this->input->post('name'),
            'area_id' => $this->input->post('area_id'),
            'address' => $this->input->post('address')
        );
        $Set = gh_escape($Set);
        $this->load->model('dealer/shop_model');

        if(!!($this->_NewShopV = $this->shop_model->insert($Set))){
            $this->Message .= '经销商店面信息新增成功, 刷新后生效!';
        }else{
            $this->Code = EXIT_ERROR;
            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商信息新增失败';
        }
        return $this->_NewShopV;
    }
    private function _add_dealer_delivery(){
        $DealerDeliveryLinker = $this->input->post('dealer_delivery_linker');
        $DealerDeliveryPhone = $this->input->post('dealer_delivery_phone');
        $DealerDeliveryArea = $this->input->post('dealer_delivery_area_id');
        $DealerDeliveryArea = intval($DealerDeliveryArea);
        $DealerDeliveryAddress = $this->input->post('dealer_delivery_address');
        $Set = array(
            'dealer_id' => $this->_NewDealerV,
            'shop_id' => $this->_NewShopV,
            'linker' => $DealerDeliveryLinker != '' ? $DealerDeliveryLinker : $this->input->post('dealer_linker_truename'),
            'phone' => $DealerDeliveryPhone != '' ? $DealerDeliveryPhone : $this->input->post('dealer_linker_mobilephone'),
            'area_id' => ($DealerDeliveryArea != 0 || $DealerDeliveryAddress != '') ? $DealerDeliveryArea : $this->input->post('area_id'),
            'address' => ($DealerDeliveryArea != 0 || $DealerDeliveryAddress != '') ? $DealerDeliveryAddress : $this->input->post('address'),
            'out_method' => $this->input->post('dealer_delivery_out_method')
        );
        $Set = gh_escape($Set);
        $this->load->model('dealer/dealer_delivery_model');
        if(!!($NewId = $this->dealer_delivery_model->insert($Set))){
            $Set = array(
                'dealer_delivery_id' => $NewId,
                'shop_id' => $this->_NewShopV
            );
            $this->load->model('dealer/dealer_delivery_shop_model');
            $this->dealer_delivery_shop_model->insert($Set);
            $this->Message .= '经销商发货信息新增成功, 刷新后生效!';
        }else{
            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商发货信息新增失败';
        }
        return true;
    }

    private function _add_dealer_linker(){
        $Set = array(
            'dealer_id' => $this->_NewDealerV,
            'shop_id' => $this->_NewShopV,
            'name' => $this->input->post('dealer_linker_name'),
            'truename' => $this->input->post('dealer_linker_truename'),
            'password' => '90009000',
            'mobilephone' => $this->input->post('dealer_linker_mobilephone'),
            'telephone' => $this->input->post('dealer_linker_telephone'),
            'email' => $this->input->post('dealer_linker_email'),
            'qq' => $this->input->post('dealer_linker_qq'),
            'fax' => $this->input->post('dealer_linker_fax'),
            'position' => $this->input->post('dealer_linker_position')
        );
        $Set = gh_escape($Set);
        $this->load->model('dealer/dealer_linker_model');
        if (!!($NewId = $this->dealer_linker_model->insert($Set))) {
            $Set = array(
                'dealer_linker_id' => $NewId,
                'shop_id' => $this->_NewShopV
            );
            $this->load->model('dealer/dealer_linker_shop_model');
            $this->dealer_linker_shop_model->insert($Set);
            $this->Message .= '经销商联系人新增成功, 刷新后生效!';
        } else {
            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商联系人新增失败';
        }
        return true;
    }

    private function _add_owner () {
        $Set = array(
            'dealer_id' => $this->_NewDealerV,
            'owner_id' => $this->session->userdata('uid'),
            'primary' => YES
        );
        $Set = gh_escape($Set);
        $this->load->model('dealer/dealer_owner_model');
        if (!!($NewId = $this->dealer_owner_model->insert($Set))) {
            $this->Message .= '经销商属主新增成功, 刷新后生效!';
        } else {
            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商属主新增失败';
        }
        return true;
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
            if(!!($this->dealer_model->update($Post, $Where))){
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
            if ($this->dealer_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 开始正常使用
     */
    public function start () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->dealer_model->update(array('status' => YES), $Where)) {
                $this->Message = '启用成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'启用失败!';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 停止正常使用
     */
    public function stop () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->dealer_model->update(array('status' => NO), $Where)) {
                $this->Message = '停用成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'停用失败!';
            }
        }
        $this->_ajax_return();
    }
}
