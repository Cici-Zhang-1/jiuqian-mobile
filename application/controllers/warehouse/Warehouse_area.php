<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * { title | title | replace({'_': ' '}) } Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Warehouse_area extends MY_Controller {
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller Warehouse_area __construct Start!');
        $this->load->model('warehouse/warehouse_area_model');
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
        if(!($Data = $this->warehouse_area_model->select($this->_Search))){
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
            if(!!($Cid = $this->warehouse_area_model->insert($Post))) {
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
            if ($this->warehouse_area_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }

    public function enable() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->warehouse_area_model->update(array('status' => 1), $Where)) {
                $this->load->model('warehouse/warehouse_shelve_model');
                $this->warehouse_shelve_model->update_by_warehouse_area_num(array('status' => 1), $Where);
                $this->load->model('warehouse/warehouse_model');
                $this->warehouse_model->update_by_warehouse_area_num(array('status' => 1), $Where);
                $this->Message = '启用成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'启用失败!';
            }
        }
        $this->_ajax_return();
    }

    public function unable() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->warehouse_area_model->update(array('status' => 0), $Where)) {
                $this->load->model('warehouse/warehouse_shelve_model');
                $this->warehouse_shelve_model->update_by_warehouse_area_num(array('status' => 0), $Where);
                $this->load->model('warehouse/warehouse_model');
                $this->warehouse_model->update_by_warehouse_area_num(array('status' => 0), $Where);
                $this->Message = '停用成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'停用失败!';
            }
        }
        $this->_ajax_return();
    }
}
