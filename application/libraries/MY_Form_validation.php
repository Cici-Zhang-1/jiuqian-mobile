<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/15
 * Time: 10:09
 */
class MY_Form_validation extends CI_Form_validation {
    public function __construct($rules = array()) {
        parent::__construct($rules);
    }

    /**
     * 判断是否是有效日期
     * @param $Str
     */
    public function valid_date ($Str) {
        if (empty($str)) {
            return TRUE;
        } elseif (preg_match('/^[\d]{2,4}[\-/\\]{1}[\d]{2}[\-/\\]{1}[\d]{2,4}$/', $Str)) {
            return TRUE;
        }
        return FALSE;
    }
}