<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Product Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Product extends MY_Controller {
    private $__Search = array(
        'undelete' => NO,
        'goods' => NO,
        'paging' => NO,
        'optimize' => NO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller product/Product __construct Start!');
        $this->load->model('product/product_model');
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
        $Data = array();
        if(!($Data = $this->product_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            if (!empty($this->_Search['undelete'])) {
                $Optimize = array(
                    CABINET,
                    WARDROBE
                );
                foreach ($Data['content'] as $Key => $Value) {
                    $Data['content'][$Key]['checked'] = false;
                    if ($this->_Search['optimize']) {
                        if (!in_array($Value['v'], $Optimize)) {
                            unset($Data['content'][$Key]);
                        }
                    }
                }
                $Data['content'] = array_values($Data['content']);
            } elseif (!empty($this->_Search['goods'])) {
                $UnGoods = array(
                    CABINET,
                    WARDROBE,
                    DOOR,
                    WOOD
                );
                $TmpSource = array();
                $TmpDes = array();
                foreach ($Data['content'] as $key => $value){
                    if (!in_array($value['v'], $UnGoods)) {
                        $value = $this->_product_format($value);
                        $TmpSource[$value['v']] = $value;
                        $Child[$value['parent']][] = $value['v'];
                    }
                }
                ksort($Child);
                $Child = gh_infinity_category($Child);
                while(list($key, $value) = each($Child)){
                    $TmpDes[] = $TmpSource[$value];
                }
                $Data['content'] = $TmpDes;
            } else {
                $TmpSource = array();
                $TmpDes = array();
                foreach ($Data['content'] as $key => $value){
                    $value = $this->_product_format($value);
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
        }
        $this->_ajax_return($Data);
    }

    private function _product_format($Product) {
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
            $Post = gh_escape($_POST);
            if ($Post['parent'] == 0) {
                $Post['class'] = 0;
            } elseif ($Parent = $this->product_model->is_exist($Post['parent'])) {
                $Post['class'] = $Parent['class'] + 1;
                if ($Post['code'] == '') {
                    $Post['code'] = $Parent['code'];
                }
                $Post['parents'] = $Parent['parents'] ? $Parent['parents'] . ',' . $Post['parent'] : $Post['parent'];
            } else {
                $Post['class'] = 0;
            }
            $Post['delete'] = 1;
            if(!!($NewId = $this->product_model->insert($Post))) {
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
            $Post = gh_escape($_POST);
            if ($Post['parent'] == 0) {
                $Post['class'] = 0;
            } elseif ($Parent = $this->product_model->is_exist($Post['parent'])) {
                $Post['class'] = $Parent['class'] + 1;
                if ($Post['code'] == '') {
                    $Post['code'] = $Parent['code'];
                }
                $Post['parents'] = $Parent['parents'] ? $Parent['parents'] . ',' . $Post['parent'] : $Post['parent'];
            } else {
                $Post['class'] = 0;
            }
            $Where = $Post['v'];
            unset($Post['v']);
            if(!!($this->product_model->update($Post, $Where))){
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
            if ($this->product_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
