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
            case CABINET_NUM:
                require_once 'D_w.php';
                $H = new Dismantle\D_w();
                break;
            case WARDROBE_NUM:
                require_once 'D_y.php';
                $H = new Dismantle\D_y();
                break;
            case DOOR_NUM:
                require_once 'D_m.php';
                $H = new Dismantle\D_m();
                break;
            case WOOD_NUM:
                require_once 'D_k.php';
                $H = new Dismantle\D_k();
                break;
            case FITTING_NUM:
                require_once 'D_p.php';
                $H = new Dismantle\D_p();
                break;
            case OTHER_NUM:
                require_once 'D_g.php';
                $H = new Dismantle\D_g();
                break;
            case SERVER_NUM:
                require_once 'D_f.php';
                $H = new Dismantle\D_f();
                break;
        }
        return $H;
    }
}