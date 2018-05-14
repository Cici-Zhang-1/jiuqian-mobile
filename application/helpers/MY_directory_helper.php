<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian.
 * User: chuangchuangzhang
 * Date: 2018/2/9
 * Time: 12:29
 *
 * Desc:
 */

if (!function_exists('current_directory')) {
    function current_directory($File) {
        return basename(dirname($File));
    }
}
