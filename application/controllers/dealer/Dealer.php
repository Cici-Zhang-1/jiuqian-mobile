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
        'public' => YES,
        'all' => NO
    );
    private $_CurrentSheet;
    private $_Import = false;
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
     * 远程调用数据
     */
    public function remote () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if (!empty($this->_Search['keyword'])) {
            $Data = $this->dealer_model->select_remote($this->_Search);
        }
        array_unshift($Data, array('v' => 0, 'name' => '---无---'));

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
        if ($this->_Import) {
            $Set['creator'] = $_POST['creator'];
            $Set['create_datetime'] = $_POST['create_datetime'];
        }
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
            'out_method' => $this->input->post('dealer_delivery_out_method'),
            'primary' => YES
        );
        if ($this->_Import) {
            $Set['creator'] = $_POST['dealer_delivery_creator'];
            $Set['create_datetime'] = $_POST['dealer_delivery_create_datetime'];
        }
        $Set = gh_escape($Set);
        $this->load->model('dealer/dealer_delivery_model');
        if(!!($NewId = $this->dealer_delivery_model->insert($Set))){
            $Set = array(
                'dealer_delivery_id' => $NewId,
                'shop_id' => $this->_NewShopV,
                'primary' => YES
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
            'position' => $this->input->post('dealer_linker_position'),
            'primary' => YES
        );
        if ($this->_Import) {
            $Set['creator'] = $_POST['dealer_linker_creator'];
            $Set['create_datetime'] = $_POST['dealer_linker_create_datetime'];
        }
        if (empty($Set['name'])) {
            $Set['name'] = $Set['mobilephone'];
        }
        $Set = gh_escape($Set);
        $this->load->model('dealer/dealer_linker_model');
        if (!!($NewId = $this->dealer_linker_model->insert($Set))) {
            $Set = array(
                'dealer_linker_id' => $NewId,
                'shop_id' => $this->_NewShopV,
                'primary' => YES
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
            'primary' => YES,
            'primary_datetime' => date('Y-m-d H:i:s')
        );
        if ($this->_Import) {
            if (empty($_POST['owner_id'])) {
                return true;
            }
            $Set['owner_id'] = $_POST['owner_id'];
        }
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

    /**
     * 导入
     */
    public function import () {
        require_once APPPATH.'third_party/PHPExcels/PHPExcel.php';
        $PHPExcel = PHPExcel_IOFactory::load('dealer.xls');
        $this->_CurrentSheet = $PHPExcel->getSheet(0);
        $AllRow = $this->_CurrentSheet->getHighestRow();
        if ($AllRow >= 1) {
            $this->_Import = true;
            for ($index = 1; $index <= $AllRow; $index++) {
                $Tmp = array();
                $Tmp['d_id'] = (int)$this->_CurrentSheet->getCell('A' . $index)->getValue();
                if ($Tmp['d_id'] > 0) {
                    $Tmp = array(
                        'num' => $index,
                        'company_type' => (string)$this->_CurrentSheet->getCell('B' . $index)->getValue(),
                        'name' => (string)$this->_CurrentSheet->getCell('C' . $index)->getValue(),
                        'shop' => (string)$this->_CurrentSheet->getCell('D' . $index)->getValue(),
                        'area_id' => $this->_CurrentSheet->getCell('E' . $index)->getValue(),
                        'address' => (string)$this->_CurrentSheet->getCell('F' . $index)->getValue(),
                        'creator' => $this->_CurrentSheet->getCell('G' . $index)->getValue(),
                        'create_datetime' => (string)$this->_CurrentSheet->getCell('H' . $index)->getValue(),

                        'owner_id' => $this->_CurrentSheet->getCell('J' . $index)->getValue(),

                        'dealer_linker_truename' => (string)$this->_CurrentSheet->getCell('N' . $index)->getValue(),
                        'dealer_linker_mobilephone' => (string)$this->_CurrentSheet->getCell('O' . $index)->getValue(),
                        'dealer_linker_position' => (string)$this->_CurrentSheet->getCell('P' . $index)->getValue(),
                        'dealer_linker_telephone' => (string)$this->_CurrentSheet->getCell('Q' . $index)->getValue(),
                        'dealer_linker_email' => (string)$this->_CurrentSheet->getCell('R' . $index)->getValue(),
                        'dealer_linker_qq' => (string)$this->_CurrentSheet->getCell('S' . $index)->getValue(),
                        'dealer_linker_fax' => (string)$this->_CurrentSheet->getCell('T' . $index)->getValue(),
                        'dealer_linker_creator' => $this->_CurrentSheet->getCell('U' . $index)->getValue(),
                        'dealer_linker_create_datetime' => (string)$this->_CurrentSheet->getCell('V' . $index)->getValue(),

                        'dealer_delivery_area_id' => $this->_CurrentSheet->getCell('Y' . $index)->getValue(),
                        'dealer_delivery_address' => (string)$this->_CurrentSheet->getCell('Z' . $index)->getValue(),
                        'dealer_delivery_logistics' => (string)$this->_CurrentSheet->getCell('AA' . $index)->getValue(),
                        'dealer_delivery_out_method' => (string)$this->_CurrentSheet->getCell('AB' . $index)->getValue(),
                        'dealer_delivery_linker' => (string)$this->_CurrentSheet->getCell('AC' . $index)->getValue(),
                        'dealer_delivery_phone' => (string)$this->_CurrentSheet->getCell('AD' . $index)->getValue(),
                        'dealer_delivery_creator' => $this->_CurrentSheet->getCell('AE' . $index)->getValue(),
                        'dealer_delivery_create_datetime' => (string)$this->_CurrentSheet->getCell('AF' . $index)->getValue()
                    );
                    if (empty($Tmp['shop'])) {
                        $Tmp['shop'] = $Tmp['name'];
                    }
                    $_POST = array_merge($_POST, $Tmp);
                    if (empty($Tmp['dealer_linker_mobilephone'])) {
                        continue;
                    }
                    if(!!($this->_NewDealerV = $this->dealer_model->insert($Tmp))) {
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
                }else {
                    $this->Message = 'Excel清单文件上传成功';
                }
            }
        }else {
            $this->Message = '文件中没有有效的数据!';
        }
    }
}
