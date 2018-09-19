<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Administrator
 * @version
 * @des
 */
class Bd extends MY_Controller{
    private $__Search = array(
        'status' => NO,
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_model');
        log_message('debug', 'Controller Order/Bd Start!');
    }

    public function read() {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->order_product_model->select_bd($this->_Search))){
            $this->Code = EXIT_ERROR;
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
        }
        $this->_ajax_return($Data);
    }


    public function download(){
        $V = $this->input->get('v', true);
        if (!is_array($V)) {
            $V = explode(',', $V);
        }
        foreach ($V as $key => $value){
            $V[$key] = intval($value);
            if (empty($V[$key])) {
                unset($V[$key]);
            }
        }
        $this->load->model('order/order_product_board_plate_model');
        if(!!($Query = $this->order_product_board_plate_model->select_bd_files($V))
            && !!($this->order_product_model->update(array('bd' => TWO), $V))){
            $zip = new ZipArchive();
            $Dirname = APPPATH.'../download/'.date('Y', time()).'/'.date('m', time()).'/'.date('d', time()).'/';
            if (!is_dir($Dirname) && !mkdir($Dirname, 0777, true)) {
                show_error('权限不足, 目录不可写, 文件上传失败!');
                exit();
            }
            $Filename = date('Y-m-d_H-i-s').'.zip';
            $FullFilename = $Dirname . $Filename;

            if ($zip->open($FullFilename, ZIPARCHIVE::CREATE)!==TRUE) {
                show_error('无法读写压缩文件!');
                exit();
            } else {
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
        } else {
            show_error(isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'该订单没有上传Bd文件');
            exit();
        }
    }
}
