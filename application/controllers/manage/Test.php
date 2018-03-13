<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月24日
 * @author Zhangcc
 * @version
 * @des
 * 添加测试数据
 */
class Test extends MY_Controller{
    private $Module = 'data';
    private $Item = '';
    public function __construct(){
        log_message('debug', 'Controller Data/Test eStart!');
        parent::__construct();
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->Module.'/'.strtolower(__CLASS__).'/'.$View;
            $this->data['action'] = site_url($Item);
            $this->load->view($Item, $this->data);
        }
    }

    public function add(){
        $Item = $this->Module.'/'.strtolower(__CLASS__);
        $Nums = $this->input->post('num', true);
        $Nums = intval(trim($Nums));
        $Nums = 1;
        if($Nums > 0){
            $this->load->model('manage/test_model');
           /*  $this->load->model('order/order_product_model');
            $this->load->model('order/order_product_board_model');
            $this->load->model('order/order_product_board_plate_model');
            $this->load->model('order/order_product_board_door_model');
            $this->load->model('order/order_product_board_wood_model');
            $this->load->model('order/order_product_cabinet_model');
            $this->load->model('order/order_product_cabinet_struct_model');
            $this->load->model('order/order_product_fitting_model');
            $this->load->model('order/order_product_other_model');
            $this->load->model('order/order_product_server_model');
            $this->load->model('order/order_product_wardrobe_struct_model'); */
            
            $Order = $this->test_model->select_order_test();
            $OrderProduct = $this->test_model->select_order_product_test();
            $OrderProductBoard = $this->test_model->select_order_product_board_test();
            $OrderProductBoardPlate = $this->test_model->select_order_product_board_plate_test();
            $OrderProductBoardDoor = $this->test_model->select_order_product_board_door_test();
            $OrderProductBoardWood = $this->test_model->select_order_product_board_wood_test();
            $OrderProductCabinet = $this->test_model->select_order_product_cabinet_test();
            $OrderProductCabinetStruct = $this->test_model->select_order_product_cabinet_struct_test();
            $OrderProductFitting = $this->test_model->select_order_product_fitting_test();
            $OrderProductOther = $this->test_model->select_order_product_other_test();
            $OrderProductServer = $this->test_model->select_order_product_server_test();
            $OrderProductWardrobeStruct = $this->test_model->select_order_product_wardrobe_struct_test();
            
            $Set = array();
            foreach ($OrderProductBoardPlate as $key => $value){
                $Id1 = $value['opbp_order_product_board_id'];
                $Id2 = $value['opbp_id'];
                unset($value['opbp_order_product_board_id']);
                unset($value['opbp_id']);
                if(empty($Set[$Id1])){
                    $Set[$Id1] = array(
                        $Id2 => $value
                    );
                }else{
                    $Set[$Id1][$Id2] = $value;
                }
            }
            $OrderProductBoardPlate = $Set;
            $Set = array();
            foreach ($OrderProductBoardDoor as $key => $value){
                $Id1 = $value['opbd_order_product_board_id'];
                $Id2 = $value['opbd_id'];
                unset($value['opbd_order_product_board_id']);
                unset($value['opbd_id']);
                if(empty($Set[$Id1])){
                    $Set[$Id1] = array(
                        $Id2 => $value
                    );
                }else{
                    $Set[$Id1][$Id2] = $value;
                }
            }
            $OrderProductBoardDoor = $Set;
            $Set = array();
            foreach ($OrderProductBoardWood as $key => $value){
                $Id1 = $value['opbw_order_product_board_id'];
                $Id2 = $value['opbw_id'];
                unset($value['opbw_order_product_board_id']);
                unset($value['opbw_id']);
                if(empty($Set[$Id1])){
                    $Set[$Id1] = array(
                        $Id2 => $value
                    );
                }else{
                    $Set[$Id1][$Id2] = $value;
                }
            }
            $OrderProductBoardWood = $Set;
            $Set = array();
            foreach ($OrderProductBoard as $key => $value){
                $Id1 = $value['opb_order_product_id'];
                $Id2 = $value['opb_id'];
                unset($value['opb_id']);
                unset($value['opb_order_product_id']);
                if(empty($Set[$Id1])){
                    $Set[$Id1] = array(
                        $Id2 => array(
                            'order_product_board' => $value,
                            'order_product_board_plate' => array(),
                            'order_product_board_door' => array(),
                            'order_product_board_wood' => array()
                        )
                    );
                }else{
                    $Set[$Id1][$Id2] = array(
                        'order_product_board' => $value,
                        'order_product_board_plate' => array(),
                        'order_product_board_door' => array(),
                        'order_product_board_wood' => array()
                    );
                }
                if(!empty($OrderProductBoardPlate[$Id2])){
                    $Set[$Id1][$Id2]['order_product_board_plate'] = $OrderProductBoardPlate[$Id2];
                }
                if(!empty($OrderProductBoardDoor[$Id2])){
                    $Set[$Id1][$Id2]['order_product_board_door'] = $OrderProductBoardDoor[$Id2];
                }
                if(!empty($OrderProductBoardWood[$Id2])){
                    $Set[$Id1][$Id2]['order_product_board_wood'] = $OrderProductBoardWood[$Id2];
                }
            }
            
            $OrderProductBoard = $Set;
            $Set = array();
            foreach ($OrderProductWardrobeStruct as $key => $value){
                $Id1 = $value['opws_order_product_id'];
                $Id2 = $value['opws_id'];
                unset($value['opws_order_product_id']);
                unset($value['opws_id']);
                if(empty($Set[$Id1])){
                    $Set[$Id1] = array(
                        $Id2 => $value
                    );
                }else{
                    $Set[$Id1][$Id2] = $value;
                }
            }
            $OrderProductWardrobeStruct = $Set;
            $Set = array();
            
            foreach ($OrderProductCabinet as $key => $value){
                $Id1 = $value['opc_order_product_cabinet_struct_id'];
                $Id2 = $value['opc_id'];
                unset($value['opc_order_product_id']);
                unset($value['opc_id']);
                if(empty($Set[$Id1])){
                    $Set[$Id1] = array(
                        $Id2 => $value
                    );
                }else{
                    $Set[$Id1][$Id2] = $value;
                }
            }
            $OrderProductCabinet = $Set;
            $Set = array();

            foreach ($OrderProductCabinetStruct as $key => $value){
                $Id1 = $value['opcs_order_product_id'];
                $Id2 = $value['opcs_id'];
                unset($value['opcs_id']);
                unset($value['opcs_order_product_id']);
                if(empty($Set[$Id1])){
                    $Set[$Id1] = array(
                        $Id2 => array(
                            'order_product_cabinet_struct' => $value,
                            'order_product_cabinet' => array()
                        )
                    );
                }else{
                    $Set[$Id1][$Id2] = array(
                        'order_product_cabinet_struct' => $value,
                        'order_product_cabinet' => array()
                    );
                    /* array_push($Set[$Id1], array($Id2 => array('order_product_cabinet_struct' => $value,
                        'order_product_cabinet' => array()))); */
                }
                if(!empty($OrderProductCabinet[$Id2])){
                    $Set[$Id1][$Id2]['order_product_cabinet'] = $OrderProductCabinet[$Id2];
                }
            }
            $OrderProductCabinetStruct = $Set;
            $Set = array();
            
            foreach ($OrderProductFitting as $key => $value){
                $Id1 = $value['opf_order_product_id'];
                $Id2 = $value['opf_id'];
                unset($value['opf_order_product_id']);
                unset($value['opf_id']);
                if(empty($Set[$Id1])){
                    $Set[$Id1] = array(
                        $Id2 => $value
                    );
                }else{
                    $Set[$Id1][$Id2] = $value;
                }
            }
            $OrderProductFitting = $Set;
            $Set = array();
            
            foreach ($OrderProductOther as $key => $value){
                $Id1 = $value['opo_order_product_id'];
                $Id2 = $value['opo_id'];
                unset($value['opo_order_product_id']);
                unset($value['opo_id']);
                if(empty($Set[$Id1])){
                    $Set[$Id1] = array(
                        $Id2 => $value
                    );
                }else{
                    $Set[$Id1][$Id2] = $value;
                }
            }
            $OrderProductOther = $Set;
            $Set = array();

            foreach ($OrderProductServer as $key => $value){
                $Id1 = $value['ops_order_product_id'];
                $Id2 = $value['ops_id'];
                unset($value['ops_order_product_id']);
                unset($value['ops_id']);
                if(empty($Set[$Id1])){
                    $Set[$Id1] = array(
                        $Id2 => $value
                    );
                }else{
                    $Set[$Id1][$Id2] = $value;
                }
            }
            $OrderProductServer = $Set;
            $Set = array();
            
            foreach ($OrderProduct as $key => $value){
                $Id1 = $value['op_order_id'];
                $Id2 = $value['op_id'];
                unset($value['op_id']);
                unset($value['op_order_id']);
                if(empty($Set[$Id1])){
                    $Set[$Id1] = array(
                        $Id2 => array(
                            'order_product' => $value,
                            'order_product_board' => array(),
                            'order_product_wardrobe_struct' => array(),
                            'order_product_cabinet_struct' => array(),
                            'order_product_fitting' => array(),
                            'order_product_other' => array(),
                            'order_product_server' => array()
                        )
                    );
                }else{
                    $Set[$Id1][$Id2] = array(
                        'order_product' => $value,
                        'order_product_board' => array(),
                        'order_product_wardrobe_struct' => array(),
                        'order_product_cabinet_struct' => array(),
                        'order_product_fitting' => array(),
                        'order_product_other' => array(),
                        'order_product_server' => array()
                    );
                }
                if(!empty($OrderProductBoard[$Id2])){
                    $Set[$Id1][$Id2]['order_product_board'] = $OrderProductBoard[$Id2];
                }
                if(!empty($OrderProductWardrobeStruct[$Id2])){
                    $Set[$Id1][$Id2]['order_product_wardrobe_struct'] = $OrderProductWardrobeStruct[$Id2];
                }
                if(!empty($OrderProductCabinetStruct[$Id2])){
                    $Set[$Id1][$Id2]['order_product_cabinet_struct'] = $OrderProductCabinetStruct[$Id2];
                }
                if(!empty($OrderProductFitting[$Id2])){
                    $Set[$Id1][$Id2]['order_product_fitting'] = $OrderProductFitting[$Id2];
                }
                if(!empty($OrderProductOther[$Id2])){
                    $Set[$Id1][$Id2]['order_product_other'] = $OrderProductOther[$Id2];
                }
                if(!empty($OrderProductServer[$Id2])){
                    $Set[$Id1][$Id2]['order_product_server'] = $OrderProductServer[$Id2];
                }
            }
            $OrderProduct = $Set;
            $Set = array();
            foreach ($Order as $key => $value){
                $Id1 = $value['o_id'];
                unset($value['o_id']);
                $Set[$Id1] = array(
                    'order' => $value,
                    'order_product' => array()
                );
                if(!empty($OrderProduct[$Id1])){
                    $Set[$Id1]['order_product'] = $OrderProduct[$Id1];
                }
            }
            $Order = $Set;
            unset($Set);
            /* var_dump($Order[43]);
            exit(); */
            /*X2015120040*/
            $Year = 'X2015';
            for($J = 2; $J <= 2; $J++){
                $Prefix = sprintf('%s%02d', $Year, $J);
                $Nums = 1;
                for($I = 0; $I < 150; $I++){
                    foreach ($Order as $key => $value){
                        $OrderNum = sprintf('%s%04d', $Prefix, $Nums);
                        $Nums++;
                        $NewOrder = $value['order'];
                        $NewOrder['o_num'] = $OrderNum;
                        $Oid = $this->test_model->insert_order($NewOrder);
                        if(!empty($value['order_product']) && count($value['order_product']) > 0){
                            foreach ($value['order_product'] as $ikey => $ivalue){
                                $OrderProduct = $ivalue['order_product'];
                                $OrderProduct['op_order_id'] = $Oid;
                                if(strlen($OrderProduct['op_num']) > 0){
                                    $OrderProduct['op_num'] = $OrderNum.substr($OrderProduct['op_num'], strlen($OrderNum));
                                }else{
                                    $OrderProduct['op_num'] = null;
                                }
                                $Opid = $this->test_model->insert_order_product($OrderProduct);
                                if(!empty($ivalue['order_product_board']) && count($ivalue['order_product_board']) > 0){
                                    foreach ($ivalue['order_product_board'] as $iikey => $iivalue){
                                        $OrderProductBoard = $iivalue['order_product_board'];
                                        $OrderProductBoard['opb_order_product_id'] = $Opid;
                                        $Opbid = $this->test_model->insert_order_product_board($OrderProductBoard);
                                        if(!empty($iivalue['order_product_board_plate']) && count($iivalue['order_product_board_plate']) > 0){
                                            foreach ($iivalue['order_product_board_plate'] as $iiikey => $iiivalue){
                                                $OrderProductBoardPlate = $iiivalue;
                                                $OrderProductBoardPlate['opbp_order_product_board_id'] = $Opbid;
                                                if(strlen($OrderProductBoardPlate['opbp_qrcode']) > 0){
                                                    $OrderProductBoardPlate['opbp_qrcode'] = $OrderNum.substr($OrderProductBoardPlate['opbp_qrcode'], strlen($OrderNum));
                                                }else{
                                                    $OrderProductBoardPlate['opbp_qrcode'] = null;
                                                }
                                                
                                                $this->test_model->insert_order_product_board_plate($OrderProductBoardPlate);
                                            }
                                        }
                                        if(!empty($iivalue['order_product_board_door']) && count($iivalue['order_product_board_door']) > 0){
                                            foreach ($iivalue['order_product_board_door'] as $iiikey => $iiivalue){
                                                $OrderProductBoardDoor = $iiivalue;
                                                $OrderProductBoardDoor['opbd_order_product_board_id'] = $Opbid;
                                                $this->test_model->insert_order_product_board_door($OrderProductBoardDoor);
                                            }
                                        }
                                        if(!empty($iivalue['order_product_board_wood']) && count($iivalue['order_product_board_wood']) > 0){
                                            foreach ($iivalue['order_product_board_wood'] as $iiikey => $iiivalue){
                                                $OrderProductBoardWood = $iiivalue;
                                                $OrderProductBoardWood['opbw_order_product_board_id'] = $Opbid;
                                                $this->test_model->insert_order_product_board_wood($OrderProductBoardWood);
                                            }
                                        }
                                    }
                                }
                                if(!empty($ivalue['order_product_cabinet_struct']) && count($ivalue['order_product_cabinet_struct']) > 0){
                                    foreach ($ivalue['order_product_cabinet_struct'] as $iikey => $iivalue){
                                        $OrderProductCabinetStruct = $iivalue['order_product_cabinet_struct'];
                                        $OrderProductCabinetStruct['opcs_order_product_id'] = $Opid;
                                        $Opcsid = $this->test_model->insert_order_product_cabinet_struct($OrderProductCabinetStruct);
                                        if(!empty($iivalue['order_product_cabinet']) && count($iivalue['order_product_cabinet']) > 0){
                                            foreach ($iivalue['order_product_cabinet'] as $iiikey => $iiivalue){
                                                $OrderProductCabinet = $iiivalue;
                                                $OrderProductCabinet['opc_order_product_cabinet_struct_id'] = $Opcsid;
                                                $this->test_model->insert_order_product_cabinet($OrderProductCabinet);
                                            }
                                        }
                                    }
                                }
                                if(!empty($ivalue['order_product_wardrobe_struct']) && count($ivalue['order_product_wardrobe_struct']) > 0){
                                    foreach ($ivalue['order_product_wardrobe_struct'] as $iikey => $iivalue){
                                        $OrderProductWardrobeStruct = $iivalue;
                                        $OrderProductWardrobeStruct['opws_order_product_id'] = $Opid;
                                        $this->test_model->insert_order_product_wardrobe_struct($OrderProductWardrobeStruct);
                                    }
                                }
                                if(!empty($ivalue['order_product_fitting']) && count($ivalue['order_product_fitting']) > 0){
                                    foreach ($ivalue['order_product_fitting'] as $iikey => $iivalue){
                                        $OrderProductFitting = $iivalue;
                                        $OrderProductFitting['opf_order_product_id'] = $Opid;
                                        $this->test_model->insert_order_product_fitting($OrderProductFitting);
                                    }
                                }
                                if(!empty($ivalue['order_product_other']) && count($ivalue['order_product_other']) > 0){
                                    foreach ($ivalue['order_product_other'] as $iikey => $iivalue){
                                        $OrderProductOther = $iivalue;
                                        $OrderProductOther['opo_order_product_id'] = $Opid;
                                        $this->test_model->insert_order_product_other($OrderProductOther);
                                    }
                                }
                                if(!empty($ivalue['order_product_server']) && count($ivalue['order_product_server']) > 0){
                                    foreach ($ivalue['order_product_server'] as $iikey => $iivalue){
                                        $OrderProductServer = $iivalue;
                                        $OrderProductServer['ops_order_product_id'] = $Opid;
                                        $this->test_model->insert_order_product_server($OrderProductServer);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            /* $Data = array(
                'o_id' => array(
                    'order' => 'order',
                    'order_product' => array(
                        'op_id' => array(
                            'order_product' => 'order_product',
                            'order_product_board' => array(
                                'opb_id' => array(
                                    'order_product_board' => 'order_product_board',
                                    'order_product_board_plate' => array(
                                        'opbp_id' => array(
                                            'order_product_board_plate' => 'order_product_board_plate'
                                        )
                                    ),
                                    'order_product_board_door' => array(
                                        'opbd_id' => array(
                                            'order_product_board_door' => 'order_product_board_door'
                                        )
                                    ),
                                    'order_product_board_wood' => array(
                                        'opbw_id' => array(
                                            'order_product_board_wood' => 'order_product_board_wood'
                                        )
                                    )
                                )
                            ),
                            'order_product_cabinet_struct' => array(
                                'opcs_id' => array(
                                    'order_product_cabinet_struct' => 'order_product_cabinet_struct',
                                    'order_product_cabinet' => array(
                                        'opc_id' => array(
                                            'order_product_cabinet' => 'order_product_cabinet'
                                        )
                                    )
                                )
                            ),
                            'order_product_wardrobe_struct' => array(
                                'opws_id' => array(
                                    'order_product_cabinet_struct' => 'order_product_cabinet_struct'
                                )
                            ),
                            'order_product_fitting' => array(
                                'opf_id' => array(
                                    'order_product_fitting' => 'order_product_fitting'
                                )
                            ),
                            'order_product_other' => array(
                                'opf_id' => array(
                                    'order_product_other' => 'order_product_other'
                                )
                            ),
                            'order_product_server' => array(
                                'opf_id' => array(
                                    'order_product_server' => 'order_product_server'
                                )
                            )
                        )
                    )
                )
            ); */
        }
        /* $Run = $Item.'/'.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $Post = gh_mysql_string($_POST);
            if(!!($Id = $this->test_model->insert_test($Post))){
                $this->Success .= '衣柜板块名称新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'衣柜板块名称新增失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return(); */
    }
}
