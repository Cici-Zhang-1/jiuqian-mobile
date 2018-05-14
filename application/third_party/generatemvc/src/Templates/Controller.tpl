<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * {{ title | title | replace({'_': ' '}) }} Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class {{ title | capitalize }} extends MY_Controller {

    /**
{% for model in models %}
     * @param {{ model | capitalize }}
{% endfor %}
     */
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller {{ tittle | capitalize }} __construct Start!');
{% for model in models %}
        $this->load->model('{{ model }}');
{% endfor %}
    }

    /**
    * Displays a listing of {{ plural | lower | replace({'_': ' '}) }}.
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

    /**
     * Shows the form for creating a new {{ singular | lower | replace({'_': ' '}) }}.
     *
     * @return void
     */
    public function add()
    {
        $data = array();
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if(!!($Cid = $this->{{ model }}->insert($Post))) {
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
            }
        }
        $this->_ajax_return();
    }

    /**
    * Shows the form for editing the specified {{ singular | lower | replace({'_': ' '}) }}.
    *
    * @param  int $id
    * @return void
    */
    public function edit($id) {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['selected'];
            unset($Post['selected']);
            if(!!($this->{{ model }}->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
    }

    /**
     * Deletes the specified {{ singular | lower | replace({'_': ' '}) }} from storage.
     * 
     * @param  int $id
     * @return void
     */
    public function remove($id) {
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('selected', true);
            if ($this->{{ model }}->delete($Where) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
