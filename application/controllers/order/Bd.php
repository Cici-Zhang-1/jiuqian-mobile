<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Administrator
 * @version
 * @des
 */
class Bd extends CWDMS_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    private $Search = array(
        'bd' => '1',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
        
        log_message('debug', 'Controller Order/Bd Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $this->data['action'] = site_url($Item);
            $this->load->view($Item, $this->data);
        }
    }

    public function read(){
        $Cookie = $this->_Cookie.__FUNCTION__;
        $this->Search = $this->get_page_conditions($Cookie, $this->Search);
        $Data = array();
        if(!empty($this->Search)){
            if(!!($Data = $this->order_product_model->select_bd($this->Search))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要打印的清单';;
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }


    public function download(){
        $Item = $this->_Item.__FUNCTION__;
        $Id = $this->input->get('id', true);
        $Id = explode(',', $Id);
        if(is_array($Id)){
            foreach ($Id as $key => $value){
                $Id[$key] = intval($value);
            }
            $this->load->model('order/order_product_board_plate_model');
            if(!!($Query = $this->order_product_board_plate_model->select_bd_files($Id))
                && !!($this->order_product_model->update(array('bd' => 2), $Id))){
                $zip = new ZipArchive();
                $Dirname = APPPATH.'../download/'.date('Y', time()).'/'.date('m', time()).'/'.date('d', time()).'/';
                if (!is_dir($Dirname) && !mkdir($Dirname, 0777, true)) {
                    $this->Failue='权限不足, 目录不可写, 文件上传失败!';
                }
                $Filename = date('Y-m-d_H-i-s').'.zip';
                $FullFilename = $Dirname.$Filename;

                if ($zip->open($FullFilename, ZIPARCHIVE::CREATE)!==TRUE) {
                    $this->Failue .= '无法读写压缩文件!';
                }else{
                    foreach ($Query as $key => $value){
                        $Path = $value['bd_file'];
                        $LocalName = $value['qrcode'].'.bd';
                        if(file_exists($Path)){
                            $zip->addFile($Path, $LocalName);
                        }
                    }
                    $zip->close();
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment;filename="'.$Filename.'"');
                    header('Cache-Control: max-age=0');
                    header('Cache-Control: max-age=1');

                    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header ('Pragma: public'); // HTTP/1.0

                    $file = fopen($FullFilename,"r");
                    echo fread($file, filesize($FullFilename));
                    fclose($file);
                }
            }else {
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'该订单没有上传Bd文件';
            }
        }else{
            $this->Failue .= '请先选择订单';
        }
        $this->_return();
    }
}