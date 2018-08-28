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
        if (isset($GLOBALS['Permission']['MenuV'])) {
            $this->_Mid = $GLOBALS['Permission']['MenuV'];
        }
    }

    /**
     * Allowed
     * @param $P
     * @param $Pid Parent Id
     * @return bool
     */
    private function _allowed($P, $Pid = 0) {
        $Model = $P . '_model';
        $Param = '_' . name_to_id($P, true);
        $this->_CI->load->model('permission/' . $Model);
        if ($Pid) {
            return $this->_Mid && $this->$Param = $this->_CI->$Model->select_allowed($this->_Ugid, $this->_Mid, $Pid);
        } else {
            return $this->_Mid && $this->$Param = $this->_CI->$Model->select_allowed($this->_Ugid, $this->_Mid);
        }
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
                    $Value['forms'] = $this->get_allowed_form(false, $Value['v']);
                    foreach ($Attr as $ivalue) {
                        $Return[$Value['v']][$ivalue] = $Value[$ivalue];
                    }
                }
            }elseif (is_string($Attr)) {
                foreach ($this->_Func as $key => $Value) {
                    if (preg_match('/^<i\s+class=\"(.*)\"><\/i>$/', $Value['img'], $Matched)) {
                        $Value['img'] = $Matched[1];
                    }
                    $Value['forms'] = $this->get_allowed_form(false, $Value['v']);
                    $Return[$Value['v']] = $Value[$Attr];
                }
            }else {
                foreach ($this->_Func as $Key => $Value) {
                    if (preg_match('/^<i\s+class=\"(.*)\"><\/i>$/', $Value['img'], $Matched)) {
                        $Value['img'] = $Matched[1];
                    }
                    $Value['forms'] = $this->get_allowed_form(false, $Value['v']);
                    $Return[$Key] = $Value;
                }
            }
        }
        return $Return;
    }

    public function get_allowed_form($Attr = false, $Pid = 0) {
        $Return = array();
        if ($this->_allowed('form', $Pid)) {
            if (is_array($Attr)) {
                foreach ($this->_Form as $key => $value) {
                    foreach ($Attr as $ivalue) {
                        $Return[$value['v']][$value['v']][$ivalue] = $value[$ivalue];
                    }
                }
            }elseif (is_string($Attr)) {
                foreach ($this->_Form as $key => $value) {
                    if (!isset($Return[$value['func']])) {
                        $Return[$value['func']] = array();
                    }
                    $Return[$value['func']][$value['v']] = $value[$Attr];
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
                        $Return[$value['v']][$ivalue] = $value[$ivalue];
                    }
                }
            }elseif (is_string($Attr)) {
                foreach ($this->_PageSearch as $key => $value) {
                    $Return[$value['v']] = $value[$Attr];
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
                    $value['elements'] = $this->get_allowed_element(false, $value['v']);
                    foreach ($Attr as $ivalue) {
                        $Return[$value['v']][$ivalue] = $value[$ivalue];
                    }
                }
            }elseif (is_string($Attr)) {
                foreach ($this->_Card as $key => $value) {
                    $value['elements'] = $this->get_allowed_element(false, $value['v']);
                    $Return[$value['v']] = $value[$Attr];
                }
            }else {
                foreach ($this->_Card as $key => $value) {
                    $value['elements'] = $this->get_allowed_element(false, $value['v']);
                    $Return[$key] = $value;
                }
            }
        }
        return $Return;
    }

    public function get_allowed_element($Attr = false, $Pid = 0) {
        $Return = array();
        if ($this->_allowed('element', $Pid)) {
            if (is_array($Attr)) {
                foreach ($this->_Element as $key => $value) {
                    foreach ($Attr as $ivalue) {
                        $Return[$value['card']][$value['v']][$ivalue] = $value[$ivalue];
                    }
                }
            }elseif (is_string($Attr)) {
                foreach ($this->_Element as $key => $value) {
                    $Return[$value['card']][$value['v']] = $value[$Attr];
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
