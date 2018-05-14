<?php
/**
 * Created for jiuqian-mobile.
 * User: chuangchuangzhang
 * Date: 2018/3/5
 * Time: 14:39
 *
 * Desc:
 */

define('BASEPATH', './');
$Diss = basename(__FILE__);
echo $Diss;
if ($handle = opendir('./')) {
    echo "Directory handle: $handle\n";
    echo "Files:\n";

    /* 这是正确地遍历目录方法 */
    while (false !== ($file = readdir($handle))) {
        if (!is_dir($file) && $file != $Diss) {
            require_once $file;
        }
    }

    closedir($handle);
    foreach ($config as $key => $value) {
        $keys = explode('/', $key);
        $categories = array_shift($keys);
        if (!is_dir($categories)) {
            mkdir($categories, 0755);
        }

        $Model = array_shift($keys);

        $Model = $categories . '/' .$Model . '_validation.php';
        if (!file_exists($Model)) {
            touch($Model);
            $OpenFile = fopen($Model, 'a+');
            $Item = '<?php defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');' . "\r\n";
            $fwrite = fwrite($OpenFile, $Item);
        } else {
            $OpenFile = fopen($Model, 'a+');
        }
        $Item = "\r\n";
        $fwrite = fwrite($OpenFile, $Item);
        $Item = '$config[\'' . $key . '\'] = array(';
        $fwrite = fwrite($OpenFile, $Item);
        $Item = "\r\n";
        $fwrite = fwrite($OpenFile, $Item);
        $i = 0;
        $Arrays = array();
        foreach ($value as $ikey => $ivalue) {
            if (is_array($ivalue)) {
                $Item = "\tarray(\r\n";
                $Items = array();
                foreach ($ivalue as $iikey => $iivalue) {
                    $Items[] = "\t\t" . '\'' . $iikey . '\' => \'' . $iivalue . '\'';
                }
                $Arrays[] = $Item . implode(",\r\n", $Items) . "\r\n\t)";
            }

        }
        $Array = implode(",\r\n", $Arrays) . "\r\n);\r\n";
        $fwrite = fwrite($OpenFile, $Array);

        fclose($OpenFile);
        if ($fwrite === false) {
            echo 'Writen File Wrong on ' . $Model;
            break;
        }
    }
}
