<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Warehouse shelve Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Warehouse_shelve extends MY_Controller {
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller warehouse/Warehouse_shelve __construct Start!');
        $this->load->model('warehouse/warehouse_shelve_model');
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
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->warehouse_shelve_model->select($this->_Search))){
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
            if(!!($NewId = $this->warehouse_shelve_model->insert($Post))) {
                $Width = $Post['width'];
                $Height = $Post['height'];
                $WarehouseAreaNum = $Post['warehouse_area_num'];
                $Remark = $Post['remark'];
                $Remark = nl2br($Remark);
                $Remark = explode("<br />", $Remark);
                for ($J = 0; $J < $Height; $J++) {
                    $Content = explode('-', $Remark[$J]);
                    if ($Content[0] > $Content[1]) {
                        $Content = array_reverse($Content);
                    }
                    $Remark[$J] = $Content;
                }
                $Num = $Post['num'];
                $Post = array();
                for ($I = 1; $I <= $Width; $I++) {
                    for ($J = 1; $J <= $Height; $J++) {
                        $Post[] = array(
                            'num' => $WarehouseAreaNum . '-' . $Num . '-' . $I . '-' . $J,
                            'warehouse_shelve_num' => $Num,
                            'width' => $I,
                            'height' => $J,
                            'min' => $Remark[$J - 1][0],
                            'max' => $Remark[$J - 1][1],
                            'status' => 1
                        );
                    }
                }
                $Post = gh_escape($Post);
                $this->load->model('warehouse/warehouse_model');
                $this->warehouse_model->insert_batch($Post);
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
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
            if ($this->warehouse_shelve_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }

    /**
     *
     * @param  int $id
     * @return void
     */
    public function enable() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->warehouse_shelve_model->update(array('status' => 1), $Where)) {
                $this->load->model('warehouse/warehouse_model');
                $this->warehouse_model->update_by_warehouse_shelve_num(array('status' => 1), $Where);
                $this->Message = '启用成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'启用失败!';
            }
        }
        $this->_ajax_return();
    }
    /**
     *
     * @param  int $id
     * @return void
     */
    public function unable() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->warehouse_shelve_model->update(array('status' => 0), $Where)) {
                $this->load->model('warehouse/warehouse_model');
                $this->warehouse_model->update_by_warehouse_shelve_num(array('status' => 0), $Where);
                $this->Message = '停用成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'停用失败!';
            }
        }
        $this->_ajax_return();
    }
}
