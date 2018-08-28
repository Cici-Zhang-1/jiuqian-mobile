<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/28
 * Time: 15:24
 */
/**
 * 2015年11月22日
 * @author Zhangcc
 * @version
 * @des
 * 上传文件
 */
class Upload extends MY_Controller {
    private $Success = '';
    private $Failue = '';

    private $_CurrentSheet;

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Upload Start!');
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

    public function add(){
        if(isset($_FILES['uploadForm']['size']) && $_FILES['uploadForm']['size'] != NULL){
            $config = array(
                'overwrite' => true,
                'allowed_types' => '*',
                'max_size' => '20000000',
                'max_filename' => '64',
                'remove_spaces' => true
            );

            $dirname = ROOTDIR.'upload/'.date('Y', time()).'/'.date('m', time()).'/'.date('d', time()).'/';
            if (!is_dir($dirname) && !mkdir($dirname, 0777, true)) {
                $this->Failue='权限不足, 目录不可写, 文件上传失败!';
            }
            $config['upload_path'] = $dirname;
            $this->load->library('upload',$config);
            if($this->upload->do_upload('uploadForm')){
                $FileInfo = $this->upload->data();
                if('.bd' == $FileInfo['file_ext']){
                    $this->_add_bds($FileInfo);
                } elseif ('.xls' == $FileInfo['file_ext'] || '.xlsx' == $FileInfo['file_ext']){
                    $this->_add_xls($FileInfo);
                } elseif ('.bmp' == $FileInfo['file_ext'] || '.jpg' == $FileInfo['file_ext']
                    || '.png' == $FileInfo['file_ext'] || '.gif' == $FileInfo['file_ext']
                    || '.jpeg' == $FileInfo['file_ext']){
                    $this->_add_drawing($FileInfo);
                } elseif('.saw' == $FileInfo['file_ext']){
                    $this->_add_saw($FileInfo);
                }else{
                    $this->Failue = '您上传的文件格式不正确';
                }
            }else{
                $this->Failue=$this->upload->display_errors('','');
            }
        }else{
            $this->Failue='您上传的是空文件!';
        }
        $this->_ajax_return();
    }

    /**
     * 上传Excel文件
     * @param $FileInfo
     * @throws PHPExcel_Exception
     */
    private function _add_xls($FileInfo){
        if(is_array($FileInfo) && !empty($FileInfo)){
            $savePath = $FileInfo['full_path'];
            require_once APPPATH.'third_party/PHPExcels/PHPExcel.php';
            log_message('error',$savePath);
            $PHPExcel = PHPExcel_IOFactory::load($savePath);
            $this->_CurrentSheet = $PHPExcel->getSheet(0);
            $value = (String)$this->_CurrentSheet->getCell('A1')->getValue();
            if('序号' == $value){
                // $this->_add_cuterite();
            }elseif (strstr($value, '木框门')){
                // $this->_add_wood();
            }elseif (strstr($value, '手工单')){
                $this->_add_manu();
            }elseif ('客户姓名' == $value || '自产门板清单' == $value) {
                $this->_add_excel_list();
            }elseif (preg_match('/[X|B|x|b][\d]{10}-[p|P][\d]+\+?/i', $FileInfo['raw_name'], $matches)) {
                $this->_add_fittings_by_excel($matches[0]);
            }else {
                $this->Message= '上传的Excel没有对应的处理过程';
            }
        }else{
            $this->Message="文件上传失败!";
        }
    }

    /**
     * 通过Excel添加板材清单
     */
    private function _add_excel_list() {
        $AllRow = $this->_CurrentSheet->getHighestRow();
        if ($AllRow >= 3) {
            $OrderProductNum = (string)$this->_CurrentSheet->getCell('B3')->getValue();
            if (preg_match('/^[xbXB][\d]{10}-[wWyYmMpPfgGkKfF][\d]{1,}$/i', $OrderProductNum)) {
                if (preg_match('/[wy]/i', $OrderProductNum)) {
                    $this->_add_plate_by_excel($AllRow, $OrderProductNum);
                } else {
                    $OrderProductNum = (string)$this->_CurrentSheet->getCell('G2')->getValue();
                    if (preg_match('/m/i', $OrderProductNum)) {
                        $this->_add_m_by_excel($AllRow, $OrderProductNum);
                    }else {
                        $this->Failue = 'Sorry! 没有找到对应的订单编号!';
                    }
                }
            } else {
                $this->Message = 'Sorry! 订单编号不正确' . $OrderProductNum;
            }
        }else {
            $this->Message = '文件中没有有效的数据!';
        }
    }

