<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dealer delivery Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Dealer_delivery extends MY_Controller {
    private $__Search = array(
        'v' => 0
    );
    private $_DealerDeliveryId;
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller dealer/Dealer_delivery __construct Start!');
        $this->load->model('dealer/dealer_delivery_model');
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
        if(!($Data = $this->dealer_delivery_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
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
            if(!!($this->_DealerDeliveryId = $this->dealer_delivery_model->insert($Post))) {
                $this->_add_dealer_delivery_shop();
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    /**
     * 新建联系人同时选择了店面
     * @return bool
     */
    private function _add_dealer_delivery_shop () {
        $Post = array(
            'shop_id' => $this->input->post('shop_id'),
            'dealer_delivery_id' => $this->_DealerDeliveryId,
            'primary' => $this->input->post('primary')
        );
        if (!empty($Post['shop_id'])) {
            $Post = gh_escape($Post);
            $this->load->model('dealer/dealer_delivery_shop_model');
            if(!!($NewId = $this->dealer_delivery_shop_model->insert($Post))) {
                $this->Message .= '客户收货店面新建成功, 刷新后生效!';
            }
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
            if(!!($this->dealer_delivery_model->update($Post, $Where))){
                $this->_set_primary($Post['dealer_id']);
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
            if (!!($DeliveryNums = $this->dealer_delivery_model->select_dealer_delivery_nums($Where))) {
                if ($DeliveryNums['nums'] > count($Where)) {
                    if ($this->dealer_delivery_model->delete($Where)) {
                        $this->_set_primary($DeliveryNums['dealer_id']);
                        $this->Message = '删除成功，刷新后生效!';
                    } else {
                        $this->Code = EXIT_ERROR;
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
                    }
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '请保留一位客户收货方式!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 修改或者删除后可能存在没有首要发货信息，随机选择一个
     * @param $DealerV
     * @return bool
     */
    private function _set_primary ($DealerV) {
        if (!($this->dealer_delivery_model->select_primary($DealerV))) { // 如果修改后没有primary,随机选择一个
            $this->dealer_delivery_model->update_primary($DealerV);
        }
        return true;
    }
}
