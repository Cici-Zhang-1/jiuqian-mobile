<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian.
 * User => chuangchuangzhang
 * Date => 2018/2/5
 * Time => 15 =>33
 *
 * Desc =>
 */
class Location extends CI_Controller {
    function __construct() {
        parent::__construct();
    }

    public function index () {

    }
    public function read() {
        $data1 = array(
            1 => array(
                'length' => 512,
                'page' => 1,
                'keyword' => '',
                'pagesize' => 50,
                'contents' => array(
                    array('id' => 1,
                        'status' => 1,
                        'sn' => 'A1',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 2,
                        'status' => 1,
                        'sn' => 'A2',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 3,
                        'status' => 1,
                        'sn' => 'A3',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 4,
                        'status' => 1,
                        'sn' => 'A4',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 5,
                        'status' => 1,
                        'sn' => 'A5',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    )
                )
            ),
            2 => array(
                'length' => 512,
                'page' => 2,
                'keyword' => '',
                'pagesize' => 50,
                'contents' => array(
                    array('id' => 1,
                        'status' => 2,
                        'sn' => 'A1',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 2,
                        'status' => 2,
                        'sn' => 'A2',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 3,
                        'status' => 3,
                        'sn' => 'A3',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 4,
                        'status' => 3,
                        'sn' => 'A4',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 5,
                        'status' => 1,
                        'sn' => 'A5',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    )
                )
            ),
            3 => array(
                'length' => 512,
                'page' => 3,
                'keyword' => '',
                'pagesize' => 50,
                'contents' => array(
                    array('id' => 1,
                        'status' => 2,
                        'sn' => 'A1',
                        'order' => 'X2016070002, X2016070003, X2016070004, X2016070005, X2016070006, X2016070007, '
                    ),
                    array(
                        'id' => 2,
                        'status' => 2,
                        'sn' => 'A2',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 3,
                        'status' => 3,
                        'sn' => 'A3',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 4,
                        'status' => 3,
                        'sn' => 'A4',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 5,
                        'status' => 1,
                        'sn' => 'A5',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    )
                )
            )
        );
        $data2 = array(
            1 => array(
                'length' => 200,
                'page' => 1,
                'keyword' => '',
                'pagesize' => 50,
                'contents' => array(
                    array('id' => 1,
                        'status' => 1,
                        'sn' => 'A1',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 2,
                        'status' => 1,
                        'sn' => 'A2',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 3,
                        'status' => 2,
                        'sn' => 'A3',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 4,
                        'status' => 2,
                        'sn' => 'A4',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 1,
                        'status' => 3,
                        'sn' => 'A1',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 2,
                        'status' => 1,
                        'sn' => 'A2',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 3,
                        'status' => 2,
                        'sn' => 'A3',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 4,
                        'status' => 2,
                        'sn' => 'A4',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 5,
                        'status' => 2,
                        'sn' => 'A5',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    )
                )
            ),
            3 => array(
                'length' => 200,
                'page' => 3,
                'keyword' => '',
                'pagesize' => 50,
                'contents' => array(
                    array('id' => 1,
                        'status' => 2,
                        'sn' => 'A1',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 2,
                        'status' => 1,
                        'sn' => 'A2',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 3,
                        'status' => 2,
                        'sn' => 'A3',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 4,
                        'status' => 2,
                        'sn' => 'A4',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    ),
                    array(
                        'id' => 5,
                        'status' => 2,
                        'sn' => 'A5',
                        'order' => 'X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, X2016070001, '
                    )
                )
            )

        );
        $keyword = $this->input->get('keyword');
        if (isset($keyword) && $keyword != '') {
            $page = $this->input->get('page');
            if ($page <= 2) {
                $data2[$page]['keyword'] = $keyword;
                exit(json_encode($data2[$page]));
            }else {
                $data2[3]['keyword'] = $keyword;
                exit(json_encode($data2[3]));
            }
            exit(json_encode($data2));
        }else {
            $page = $this->input->get('page');
            if ($page <= 2) {
                exit(json_encode($data1[$page]));
            }else {
                exit(json_encode($data1[3]));
            }
        }
    }

    public function add () {
        $Name = $this->input->post('sn');
        if ($Name == 'A1') {
            $Return = array(
                'code' => EXIT_SUCCESS,
                'message' => 'Your data is wrong'
            );
        }else {
            $Return = array(
                'code' => EXIT_ERROR
            );
        }
        exit(json_encode($Return));
    }

    public function edit () {
        $data = array(
            'code' => EXIT_SUCCESS,
            'contents' => array(
                'sn' => 'B3',
                'status' => 1
            )
        );
        exit(json_encode($data));
    }

    public function remove () {
        $Id = $this->input->post('id');
        log_message('error', $Id);
        if ($Id > 0) {
            $Return = array(
                'code' => EXIT_SUCCESS,
                'message' => '库位删除成功'
            );
        }else {
            $Return = array(
                'code' => EXIT_ERROR,
                'message' => 'You Id is Not Right, Please Check'
            );
        }
        exit(json_encode($Return));
    }

    public function in() {
        $Return = array(
            'code' => EXIT_ERROR,
            'message' => '入库失败!'
        );
        exit(json_encode($Return));
    }

    public function out() {
        $Return = array(
            'code' => EXIT_SUCCESS,
            'message' => '出库成功!'
        );
        exit(json_encode($Return));
    }

    public function search() {
        $Return = array(
            'code' => EXIT_SUCCESS,
            'contents' => array(
                array(
                    'id' => 1,
                    'num' => 'X2018010001'
                ),
                array(
                    'id' => 2,
                    'num' => 'X2018010002'
                )
            )
        );
        exit(json_encode($Return));
    }
}
