<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-mobile.
 * User: chuangchuangzhang
 * Date: 2018/3/6
 * Time: 13:26
 *
 * Desc:
 * php index.php generate create false/controller/view/modal/dbview/dbtable/validation data-page_type
 */
class Generate extends MY_Controller {
    private $_Name = '';
    private $_ControllerPath;
    private $_ModelPath;
    private $_DbviewPath;
    private $_DbtablePath;
    private $_ValidationPath;
    private $_Twig;
    public function __construct() {
        parent::__construct();
        $this->_ControllerPath = APPPATH . 'controllers/';
        $this->_ModelPath = APPPATH . 'models/';
        $this->_DbviewPath = APPPATH . 'config/dbview/';
        $this->_DbtablePath = APPPATH . 'config/dbtable/';
        $this->_ValidationPath = APPPATH . 'config/validation/';

        $loader = new Twig_Loader_Filesystem(VIEWPATH. 'generate');
        $this->_Twig = new Twig_Environment($loader, array());
    }

    public function create($Type = '', $Name = '') {
        if (isset($Name) && $Name != false) {
            $this->_Name = $Name;
        } else {
            echo 'Please Give the name';
            exit();
        }

        echo "Start...\n";
        if ($Type != false && method_exists(__CLASS__, '_' . $Type)) {
            $Method = '_' . $Type;
            $this->$Method();
        } else {
            $this->_controller();
            $this->_model();
            $this->_dbview();
            $this->_dbtable();
            $this->_validation();
        }
        echo "Do Success!\n";
        exit();
    }

