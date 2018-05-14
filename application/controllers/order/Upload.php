<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月22日
 * @author Zhangcc
 * @version
 * @des
 * 上传文件
 */
class Upload extends CI_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    
    private $Success = '';
    private $Failue = '';
    
    private $_FileInfo;
    private $_Json;

    private $_CurrentSheet;

    public function __construct(){
        parent::__construct();
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        
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
        if(isset($_FILES['orderUploadAddForm']['size']) && $_FILES['orderUploadAddForm']['size'] != NULL){
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
            if($this->upload->do_upload('orderUploadAddForm')){
                $FileInfo = $this->upload->data();
                if('.bd' == $FileInfo['file_ext']){
                    $this->_add_bds($FileInfo);
                }elseif ('.xls' == $FileInfo['file_ext'] || '.xlsx' == $FileInfo['file_ext']){
                    $this->_add_xls($FileInfo);
                }elseif ('.bmp' == $FileInfo['file_ext'] || '.jpg' == $FileInfo['file_ext'] 
                    || '.png' == $FileInfo['file_ext'] || '.gif' == $FileInfo['file_ext'] 
                    || '.jpeg' == $FileInfo['file_ext']){
                    $this->_add_drawing($FileInfo);
                }else{
                    $this->Failue = '您上传的文件格式不正确';
                }
            }else{
                $this->Failue=$this->upload->display_errors('','');
            }
        }else{
            $this->Failue='您上传的是空文件!';
        }
        $this->_return();
    }

    /**
     * 上传Excel文件
     * @param $FileInfo
     * @throws PHPExcel_Exception
     */
    private function _add_xls($FileInfo){
        if(is_array($FileInfo) && !empty($FileInfo)){
            $savePath = $FileInfo['full_path'];
            require_once APPPATH.'/third_party/PHPExcels/PHPExcel.php';
            log_message('error',$savePath);
            $PHPExcel = PHPExcel_IOFactory::load($savePath);
            $this->_CurrentSheet = $PHPExcel->getSheet(0);
            $value = (String)$this->_CurrentSheet->getCell('A1')->getValue();
            if('序号' == $value){
                $this->_add_cuterite();
            }elseif (strstr($value, '木框门')){
                $this->_add_wood();
            }elseif (strstr($value, '手工单')){
                $this->_add_manu();
            }elseif ('客户姓名' == $value || '自产门板清单' == $value) {
                $this->_add_excel_list();
            }elseif (preg_match('/p[\d]+/i', $FileInfo['raw_name'])) {
                $this->_add_fittings_by_excel($FileInfo['raw_name']);
            }else {
                $this->Failue= '上传的Excel没有对应的处理过程';
            }
        }else{
            $this->Failue="文件上传失败!";
        }
    }

    /**
     * 通过Excel添加板材清单
     */
    private function _add_excel_list() {
        $AllRow = $this->_CurrentSheet->getHighestRow();
        if ($AllRow >= 3) {
            $OrderProductNum = (string)$this->_CurrentSheet->getCell('B3')->getValue();
            if (preg_match('/w/i', $OrderProductNum)) {
                $this->_add_w_by_excel($AllRow, $OrderProductNum);
            }elseif (preg_match('/y/i', $OrderProductNum)) {
                $this->_add_y_by_excel($AllRow, $OrderProductNum);
            }else {
                $OrderProductNum = (string)$this->_CurrentSheet->getCell('G2')->getValue();
                if (preg_match('/m/i', $OrderProductNum)) {
                    $this->_add_m_by_excel($AllRow, $OrderProductNum);
                }else {
                    $this->Failue = 'Sorry! 没有找到对应的订单编号!';
                }
            }
        }else {
            $this->Failue = '文件中没有有效的数据!';
        }
    }

    private function _add_w_by_excel($AllRow, $OrderProductNum) {
        $this->load->model('product/board_model');
        $this->load->model('order/order_product_model');
        $this->load->model('order/order_product_board_model');
        $this->load->model('order/order_product_board_plate_model');

        $OrderProductNum = trim($OrderProductNum);
        $OrderProductNum = gh_escape($OrderProductNum);

        $Product = (string)$this->_CurrentSheet->getCell('H1')->getValue();
        $Product = trim($Product);
        $Product = gh_escape($Product);
        for ($index = 3; $index <= $AllRow; $index++) {
            $PlateNum = (int)$this->_CurrentSheet->getCell('A' . $index)->getValue();
            if ($PlateNum > 0) {
                $Remark = (string)$this->_CurrentSheet->getCell('X' . $index)->getValue();
                if ($this->_need_removed($Remark)) {
                    continue;
                }
                $DecideSize = (string)$this->_CurrentSheet->getCell('Y' . $index)->getValue();
                $WidthFull = (int)$this->_CurrentSheet->getCell('P' . $index)->getValue();
                $LengthFull = (int)$this->_CurrentSheet->getCell('O' . $index)->getValue();
                $EdgeString = (string)$this->_CurrentSheet->getCell('T' . $index)->getValue();
                $Edge = $this->_get_edge_thick($EdgeString);
                $Data = array(
                    'thick' => (int)$this->_CurrentSheet->getCell('N' . $index)->getValue(),
                    'length' => $LengthFull,
                    'width' => $WidthFull,  //成型尺寸
                    'left_edge' => $Edge['left_edge'],  //封边厚度
                    'right_edge' => $Edge['right_edge'],
                    'up_edge' => $Edge['up_edge'],
                    'down_edge' => $Edge['down_edge'],
                    'cubicle_num' => (int)$this->_CurrentSheet->getCell('E' . $index)->getValue(),
                    'cubicle_name' => (string)$this->_CurrentSheet->getCell('H' . $index)->getValue(),
                    'plate_name' => (string)$this->_CurrentSheet->getCell('I' . $index)->getValue(),
                    'plate_num' => (int)$this->_CurrentSheet->getCell('A' . $index)->getValue(),
                    'edge' => $EdgeString,  //封边
                    'remark' => $Remark,
                    'decide_size' => $DecideSize,  //尺寸判定
                    'abnormity' => $this->_is_abnormity($Remark),  //异形
                    'slot' => (string)$this->_CurrentSheet->getCell('U' . $index)->getValue(), //开槽
                    'punch' => (string)$this->_CurrentSheet->getCell('V' . $index)->getValue(),  //打孔
                    'qrcode' => trim((string)$this->_CurrentSheet->getCell('Z' . $index)->getValue()),  /*二维码*/
                    'bd_file' => ''  /*BD文件*/
                );

                if(($LengthFull > 2436 || $WidthFull > 2436) || ($LengthFull > 1220 && $WidthFull > 1220)){
                    $this->Failue .= $Data['qrcode'].'的板块尺寸太长';
                    break;
                }

                $Data['area'] = ceil($Data['length'] * $Data['width']/M_ONE)/M_TWO;
                if ($Data['area'] < MIN_AREA) {
                    $Data['area'] = MIN_AREA;
                }
                //$Data['area'] = ceil($Data['length'] * $Data['width']/1000)/1000;
                $Data = gh_escape($Data);

                $Mat = (string)$this->_CurrentSheet->getCell('J' . $index)->getValue();
                $Color = (string)$this->_CurrentSheet->getCell('K' . $index)->getValue();

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
                        'board' => $Data['thick'].$Mat.$Color,
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
                                    $this->Success = 'Excel清单文件上传成功';
                                }else{
                                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'Excel清单文件上传失败失败!';
                                    break;
                                }
                            }else{
                                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'Excel清单文件上传失败失败!';
                                break;
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
                                    $this->Success = 'Excel清单文件上传成功';
                                }else{
                                    $this->Failue = $this->workflow->get_failue();
                                    break;
                                }
                            }else{
                                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'Excel清单文件上传失败失败!';
                                break;
                            }
                        }
                    }else{
                        $this->Failue .= '<strong>'.$Board['board'].'不在系统中, 请先登记板材!</strong>';
                        break;
                    }
                }else{
                    $this->Failue .= '当前订单已经确认拆单，请先返回订单，然后再上传';
                    break;
                }
            }else {
                $this->Success = 'Excel清单文件上传成功';
            }
        }
    }

    private function _add_y_by_excel($AllRow, $OrderProductNum) {
        $this->load->model('product/board_model');
        $this->load->model('order/order_product_model');
        $this->load->model('order/order_product_board_model');
        $this->load->model('order/order_product_board_plate_model');

        $OrderProductNum = trim($OrderProductNum);
        $OrderProductNum = gh_escape($OrderProductNum);

        $Product = (string)$this->_CurrentSheet->getCell('H1')->getValue();
        $Product = trim($Product);
        $Product = gh_escape($Product);
        for ($index = 3; $index <= $AllRow; $index++) {
            $PlateNum = (int)$this->_CurrentSheet->getCell('A' . $index)->getValue();
            if ($PlateNum > 0) {
                $Remark = (string)$this->_CurrentSheet->getCell('X' . $index)->getValue();
                if ($this->_need_removed($Remark)) {
                    continue;
                }
                $DecideSize = (string)$this->_CurrentSheet->getCell('Y' . $index)->getValue();
                $WidthFull = (int)$this->_CurrentSheet->getCell('P' . $index)->getValue();
                $LengthFull = (int)$this->_CurrentSheet->getCell('O' . $index)->getValue();
                $EdgeString = (string)$this->_CurrentSheet->getCell('T' . $index)->getValue();
                $Edge = $this->_get_edge_thick($EdgeString);
                $Data = array(
                    'thick' => (int)$this->_CurrentSheet->getCell('N' . $index)->getValue(),
                    'length' => $LengthFull,
                    'width' => $WidthFull,  //成型尺寸
                    'left_edge' => $Edge['left_edge'],  //封边厚度
                    'right_edge' => $Edge['right_edge'],
                    'up_edge' => $Edge['up_edge'],
                    'down_edge' => $Edge['down_edge'],
                    'cubicle_num' => (int)$this->_CurrentSheet->getCell('E' . $index)->getValue(),
                    'cubicle_name' => (string)$this->_CurrentSheet->getCell('H' . $index)->getValue(),
                    'plate_name' => (string)$this->_CurrentSheet->getCell('I' . $index)->getValue(),
                    'plate_num' => (int)$this->_CurrentSheet->getCell('A' . $index)->getValue(),
                    'edge' => $EdgeString,  //封边
                    'remark' => $Remark,
                    'decide_size' => $DecideSize,  //尺寸判定
                    'abnormity' => $this->_is_abnormity($Remark),  //异形
                    'slot' => (string)$this->_CurrentSheet->getCell('U' . $index)->getValue(), //开槽
                    'punch' => (string)$this->_CurrentSheet->getCell('V' . $index)->getValue(),  //打孔
                    'qrcode' => (string)$this->_CurrentSheet->getCell('Z' . $index)->getValue(),  /*二维码*/
                    'bd_file' => ''  /*BD文件*/
                );

                if(($LengthFull > 2436 || $WidthFull > 2436) || ($LengthFull > 1220 && $WidthFull > 1220)){
                    $this->Failue .= $Data['qrcode'].'的板块尺寸太长';
                    break;
                }

                $Data['area'] = ceil($Data['length'] * $Data['width']/M_ONE)/M_TWO;
                if ($Data['area'] < MIN_AREA) {
                    $Data['area'] = MIN_AREA;
                }
                //$Data['area'] = ceil($Data['length'] * $Data['width']/1000)/1000;
                $Data = gh_escape($Data);

                $Mat = (string)$this->_CurrentSheet->getCell('J' . $index)->getValue();
                $Color = (string)$this->_CurrentSheet->getCell('K' . $index)->getValue();

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
                        'board' => $Data['thick'].$Mat.$Color,
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
                                    $this->Success = 'Excel清单文件上传成功';
                                }else{
                                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'Excel清单文件上传失败失败!';
                                    break;
                                }
                            }else{
                                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'Excel清单文件上传失败失败!';
                                break;
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
                                    $this->Success = 'Excel清单文件上传成功';
                                }else{
                                    $this->Failue = $this->workflow->get_failue();
                                    break;
                                }
                            }else{
                                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'Excel清单文件上传失败失败!';
                                break;
                            }
                        }
                    }else{
                        $this->Failue .= '<strong>'.$Board['board'].'不在系统中, 请先登记板材!</strong>';
                        break;
                    }
                }else{
                    $this->Failue .= '当前订单已经确认拆单，请先返回订单，然后再上传';
                    break;
                }
            }else {
                $this->Success = 'Excel清单文件上传成功';
            }
        }
    }

    /**
     * 通过Excel上传门板
     * @param $AllRow
     * @param $OrderProductNum
     */
    private function _add_m_by_excel($AllRow, $OrderProductNum) {
        $this->load->model('product/board_model');
        $this->load->model('order/order_product_model');
        $this->load->model('order/order_product_board_model');
        $this->load->model('order/order_product_door_model');
        $this->load->model('order/order_product_board_door_model');

        $OrderProductNum = trim($OrderProductNum);
        $OrderProductNum = gh_escape($OrderProductNum);

        $Data = array();
        $Board = array(); //板材统计
        $Opbids = array(); //板材统计Id

        $Opid = $this->order_product_model->is_exist_by_num($OrderProductNum);

        if (is_array($Opid) && (1== $Opid['status'] || 2 == $Opid['status'])) {
            $OrderProductPostData = array(
                'dismantler' => $this->input->cookie('uid')
            );
            $this->order_product_model->update($OrderProductPostData, $Opid);
            $Opid = $Opid['opid'];

            for ($index = 21; $index <= $AllRow; $index++) {
                $DoorName = $this->_CurrentSheet->getCell('A' . $index)->getValue();
                if (!empty($DoorName)) {
                    $Remark = (string)$this->_CurrentSheet->getCell('K' . $index)->getValue();
                    $WidthFull = (int)$this->_CurrentSheet->getCell('F' . $index)->getValue();
                    $LengthFull = (int)$this->_CurrentSheet->getCell('E' . $index)->getValue();
                    $EdgeString = (string)$this->_CurrentSheet->getCell('J' . $index)->getValue();
                    $Edge = $this->_get_edge_thick($EdgeString);
                    $TmpData = array(
                        'thick' => (int)$this->_CurrentSheet->getCell('G' . $index)->getValue(),
                        'length' => $LengthFull,
                        'width' => $WidthFull,  //成型尺寸
                        'left_edge' => $Edge['left_edge'],  //封边厚度
                        'right_edge' => $Edge['right_edge'],
                        'up_edge' => $Edge['up_edge'],
                        'down_edge' => $Edge['down_edge'],
                        'edge' => $EdgeString,  //封边
                        'remark' => $Remark,    //备注
                        'slot' => '', //开槽
                        'punch' => '',  //打孔
                        'board' => (string)$this->_CurrentSheet->getCell('C' . $index)->getValue(),
                        'open_hole' => 0,
                        'invisibility' => 0
                    );

                    if(($LengthFull > 2436 || $WidthFull > 2436) || ($LengthFull > 1220 && $WidthFull > 1220)){
                        $this->Failue .= $DoorName.'的板块尺寸太长';
                        break;
                    }
                    if(!$this->board_model->select_board_id(gh_escape($TmpData['board']))){
                        $this->Failue = '<strong>'.$TmpData['board'].'不在系统中, 请先登记板材!</strong>';
                        break;
                    }
                    $TmpData['area'] = ceil($TmpData['length'] * $TmpData['width']/M_ONE)/M_TWO;
                    if ($TmpData['area'] < MIN_AREA) {
                        $TmpData['area'] = MIN_AREA;
                    }
                    //$TmpData['area'] = ceil($TmpData['length'] * $TmpData['width']/1000)/1000;

                    if(!isset($Board[$TmpData['board']])){
                        $Board[$TmpData['board']] = array(
                            'opid' => $Opid,
                            'board' => $TmpData['board'],
                            'amount' => 1,
                            'area' => $TmpData['area'],
                            'open_hole' => $TmpData['open_hole'],
                            'invisibility' => $TmpData['invisibility']
                        );
                        if(!($Board[$TmpData['board']]['opbid'] = $this->order_product_board_model->is_existed($Opid, gh_escape($TmpData['board'])))){
                            $Board[$TmpData['board']] = gh_escape($Board[$TmpData['board']]);
                            $Board[$TmpData['board']]['opbid'] = $this->order_product_board_model->insert($Board[$TmpData['board']]);
                        }/* else{ */
                        array_push($Opbids, $Board[$TmpData['board']]['opbid']);  //记录已经统计的板材的记录ID
                        /* } */
                    }else{
                        $Board[$TmpData['board']]['amount']++;
                        $Board[$TmpData['board']]['area'] += $TmpData['area'];
                        $Board[$TmpData['board']]['open_hole'] += $TmpData['open_hole'];
                        $Board[$TmpData['board']]['invisibility'] += $TmpData['invisibility'];
                    }
                    $TmpData['opbid'] = $Board[$TmpData['board']]['opbid'];

                    $TmpData = gh_escape($TmpData);
                    array_push($Data, $TmpData);
                }else {
                    break;
                }
            }

            if ('' == $this->Failue || empty($this->Failue)) {
                if (count($Data) > 0 && !empty($Data)) {
                    if(!empty($Opbids)){
                        $this->order_product_board_door_model->delete_by_opbid($Opbids)
                        && $this->order_product_board_model->delete_not_in($Opid, $Opbids);
                    }
                    if(!!($this->order_product_board_door_model->insert_batch($Data))
                        && !!($this->order_product_board_model->update_batch($Board))){
                        $this->load->library('workflow/workflow');
                        if($this->workflow->initialize('order_product', $Opid)){
                            $this->workflow->dismantle();
                            $this->Success = '门板Excel清单文件上传成功';
                        }else{
                            $this->Failue = $this->workflow->get_failue();
                        }
                    }else{
                        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'保存门板拆单板块失败!';
                    }
                }else {
                    $this->Failue = '当前门板清单中没有可上传的数据';
                }
            }
        }else {
            $this->Failue .= '当前订单已经确认拆单，请先返回订单，然后再上传';
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
        $this->load->model('order/order_product_fitting_model');
        $this->load->model('product/fitting_model');

        $allRow = $this->_CurrentSheet->getHighestRow();
        if ($allRow > 4) {
            $Opid = $this->order_product_model->is_exist_by_num($OrderProductNum);
            if(is_array($Opid) && (1 == $Opid['status'] || 2 == $Opid['status'])) { /*是否处于已建立并且处于拆单状态*/
                /*更新订单产品信息*/
                $OrderProductPostData = array(
                    'dismantler' => $this->input->cookie('uid')
                );
                $this->order_product_model->update($OrderProductPostData, $Opid);

                $Opid = $Opid['opid'];
                $this->order_product_fitting_model->delete_by_opid($Opid);  //删除原有存储的Fitting

                $Fittings = $this->_get_fittings();
                $Data = array();
                $DataTmp = array();
                for ($index = 5; $index <= $allRow; $index++) {
                    $Num = $this->_CurrentSheet->getCell('A' . $index)->getValue();
                    $Num = intval(trim($Num));
                    if ($Num > 0) {
                        $FittingName = (string)$this->_CurrentSheet->getCell('B' . $index)->getValue(); //配件名称
                        $FittingName = trim($FittingName);
                        $Specis = (string)$this->_CurrentSheet->getCell('E' . $index)->getValue();  //规格型号
                        $Specis = trim($Specis);
                        $Count = (int)$this->_CurrentSheet->getCell('F'. $index)->getValue();   //数量
                        $Count = trim($Count);
                        $Remark = (string)$this->_CurrentSheet->getCell('G'. $index)->getValue();   //备注
                        $Remark = trim($Remark);
                        $KeyName = $FittingName . '～' . $Remark;
                        if (isset($DataTmp[$KeyName])) {
                            if (preg_match('/^[123]{1,1}(\*[123]{1,1}(\*[123]{1,1})?)?$/', $Fittings[$FittingName]['speci'])) {
                                $Devides = explode('*', $Fittings[$FittingName]['speci']);
                                $Specis = explode('*', $Specis);
                                $Devide = array_pop($Devides);
                                $Amount = $Specis[--$Devide];
                                foreach ($Devides as $value) {
                                    $Amount = $Specis[$value] * $Amount;
                                }
                            }else {
                                $Amount = $Count;
                            }
                            if ('1' == $Fittings[$FittingName]['together']) {   //如果需要合并
                                $LastData = array_pop($DataTmp[$KeyName]);
                                $LastData['amount'] += $Amount;
                                array_push($DataTmp[$KeyName], $LastData);
                            }else {     //如果不需要合并
                                $LastData = current($DataTmp[$KeyName]);
                                $LastData['amount'] = $Amount;
                                for($I=0; $I < $Count; $I++) {
                                    array_push($DataTmp[$KeyName], $LastData);
                                }
                            }
                        }elseif (isset($Fittings[$FittingName])) {
                            if (preg_match('/^[123]{1,1}(\*[123]{1,1}(\*[123]{1,1})?)?$/', $Fittings[$FittingName]['speci'])) {
                                $Devides = explode('*', $Fittings[$FittingName]['speci']);
                                $Specis = explode('*', $Specis);
                                $Devide = array_pop($Devides);
                                $Amount = $Specis[--$Devide];
                                foreach ($Devides as $value) {
                                    $Amount = $Specis[$value] * $Amount;
                                }
                            }else {
                                $Amount = $Count;
                            }
                            $DataTmp[$KeyName] = array();
                            if ('1' == $Fittings[$FittingName]['together']) {
                                array_push($DataTmp[$KeyName], array(
                                    'amount' => $Amount,
                                    'unit' => $Fittings[$FittingName]['unit'],
                                    'fid' => $Fittings[$FittingName]['fid'],
                                    'remark' => $Remark,
                                    'opid' => $Opid
                                ));
                            }else {
                                for($I=0; $I < $Count; $I++) {
                                    array_push($DataTmp[$KeyName], array(
                                        'amount' => $Amount,
                                        'unit' => $Fittings[$FittingName]['unit'],
                                        'fid' => $Fittings[$FittingName]['fid'],
                                        'remark' => $Remark,
                                        'opid' => $Opid
                                    ));
                                }
                            }
                        }else {
                            continue;
                        }
                    }else {
                        break;
                    }
                }

                if (!empty($DataTmp)) {
                    foreach ($DataTmp as $key => $value) {
                        $Name = explode('～', $key);
                        $Name = array_shift($Name);
                        if (preg_match('/^\w\/\d+(\.\d+)?$/', $Fittings[$Name]['speci'], $Matches)) {   //计算需不需要换算，除以某个树枝
                            $Devide = explode('/', $Fittings[$FittingName]['speci']);
                            $Devide = array_pop($Devide);
                        }else {
                            $Devide = false;
                        }
                        foreach ($value as $ikey => $ivalue) {
                            $ivalue['name'] = $Name;
                            if (false !== $Devide) {
                                if (isset($Matches[1])) {
                                    $ivalue['amount'] = $ivalue['amount']/$Devide;      //如果有小数，就按小数除法
                                }else {
                                    $ivalue['amount'] = round($ivalue['amount']/$Devide);   //否则按进1计算
                                }
                            }
                            $Data[] = $ivalue;
                        }
                    }
                    unset($Fittings);
                    $this->order_product_fitting_model->insert_batch($Data);

                    $this->load->library('workflow/workflow');
                    if($this->workflow->initialize('order_product', $Opid)){
                        $this->workflow->dismantle();
                        $this->Success = 'Excel清单文件上传成功';
                    }else{
                        $this->Failue = $this->workflow->get_failue();
                    }
                }else {
                    $this->Failue = '您上传的配件表格中数据格式不正确，或者没有数据';
                }
            }else {
                $this->Failue = '您上传的配件订单不存在，或者已经确认拆单';
            }
        }else {
            $this->Failue = '您上传的配件表格中没有可识别的有效数据';
        }
    }

    /**
     * 获取数据库中的配件类型
     * @return array
     */
    private function _get_fittings() {
        $this->load->model('product/fitting_model');
        $Query = $this->fitting_model->select_fitting();
        $Fittings = array();
        if (!!$Query) {
            $Query = $Query['content'];
            foreach ($Query as $value) {
                $Fittings[$value['name']] = array(
                    'fid' => $value['fid'],
                    'unit' => $value['unit'],
                    'speci' => $value['speci'],
                    'together' => $value['together']
                );
            }
        }
        return $Fittings;
    }

    /**
     * 上传给Cutrite的Excel文件
     */
    private function _add_cuterite(){
        $this->Failue = '目前不支持优化Excel处理!';
    }

    /**
     * 上传木框门文件
     * @return bool
     */
    private function _add_wood(){
        $this->Failue = '暂时不支持处理木框门Excel';
        return false;
        
        $this->load->model('product/product_model');
        $this->load->model('order/order_model');
        $this->load->model('order/order_product_model');
        $this->load->model('order/order_product_door_model');
        $this->load->model('order/order_product_door_plate_model');
        $allRow = $this->_CurrentSheet->getHighestRow();
        $colNum = $this->_CurrentSheet->getHighestColumn();
        $allCol = 0;
        for($i=0; $i< strlen($colNum); $i++){
            $allCol = $allCol*10 + ord(substr($colNum, $i, 1))-65;
        }
        log_message('error', __LINE__.$allRow.'Col'.$allCol);
        if ($allRow <= 1) {
            $this->Failue='文件中不包含有效数据!';
        } else {
            $errorline = '';
            $postData = array();
            $sqlField = array();
            $this->config->load('formview/order');
            $OrderTitle = $this->config->item('order/upload/order/wood');
            $OrderProductTitle = $this->config->item('order/upload/order_product/wood');
            $OrderProductDoorTitle = $this->config->item('order/upload/order_product_door/wood');
            $OrderProductDoorPlateTitle = $this->config->item('order/upload/order_product_door_plate/wood');
    
            $OrderProductNum = (String)$this->_CurrentSheet->getCell('J2')->getValue(); //订单编号(Y-10-221W_4);
            $OrderProductNum = explode('_', $OrderProductNum);
            $CubicleNum = array_pop($OrderProductNum);
            $OrderProductNum = implode('_', $OrderProductNum);
            if(preg_match('/W$/', $OrderProductNum)){
                $OrderProductNum = substr($OrderProductNum, 0, strlen($OrderProductNum)-1);
            }
            if(empty($OrderProductNum)){
                $this->Failue = '上传的Excel中不包含订单编号';
                return false;
            }
            
            $Color = (String)$this->_CurrentSheet->getCell('J4')->getValue();  /*板材颜色*/
            if(empty($Color)){
                $this->Failue = '上传的Excel中不包含板材颜色';
                return false;
            }
        }
    }
    
    /**
     * 手工单导入
     */
    private function _add_manu(){
        $this->load->model('product/board_model');
        $this->load->model('order/order_product_model');
        $this->load->model('order/order_product_board_model');
        $this->load->model('order/order_product_board_plate_model');
        $allRow = $this->_CurrentSheet->getHighestRow();
        $colNum = $this->_CurrentSheet->getHighestColumn();
        $allCol = 0;
        for($i=0; $i< strlen($colNum); $i++){
            $allCol = $allCol*10 + ord(substr($colNum, $i, 1))-65;
        }
        if ($allRow <= 6) {
            $this->Failue='文件中不包含有效数据!';
        } else {
            $errorline = '';
            $postData = array();
            $sqlField = array();
            
            $OrderProductNum = (String)$this->_CurrentSheet->getCell('K3')->getValue();  /*订单产品编号*/
            $Product = (String)$this->_CurrentSheet->getCell('C4')->getValue();            /*订单产品名称*/
            $OrderProductNum = trim($OrderProductNum);
            $Product = trim($Product);
            
            if(!empty($OrderProductNum)){
                $Opid = $this->order_product_model->is_exist_by_num($OrderProductNum);
                if(is_array($Opid) && (1 == $Opid['status'] || 2 == $Opid['status'])){ /*是否处于已建立并且处于拆单状态*/
                    $Opid = $Opid['opid'];
                    $OrderProductPost = array(
                        'product' => $Product,
                        'dismantler' => $this->input->cookie('uid')
                    );
                    $this->order_product_model->update($OrderProductPost, $Opid);
                    
                    $ascii = '65';
                    $No = 1;
                    $OrderProductBoardPlate = array();
                    $Board = array();
                    $Opbids = array();
                    for($currentRow = 7; $currentRow <= $allRow; $currentRow++){
                        $Sn = (String)$this->_CurrentSheet->getCell('A'.$currentRow)->getValue();
                        $Plate = array(
                            'plate_name' => (String)$this->_CurrentSheet->getCell('B'.$currentRow)->getValue()
                        );
                        
                        if(!empty($Sn) && !empty($Plate['plate_name'])){
                            $Good = (String)$this->_CurrentSheet->getCell('I'.$currentRow)->getValue();
                            
                            $Plate['length'] = (Float)$this->_CurrentSheet->getCell('C'.$currentRow)->getValue();
                            $Plate['width'] = (Float)$this->_CurrentSheet->getCell('D'.$currentRow)->getValue();
                            $Plate['thick'] = (Int)$this->_CurrentSheet->getCell('G'.$currentRow)->getValue();
                            $Plate['edge'] = (String)$this->_CurrentSheet->getCell('H'.$currentRow)->getValue();
                            $Plate['remark'] = (String)$this->_CurrentSheet->getCell('J'.$currentRow)->getValue();
                            $Plate['area'] = ceil($Plate['length'] * $Plate['width']/M_ONE)/M_TWO;
                            if ($Plate['area'] < MIN_AREA) {
                                $Plate['area'] = MIN_AREA;
                            }
                            //$Plate['area'] = ceil($Plate['length'] * $Plate['width']/1000)/1000;
                            
                            $Num = (Int)$this->_CurrentSheet->getCell('E'.$currentRow)->getValue();
                            
                            $Good = $Plate['thick'].$Good;
                            
                            if($this->board_model->select_board_id(gh_escape($Good))){
                                if(!isset($Board[$Good])){
                                    $Board[$Good] = array(
                                        'opid' => $Opid,
                                        'board' => $Good,
                                        'amount' => $Num,
                                        'area' => $Plate['area']*$Num
                                    );
                                    if(!($Board[$Good]['opbid'] = $this->order_product_board_model->is_existed($Opid, gh_escape($Good)))){
                                        /*如果不存在则插入订单产品板材*/
                                        $Board[$Good] = gh_escape($Board[$Good]);
                                        $Board[$Good]['opbid'] = $this->order_product_board_model->insert($Board[$Good]);
                                    }
                                    /* }else{ */
                                    array_push($Opbids, $Board[$Good]['opbid']);
                                    /* } */
                                }else{
                                    $Board[$Good]['amount'] += $Num;
                                    $Board[$Good]['area'] += $Plate['area']*$Num;
                                }
                                
                                $Plate['opbid'] = $Board[$Good]['opbid'];
                                
                                $Plate = array_merge($Plate, $this->_get_edge_thick($Plate['edge']));
                                
                                $Plate['qrcode'] = null;
                                if(isset($Plate['remark']) && '' != $Plate['remark']){
                                    $Plate['abnormity'] = $this->_is_abnormity($Plate['remark']);
                                }else{
                                    $Plate['abnormity'] = 0;
                                }
                                
                                for($J = 0; $J < $Num; $J++){
                                    $No = $No + $J;
                                    $Plate['plate_num'] = $No;
                                    array_push($OrderProductBoardPlate, $Plate);
                                }
                            }else{
                                $this->Failue .= '<strong>'.$Good.'不在系统中, 请先登记板材!</strong>';
                                break;
                            }
                        }else{
                            break;
                        }
                    }
                    if(empty($this->Failue)){
                        $this->order_product_board_plate_model->delete_by_opid($Opid);
                        if(!empty($Opbids)){
                            $this->order_product_board_model->delete_not_in($Opid, $Opbids);
                        }
                        
                        $OrderProductBoardPlate = gh_escape($OrderProductBoardPlate);
                        if(!!($this->order_product_board_plate_model->insert_batch($OrderProductBoardPlate))
                            && !!($this->order_product_board_model->update_batch($Board))){
                            $this->load->library('workflow/workflow');
                            if($this->workflow->initialize('order_product', $Opid)){
                                $this->workflow->dismantle();
                                $this->Success = '上传的手工单Excel成功!';
                                return true;
                            }else{
                                $this->Failue = $this->workflow->get_failue();
                            }
                        }else{
                            $this->_Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'保存拆单板块失败!';
                        }
                    }
                }else{
                    $this->Failue = '订单产品编号已经确认拆单或者作废, 不能拆单!';
                }
            }else{
                $this->Failue = '上传的手工单Excel中不包含订单产品编号!';
            }
        }
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
                $Edge = $this->_get_source_edge_thick((string)$xml['FBSTR']);
                $Width = $Width + $Edge['up_edge'] + $Edge['down_edge'];
                $Length = $Length + $Edge['left_edge'] + $Edge['right_edge'];
                $Edge = $this->_get_edge_thick((string)$xml['FBSTR']);

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
                if(($Length > 2436 || $Width > 2436) || ($Length > 1220 && $Width > 1220)){
                    $this->Failue .= $xml['ORDER'].'的板块尺寸太长';
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
                                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'BD文件上传失败失败!';
                                }
                            }else{
                                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'BD文件上传失败失败!';
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
                                    $this->Failue = $this->workflow->get_failue();
                                }
                            }else{
                                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'BD文件上传失败失败!';
                            }
                        }
                    }else{
                        $this->Failue .= '<strong>'.$Board['board'].'不在系统中, 请先登记板材!</strong>';
                    }
                }else{
                    $this->Failue .= '当前订单已经确认拆单，请先返回订单，然后再上传';
                }
            }

        }else{
            $this->Failue .= "文件上传失败!";
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
            $this->load->model('order/order_product_board_plate_model');
            $Data = array(
                'qrcode' => $FileInfo['raw_name'],
                'bd_file' => $FileInfo['full_path']
            );
            $Data = gh_escape($Data);
            if (!!($this->order_product_board_plate_model->is_uploaded($Data['qrcode']))) {
                $this->order_product_board_plate_model->update_bd_file($Data);
                $this->Success = $FileInfo['raw_name'].'文件上传成功';
                return true;
            }else {
                $this->Failue .= "您上传的Bd文件". $Data['qrcode'] . '在系统中没有相对应的数据，所以上传失败！';
                unlink($FileInfo['full_path']);
            }
        }else{
            $this->Failue .= "文件上传失败!";
        }
        return false;
    }

    /**
     * 计算封边厚度
     * @param $Value
     * @return array
     */
    private function _get_edge_thick($Value){
        $this->load->model('data/wardrobe_edge_model');
        $Return = array();
        if (!!($Edges = $this->wardrobe_edge_model->select_wardrobe_edge_by_name(gh_escape($Value)))) {
            if (!empty($Edges['ups'])) {
                $Return['up_edge'] = $Edges['ups'];
            }else {
                $Return['up_edge'] = O_EDGE;
            }
            if (!empty($Edges['downs'])) {
                $Return['down_edge'] = $Edges['downs'];
            }else {
                $Return['down_edge'] = O_EDGE;
            }
            if (!empty($Edges['lefts'])) {
                $Return['left_edge'] = $Edges['lefts'];
            }else {
                $Return['left_edge'] = O_EDGE;
            }
            if (!empty($Edges['rights'])) {
                $Return['right_edge'] = $Edges['rights'];
            }else {
                $Return['right_edge'] = O_EDGE;
            }
        }else {
            $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = O_EDGE;
        }

        return $Return;
    }

    private function _get_source_edge_thick($Value) {
        $this->load->model('data/wardrobe_edge_model');
        $Return = array();
        if (!!($Edges = $this->wardrobe_edge_model->select_wardrobe_edge_by_name(gh_escape($Value)))) {
            if (!empty($Edges['ups'])) {
                $Return['up_edge'] = $Edges['ups'];
            }else {
                $Return['up_edge'] = O_EDGE;
            }
            if (!empty($Edges['downs'])) {
                $Return['down_edge'] = $Edges['downs'];
            }else {
                $Return['down_edge'] = O_EDGE;
            }
            if (!empty($Edges['lefts'])) {
                $Return['left_edge'] = $Edges['lefts'];
            }else {
                $Return['left_edge'] = O_EDGE;
            }
            if (!empty($Edges['rights'])) {
                $Return['right_edge'] = $Edges['rights'];
            }else {
                $Return['right_edge'] = O_EDGE;
            }
        }else {
            $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = O_EDGE;
        }
        return $Return;
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
                $this->Failue = '图纸文件名不正确，请重新上传';
                return false;
            }elseif (count($FileNames) >= 4){
                $Drawing['type'] = 1;
            }else{
                $Drawing['type'] = 0;
            }
            if(!!($this->drawing_model->update_drawing($FileName))){
                $this->Success = $FileName.'图纸上传成功';
            }else{
                $OrderProductNum = implode('-', array_slice($FileNames, 0, 2));
                $Opid = $this->order_product_model->is_exist_by_num($OrderProductNum);
                if(is_array($Opid) && ($Opid['status'] >= 1 && $Opid['status'] <= 9)){ /*是否处于已建立并且处于拆单状态*/
                    unset($OrderProductNum);
                    $Drawing['opid'] = $Opid['opid'];
                    $Drawing['name'] = $FileName;
                    $Drawing['path'] = $FileInfo['full_path'];
                    $Drawing = gh_escape($Drawing);
                    if(!!($this->drawing_model->insert_drawing($Drawing))){
                        $this->Success = $FileName.'图纸上传成功';
                    }else{
                        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'图纸上传失败失败!';
                    }
                }else{
                    $this->Failue .= '当前订单已经确认拆单，请先返回订单，然后再上传';
                }
            }
        }else{
            $this->Failue .= "文件上传失败!";
        }
        return ;
    }

    public function _return($data = array()){
        if(empty($this->Failue)){
            log_message('debug', 'Controller Order/Upload Success!');
            echo $this->Success;
        }else{
            log_message('debug', 'Controller Order/Upload Failue!');
            echo $this->Failue;
        }
        exit();
    }
    
    private function _default($Name, $Value = ''){
    	$Return = '';
    	switch ($Name){
    	    case 'creator':
    	        $Return = $this->input->cookie('uid');
    	        break;
    	    case 'create_datetime':
    	        $Return = time();
    	        break;
    		case 'abnormity':
    			if(is_array($Value)){
    				$Return = $this->_is_abnormity($Value['remark']);
    			}else{
    				$Return = $this->_is_abnormity($Value);
    			}
    			break;
    	}
    	return $Return;
    }

    /**
     * 判断是否是异形
     * @param $Name
     * @return int
     */
    private function _is_abnormity($Name){
        static $Abnormity = array();
        if(empty($Abnormity)){
            $this->load->model('data/abnormity_model');
            if(!($Abnormity = $this->abnormity_model->select_abnormity(1, false))){
                return 0;
            }
        }
        $Flag = 0;
        foreach ($Abnormity as $key => $value){
            if(preg_match('/'.$value['name'].'/', $Name)){
                $Flag = 1;
                break;
            }
        }
        return $Flag;
    }
}