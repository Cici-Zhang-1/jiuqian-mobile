<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/18
 * Time: 15:57
 *
 * Desc: 权限管理
 */
class Permission {
    private $_CI;
    private $_Ugid;
    private $_Mid;
    private $_Func;
    private $_Form;
    private $_PageSearch;
    private $_Card;
    private $_Element;

    public function __construct() {
        $this->_CI = &get_instance();
        $this->_Ugid = $this->_CI->session->userdata('ugid');
        $this->_init();
    }

    private function _init() {
        if (isset($GLOBALS['Permission']['Mid'])) {
            $this->_Mid = $GLOBALS['Permission']['Mid'];
        }
    }

    private function _allowed($P) {
        $Model = $P . '_model';
        $Param = '_' . name_to_id($P, true);
        $this->_CI->load->model('permission/' . $Model);
        echo $this->_Ugid;
        echo $this->_Mid;
        return $this->_Mid && $this->$Param = $this->_CI->$Model->select_allowed($this->_Ugid, $this->_Mid);
    }

    /**
     * 获取准许的func
     * @param $Attr // Field_data
     * @return array
     */
    public function get_allowed_func($Attr = false) {
        $Return = array();
        if ($this->_allowed('func')) {
            if (is_array($Attr)) {
                foreach ($this->_Func as $key => $Value) {
                    if (preg_match('/^<i\s+class=\"(.*)\"><\/i>$/', $Value['img'], $Matched)) {
                        $Value['img'] = $Matched[1];
                    }
                    $Value['forms'] = $this->get_allowed_form();
                    foreach ($Attr as $ivalue) {
                        $Return[$Value['fid']][$ivalue] = $Value[$ivalue];
                    }
                }
            }elseif (is_string($Attr)) {
                foreach ($this->_Func as $key => $Value) {
                    if (preg_match('/^<i\s+class=\"(.*)\"><\/i>$/', $Value['img'], $Matched)) {
                        $Value['img'] = $Matched[1];
                    }
                    $Value['forms'] = $this->get_allowed_form();
                    $Return[$Value['fid']] = $Value[$Attr];
                }
            }else {
                foreach ($this->_Func as $Key => $Value) {
                    if (preg_match('/^<i\s+class=\"(.*)\"><\/i>$/', $Value['img'], $Matched)) {
                        $Value['img'] = $Matched[1];
                    }
                    $Value['forms'] = $this->get_allowed_form();
                    $Return[$Key] = $Value;
                }
            }
        }
        return $Return;
    }

    public function get_allowed_form($Attr = false) {
        $Return = array();
        if ($this->_allowed('form')) {
            if (is_array($Attr)) {
                foreach ($this->_Form as $key => $value) {
                    foreach ($Attr as $ivalue) {
                        $Return[$value['fid']][$value['fid']][$ivalue] = $value[$ivalue];
                    }
                }
            }elseif (is_string($Attr)) {
                foreach ($this->_Form as $key => $value) {
                    if (!isset($Return[$value['func']])) {
                        $Return[$value['func']] = array();
                    }
                    $Return[$value['func']][$value['fid']] = $value[$Attr];
                }
            }else {
                $Return = $this->_Form;
            }
        }
        return $Return;
    }

    public function get_allowed_page_search($Attr = false) {
        $Return = array();
        if ($this->_allowed('page_search')) {
            if (is_array($Attr)) {
                foreach ($this->_PageSearch as $key => $value) {
                    foreach ($Attr as $ivalue) {
                        $Return[$value['psid']][$ivalue] = $value[$ivalue];
                    }
                }
            }elseif (is_string($Attr)) {
                foreach ($this->_PageSearch as $key => $value) {
                    $Return[$value['psid']] = $value[$Attr];
                }
            }else {
                $Return = $this->_PageSearch;
            }
        }
        return $Return;
    }

    public function get_allowed_card($Attr = false) {
        $Return = array();
        if ($this->_allowed('card')) {
            if (is_array($Attr)) {
                foreach ($this->_Card as $key => $value) {
                    $value['elements'] = $this->get_allowed_element();
                    foreach ($Attr as $ivalue) {
                        $Return[$value['cid']][$ivalue] = $value[$ivalue];
                    }
                }
            }elseif (is_string($Attr)) {
                foreach ($this->_Card as $key => $value) {
                    $value['elements'] = $this->get_allowed_element();
                    $Return[$value['cid']] = $value[$Attr];
                }
            }else {
                foreach ($this->_Card as $key => $value) {
                    $value['elements'] = $this->get_allowed_element();
                    $Return = $this->_Card;
                }
            }
        }
        return $Return;
    }

    public function get_allowed_element($Attr = false) {
        $Return = array();
        if ($this->_allowed('element')) {
            if (is_array($Attr)) {
                foreach ($this->_Element as $key => $value) {
                    foreach ($Attr as $ivalue) {
                        $Return[$value['card']][$value['eid']][$ivalue] = $value[$ivalue];
                    }
                }
            }elseif (is_string($Attr)) {
                foreach ($this->_Element as $key => $value) {
                    $Return[$value['card']][$value['eid']] = $value[$Attr];
                }
            }else {
                $Return = $this->_Element;
            }
        }
        return $Return;
    }

    public function get_element_by_operation() {
        $this->_CI->load->model('permission/element_model');
        if (isset($GLOBALS['Permission']['Operation']) && !!($Query = $this->_CI->element_model->select_by_card_url($this->_Ugid, $GLOBALS['Permission']['Operation']))) {
            $E = array();
            foreach ($Query as $Key => $Value) {
                $E[] = $Value['name'];
            }
            log_message('debug', 'Permission get_element_by_operation ' . implode(',', $E));
            return $E;
        }
        log_message('debug', 'Permission get_element_by_operation error on ' . $GLOBALS['Permission']['Operation']);
        return false;
    }
}
