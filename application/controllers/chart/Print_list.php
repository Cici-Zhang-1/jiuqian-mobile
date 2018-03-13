<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author Zhangcc
 * @version
 * @des
 * 打印清单
 */
class Print_list extends MY_Controller{

    private $_Type;
    private $_Id;
    private $_Code;
    
    private $_Return;
    
    private $_Quote = false;

    private $_FormStyle;

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Chart/Print_list Start !');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){ // Condition View
            $View = '_'.$View;
            $this->$View();
        }else{  // General View
            $this->_index($View);
        }
    }

    private function _read(){
        $this->_Type = $this->uri->segment(5, 'list');
        $this->_Type = trim($this->_Type);
        $this->_Id = $this->input->get('id', true);
        $this->_Id = intval(trim($this->_Id));
        $this->_FormStyle = $this->input->get('form_style', true);
        if (!isset($this->_FormStyle) || empty($this->_FormStyle) || !in_array($this->_FormStyle, array('_new'))) {
            $this->_FormStyle = '';
        }
        $this->load->model('order/order_product_model');
        if(!empty($this->_Id)){
            if(!!($this->_Return['Info'] = $this->order_product_model->select_order_detail_by_opid($this->_Id))){
                $this->_Return['All'] = $this->_read_all_order_product($this->_Return['Info']['oid']);
                if('preview' == $this->_Type){
                    $this->_read_preview();
                }elseif('print' == $this->_Type){
                    $this->_read_print();
                }elseif('quote' == $this->_Type){
                    $this->_read_quote();
                }else{
                    show_error('您要打印的清单类型不存在!');
                }
            }else{
                show_error('您要打印的订单产品不存在!');
            }
        }else{
            show_404();
        }
    }

    /**
     * 读预览
     */
    private function _read_preview(){
        $this->_Code = strtolower($this->_Return['Info']['code']);
        $Item = $this->_Item.__FUNCTION__.'_'.$this->_Code . $this->_FormStyle;
        $Method = '_read_'.$this->_Code . $this->_FormStyle;
        if(method_exists(__CLASS__, $Method)){
            $Data = $this->$Method();
            $Return = array_merge($this->_Return, $Data);
            unset($Data);
        }else{
            $this->Failue = '您要预览的内容不存在!';
        }
        if(empty($this->Failue)){
            $this->load->view('header2');
            $this->load->view($Item, $Return);
        }else{
            show_error($this->Failue);
        }
    }

    /**
     * 读打印
     */
    private function _read_print(){
        $this->_Code = strtolower($this->_Return['Info']['code']);
        $Item = $this->_Item.__FUNCTION__.'_'.$this->_Code . $this->_FormStyle;
        
        $Return = array();
        $Data = array();
        switch ($this->_Code){
            case 'w':
                $Data['Cabinet'] = $this->{'_read_w' . $this->_FormStyle}();
                $Data['Abnormity'] = $this->_read_abnormity();
                $Data['Classify'] = $this->_read_classify_print_list();
                $this->_edit();
                break;
            case 'y':
                $Data['Wardrobe'] = $this->_read_y();
                $Data['Abnormity'] = $this->_read_abnormity();
                $Data['Classify'] = $this->_read_classify_print_list();
                $this->_edit();
                break;
            case 'm':
                $Data = $this->_read_m();
                $this->_edit();
                break;
            case 'k':
                $Data = $this->_read_k();
                $this->_edit();
                break;
            case 'p':
                $Data = $this->_read_p();
                $this->_edit();
                break;
            case 'g':
                $Data = $this->_read_g();
                $this->_edit();
                break;
            case 'f':
                $Data = $this->_read_f();
                $this->_servered();
                break;
            default:
                $this->Failue = '您要打印的内容不存在!';
        }
        $Return = array_merge($this->_Return, $Data);
        if(empty($this->Failue)){
            $this->load->view('header2');
            $this->load->view($Item, $Return);
        }else{
            show_error($this->Failue);
        }
    }
    /**
     * 读报价
     */
    private function _read_quote(){
        $this->_Code = strtolower($this->_Return['Info']['code']);
        $Item = $this->_Item.__FUNCTION__.'_'.$this->_Code;
        $Method = '_read_'.$this->_Code;
        $this->_Quote = true;
        if(method_exists(__CLASS__, $Method)){
            $Data = $this->$Method();
            $Return = array_merge($this->_Return, $Data);
            unset($Data);
        }else{
            $this->Failue = '您要预览的内容不存在!';
        }
        if(empty($this->Failue)){
            $this->load->view('header2');
            $this->load->view($Item, $Return);
        }else{
            show_error($this->Failue);
        }
    }
    
    private function _edit(){
        $this->load->model('order/order_product_model');
        $this->load->library('workflow/workflow');
        if(!!($this->workflow->initialize('order_product', $this->_Id))){
            $this->workflow->print_list();
            $this->Success = '打印成功';
        }else{
            $this->Failue .= $this->workflow->get_failue();
        }
    }
    
    private function _servered(){
        $this->load->model('order/order_product_model');
        $this->load->library('workflow/workflow');
        if(!!($this->workflow->initialize('order_product', $this->_Id))){
            $this->workflow->servered();
            $this->Success = '服务成功';
        }else{
            $this->Failue .= $this->workflow->get_failue();
        }
    }
    
    private function _read_all_order_product($Oid){
        $Data = array();
        if(!!($Return = $this->order_product_model->select_by_oid($Oid))){
            foreach ($Return as $key => $value){
                if($value['code'] != 'F'){
                    $Tmp = explode('-', $value['order_product_num']);
                    $Data[$value['name']][] = array_pop($Tmp);
                }
            }
            unset($Return);
            return $Data;
        }else{
            return $Data;
        }
    }
    private function _read_abnormity(){
        $this->load->library('d_dismantle');
        $Data = array('Statistic' => array(), 'Amount' => 0, 'Area' => 0);
        $BoardPlate = $this->d_dismantle->read_detail('abnormity', $this->_Id);
        $Failue = $this->d_dismantle->get_failue();
        if(empty($Failue)){
            $List = array();
            foreach ($BoardPlate as $key => $value){
                $Tmp2 = implode('^', array($value['thick'], $value['plate_name'], $value['width'], $value['length'],
                    $value['punch'], $value['slot'], $value['punch'], $value['good'], $value['remark']));
                $value['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                $value['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                //
                $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                if ($value['area'] < MIN_AREA) {
                    $value['area'] = MIN_AREA;
                }
                //$value['area'] = round($value['width']*$value['length']/1000)/1000;
                if(isset($List[$Tmp2])){
                    $List[$Tmp2]['area'] += $value['area'];
                    $List[$Tmp2]['num'] += 1;
                }else{
                    $List[$Tmp2] = $value;
                }
                if(isset($Data['Statistic'][$value['thick']])){
                    $Data['Statistic'][$value['thick']]['amount'] += 1;
                    $Data['Statistic'][$value['thick']]['area'] += $value['area'];
                }else{
                    $Data['Statistic'][$value['thick']] = array('amount' => 1, 'area' => $value['area']);
                }
                $Data['Area'] += $value['area'];
            }
            $Data['Amount'] = count($BoardPlate);
            ksort($List);
            $Data['List'] = $List;
        }else{
            $Data = false;
        }
        return $Data;
    }
    
    /**
     * 读取非常规板块清单
     */
    private function _read_classify_print_list(){
        $this->load->model('data/classify_model');
        $Data = array();
        if(!!($Classify = $this->classify_model->select_print_list())){
            $this->load->model('order/order_product_board_plate_model');
            foreach ($Classify as $okey => $ovalue){
                if(!!($BoardPlate = $this->order_product_board_plate_model->select_classify_print_list($this->_Id, $ovalue['cid']))){
                    $Data[$ovalue['name']] = array('Statistic' => array(), 'Amount' => 0, 'Area' => 0);
                    $List = array();
                    foreach ($BoardPlate as $key => $value){
                        $Tmp2 = implode('^', array($value['thick'], $value['plate_name'], $value['width'], $value['length'],
                            $value['punch'], $value['slot'], $value['punch'], $value['good'], $value['remark']));
                        $value['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                        $value['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                        //
                        $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                        if ($value['area'] < MIN_AREA) {
                            $value['area'] = MIN_AREA;
                        }
                        //$value['area'] = round($value['width']*$value['length']/1000)/1000;
                        if(isset($List[$Tmp2])){
                            $List[$Tmp2]['area'] += $value['area'];
                            $List[$Tmp2]['num'] += 1;
                        }else{
                            $List[$Tmp2] = $value;
                        }
                        if(isset($Data[$ovalue['name']]['Statistic'][$value['thick']])){
                            $Data[$ovalue['name']]['Statistic'][$value['thick']]['amount'] += 1;
                            $Data[$ovalue['name']]['Statistic'][$value['thick']]['area'] += $value['area'];
                        }else{
                            $Data[$ovalue['name']]['Statistic'][$value['thick']] = array('amount' => 1, 'area' => $value['area']);
                        }
                        $Data[$ovalue['name']]['Area'] += $value['area'];
                    }
                    $Data[$ovalue['name']]['Amount'] = count($BoardPlate);
                    ksort($List);
                    $Data[$ovalue['name']]['List'] = $List;
                }
            }
        }
        return $Data;
    }

    /**
     * 获取相关图纸
     */
    private function _read_drawing(){
        $Return = array();
        $this->load->model('drawing/drawing_model');
        $Drawing = $this->drawing_model->select_by_opid($this->_Id);
        return $Drawing;
    }
    /**
     * 橱柜清单
     */
    private function _read_w(){
        $ColorFlag = array(
             '<i>', '<i class="fa fa-square">','<i class="fa fa-circle"','<i class="fa fa-heart"',
             '<i class="fa fa-star"','<i class="fa fa-smile-o"','<i class="fa fa-diamond"','<i class="fa fa-female"',
             '<i class="fa fa-futbol-o"','<i class="fa fa-music"','<i class="fa fa-plane"','<i class="fa fa-moon-o"',
             '<i class="fa fa-square-o"','<i class="fa fa-heart-o"','<i class="fa fa-car"','<i class="fa fa-tree"'
        );
        $ColorFlagChar = 'A';
        $this->load->library('d_dismantle');
        $Data = array('Statistic' => array());
        $Data['CabinetStruct'] = $this->d_dismantle->read_detail('cabinet_struct', $this->_Id);
        $this->d_dismantle->set_failue();
        $BoardPlate = $this->d_dismantle->read_detail('board_plate', $this->_Id);
        $this->Failue = $this->d_dismantle->get_failue();
        if(empty($this->Failue)){
            $List = array();
            $Other = array();
            $Implode = array();
            $BoardPlateTmp = array();
            foreach ($BoardPlate as $key => $value){
                $Implode[] = implode('^', array($value['cubicle_num'].$value['cubicle_name'], $value['plate_name'],$key));
            }
            sort($Implode,SORT_STRING);
            foreach ($Implode as $key => $value){
                $value = explode('^', $value);
                $BoardPlateTmp[] = $BoardPlate[$value[2]];
            }
             $BoardPlate = $BoardPlateTmp;
             unset($BoardPlateTmp);
             $Implode = array();
             foreach ($BoardPlate as $key => $value){
                 $Name = $value['plate_name'];
                 switch ($value['plate_name']){
                     case '左立板':
                     case '右立板':
                     case '右侧板':
                     case '左侧板':
                     case '左右板':
                     case '左板':
                     case '右板':
                         $Name = '立板';
                         break;
                     case '顶板':
                     case '底板':
                     case '前后板':
                     case '前板':
                     case '后板':
                         $Name = '顶底板';
                         break;
                     case '活动隔板':
                         break;
                     case '固定隔板':
                         break;
                     case '前撑':
                     case '后撑':
                         $Name = '连接条';
                         break;
                     case '背板':
                         break;
                     default:
                         $Name = '其它板块';
                         break;
                 }
                 if($this->_Quote){
                     //
                     $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                     if($value['area'] < MIN_AREA){
                         $value['area'] = MIN_AREA;
                     }
                     /*$value['area'] = ceil($value['width']*$value['length']/1000)/1000;
                     if($value['area'] < 0.001){
                         $value['area'] = 0.001;
                     }*/
                 }else{
                     $value['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                     $value['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                     $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                     if($value['area'] < MIN_AREA){
                         $value['area'] = MIN_AREA;
                     }
                     //$value['area'] = round($value['width']*$value['length']/1000)/1000;
                 }

                 if(isset($Data['Statistic'][$value['good']])){
                     $Data['Statistic'][$value['good']]['amount'] += 1;
                     $Data['Statistic'][$value['good']]['area'] += $value['area'];
                 }else{
                     $Data['Statistic'][$value['good']] = array('amount' => 1, 'area' => $value['area']);
                 }
                 $Implode[] = implode('^', array($value['cubicle_num'].$value['cubicle_name'], $Name,
                     $value['width'].'x'.$value['length'], $value['thick'], $value['good'], $value['remark']
                 ));
             }
             $Implode = array_count_values($Implode); /*计算重复出现的次数*/
             arsort($Implode);
             foreach ($Implode as $key => $value){
                 $Keys = explode('^', $key);
                 if(!isset($List[$Keys[0]])){
                     $List[$Keys[0]] = array(
                         'li' => '',
                         'dingdi' => '',
                         'huo' => '',
                         'gu' => '',
                         'lian' => '',
                         'bei' => ''
                     );
                 }
                 if(!isset($Color[$Keys[4]])){
                     if(!empty($ColorFlag)){
                         $Color[$Keys[4]] = array_shift($ColorFlag);
                     }else{
                         $Color[$Keys[4]] = '<i>'.$ColorFlagChar;
                         $ColorFlagChar++;
                     }
                 }
                 $Value = $Color[$Keys[4]].'</i>&nbsp;'.$Keys[2].'x'.$value.'<br />';
                 if(!empty($Keys[5])){
                     $Value = $Value.$Keys[5].'<br />';
                 }
                 switch ($Keys[1]){
                     case '立板':
                         $List[$Keys[0]]['li'] .= $Value;
                         break;
                     case '顶底板':
                         $List[$Keys[0]]['dingdi'] .= $Value;
                         break;
                     case '活动隔板':
                         $List[$Keys[0]]['huo'] .= $Value;
                         break;
                     case '固定隔板':
                         $List[$Keys[0]]['gu'] .= $Value;
                         break;
                     case '连接条':
                         $List[$Keys[0]]['lian'] .= $Value;
                         break;
                     case '背板':
                         $List[$Keys[0]]['bei'] .= $Value;
                         break;
                     case '其它板块':
                         if(isset($Other[$Keys[0]])){
                             $Other[$Keys[0]] .= $Value;
                         }else{
                             $Other[$Keys[0]] = $Value;
                         }
                         break;
                 }
             }
             ksort($List,SORT_NUMERIC);
             $Data['List'] = $List;
             $Data['Other'] = $Other;
         }
         return $Data;
    }

    private function _read_w_new() {
        $this->load->library('d_dismantle');
        $this->load->helper('dismantle_helper');
        $Data = array('Statistic' => array(), 'FourH' => array('Amount' => 0, 'Area' => 0), 'Amount' => 0, 'Area' => 0, 'Face' => '');
        $Data['CabinetStruct'] = $this->d_dismantle->read_detail('cabinet_struct', $this->_Id);
        $this->d_dismantle->set_failue();
        $BoardPlate = $this->d_dismantle->read_detail('board_plate', $this->_Id);
        $this->Failue = $this->d_dismantle->get_failue();
        if(empty($this->Failue)){
            $List = array();
            $Face = get_face();
            $FaceOut = array(
                '右抽侧',
                '左抽侧',
                '抽后',
                '抽前',
                '裤侧左(圆)',
                '裤侧右(圆)',
                '右侧(圆)',
                '左侧(圆)',
                '后板',
                '裤后'
            );
            $SuffQrcodes = array();
            foreach ($BoardPlate as $key => $value){
                if ((isset($Face[$value['punch'] . $value['slot']]) || isset($Face[A_ALL . $value['slot']]) || isset($Face[$value['punch'] . A_ALL]))
                && !in_array($value['plate_name'], $FaceOut)) {
                    $Qrcode = explode('-', $value['qrcode']);
                    $SuffQrcodes[] = array_pop($Qrcode);
                }
                if($this->_Quote){
                    $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                    if($value['area'] < MIN_AREA){
                        $value['area'] = MIN_AREA;
                    }
                    /*$value['area'] = ceil($value['width']*$value['length']/1000)/1000;
                    if($value['area'] < 0.001){
                        $value['area'] = 0.001;
                    }*/
                }else{
                    $value['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                    $value['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                    $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                    if($value['area'] < MIN_AREA){
                        $value['area'] = MIN_AREA;
                    }
                    //$value['area'] = round($value['width']*$value['length']/1000)/1000;
                }

                $Tmp2 = implode('^', array($value['thick'], $value['plate_name'], $value['width'], $value['length'],
                    $value['edge'], $value['slot'], $value['punch'], $value['good'], $value['remark']));

                if(isset($List[$Tmp2])){
                    $List[$Tmp2]['area'] += $value['area'];
                    $List[$Tmp2]['num'] += 1;
                }else{
                    $List[$Tmp2] = $value;
                }
                if(isset($Data['Statistic'][$value['thick']])){
                    $Data['Statistic'][$value['thick']]['amount'] += 1;
                    $Data['Statistic'][$value['thick']]['area'] += $value['area'];
                }else{
                    $Data['Statistic'][$value['thick']] = array('amount' => 1, 'area' => $value['area']);
                }
                if ('4H' == $value['edge']) {
                    $Data['FourH']['Amount'] += 1;
                    $Data['FourH']['Area'] += $value['area'];
                }
                $Data['Area'] += $value['area'];
            }
            $Data['Amount'] = count($BoardPlate);
            ksort($List);
            $Data['List'] = $List;
            sort($SuffQrcodes);
            $Data['Face'] = implode('; ', $SuffQrcodes);
        }
        return $Data;
    }
    /**
     * 衣柜清单
     */
    private function _read_y(){
        $this->load->library('d_dismantle');
        $this->load->helper('dismantle_helper');
        $Data = array('Statistic' => array(), 'FourH' => array('Amount' => 0, 'Area' => 0), 'Amount' => 0, 'Area' => 0, 'Face' => '');
        $Data['WardRobeStruct'] = $this->d_dismantle->read_detail('wardrobe_struct', $this->_Id);
        $this->d_dismantle->set_failue();
        $BoardPlate = $this->d_dismantle->read_detail('board_plate', $this->_Id);
        $this->Failue = $this->d_dismantle->get_failue();
        if(empty($this->Failue)){
            $List = array();
            $Face = get_face();
            $FaceOut = array(
                '右抽侧',
                '左抽侧',
                '抽后',
                '抽前',
                '裤侧左(圆)',
                '裤侧右(圆)',
                '右侧(圆)',
                '左侧(圆)',
                '后板',
                '裤后'
            );
            $SuffQrcodes = array();
            foreach ($BoardPlate as $key => $value){
                if ((isset($Face[$value['punch'] . $value['slot']]) || isset($Face[A_ALL . $value['slot']]) || isset($Face[$value['punch'] . A_ALL]))
                && !in_array($value['plate_name'], $FaceOut)) {
                    $Qrcode = explode('-', $value['qrcode']);
                    $SuffQrcodes[] = array_pop($Qrcode);
                }
                if($this->_Quote){
                    $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                    if($value['area'] < MIN_AREA){
                        $value['area'] = MIN_AREA;
                    }
                    /*$value['area'] = ceil($value['width']*$value['length']/1000)/1000;
                    if($value['area'] < 0.001){
                        $value['area'] = 0.001;
                    }*/
                }else{
                    $value['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                    $value['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                    $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                    if($value['area'] < MIN_AREA){
                        $value['area'] = MIN_AREA;
                    }
                    //$value['area'] = round($value['width']*$value['length']/1000)/1000;
                }
                
                $Tmp2 = implode('^', array($value['thick'], $value['plate_name'], $value['width'], $value['length'],
                    $value['edge'], $value['slot'], $value['punch'], $value['good'], $value['remark']));
                
                if(isset($List[$Tmp2])){
                    $List[$Tmp2]['area'] += $value['area'];
                    $List[$Tmp2]['num'] += 1;
                }else{
                    $List[$Tmp2] = $value;
                }
                if(isset($Data['Statistic'][$value['thick']])){
                    $Data['Statistic'][$value['thick']]['amount'] += 1;
                    $Data['Statistic'][$value['thick']]['area'] += $value['area'];
                }else{
                    $Data['Statistic'][$value['thick']] = array('amount' => 1, 'area' => $value['area']);
                }
                if ('4H' == $value['edge']) {
                    $Data['FourH']['Amount'] += 1;
                    $Data['FourH']['Area'] += $value['area'];
                }
                $Data['Area'] += $value['area'];
            }
            $Data['Amount'] = count($BoardPlate);
            ksort($List);
            $Data['List'] = $List;
            sort($SuffQrcodes);
            $Data['Face'] = implode('; ', $SuffQrcodes);
        }
        return $Data;
    }
    /**
     * 门板类清单
     */
    private function _read_m(){
        $this->load->library('d_dismantle');
        $Data = array('Amount' => 0, 'Area' => 0, 'OpenHole' => 0, 'Invisibility' => 0);
        $Data['Door'] = $this->d_dismantle->read_detail('door', $this->_Id);
        $BoardDoor = $this->d_dismantle->read_detail('board_door', $this->_Id);
        $this->Failue = $this->d_dismantle->get_failue();
        if(empty($this->Failue)){
            $List = array();
            foreach ($BoardDoor as $key => $value){
                if($this->_Quote){
                    $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                    if($value['area'] < MIN_M_AREA){
                        $value['area'] = MIN_M_AREA;
                    }
                    /*$value['area'] = ceil($value['width']*$value['length']/1000)/1000;
                    if($value['area'] < 0.1){
                        $value['area'] = 0.1;
                    }*/
                }else{
                    $value['width'] = $value['width'] - $value['up_edge'] - $value['down_edge'];
                    $value['length'] = $value['length'] - $value['left_edge'] - $value['right_edge'];
                    $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                    if ($value['area'] < MIN_AREA) {
                        $value['area'] = MIN_AREA;
                    }
                    //$value['area'] = round($value['width']*$value['length']/1000)/1000;
                }
                
                $Tmp2 = implode('^', array($value['good'], $value['width'], $value['length'],
                    $value['thick'], $value['punch'], $value['handle'],
                    $value['open_hole'], $value['invisibility'], $value['remark']));
                
                if(isset($List[$Tmp2])){
                    $List[$Tmp2]['area'] += $value['area'];
                    $List[$Tmp2]['open_hole'] += $value['open_hole'];
                    $List[$Tmp2]['invisibility'] += $value['invisibility'];
                    $List[$Tmp2]['num'] += 1;
                }else{
                    $List[$Tmp2] = $value;
                }
                $Data['Area'] += $value['area'];
                $Data['OpenHole'] += $value['open_hole'];
                $Data['Invisibility'] += $value['invisibility'];
            }
            $Data['Amount'] = count($BoardDoor);
            ksort($List);
            $Data['List'] = $List;
        }
        return $Data;
    }
    /**
     * 木框门清单
     */
    private function _read_k(){
        $this->load->library('d_dismantle');
        $Data = array('Amount' => 0, 'Area' => 0);
        $BoardWood = $this->d_dismantle->read_detail('board_wood', $this->_Id);
        $this->Failue = $this->d_dismantle->get_failue();
        if(empty($this->Failue)){
            $List = array();
            foreach ($BoardWood as $key => $value){
                $Tmp2 = implode('^', array($value['width'], $value['length'],
                    $value['thick'], $value['punch'],
                    $value['wood_name'], $value['core'], $value['good'], $value['remark']));
                if($this->_Quote){
                    $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                    if($value['area'] < MIN_K_AREA){
                        $value['area'] = MIN_K_AREA;
                    }
                }else{
                    $value['area'] = ceil($value['width']*$value['length']/M_ONE)/M_TWO;
                    if ($value['area'] < MIN_AREA) {
                        $value['area'] = MIN_AREA;
                    }
                    //$value['area'] = round($value['width']*$value['length']/1000)/1000;
                }

                $value['m_width'] = $value['width'] - 3;
                $value['m_length'] = $value['length'] - 3;

                if(isset($List[$Tmp2])){
                    $List[$Tmp2]['area'] += $value['area'];
                    $List[$Tmp2]['num'] += 1;
                }else{
                    $List[$Tmp2] = $value;
                }
                $Data['Area'] += $value['area'];
            }
            $Data['Amount'] = count($BoardWood);
            $Data['List'] = $List;
        }
        return $Data;
    }
    /**
     * 配件类清单
     */
    private function _read_p(){
        $this->load->library('d_dismantle');
        $Data = array();
        $Data['List'] = $this->d_dismantle->read_detail('fitting', $this->_Id);
        $this->Failue = $this->d_dismantle->get_failue();
        return $Data;
    }
    /**
     * 外购类清单
     */
    private function _read_g(){
        $this->load->library('d_dismantle');
        $Data = array();
        $Data['List'] = $this->d_dismantle->read_detail('other', $this->_Id);
        $this->Failue = $this->d_dismantle->get_failue();
        return $Data;
    }
    
    /**
     * 服务类清单
     */
    private function _read_f(){
        $this->load->library('d_dismantle');
        $Data = array();
        $Data['List'] = $this->d_dismantle->read_detail('server', $this->_Id);
        $this->Failue = $this->d_dismantle->get_failue();
        return $Data;
    }
}
