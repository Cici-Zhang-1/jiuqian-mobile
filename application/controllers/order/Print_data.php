<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月11日
 * @author Zhangcc
 * @version
 * @des
 * 打印表格
 */
class Print_data extends CWDMS_Controller{
    private $Module = 'order';

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Order_product_board_plate Start !');
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

    private function _read(){
        $Opid = $this->input->get('id', true);
        $Product = $this->input->get('product', true);
        $Opid = intval(trim($Opid));
        $Product = trim($Product);
        if($Opid && $Product){
            $Return = array();
            $Item = $this->Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
            $this->e_cache->open_cache();
            $Cache = $Opid.'_'.$Product.'_order_print_data_read';
            if(!($Return = $this->cache->get($Cache))){
                $this->load->model('order/print_data_model');
                if(!!($Return['Info'] = $this->_print_info($Opid)) && !empty($Return['Info'])){
                    switch ($Product){
                        case 'w':
                            $Return['Struct'] = $this->_print_cabinet_struct($Opid);
                            $Return['Product'] = $this->_print_cabinet($Opid);
                            $Return['item'] = $Item.'_cabinet';
                            break;
                        case 'y':
                            $Return['Product'] = $this->_print_wardrobe($Opid);
                            $Return['item'] = $Item.'_wardrobe';
                            break;
                        case 'm':
                            $Return['Product'] = $this->_print_door($Opid);
                            $Return['item'] = $Item.'_door';
                            break;
                        case 'k':
                            $Return['Product'] = $this->_print_wood($Opid);
                            $Return['item'] = $Item.'_wood';
                            break;
                        case 'p':
                            $Return['Product'] = $this->_print_fitting($Opid);
                            $Return['item'] = $Item.'_fitting';
                            break;
                        case 'g':
                            $Return['Product'] = $this->_print_other($Opid);
                            $Return['item'] = $Item.'_other';
                            break;
                    }
                    //$this->cache->save($Cache, $Return, HOURS);
                }
            }
            if(empty($this->Failue)){
                $this->load->view('header2');
                $this->load->view($Return['item'], $Return);
            }else{
                show_error($this->Failue);
            }
        }else{
            show_404();
        }
    }
    
    private function _print_info($Opid){
        $Data = array();
        if(!!($Query = $this->print_data_model->select_print_data($Opid))){
            $this->config->load('dbview/order');
            $Dbview = $this->config->item('order/print_data/_print_info');
            foreach ($Dbview as $ikey => $ivalue){
                $Data[$ivalue] = isset($Query[$ikey])?$Query[$ikey]: '';
            }
        }else{
            $this->Failue .= '您要访问的订单信息不存在!';
        }
        return $Data;
    }

    private function _print_cabinet_struct($Opid){
        $Return = array();
        if(!!($Query = $this->print_data_model->select_print_data_cabinet_struct($Opid))){
            $this->config->load('dbview/order');
            $Dbview = $this->config->item('order/print_data/_print_cabinet_struct');
            foreach ($Dbview as $ikey => $ivalue){
                $Return[$ivalue] = isset($Query[$ikey])?$Query[$ikey]: '';
            }
        }
        return $Return;
    }
    
    private function _print_cabinet($Opid){
        $Plate = array();
        $Board = array();
        if(!!($Query = $this->print_data_model->select_print_data_cabinet($Opid))){
            $Count = array();
            foreach ($Query as $key => $value){
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
                $ikey = $value['cubicle_num'].'^'.$value['cubicle_name'].'^'.$Name.'^'.floatval($value['width']).'x'.floatval($value['length']);
                if(empty($Count[$ikey])){
                    $Count[$ikey] = $value['amount'];
                }else{
                    $Count[$ikey] += $value['amount'];
                }
                if(empty($Board[$value['board']])){
                    $Board[$value['board']] = array(
                        'area' => $value['area'],
                        'amount' => $value['amount']
                    );
                }else{
                    $Board[$value['board']]['area'] += $value['area'];
                    $Board[$value['board']]['amount'] += $value['amount'];
                }
            }
            foreach ($Count as $key => $value){
                $Tmp = explode('^', $key);
                if(empty($Plate[$Tmp[0]])){
                    $Plate[$Tmp[0]] = array(
                        '柜号名称' => $Tmp[1],
                        '立板' => '',
                        '顶底板' => '',
                        '活动隔板' => '',
                        '固定隔板' => '',
                        '连接条' => '',
                        '背板' => '',
                        '其它板块' => ''
                    );
                }
                $Plate[$Tmp[0]][$Tmp[2]] .= $Tmp[3].'x'.$value.'<br />';
            }
            ksort($Plate);
            ksort($Board);
        }else{
            $this->Failue .= '您要访问的订单信息不存在!';
        }
        $Return = array(
            'plate' => $Plate,
            'board' => $Board
        );
        return $Return;
    }
    
