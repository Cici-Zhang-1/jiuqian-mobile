<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Administrator
 * @version
 * @des
 * 优化列表
 */
class Optimize extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    private $_Cookie;
    private $Search = array(
        'sort' => '0',
        'optimize' => '1',
        'product' => '1',
        'keyword' => '',
    );
    private $_ExcelTitle = array(
        '序号' => 'no',
        '名称' => 'plate_name',
        '颜色' => 'color',
        '材料' => 'board',
        '长度' => 'length',
        '宽度' => 'width',
        '厚度' => 'thick',
        '数量' => 'num',
        '封边' => 'edge',
        '备注' => 'remark',
        '打孔分类' => 'punch',
        '开槽信息' => 'slot',
        '客户地址' => 'owner',
        '柜体位置' => 'cubicle_num',
        '经销商名称' => 'dealer',
        '流水号' => 'abnormity',
        '条形码' => 'qrcode',
        '订单号' => 'order_product_num',
        '包装号' => 'status',
        '批次号' => 'sn',
        '类别' => 'cubicle_name',
        '尺寸判定' => 'decide_size',
        '单双面' => 'face',
        '产品名称' => 'product',
        '成品长' => 'full_length',
        '成品宽' => 'full_width',
        '店名' => 'shop'
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_classify_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
        
        log_message('debug', 'Controller Produce/Optimize Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view($Item, $Data);
        }
    }

    public function read(){
        $Cookie = $this->_Cookie.__FUNCTION__;
        $this->Search = $this->get_page_conditions($Cookie, $this->Search);
        $Data = array();
        if(!empty($this->Search)){
            if(!!($Data = $this->order_product_classify_model->select_optimize($this->Search))){
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

    /**
     * 获得优化的编号-批次号
     */
    public function read_sn(){
        $OrderProductNum = $this->input->get('order_product_num', true);
        $OrderProductNum = trim($OrderProductNum);
        $OrderProductNum = strtoupper($OrderProductNum);
        $Length = ORDER_PREFIX + ORDER_SUFFIX;

        if(preg_match("/^(X|B)[\d]{{$Length},{$Length}}\-[A-Z][\d]{1,10}$/", $OrderProductNum)){
            $this->load->model('order/order_product_classify_model');
            if(!!($Data = $this->order_product_classify_model->select_sn($OrderProductNum))){
                $this->Success = '获取订单批次号成功!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要对应的订单批次号';;
            }
        }else{
            $this->Failue = '请填写正确的订单编号!';
        }
        $this->_return($Data);
    }

    public function download(){
        $Item = $this->_Item.__FUNCTION__;
        $Type = $this->uri->segment(4, 'preview');
        $Type = trim($Type);
        $Id = $this->input->get('id', true);
        $Id = explode(',', $Id);
        if(is_array($Id)){
            foreach ($Id as $key => $value){
                $Id[$key] = intval($value);
            }
            $FileName = date('YmdHis', time());
            $this->load->model('order/order_product_board_plate_model');
            if('preview' == $Type){
                $Query = $this->order_product_board_plate_model->select_optimize($Id);
            }elseif ('optimize' == $Type){
                if(!!($this->order_product_classify_model->update_optimize($Id, $FileName))){
                    $Query = $this->order_product_board_plate_model->select_optimize($Id);
                    
                    $this->load->library('workflow/workflow');
                    foreach ($Id as $key => $value){
                        /*由于在导出给cuterite时，当前状态和下一状态可能不一致，因此分别执行*/
                        if($this->workflow->initialize('order_product_classify', $Id)){
                            $this->workflow->optimize();
                        }else{
                            $this->Failue .= $this->workflow->get_failue();
                            $Query = false;
                            break;
                        }
                    }
                }else{
                    $Query = false;
                }
            }else{
                $this->Failue = '您要访问的内容不存在';
                $Query = false;
            }
            if(!!($Query)){
                $Set = array();
                $Set[] = array_keys($this->_ExcelTitle);
                foreach ($Query as $ikey => $ivalue){
                    $Value = array();
                    $ivalue['full_width'] = $ivalue['width'];
                    $ivalue['full_length'] = $ivalue['length'];
                    $ivalue['width'] = $ivalue['width'] - $ivalue['up_edge'] - $ivalue['down_edge'];
                    $ivalue['length'] = $ivalue['length'] - $ivalue['left_edge'] - $ivalue['right_edge'];
                    foreach ($this->_ExcelTitle as $kkey => $kvalue){
                        if(empty($kvalue)){
                            $Value[] = '';
                        }elseif(isset($ivalue[$kvalue])){
                            $Value[] = $ivalue[$kvalue];
                        }else{
                            $Value[] = $this->_default_download($kvalue, $ivalue);
                        }
                    }
                    $Set[] = $Value;
                }
                require_once APPPATH.'/third_party/PHPExcels/PHPExcel.php';
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("9000")
                    ->setLastModifiedBy("9000")
                    ->setTitle("9000CuteRite")
                    ->setSubject("9000CuteRite")
                    ->setDescription("CuteRite,电子锯")
                    ->setKeywords("9000 CuteRite")
                    ->setCategory("CuteRite");
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()
                    ->fromArray(
                        $Set,  // The data to set
                        NULL,        // Array values with this value will not be set
                        'A1'         // Top left coordinate of the worksheet range where
                    )->setTitle('9000CuteRite下料尺寸');

                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$FileName.'cuterite.xls"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
            }else {
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'优化导出失败';
            }
        }else{
            $this->Failue .= '请先选择订单';
        }
        $this->_return();
    }


    /**
     * 导出预处理的表格
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function produce_prehandle(){
        $Id = $this->input->get('id', true);
        $Id = explode(',', $Id);
        if(is_array($Id)){
            foreach ($Id as $key => $value){
                $Id[$key] = intval($value);
            }
            $FileName = date('YmdHis', time());
            $this->load->model('order/order_product_board_plate_model');

            $Query = $this->order_product_board_plate_model->select_optimize_produce_prehandle($Id);

            if(!!($Query)){
                $this->_out_put_excel($Query, $FileName);
            }else {
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'优化导出失败';
            }
        }else{
            $this->Failue .= '请先选择订单';
        }
        $this->_return();
    }

    /**
     * 预处理选择条目导出到Excel
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function produce_prehandled(){
        $Id = $this->input->get('id', true);
        $Id = explode(',', $Id);
        if(is_array($Id)){
            foreach ($Id as $key => $value){
                $Id[$key] = intval($value);
            }
            $FileName = date('YmdHis', time());
            $this->load->model('order/order_product_board_plate_model');

            $Query = $this->order_product_board_plate_model->select_optimize_produce_prehandled($Id);

            if(!!($Query)){
                $this->_out_put_excel($Query, $FileName);
            }else {
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'优化导出失败';
            }
        }else{
            $this->Failue .= '请先选择订单';
        }
        $this->_return();
    }

    private function _out_put_excel($Query, $FileName){
        $Set = array();
        $Set[] = array_keys($this->_ExcelTitle);
        foreach ($Query as $ikey => $ivalue){
            $Value = array();
            $ivalue['width'] = $ivalue['width'] - $ivalue['up_edge'] - $ivalue['down_edge'];
            $ivalue['length'] = $ivalue['length'] - $ivalue['left_edge'] - $ivalue['right_edge'];
            foreach ($this->_ExcelTitle as $kkey => $kvalue){
                if(empty($kvalue)){
                    $Value[] = '';
                }elseif(isset($ivalue[$kvalue])){
                    $Value[] = $ivalue[$kvalue];
                }else{
                    $Value[] = $this->_default_download($kvalue, $ivalue);
                }
            }
            $Set[] = $Value;
        }
        require_once APPPATH.'/third_party/PHPExcels/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("9000")
            ->setLastModifiedBy("9000")
            ->setTitle("9000CuteRite")
            ->setSubject("9000CuteRite")
            ->setDescription("CuteRite,电子锯")
            ->setKeywords("9000 CuteRite")
            ->setCategory("CuteRite");
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()
            ->fromArray(
                $Set,  // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
            )->setTitle('9000CuteRite下料尺寸');

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$FileName.'cuterite.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    private function _default_download($Name, $Tmp){
        static $No = 1, $Abnormity = array(), $Face = array();
        $Return = '';
        switch ($Name){
            case 'no':
                $Return = $No++;
                break;
            case 'color':
                $Return = $Tmp['board'];
                break;
            case 'num':
                $Return = 1;
                break;
            case 'dealer':
                if(mb_strlen($Tmp['dealers']) > 10){
                    $Dealers = explode('_', $Tmp['dealers']);
                    if(count($Dealers) > 2){
                        $Return = $Dealers[1];
                    }else{
                        $Return = $Tmp['dealers'];
                    }
                }else{
                    $Return = $Tmp['dealers'];
                }
                break;
            case 'face':
                if (empty($Face)) {
                    $this->load->helper('dismantle_helper');
                    $Face = get_face();
                }
                if (isset($Face[$Tmp['punch'] . $Tmp['slot']])) {
                    $Return = $Face[$Tmp['punch'] . $Tmp['slot']];
                }elseif (isset($Face[A_ALL . $Tmp['slot']])) {
                    $Return = $Face[A_ALL . $Tmp['slot']];
                }elseif (isset($Face[$Tmp['punch'] . A_ALL])) {
                    $Return = $Face[$Tmp['punch'] . A_ALL];
                }
        }
        return $Return;
    }

    /*private function _face() {
        $this->load->model('data/face_model');
        $Face = array();
        if (!!($Query = $this->face_model->select_face())) {
            foreach ($Query as $value) {
                $Face[$value['wardrobe_punch'] . $value['wardrobe_slot']] = $value['flag'];
            }
        }

        return $Face;
    }*/
}
