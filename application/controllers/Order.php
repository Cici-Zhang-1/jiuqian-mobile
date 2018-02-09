<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian.
 * User: chuangchuangzhang
 * Date: 2018/2/8
 * Time: 11:37
 *
 * Desc:
 */

class Order extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function search() {
        $Num = $this->input->get('num');
        if ($Num === 1) {
            $Return = array(
                'code' => EXIT_SUCCESS,
                'contents' => array(
                    'id' => 1099,
                    'num' => 'X2018020005'
                )
            );
        }else {
            $Return = array(
                'code' => EXIT_SUCCESS,
                'contents' => array(
                    'id' => 1011,
                    'num' => 'X2018020010'
                )
            );
        }

        header("Access-Control-Allow-Origin: http://localhost:8080");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');
        exit(json_encode($Return));
    }
}
