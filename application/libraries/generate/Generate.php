<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-mobile.
 * User: chuangchuangzhang
 * Date: 2018/3/13
 * Time: 16:02
 *
 * Desc:
 */
class Generate {
    private $_Name = '';
    private $_View;
    private $_Twig;

    private $_CI;
    public function __construct() {
        $this->_CI = &get_instance();
        $this->_View = VIEWPATH;

        $loader = new Twig_Loader_Filesystem(__DIR__);
        $this->_Twig = new Twig_Environment($loader, array());
    }

    /**
     * @param $File
     * @param $Data
     * array(
            'title' => '',
     *      'page_searches' => array(),
     *      'func_group' => array(
                'forms' => array()
     * ),
     *      'funcs' => array(
                'forms' => array()
     * ),
     *      'cards' => array(
                'elements' => array()
     * )
     * )
     * @return bool|void
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function create_view($File, $Data) {
        $this->_View = $this->_View . $File;
        $_ci_ext = pathinfo($this->_View, PATHINFO_EXTENSION);
        $this->_View = ($_ci_ext === '') ? $this->_View.'.php' : $this->_View;

        if (file_exists($this->_View)) {
            $this->_delete();
        }

        if ( ! $Fp = @fopen($this->_View, 'w+')) {
            log_message('error', 'Unable to write view file: '.$this->_View);
            return;
        }

        $Template = $this->_Twig->load('PermitView.twig');
        $String = $Template->render($Data);

        $Fwrite = fwrite($Fp, $String);
        fclose($Fp);
        if (!$Fwrite) {
            log_message('error', "Error happend when write to the view file " . $File);
            return false;
        } else {
            log_message('debug', 'File Created Success.');
            return true;
        }
    }

    private function _delete() {
        if ( ! @unlink($this->_View)) {
            log_message('error', 'Unable to delete View file for '.$this->_View);
            return FALSE;
        }
        return TRUE;
    }
}
