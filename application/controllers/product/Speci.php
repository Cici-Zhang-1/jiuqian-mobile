<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Speci Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Speci extends MY_Controller {
    private $__Search = array(
        'product_id' => 0
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller product/Speci __construct Start!');
        $this->load->model('product/speci_model');
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
        if (empty($this->_Search['product_id'])) {
            $ProductId = $this->input->get('v', true);
            $ProductId  = intval($ProductId);
            if (!empty($ProductId)) {
                $this->_Search['product_id'] = $ProductId;
            }
        }
        $Data = array();
        if(!($Data = $this->speci_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $TmpSource = array();
            $TmpDes = array();
            foreach ($Data['content'] as $key => $value){
                $value = $this->_speci_format($value);
                $TmpSource[$value['v']] = $value;
                $Child[$value['parent']][] = $value['v'];
            }
            ksort($Child);
            $Child = gh_infinity_category($Child);
            while(list($key, $value) = each($Child)){
                $TmpDes[] = $TmpSource[$value];
            }
            $Data['content'] = $TmpDes;
        }
        $Data['query']['product_id'] = $this->_Search['product_id'];
        $this->_ajax_return($Data);
    }

    private function _speci_format($Product) {
        $Product['class_alien'] = '|';
        for($I = 0; $I < $Product['class']; $I++) {
            $Product['class_alien'] .=  '---';
        }
        return $Product;
    }
    /**
     *
     * @return void
     */
    public function add() {
        $Parent = $this->input->post('parent');
        $_POST['parent'] = $Parent ? $Parent : 0;
        if ($this->_do_form_validation()) {
            $this->_set_class();
            $Post = gh_escape($_POST);
            if(!!($NewId = $this->speci_model->insert($Post))) {
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
        $Parent = $this->input->post('parent');
        $_POST['parent'] = $Parent ? $Parent : 0;
        if ($this->_do_form_validation()) {
            $this->_set_class();
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->speci_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
    }

    private function _set_class () {
        if ($_POST['parent'] == ZERO) {
            $_POST['class'] = ZERO;
        } elseif ($Parent = $this->speci_model->is_exist($_POST['parent'])) {
            if ($Parent['class'] == ONE) {
              $_POST['parent'] = $Parent['parent'];
            }
            $_POST['class'] = ONE;
        } else {
            $_POST['class'] = ZERO;
        }
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
            if ($this->speci_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
