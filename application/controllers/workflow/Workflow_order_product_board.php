<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Workflow order product board Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Workflow_order_product_board extends MY_Controller {
    private $__Search = array(
        'paging' => 0
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller workflow/Workflow_order_product_board __construct Start!');
        $this->load->model('workflow/workflow_order_product_board_model');
    }

    public function read () {
        $this->_ajax_return($this->_read());
    }

    private function _read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->workflow_order_product_board_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        return $Data;
    }

    public function edge () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if ($Value['v'] == OPB_EDGE || $Value['v'] == OPB_EDGING || $Value['v'] == OPB_EDGED) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
        }
        $this->_ajax_return($Data);
    }
    public function punch () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if ($Value['v'] == OPB_PUNCH || $Value['v'] == OPB_PUNCHED) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
        }
        $this->_ajax_return($Data);
    }
    public function sscan () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if ($Value['v'] == OPB_SCAN || $Value['v'] == OPB_SCANNED || $Value['v'] == OPB_SCANNING) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
        }
        $this->_ajax_return($Data);
    }
    public function ppack () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if ($Value['v'] == OPB_PACK || $Value['v'] == OPB_PACKED || $Value['v'] == OPB_PACKING) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
        }
        $this->_ajax_return($Data);
    }
    public function produce_door () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if (in_array($Value['v'], array(OPB_PRODUCE, OPB_EDGE, OPB_EDGING, OPB_EDGED, OPB_SCAN, OPB_SCANNED, OPB_PACK, OPB_PACKED))) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
        }
        $this->_ajax_return($Data);
    }
    public function produce_door_distribution () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if (in_array($Value['v'], array(OPB_EDGE, OPB_SCAN, OPB_PACK))) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
        }
        $this->_ajax_return($Data);
    }
    public function produce_wood () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if (in_array($Value['v'], array(OPB_PRODUCE, OPB_EDGE, OPB_EDGING, OPB_EDGED, OPB_SCAN, OPB_SCANNED, OPB_PACK, OPB_PACKED))) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
        }
        $this->_ajax_return($Data);
    }
    public function produce_wood_distribution () {
        if (!!($Data = $this->_read())) {
            foreach ($Data['content'] as $Key => $Value) {
                if (in_array($Value['v'], array(OPB_EDGE, OPB_SCAN, OPB_PACK))) {
                    continue;
                } else {
                    unset($Data['content'][$Key]);
                }
            }
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
            if(!!($NewId = $this->workflow_order_product_board_model->insert($Post))) {
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
    * @return void
    */
    public function edit() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->workflow_order_product_board_model->update($Post, $Where))){
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
            if ($this->workflow_order_product_board_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
