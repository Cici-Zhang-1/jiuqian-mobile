<?php
use \Post_sale as Post_sale;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/29
 * Time: 15:18
 */
class P {
    public function initialize($Code){
        $H = false;
        switch ($Code) {
            case FITTING_NUM:
                require_once 'P_p.php';
                $H = new Post_sale\P_p();
                break;
            case OTHER_NUM:
                require_once 'P_g.php';
                $H = new Post_sale\P_g();
                break;
            case SERVER_NUM:
                require_once 'P_f.php';
                $H = new Post_sale\P_f();
                break;
        }
        return $H;
    }
}