    /**
     * Create Directory
     * @param $Dirs
     * @param $Path
     * @return bool
     */
    private function _create_dir($Dirs, $Path) {
        foreach ($Dirs as $Dir) {
            $this->$Path .= $Dir . '/';
            if (is_dir($this->$Path)) {
                continue;
            } else {
                echo "Start to create Directory " . $this->$Path . "\n";
                if (!!mkdir($this->$Path, 0755)) {
                    echo "Created Directory " . $this->$Path . "\n";
                } else {
                    echo "The Directory " . $this->$Path . " is not writable!\n";
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Create Controller
     * @return bool
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    private function _controller() {
        echo "Start create controller...\n";
        $Data = array();
        $Names = explode('-', $this->_Name);
        $Data['controller'] = implode('/', $Names);
        $Controller = array_pop($Names);
        $Data['dirs'] = implode('/', $Names);
        if (count($Names) > 0) {
            if (!$this->_create_dir($Names, '_ControllerPath')) {
                echo "End create controller...\n";
                return false;
            }
        }
        $Data['title'] = ucfirst($Controller);
        $Data['model'] = $Controller . '_model';
        $Template = $this->_Twig->load('Controller.twig');
        $String = $Template->render($Data);
        $File = $this->_ControllerPath . $Data['title'] . '.php';
        $Fp = fopen($File, 'w+');
        $fwrite = fwrite($Fp, $String);
        if (!$fwrite) {
            echo "Error happend when write to the file " . $File . "\n";
        } else {
            echo $File . " File Created Success. \n";
        }
        fclose($Fp);
        echo "End create controller...\n";
    }

    /**
     * Create Model
     * @return bool
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    private function _model() {
        echo "Start create model...\n";
        $Data = array();
        $Names = explode('-', $this->_Name);

        $Data['module'] = implode('/', $Names);
        $Model = array_pop($Names);
        $Data['title'] = ucfirst($Model . '_model');
        $Data['table'] = $this->_get_table($Model);
        if (!empty($Data['table'])) {
            $Data['dirs'] = implode('/', $Names);
            if (count($Names) > 0) {
                if (!$this->_create_dir($Names, '_ModelPath')) {
                    echo "End create Model...\n";
                    return false;
                }
            }

            $Template = $this->_Twig->load('Model.twig');
            $String = $Template->render($Data);
            $File = $this->_ModelPath . $Data['title'] . '.php';
            $Fp = fopen($File, 'w+');
            $fwrite = fwrite($Fp, $String);
            if (!$fwrite) {
                echo "Error happend when write to the file " . $File . "\n";
            } else {
                echo $File . " File Created Success. \n";
            }
            fclose($Fp);
        }
        echo "End create Model...\n";
    }

    /**
     * Create Dbview
     * @return bool
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    private function _dbview() {
        echo "Start create dbview...\n";
        $Data = array();
        $Names = explode('-', $this->_Name);

        $Dbview = array_pop($Names);
        $Data['module'] = implode('/', $Names);
        $Data['title'] = $Dbview . '_dbview';
        $Data['table'] = $this->_get_table($Dbview);
        if (!empty($Data['table'])) {
            $Data['dirs'] = implode('/', $Names);
            if (count($Names) > 0) {
                if (!$this->_create_dir($Names, '_DbviewPath')) {
                    echo "End create Dbview...\n";
                    return false;
                }
            }

            $Data['configs'] = $this->_create_dbview($Data['module'], $Dbview, $Data['table']['field_data']);

            $Template = $this->_Twig->load('Dbview.twig');
            $String = $Template->render($Data);
            $File = $this->_DbviewPath . $Data['title'] . '.php';
            $Fp = fopen($File, 'w+');
            $fwrite = fwrite($Fp, $String);
            if (!$fwrite) {
                echo "Error happend when write to the file " . $File . "\n";
            } else {
                echo $File . " File Created Success. \n";
            }
            fclose($Fp);
        }
        echo "End create Dbview...\n";
    }

    private function _create_dbview($Module, $Dbview, $Table) {
        $Keys = array(
            'select'
        );
        $Configs = array();
        $Config = array();
        foreach ($Table as $Key => $Value) {
            $I = explode('_', $Key);
            array_shift($I);
            $NewKey = implode('_', $I);
            if ($Value['primary_key']) {
                $NewKey = 'v';
            }
            $Config[$Key] = $NewKey;
        }
        foreach ($Keys as $Key => $Value) {
            $Value = $Module . '/' . $Dbview . '_model/' . $Value;
            $Configs[$Value] = $Config;
        }
        return $Configs;
    }

    /**
     * Create Dbtable File
     * @return bool
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    private function _dbtable() {
        echo "Start create dbtable...\n";
        $Data = array();
        $Names = explode('-', $this->_Name);

        $Dbtable = array_pop($Names);
        $Data['module'] = implode('/', $Names);
        $Data['title'] = $Dbtable . '_dbtable';
        $Data['table'] = $this->_get_table($Dbtable);
        if (!empty($Data['table'])) {
            $Data['dirs'] = implode('/', $Names);
            if (count($Names) > 0) {
                if (!$this->_create_dir($Names, '_DbtablePath')) {
                    echo "End create Dbtable...\n";
                    return false;
                }
            }

            $Data['configs'] = $this->_create_dbtable($Data['module'], $Dbtable, $Data['table']['field_data']);

            $Template = $this->_Twig->load('Dbtable.twig');
            $String = $Template->render($Data);
            $File = $this->_DbtablePath . $Data['title'] . '.php';
            $Fp = fopen($File, 'w+');
            $fwrite = fwrite($Fp, $String);
            if (!$fwrite) {
                echo "Error happend when write to the file " . $File . "\n";
            } else {
                echo $File . " File Created Success. \n";
            }
            fclose($Fp);
        }
        echo "End create dbtable...\n";
    }

    private function _create_dbtable($Module, $Dbtable, $Table) {
        $Keys = array(
            '',
            'insert',
            'insert_batch',
            'update',
            'update_batch'
        );
        $Configs = array();
        $Config = array();
        foreach ($Table as $Key => $Value) {
            if ($Value['primary_key']) {
                continue;
            }
            $I = explode('_', $Key);
            array_shift($I);
            $NewKey = implode('_', $I);
            $Config[$NewKey] = $Key;
        }
        foreach ($Keys as $Key => $Value) {
            if ($Value == '') {
                $Value = $Module . '/' . $Dbtable . '_model';
            } else {
                $Value = $Module . '/' . $Dbtable . '_model/' . $Value;
            }
            $Configs[$Value] = $Config;
        }
        return $Configs;
    }

    /**
     * Create Validation File
     * @return bool
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    private function _validation() {
        echo "Start create validation...\n";
        $Data = array();
        $Names = explode('-', $this->_Name);

        $Validation = array_pop($Names);
        $Data['module'] = implode('/', $Names);
        $Data['title'] = $Validation;
        $Data['table'] = $this->_get_table($Validation);
        if (!empty($Data['table'])) {
            $Data['dirs'] = implode('/', $Names);
            if (count($Names) > 0) {
                if (!$this->_create_dir($Names, '_ValidationPath')) {
                    echo "End create Validation...\n";
                    return false;
                }
            }

            $Data['configs'] = $this->_create_validation($Data['table']['field_data']);

            $Template = $this->_Twig->load('Validation.twig');
            $String = $Template->render($Data);
            $File = $this->_ValidationPath . $Data['title'] . '_validation.php';
            $Fp = fopen($File, 'w+');
            $fwrite = fwrite($Fp, $String);
            if (!$fwrite) {
                echo "Error happend when write to the file " . $File . "\n";
            } else {
                echo $File . " File Created Success. \n";
            }
            fclose($Fp);
        }
        echo "End create _validation...\n";
    }

    private function _create_validation($Table) {
        $Configs = array();
        array (
            'field' => '{{ config.name }}',
            'label' => '{{ config.comment }}',
            'rules' => 'trim|{{ config.rules | join('|') }}'
        );
        $Numeric = array(
            'int',
            'smallint',
            'tinyint'
        );
        foreach ($Table as $Key => $Value) {
            $Config = array();
            if ($Value['primary_key']) {
                $Config['name'] = 'v';
                if (in_array($Value['type'], $Numeric)) {
                    $Config['rules']['required'] = 'required';
                    $Config['rules']['numeric'] = 'numeric';
                    $Config['rules']['min_length'] = 'max_length[1]';
                }
                if ($Value['max_length'] > 0) {
                    $Config['rules']['max_length'] = 'max_length[' . $Value['max_length'] . ']';
                }
                $Configs[] = $Config;
                continue;
            }
            $I = explode('_', $Key);
            array_shift($I);
            $NewKey = implode('_', $I);
            $Config['name'] = $NewKey;
            $Config['comment']  = $NewKey;
            if (in_array($Value['type'], $Numeric)) {
                $Config['rules']['numeric'] = 'numeric';
            }
            if ($Value['max_length'] > 0) {
                $Config['rules']['max_length'] = 'max_length[' . $Value['max_length'] . ']';
            }
            $Configs[] = $Config;
        }
        return $Configs;
    }

    /**
     * Get the info of table
     * @param $Name
     */
    private function _get_table($Name) {
        $Table = array();
        if ($this->db->table_exists($Name)) {
            $TableInfo = $this->db->table_info($Name);
            $Table['name'] = $Name;
            $Table['comment'] = $TableInfo->comment;
            $FieldData = $this->db->field_data($Name);
            foreach ($FieldData as $Value) {
                $Table['field_data'][$Value->name] = array(
                    'name' => $Value->name,
                    'type' => $Value->type,
                    'max_length' => $Value->max_length,
                    'comment' => $Value->comment,
                    'primary_key' => $Value->primary_key
                );
                if ($Value->primary_key) {
                    $Table['v'] = $Value->name;
                }
            }
        } else {
            echo "Table is not exist.\n";
        }
        return $Table;
    }
}
