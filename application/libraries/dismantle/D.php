<?php
use \Dismantle as Dismantle;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/10
 * Time: 11:36
 */
class D {
    public function initialize($Code){
        $H = false;
        switch ($Code) {
            case 'W':
                require_once 'D_w.php';
                $H = new Dismantle\D_w();
                break;
            case 'Y':
                require_once 'D_y.php';
                $H = new Dismantle\D_y();
                break;
            case 'M':
                require_once 'D_m.php';
                $H = new Dismantle\D_m();
                break;
            case 'K':
                require_once 'D_k.php';
                $H = new Dismantle\D_k();
                break;
            case 'G':
                require_once 'D_g.php';
                $H = new Dismantle\D_g();
                break;
            case 'S':
                require_once 'D_s.php';
                $H = new Dismantle\D_s();
                break;
        }
        return $H;
    }
}