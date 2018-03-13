<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/19
 * Time: 14:39
 *
 * Desc: 通过模版文件生成View文件
 */
class Template {
    private $_CI;
    private $_View;
    private $_Data;
    private $_Dir;
    private $_Template = 'template/';
    public function __construct() {
        $this->_CI = &get_instance();
    }

    public function generate($File, $Data) {
        $this->_View = VIEWPATH . $File;
        $_ci_ext = pathinfo($this->_View, PATHINFO_EXTENSION);
        $this->_View = ($_ci_ext === '') ? $this->_View.'.php' : $this->_View;

        if (file_exists($this->_View)) {
            $this->_delete();
        }
        $this->_Dir = explode('/', $this->_View);
        array_pop($this->_Dir);
        $this->_Dir = implode('/', $this->_Dir) . '/';
        $this->_Template = $this->_Dir . $this->_Template;
        $this->_Data = $Data;

        if ( ! is_dir($this->_Dir) OR ! is_really_writable($this->_Dir)) {
            log_message('error', 'Unable to write view floder: '.$this->_Dir);
            return;
        }

        if ( ! $fp = @fopen($this->_View, 'w+b')) {
            log_message('error', 'Unable to write view file: '.$this->_View);
            return;
        }

        if (flock($fp, LOCK_EX)) {
            $output = $this->_output();

            for ($written = 0, $length = strlen($output); $written < $length; $written += $result)
            {
                if (($result = fwrite($fp, substr($output, $written))) === FALSE)
                {
                    break;
                }
            }

            flock($fp, LOCK_UN);
        }else {
            log_message('error', 'Unable to secure a file lock for file at: '.$this->_View);
            return;
        }

        fclose($fp);

        if (is_int($result)) {
            chmod($this->_View, 0647);
            log_message('debug', 'View file written: '.$this->_View);
        }
        else
        {
            @unlink($this->_View);
            log_message('error', 'Unable to write the complete view content at: '.$this->_View);
        }
    }

    /**
     * 生成Output内容
     */
    private function _output() {
        $Index = $this->_Template . 'index.php';
        if (is_array($this->_Data)) {
            extract($this->_Data);
        }

        ob_start();

        echo <<<END
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
END;

        // If the PHP installation does not support short tags we'll
        // do a little string replacement, changing the short tags
        // to standard PHP echo statements.
        if ( ! is_php('5.4') && ! ini_get('short_open_tag') && config_item('rewrite_short_tags') === TRUE) {
            echo eval('?>'.preg_replace('/;*\s*\?>/', '; ?>', str_replace('<?=', '<?php echo ', file_get_contents($Index))));
        }else {
            include($Index); // include() vs include_once() allows for multiple views with the same name
        }

        log_message('info', 'File loaded: '.$Index);

        // Return the file data if requested
        $buffer = ob_get_contents();
        @ob_end_clean();
        return $buffer;
    }

    /**
     * 删除旧的内容
     */
    private function _delete() {
        if ( ! @unlink($this->_View)) {
            log_message('error', 'Unable to delete View file for '.$this->_View);
            return FALSE;
        }
        return TRUE;
    }
}