    private function _add_plate_by_excel($AllRow, $OrderProductNum) {
        $this->load->model('order/order_product_model');
        $OrderProductNum = trim($OrderProductNum);
        $OrderProductNum = gh_escape($OrderProductNum);

        if (!!($OrderProduct = $this->order_product_model->is_dismantlable(ZERO, $OrderProductNum))) {
            $_POST['v'] = $OrderProduct['order_product_id'];
            $Product = (string)$this->_CurrentSheet->getCell('H1')->getValue();
            $_POST['product'] = trim($Product);
            $_POST['bd'] = YES;
            $Plate = array();
            for ($index = 3; $index <= $AllRow; $index++) {
                $PlateNum = (int)$this->_CurrentSheet->getCell('A' . $index)->getValue();
                if ($PlateNum > 0) {
                    $Remark = (string)$this->_CurrentSheet->getCell('X' . $index)->getValue();
                    if ($this->_need_removed($Remark)) {
                        continue;
                    }
                    $DecideSize = (string)$this->_CurrentSheet->getCell('Y' . $index)->getValue();
                    $WidthFull = (float)$this->_CurrentSheet->getCell('P' . $index)->getValue();
                    $LengthFull = (float)$this->_CurrentSheet->getCell('O' . $index)->getValue();
                    $EdgeString = (string)$this->_CurrentSheet->getCell('T' . $index)->getValue();
                    $Thick = (int)$this->_CurrentSheet->getCell('N' . $index)->getValue();
                    $Data = array(
                        'thick' => $Thick,
                        'length' => $LengthFull,
                        'width' => $WidthFull,  //成型尺寸
                        'cubicle_num' => (int)$this->_CurrentSheet->getCell('E' . $index)->getValue(),
                        'cubicle_name' => (string)$this->_CurrentSheet->getCell('H' . $index)->getValue(),
                        'plate_name' => (string)$this->_CurrentSheet->getCell('I' . $index)->getValue(),
                        'plate_num' => (int)$this->_CurrentSheet->getCell('A' . $index)->getValue(),
                        'edge' => $EdgeString,  //封边
                        'remark' => $Remark,
                        'decide_size' => $DecideSize,  //尺寸判定
                        'slot' => (string)$this->_CurrentSheet->getCell('U' . $index)->getValue(), //开槽
                        'punch' => (string)$this->_CurrentSheet->getCell('V' . $index)->getValue(),  //打孔
                        'qrcode' => trim((string)$this->_CurrentSheet->getCell('Z' . $index)->getValue()),  /*二维码*/
                        'bd_file' => '',  /*BD文件*/
                        'amount' => ONE
                    );

                    $Data['area'] = ceil($Data['length'] * $Data['width']/M_ONE)/M_TWO;

                    $Mat = trim((string)$this->_CurrentSheet->getCell('J' . $index)->getValue());
                    $Color = trim((string)$this->_CurrentSheet->getCell('K' . $index)->getValue());
                    $Data['board'] = $Data['thick'].$Mat.$Color;
                    array_push($Plate, $Data);
                }else {
                    $this->Message = 'Excel清单文件上传成功';
                }
            }
            $this->load->library('d/d');
            if (!!($D = $this->d->initialize($OrderProduct['code']))) {
                $_POST['order_product_board_plate'] = $Plate;
                if (!($D->edit('dismantling'))) {
                    $this->Code = EXIT_ERROR;
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单板块拆单出错!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '订单产品拆单出错!';
            }
        } else {
            $this->Message .= $OrderProductNum . '当前订单已经确认拆单，请先返回订单，然后再上传';
            return false;
        }
    }

    /**
     * 通过Excel上传门板
     * @param $AllRow
     * @param $OrderProductNum
     */
    private function _add_m_by_excel($AllRow, $OrderProductNum) {
        $this->load->model('order/order_product_model');
        $OrderProductNum = trim($OrderProductNum);
        $OrderProductNum = gh_escape($OrderProductNum);

        if (!!($OrderProduct = $this->order_product_model->is_dismantlable($OrderProductNum))) {
            $_POST['v'] = $OrderProduct['order_product_id'];
            $Plate = array();
            for ($index = 21; $index <= $AllRow; $index++) {
                $DoorName = $this->_CurrentSheet->getCell('A' . $index)->getValue();
                if (!empty($DoorName)) {
                    $Remark = (string)$this->_CurrentSheet->getCell('K' . $index)->getValue();
                    $EdgeString = (string)$this->_CurrentSheet->getCell('J' . $index)->getValue();
                    $Thick = (int)$this->_CurrentSheet->getCell('G' . $index)->getValue();
                    $Data = array(
                        'thick' => $Thick,
                        'length' => (float)$this->_CurrentSheet->getCell('E' . $index)->getValue(),
                        'width' => (float)$this->_CurrentSheet->getCell('F' . $index)->getValue(),  //成型尺寸
                        'handle' => $EdgeString,  //封边
                        'remark' => $Remark,    //备注
                        'punch' => '',  //打孔
                        'board' => trim((string)$this->_CurrentSheet->getCell('C' . $index)->getValue()),
                        'open_hole' => 0,
                        'invisibility' => 0,
                        'amount' => ONE
                    );

                    $Data['area'] = ceil($Data['length'] * $Data['width']/M_ONE)/M_TWO;
                    array_push($Plate, $Data);
                }else {
                    break;
                }
            }
            if (!empty($Plate)) {
                $_POST['order_product_board_plate'] = $Plate;
                $this->load->library('d/d');
                if (!!($D = $this->d->initialize($OrderProduct['code']))) {
                    if (!($D->edit('dismantling'))) {
                        $this->Code = EXIT_ERROR;
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'门板订单板块拆单出错!';
                    }
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '订单产品门板拆单出错!';
                }
            } else {
                $this->Message = '没有有效门板板块!';
            }
        } else {
            $this->Message .= '当前订单已经确认拆单，请先返回订单，然后再上传';
            return false;
        }
    }

    private function _need_removed($Remark) {
        if (preg_match('/自产门板|外发门板|外购/', $Remark)) {
            return true;
        }else {
            return false;
        }
    }

    /**
     * 通过Excel清单添加配件清单
     */
    private function _add_fittings_by_excel($OrderProductNum) {
        $this->load->model('order/order_product_model');

        $allRow = $this->_CurrentSheet->getHighestRow();
        if ($allRow > 4) {
            $OrderProductNum = rtrim($OrderProductNum, '+');
            if (!!($OrderProduct = $this->order_product_model->is_dismantlable(ZERO, $OrderProductNum))) {
                $_POST['v'] = $OrderProduct['order_product_id'];
                $Plate = array();
                for ($index = 5; $index <= $allRow; $index++) {
                    $Num = $this->_CurrentSheet->getCell('A' . $index)->getValue();
                    $Num = intval(trim($Num));
                    if ($Num > 0) {
                        $FittingName = (string)$this->_CurrentSheet->getCell('B' . $index)->getValue(); //配件名称
                        $FittingName = trim($FittingName);
                        $Speci = (string)$this->_CurrentSheet->getCell('E' . $index)->getValue();  //规格型号
                        $Speci = trim($Speci);
                        $Amount = (int)$this->_CurrentSheet->getCell('F'. $index)->getValue();   //数量
                        $Remark = (string)$this->_CurrentSheet->getCell('G'. $index)->getValue();   //备注
                        $Remark = trim($Remark);
                        $Data = array(
                            'fitting' => $FittingName,
                            'speci' => $Speci,
                            'amount' => $Amount,
                            'remark' => $Remark
                        );
                        array_push($Plate, $Data);
                    }else {
                        break;
                    }
                }
                if (!empty($Plate)) {
                    $_POST['order_product_board_plate'] = $Plate;
                    $this->load->library('d/d');
                    if (!!($D = $this->d->initialize($OrderProduct['code']))) {
                        if (!($D->edit('dismantling'))) {
                            $this->Code = EXIT_ERROR;
                            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单板块拆单出错!';
                        }
                    } else {
                        $this->Code = EXIT_ERROR;
                        $this->Message = '订单产品拆单出错!';
                    }
                } else {
                    $this->Message = '没有有效板块!';
                }
            } else {
                $this->Message = '您上传的配件订单不存在，或者已经确认拆单';
                return false;
            }
        }else {
            $this->Message = '您上传的配件表格中没有可识别的有效数据';
            return false;
        }
    }

    /**
     * 手工单导入
     */
    private function _add_manu(){
        $this->load->model('order/order_product_model');
        $allRow = $this->_CurrentSheet->getHighestRow();
        $colNum = $this->_CurrentSheet->getHighestColumn();
        $allCol = 0;
        for($i=0; $i< strlen($colNum); $i++){
            $allCol = $allCol*10 + ord(substr($colNum, $i, 1))-65;
        }
        if ($allRow <= 6) {
            $this->Message='文件中不包含有效数据!';
            return false;
        } else {
            $OrderProductNum = (String)$this->_CurrentSheet->getCell('K3')->getValue();  /*订单产品编号*/
            $Product = (String)$this->_CurrentSheet->getCell('C4')->getValue();            /*订单产品名称*/
            $OrderProductNum = trim($OrderProductNum);
            $Product = trim($Product);

            if(!empty($OrderProductNum)){
                if (!!($OrderProduct = $this->order_product_model->is_dismantlable(ZERO, $OrderProductNum))) {
                    $_POST['v'] = $OrderProduct['order_product_id'];
                    $_POST['product'] = $Product;
                    $Plate = array();
                    for($currentRow = 7; $currentRow <= $allRow; $currentRow++){
                        $Sn = (String)$this->_CurrentSheet->getCell('A'.$currentRow)->getValue();
                        $PlateName = (String)$this->_CurrentSheet->getCell('B'.$currentRow)->getValue();

                        if(!empty($Sn) && !empty($PlateName)){
                            $Data = array(
                                'plate_name' => $PlateName,
                                'width' => (Float)$this->_CurrentSheet->getCell('D'.$currentRow)->getValue(),
                                'length' => (Float)(Float)$this->_CurrentSheet->getCell('C'.$currentRow)->getValue(),
                                'thick' => (Int)$this->_CurrentSheet->getCell('G'.$currentRow)->getValue(),
                                'edge' => (String)$this->_CurrentSheet->getCell('H'.$currentRow)->getValue(),
                                'remark' => (String)$this->_CurrentSheet->getCell('J'.$currentRow)->getValue(),
                                'amount' => (Int)$this->_CurrentSheet->getCell('E'.$currentRow)->getValue(),
                                'qrcode' => null,
                                'bd_file' => null,
                                'slot' => '',
                                'punch' => '',
                                'decide_size' => '',
                                'cubicle_name' => '',
                                'cubicle_num' => 0,
                                'plate_num' => 0
                            );
                            $Good = (String)$this->_CurrentSheet->getCell('I'.$currentRow)->getValue();
                            $Data['board'] = $Data['thick'].$Good;
                            $Data['area'] = ceil($Data['length'] * $Data['width']/M_ONE)/M_TWO;
                            if ($Data['area'] < MIN_AREA) {
                                $Data['area'] = MIN_AREA;
                            }
                            array_push($Plate, $Data);
                        } else {
                            break;
                        }
                    }
                    if (!empty($Plate)) {
                        $_POST['order_product_board_plate'] = $Plate;
                        $this->load->library('d/d');
                        if (!!($D = $this->d->initialize($OrderProduct['code']))) {
                            if (!($D->edit('dismantling'))) {
                                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单板块拆单出错!';
                            } else {
                                $this->Message = '手工单上传成功!';
                                return true;
                            }
                        } else {
                            $this->Message = '订单产品拆单出错!';
                        }
                    } else {
                        $this->Message = '没有有效板块!';
                    }
                } else {
                    $this->Message = '订单产品编号已经确认拆单或者作废, 不能拆单!';
                }
            }else{
                $this->Message = '上传的手工单Excel中不包含订单产品编号!';
            }
        }
        $this->Code = EXIT_ERROR;
        return false;
    }

    /**
     * 添加BD文件
     * @param $FileInfo
     * @return bool
     */
    private function _add_bds($FileInfo){
        if(is_array($FileInfo) && !empty($FileInfo)){
            $this->load->model('product/board_model');
            $this->load->model('order/order_product_model');
            $this->load->model('order/order_product_board_model');
            $this->load->model('order/order_product_board_plate_model');
            $savePath = $FileInfo['full_path'];
            $xml = @simplexml_load_file($savePath);
            if (false == $xml) {
                return $this->_add_bd($FileInfo);
            }else {
                $Left = floatval($xml['LFB']);
                $Right = floatval($xml['RFB']);
                $Up = floatval($xml['UFB']);
                $Down = floatval($xml['DFB']);

                $Length = $xml['L'];
                $Width = $xml['W'];
                if(2 == $xml['CncBack1']){
                    list($Length, $Width) = array($Width, $Length);
                    list($Left, $Right, $Down, $Up) = array($Up, $Down, $Left, $Right);
                }
                $Width = $Width - $Up - $Down;
                $Length = $Length - $Left - $Right;
                unset($Up, $Down, $Left, $Right);
                $Edge = $this->_get_source_edge_thick((string)$xml['FBSTR'], (int)$xml['BH']);
                $Width = $Width + $Edge['up_edge'] + $Edge['down_edge'];
                $Length = $Length + $Edge['left_edge'] + $Edge['right_edge'];
                $Edge = $this->_get_edge_thick((string)$xml['FBSTR'], (int)$xml['BH']);

                $KcFaceInfo = '';
                if($xml->FaceA->Cut->count() > 0){
                    $KcFaceInfo .= 'KA';
                }
                if($xml->FaceB->Cut->count() > 0){
                    $KcFaceInfo .= 'KB';
                }
                $RawName = $FileInfo['raw_name'];
                $RawNames = explode('-', $RawName);
                $RawName = array_pop($RawNames);
                $PlateNum = substr($RawName, strlen($RawName)-3);
                $CubicleNum = substr($RawName, 0, strlen($RawName)-3);
                if(($Length > MAX_LENGTH || $Width > MAX_LENGTH) || ($Length > MAX_WIDTH && $Width > MAX_WIDTH)){
                    $this->Message .= $xml['ORDER'].'的板块尺寸太长';
                    return false;
                }
                $Product = gh_escape((string)$xml['DESNO']);
                $OrderProductNum = (string)$xml['ORDER']; /*订单产品编号*/
                if(empty($OrderProductNum)){
                    $OrderProductNum = implode('-', $RawNames);
                }
                $Data = array(
                    'thick' => (int)$xml['BH'],
                    'length' => $Length,
                    'width' => $Width,  //成型尺寸
                    'left_edge' => $Edge['left_edge'],
                    'right_edge' => $Edge['right_edge'],
                    'up_edge' => $Edge['up_edge'],
                    'down_edge' => $Edge['down_edge'],
                    'cubicle_num' => $CubicleNum,
                    'cubicle_name' => (string)$xml['TYPE'],
                    'plate_name' => (string)$xml['NAME'],
                    'plate_num' => $PlateNum,
                    'edge' => (string)$xml['FBSTR'],
                    'remark' => (string)$xml['MEMO'],
                    'abnormity' => $this->_is_abnormity($xml['MEMO']),
                    'slot' => $xml['KcStr'].$KcFaceInfo.$xml['KcFlag'],
                    'punch' => (string)$xml['BomStd'],
                    'qrcode' => $FileInfo['raw_name'],  /*二维码*/
                    'bd_file' => $FileInfo['full_path']  /*BD文件*/
                );

                $Data['area'] = ceil($Length * $Width/M_ONE)/M_TWO;
                if ($Data['area'] < MIN_AREA) {
                    $Data['area'] = MIN_AREA;
                }
                //$Data['area'] = ceil($Length * $Width/1000)/1000;
                $Data = gh_escape($Data);

                $OrderProductNum = gh_escape($OrderProductNum);

                $Opid = $this->order_product_model->is_exist_by_num($OrderProductNum);
                if(is_array($Opid) && (1 == $Opid['status'] || 2 == $Opid['status'])){ /*是否处于已建立并且处于拆单状态*/
                    /*更新订单产品信息*/
                    $OrderProductPostData = array(
                        'product' => $Product,
                        'bd' => 1,
                        'dismantler' => $this->input->cookie('uid')
                    );
                    $this->order_product_model->update($OrderProductPostData, $Opid);

                    $Opid = $Opid['opid'];

                    $Board = array(
                        'opid' => $Opid,
                        'board' => $xml['BH'].$xml['Mat'].$xml['Color'],
                        'amount' => 1,
                        'area' => $Data['area']
                    );
                    $Board = gh_escape($Board);

                    if($this->board_model->select_board_id($Board['board'])){
                        if(!!($Opbpid = $this->order_product_board_plate_model->is_uploaded($Data['qrcode']))){
                            /*之前已经上传*/
                            if(!!($this->order_product_board_plate_model->update($Data, $Opbpid['opbpid']))){
                                $Opbid = array(
                                    'area' => $Opbpid['area'] + ($Data['area'] - $Opbpid['plate_area'])
                                );
                                if(!!($this->order_product_board_model->update($Opbid, $Opbpid['opbid']))){
                                    $this->Success = $FileInfo['raw_name'].'文件上传成功';
                                }else{
                                    $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'BD文件上传失败失败!';
                                }
                            }else{
                                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'BD文件上传失败失败!';
                            }
                        }else{
                            if(!!($Opbid = $this->order_product_board_model->is_exist($Opid, $Board['board']))){
                                $Data['opbid'] = $Opbid['opbid'];
                                unset($Opbid['opbid']);
                                $Opbid['amount'] += 1;
                                $Opbid['area'] += $Data['area'];
                                $this->order_product_board_model->update($Opbid, $Data['opbid']);
                            }else{
                                $Data['opbid'] = $this->order_product_board_model->insert($Board);
                            }
                            unset($Board);
                            if(!!($this->order_product_board_plate_model->insert($Data))){
                                $this->load->library('workflow/workflow');
                                if($this->workflow->initialize('order_product', $Opid)){
                                    $this->workflow->dismantle();
                                    $this->Success = $FileInfo['raw_name'].'文件上传成功';
                                    return true;
                                }else{
                                    $this->Message = $this->workflow->get_failue();
                                }
                            }else{
                                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'BD文件上传失败失败!';
                            }
                        }
                    }else{
                        $this->Message .= '<strong>'.$Board['board'].'不在系统中, 请先登记板材!</strong>';
                    }
                }else{
                    $this->Message .= '当前订单已经确认拆单，请先返回订单，然后再上传';
                }
            }

        }else{
            $this->Message .= "文件上传失败!";
        }
        return false;
    }


    /**
     * 只上传BD文件
     * @param $FileInfo
     * @return bool
     */
    private function _add_bd($FileInfo) {
        if(is_array($FileInfo) && !empty($FileInfo)){
            $this->load->model('order/order_product_model');
            $this->load->model('order/order_product_board_plate_model');
            $Qrcode = $FileInfo['raw_name'];
            $Data = array(
                'bd_file' => $FileInfo['full_path']
            );
            $Qrcode = gh_escape($Qrcode);
            $Data = gh_escape($Data);
            $OrderProductNum = explode('-', $FileInfo['raw_name']);
            array_pop($OrderProductNum);
            $OrderProductNum = implode('-', $OrderProductNum);
            if (!!($OrderProduct = $this->order_product_model->is_exist($OrderProductNum))) {
                $OrderProductPostData = array(
                    'bd' => YES
                );
                $this->order_product_model->update($OrderProductPostData, $OrderProduct['v']);
                if (!!($OrderProductBoardPlate = $this->order_product_board_plate_model->is_uploaded($Qrcode))) {
                    $this->order_product_board_plate_model->update($Data, $OrderProductBoardPlate['v']);
                    $this->Success = $FileInfo['raw_name'] . '文件上传成功';
                    return true;
                }else {
                    $this->Message .= "您上传的Bd文件". $Data['qrcode'] . '在系统中没有相对应的数据，所以上传失败！';
                    unlink($FileInfo['full_path']);
                }
            } else {
                $this->Message .= "您上传的Bd文件". $Data['qrcode'] . '在系统中没有相对应的数据，所以上传失败！';
                unlink($FileInfo['full_path']);
            }
        }else{
            $this->Message .= "文件上传失败!";
        }
        return false;
    }

    /**
     * 添加图纸
     * @param $FileInfo
     * @return bool|void
     */
    private function _add_drawing($FileInfo){
        if(is_array($FileInfo) && !empty($FileInfo)){
            $this->load->model('drawing/drawing_model');
            $this->load->model('order/order_product_model');
            $FileName = $FileInfo['raw_name'];
            $FileName = strtoupper($FileName);
            $FileNames = explode('-', $FileName);
            $Drawing = array();
            if(count($FileNames) < 2){
                $this->Message = '图纸文件名不正确，请重新上传';
                return false;
            }elseif (count($FileNames) >= 4){
                $Drawing['type'] = 1;
            }else{
                $Drawing['type'] = 0;
            }
            if(!!($this->drawing_model->update_drawing($FileName))){
                $this->Message = $FileName.'图纸上传成功';
            }else{
                $OrderProductNum = implode('-', array_slice($FileNames, 0, 2));
                if (!!($OrderProduct = $this->order_product_model->is_exist($OrderProductNum))) {
                    $Drawing = array(
                        'order_product_id' => $OrderProduct['v'],
                        'name' => $FileName,
                        'path' => $FileInfo['full_path']
                    );
                    if (!!($this->drawing_model->insert($Drawing))) {
                        $this->Message = $FileName.'图纸上传成功';
                    } else {
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'图纸上传失败!';
                    }
                } else {
                    $this->Message = '当前订单不存在';
                }
            }
        }else{
            $this->Message .= "文件上传失败!";
        }
    }

    private function _add_saw($FileInfo) {
        if(is_array($FileInfo) && !empty($FileInfo)){
            $this->load->model('order/order_product_classify_model');
            $this->load->model('order/mrp_model');
            $handle = @fopen($FileInfo['full_path'], "r");
            $BatchName =  $FileInfo['raw_name'];
            $Saw = array();
            if ($handle) {
                while (($buffer = fgets($handle)) !== false) {
                    if (preg_match('/^MAT2.*$/', $buffer, $Matches)) {
                        array_push($Saw, $Matches[0]);
                    }
                }
                if (!feof($handle)) {
                    echo "Error: unexpected fgets() fail\n";
                }
                fclose($handle);
            }
            if (!empty($Saw)) {
                $NewIds = array();
                $this->load->library('workflow/workflow_mrp/workflow_mrp');
                foreach ($Saw as $Key => $Value) {
                    $Item = explode(',', $Value);
                    $Board = iconv('GBK','UTF-8', trim($Item[1]));    //获取板块信息
                    if (!!($Classify = $this->order_product_classify_model->is_exist_batch_num($BatchName, $Board))) {  // 判断是否是Classify
                        if (!!($this->mrp_model->is_exist_batch_num($BatchName, $Board))) { // 判断是否是已经建立的Classify
                            $this->Message .= '系统中已经建立了' . $BatchName . $Board . '的MRP, 请手动修改!';
                        } else {
                            $Data = array(
                                'batch_num' => $BatchName,
                                'board' => $Board,
                                'num' => trim($Item[50]),
                                'status' => M_SHEAR
                            );
                            if(!!($NewId = $this->mrp_model->insert($Data))) {
                                $GLOBALS['workflow_msg'] = $BatchName . $Board;
                                if(!!($this->workflow_mrp->initialize($NewId))){
                                    $this->workflow_mrp->shear();
                                    $this->Success .= $BatchName . $Board . '上传成功! <br />';
                                }else{
                                    $this->Message .= $this->workflow_mrp->get_failue();
                                }
                            }else{
                                $this->Message .= $BatchName . $Board . '新建失败!';
                            }
                        }
                    } else {
                        $this->Message .= '系统中没有对应批次号' . $BatchName . $Board;
                    }
                }
            }
        }else{
            $this->Message .= "文件上传失败!";
        }

        return false;
    }
}