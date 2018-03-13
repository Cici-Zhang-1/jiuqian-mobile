<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月5日
 * @author Zhangcc
 * @version
 * @des
 * 拆单详情(板材、板块)
 */
class Dismantle_detail extends MY_Controller{
    private $Module = 'order';

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Dismantle_detail Start !');
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
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        if($Id){
            $Data = array(
                'Id' => $Id
            );
            $Return = array();
            if(!!($Return['Info'] = $this->_read_info($Id))){
                $this->load->library('d_dismantle');
                switch($Return['Info']['code']){
                    case 'W':
                        $Return['Detail'] = $this->d_dismantle->read_detail('board_plate', $Id);
                        break;
                    case 'Y':
                        $Return['Detail'] = $this->d_dismantle->read_detail('board_plate', $Id);
                        break;
                    case 'M':
                        $Return['Detail'] = $this->d_dismantle->read_detail('board_door', $Id);
                        break;
                    case 'K':
                        $Return['Detail'] = $this->d_dismantle->read_detail('board_wood', $Id);
                        break;
                    case 'F': //服务
                        $Return['Detail'] = $this->d_dismantle->read_detail('fitting', $Id);
                        break;
                    case 'G': //外购
                        $Return['Detail'] = $this->d_dismantle->read_detail('other', $Id);
                        break;
                    case 'P': //配件
                        $Return['Detail'] = $this->d_dismantle->read_detail('fitting', $Id);
                        break;
                }
            }
            if(!empty($Return['Info'])){
                $Data = array_merge($Data, $Return);
                $this->load->view('order/dismantle_detail/_read', $Data);
            }else{
                show_error('您要访问的订单不存在!');
            }
        }else{
            show_error('您要访问的订单不存在!');
        }
    }
    
    private function _read_floatover(){
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        if($Id){
            $Data = array(
                'Id' => $Id
            );
            $Return = array();
            if(!!($Info = $this->_read_info($Id))){
                $this->load->library('d_dismantle');
                switch($Info['code']){
                    case 'W':
                        $Return['Board'] = $this->d_dismantle->read_detail('board', $Id);
                        break;
                    case 'Y':
                        $Return['Board'] = $this->d_dismantle->read_detail('board', $Id);
                        break;
                    case 'M':
                        $Return['Board'] = $this->d_dismantle->read_detail('board', $Id);
                        break;
                    case 'K':
                        $Return['Board'] = $this->d_dismantle->read_detail('board', $Id);
                        break;
                    case 'F': //服务
                        $Return['Server'] = $this->d_dismantle->read_detail('server', $Id);
                        break;
                    case 'G': //外购
                        $Return['Other'] = $this->d_dismantle->read_detail('other', $Id);
                        break;
                    case 'P': //配件
                        $Return['Fitting'] = $this->d_dismantle->read_detail('fitting', $Id);
                        break;
                }
            }
            if(!empty($Info)){
                $Data = array_merge($Data, $Return);
                $this->load->view('order/dismantle_detail/_read_floatover', $Data);
            }else{
                show_error('您要访问的订单产品不存在!');
            }
        }else{
            show_error('您要访问的内容不存在!');
        }
    }
    
    /**
     * 读取订单信息
     * @param unknown $Id
     */
    private function _read_info($Id){
        $Return = false;
        $this->load->model('order/order_product_model');
        if(!($Return = $this->order_product_model->select_order_detail_by_opid($Id))){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要访问的订单产品不存在!';;
        }
        return $Return;
    }
}
