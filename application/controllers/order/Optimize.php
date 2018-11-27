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
    private $__Search = array(
        'status' => NO,
        'product_id' => 1
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
        log_message('debug', 'Controller Produce/Optimize Start!');
        $this->load->model('order/order_product_classify_model');
        $this->load->model('order/order_product_model');
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
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->order_product_classify_model->select_optimize($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
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

    private function _pre_handle () {
        $V = $this->input->get('v', true);
        if (!is_array($V)) {
            $V = explode(',', $V);
        }
        foreach ($V as $Key => $Value) {
            $V[$Key] = intval(trim($Value));
        }
        $_POST['v'] = $V;
        return true;
    }
    public function pre_download () {
        if ($this->_pre_handle()) {
            $V = $_POST['v'];
            $FileName = date('Y-m-d H:i:s');
            $this->load->model('order/order_product_board_plate_model');
            if (!!($Query = $this->order_product_board_plate_model->select_optimize($V))) {
                $this->_write_to_excel($Query, $FileName);
            } else {
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'优化导出失败';
                $this->Code = EXIT_ERROR;
            }
        } else {
            echo 111;
        }
        $this->_ajax_return();
    }

    public function download(){
        if ($this->_pre_handle()) {
            $V = $_POST['v'];
            $FileName = date('Y-m-d H:i:s');
            $this->load->model('order/order_product_board_plate_model');
            if (!!($this->order_product_classify_model->update_optimize($V, $FileName))) {
                if (!!($Query = $this->order_product_board_plate_model->select_optimize($V))) {
                    $this->load->library('workflow/workflow');
                    $W = $this->workflow->initialize('order_product_classify');
                    foreach ($V as $Value) {
                        $W->initialize($Value);
                        $W->optimize();
                    }
                    $this->_write_to_excel($Query, $FileName);
                    /*$W->initialize($V);
                    if ($W->optimize()) {
                        $this->_write_to_excel($Query, $FileName);
                    } else {
                        $this->Message .= $W->get_failue();
                        $this->Code = EXIT_ERROR;
                    }*/
                } else {
                    $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'优化导出失败';
                    $this->Code = EXIT_ERROR;
                }
            } else {
                $this->Message .= '优化时，预处理板块清单时发生错误!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    private function _write_to_excel ($BoardPlate, $FileName) {
        $Set = array();
        $Set[] = array_keys($this->_ExcelTitle);
        foreach ($BoardPlate as $ikey => $ivalue){
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
        require_once APPPATH.'third_party/PHPExcels/PHPExcel.php';
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
            /*case 'dealer':
                if(mb_strlen($Tmp['dealers']) > 10){
                    $Dealers = explode('_', $Tmp['dealers']);
                    if(count($Dealers) > 4){
                        $Return = $Dealers[3];
                    }else{
                        $Return = $Tmp['dealers'];
                    }
                }else{
                    $Return = $Tmp['dealers'];
                }
                break;*/
            case 'face':
                if (empty($Face)) {
                    $this->load->helper('dismantle_helper');
                    $Face = $this->_get_face();
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
    private function _get_face () {
        $this->load->model('data/face_model');
        $Face = array();
        if (!!($Query = $this->face_model->select())) {
            foreach ($Query as $value) {
                $Face[$value['punch'] . $value['slot']] = $value['flag'];
            }
        }
        return $Face;
    }

    /**
     * 走推台锯，不走优化
     */
    public function to_board () {
        $V = $this->input->post('v', true);
        if (!is_array($V)) {
            $V = explode(',', $V);
        }
        foreach ($V as $Key => $Value) {
            $V[$Key] = intval(trim($Value));
        }
        $this->load->model('order/order_product_classify_model');
        if (!!($Query = $this->order_product_classify_model->are_to_able($V))) {
            foreach ($Query as $Key => $Value) {
                $Query[$Key] = $Value['order_product_id'];
            }
            $this->load->library('workflow/workflow');
            $W = $this->workflow->initialize('order_product');
            if ($W->initialize($Query)) {
                if ($W->to_board()) {
                    $this->Message = '已转推台锯';
                } else {
                    $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']: $W->get_failue();
                    $this->Code = EXIT_ERROR;
                }
            } else {
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']: $W->get_failue();
                $this->Code = EXIT_ERROR;
            }
        } else {
            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'当前状态不可以转换';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return();
    }
}
