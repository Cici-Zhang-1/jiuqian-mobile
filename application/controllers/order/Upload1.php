<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
    
    private function _add_xls($FileInfo){
        if(is_array($FileInfo) && !empty($FileInfo)){
            $savePath = $FileInfo['full_path'];
            require_once APPPATH.'/third_party/PHPExcels/PHPExcel.php';
            log_message('error',$savePath);
            $PHPExcel = PHPExcel_IOFactory::load($savePath);
            $currentSheet = $PHPExcel->getSheet(0);
            $value = (String)$currentSheet->getCell('A1')->getValue();
            if('序号' == $value){
                $this->_add_cuterite($currentSheet);
            }elseif (strstr($value, '木框门')){
                $this->_add_wood($currentSheet);
            }elseif (strstr($value, '手工单')){
                $this->_add_manu($currentSheet);
            }
        }else{
            $this->Failue="文件上传失败!";
        }
    }
    
    private function _add_cuterite($currentSheet){
        $this->Failue = '目前不支持优化Excel处理!';
    }
    
    private function _add_wood($currentSheet){
        $this->Failue = '暂时不支持处理木框门Excel';
        return false;
        
        $this->load->model('product/product_model');
        $this->load->model('order/order_model');
        $this->load->model('order/order_product_model');
        $this->load->model('order/order_product_door_model');
        $this->load->model('order/order_product_door_plate_model');
        $allRow = $currentSheet->getHighestRow();
        $colNum = $currentSheet->getHighestColumn();
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
    
            $OrderProductNum = (String)$currentSheet->getCell('J2')->getValue(); //订单编号(Y-10-221W_4);
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
            
            $Color = (String)$currentSheet->getCell('J4')->getValue();  /*板材颜色*/
            if(empty($Color)){
                $this->Failue = '上传的Excel中不包含板材颜色';
                return false;
            }
        }
    }
    
    /**
     * 手工单导入
     * @param unknown $currentSheet
     */
    private function _add_manu($currentSheet){
        $this->load->model('product/board_model');
        $this->load->model('order/order_product_model');
        $this->load->model('order/order_product_board_model');
        $this->load->model('order/order_product_board_plate_model');
        $allRow = $currentSheet->getHighestRow();
        $colNum = $currentSheet->getHighestColumn();
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
            
            $OrderProductNum = (String)$currentSheet->getCell('K3')->getValue();  /*订单产品编号*/
            $Product = (String)$currentSheet->getCell('C4')->getValue();            /*订单产品名称*/
            $OrderProductNum = trim($OrderProductNum);
            $Product = trim($Product);
            
            if(!empty($OrderProductNum)){
                $Opid = $this->order_product_model->is_exist_by_num($OrderProductNum);
                if(is_array($Opid) && (1 == $Opid['status'] || 2 == $Opid['status'])){ /*是否处于已建立并且处于拆单状态*/
                    $Opid = $Opid['opid'];
                    $OrderProductPost = array(
                        'product' => $Product,
                        'dismantler' => $this->input->post('access2008_cookie_uid')
                    );
                    $this->order_product_model->update($OrderProductPost, $Opid);
                    
                    $ascii = '65';
                    $No = 1;
                    $OrderProductBoardPlate = array();
                    $Board = array();
                    $Opbids = array();
                    for($currentRow = 7; $currentRow <= $allRow; $currentRow++){
                        $Sn = (String)$currentSheet->getCell('A'.$currentRow)->getValue();
                        $Plate = array(
                            'plate_name' => (String)$currentSheet->getCell('B'.$currentRow)->getValue()
                        );
                        
                        if(!empty($Sn) && !empty($Plate['plate_name'])){
                            $Good = (String)$currentSheet->getCell('I'.$currentRow)->getValue();
                            
                            $Plate['length'] = (Float)$currentSheet->getCell('C'.$currentRow)->getValue();
                            $Plate['width'] = (Float)$currentSheet->getCell('D'.$currentRow)->getValue();
                            $Plate['thick'] = (Int)$currentSheet->getCell('G'.$currentRow)->getValue();
                            $Plate['edge'] = (String)$currentSheet->getCell('H'.$currentRow)->getValue();
                            $Plate['remark'] = (String)$currentSheet->getCell('J'.$currentRow)->getValue();
                            $Plate['area'] = ceil($Plate['length'] * $Plate['width']/1000)/1000;
                            
                            $Num = (Int)$currentSheet->getCell('E'.$currentRow)->getValue();
                            
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
    
    private function _add_bds($FileInfo){
        if(is_array($FileInfo) && !empty($FileInfo)){
            $this->load->model('product/board_model');
            $this->load->model('order/order_product_model');
            $this->load->model('order/order_product_board_model');
            $this->load->model('order/order_product_board_plate_model');
            $savePath = $FileInfo['full_path'];
            $xml = simplexml_load_file($savePath);
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
            $Edge = $this->_get_edge_thick((string)$xml['FBSTR']);
            $Width = $Width + $Edge['up_edge'] + $Edge['down_edge'];
            $Length = $Length + $Edge['left_edge'] + $Edge['right_edge'];
            
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
            if($Length > 2430 || $Width > 2430){
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
            
            $Data['area'] = ceil($Length * $Width/1000)/1000;
            $Data = gh_escape($Data);
            
            $OrderProductNum = gh_escape($OrderProductNum);
            
            $Opid = $this->order_product_model->is_exist_by_num($OrderProductNum);
            if(is_array($Opid) && (1 == $Opid['status'] || 2 == $Opid['status'])){ /*是否处于已建立并且处于拆单状态*/
                /*更新订单产品信息*/
                $OrderProductPostData = array(
                    'product' => $Product,
                    'bd' => 1,
                    'dismantler' => $this->input->post('access2008_cookie_uid')
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
        }else{
            $this->Failue .= "文件上传失败!";
        }
        return false;
    }
    
    private function _get_edge_thick($Value){
        if(false === $Value){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 0;
        }elseif('HHHH' == $Value){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1.5;
        }elseif ('bbbb' == $Value){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1;
        }elseif ('Hb' == $Value){
            $Return['up_edge'] = 1.5;
            $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = 1;
        }elseif ('HHH' == $Value){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1.5;
        }elseif ('bbb' == $Value){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1;
        }elseif ('Hbbb' == $Value){
            $Return['up_edge'] = 1.5;
            $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = 1;
        }elseif(!!(preg_match("/[\x4e00-\x9fa5]+/", $Value))){
            $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = 1;
        }else{
            $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = 0;
        }
        return $Return;
    }
    
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
    	        $Return = $this->input->post('access2008_cookie_uid');
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