    private function _print_wardrobe($Opid){
        $Plate = array();
        $Board = array();
        if(!!($Query = $this->print_data_model->select_print_data_wardrobe($Opid))){
            $Area = 0;
            $Amount = 0;
            foreach ($Query as $key => $value){
                $Tmp = explode('^', $value['spec']);
                $Plate[$key] = array(
                    $key+1,
                    $Tmp[0],
                    floatval($Tmp[2]),
                    floatval($Tmp[1]),
                    $Tmp[3],
                    $value['area'],
                    $value['amount'],
                    $Tmp[4],
                    $Tmp[5],
                    $Tmp[6],
                );
                if(empty($Board[$Tmp[3]])){
                    $Board[$Tmp[3]] = array(
                        'thick' => $Tmp[3],
                        'amount' => $value['amount'],
                        'area' => $value['area']
                    );
                }else{
                    $Board[$Tmp[3]]['area'] += $value['area'];
                    $Board[$Tmp[3]]['amount'] += $value['amount'];
                }
                $Area += $value['area'];
                $Amount += $value['amount'];
            }
            ksort($Plate);
            ksort($Board);
            $Board[] = array(
                'thick' => '合计',
                'amount' => $Amount,
                'area' => $Area
            );
        }else{
            $this->Failue .= '您要访问的订单信息不存在!';
        }
        $Return = array(
            'plate' => $Plate,
            'board' => $Board
        );
        return $Return;
    }
    
    private function _print_door($Opid){
        $Plate = array();
        $Board = array();
        if(!!($Query = $this->print_data_model->select_print_data_door($Opid))){
            $Area = 0;
            $Amount = 0;
            foreach ($Query as $key => $value){
                if(empty($Plate[$value['board']])){
                    $Plate[$value['board']] = array();
                    $Count[$value['board']] = 1;
                }
                $Plate[$value['board']][] = array(
                    $Count[$value['board']]++,
                    floatval($value['width']),
                    floatval($value['length']),
                    $value['amount'],
                    $value['area'],
                    $value['punch'],
                    $value['handle'],
                    $value['open_hole'],
                    $value['invisibility']
                );
                if(empty($Board[$value['board']])){
                    $Board[$value['board']] = array(
                        'amount' => $value['amount'],
                        'area' => $value['area'],
                        'open_hole' => $value['open_hole'],
                        'invisibility' => $value['invisibility']
                    );
                }else{
                    $Board[$value['board']]['amount'] += $value['amount'];
                    $Board[$value['board']]['area'] += $value['area'];
                    $Board[$value['board']]['open_hole'] += $value['open_hole'];
                    $Board[$value['board']]['invisibility'] += $value['invisibility'];
                }
            }
        }else{
            $this->Failue .= '您要访问的订单信息不存在!';
        }
        $Return = array(
            'plate' => $Plate,
            'board' => $Board
        );
        return $Return;
    }
    
    private function _print_wood($Opid){
        $Plate = array();
        $Board = array('name' => '合计','amount' => 0, 'area' => 0);
        if(!!($Query = $this->print_data_model->select_print_data_wood($Opid))){
            $Area = 0;
            $Amount = 0;
            $Count = 1;
            foreach ($Query as $key => $value){
                $Board['amount'] += $value['amount'];
                $Board['area'] += $value['area'];
                
                $Plate[] = array(
                    $Count++,
                    $value['plate_name'],
                    $value['color'],
                    $value['length'],
                    $value['width'],
                    $value['thick'],
                    $value['amount'],
                    $value['punch'],
                    $value['spec'],
                    $value['area']
                );
            }
        }else{
            $this->Failue .= '您要访问的订单信息不存在!';
        }
        $Return = array(
            'plate' => $Plate,
            'board' => $Board
        );
        return $Return;
    }
    
    private function _print_fitting($Opid){
        $Return = array();
        if(!!($Query = $this->print_data_model->select_print_data_fitting($Opid))){
            foreach ($Query as $key => $value){
                $Return[] = array(
                    $key + 1,
                    $value['type'],
                    $value['name'],
                    $value['amount'],
                    $value['unit'],
                    $value['remark']
                );
            }
        }else{
            $this->Failue .= '您要访问的订单信息不存在!';
        }
        return $Return;
    }
    
    private function _print_other($Opid){
        $Return = array();
        if(!!($Query = $this->print_data_model->select_print_data_fitting($Opid))){
            foreach ($Query as $key => $value){
                $Return[] = array(
                    $key + 1,
                    $value['name'],
                    $value['length'],
                    $value['width'],
                    $value['amount'],
                    $value['remark']
                );
            }
        }else{
            $this->Failue .= '您要访问的订单信息不存在!';
        }
        return $Return;
    }
}