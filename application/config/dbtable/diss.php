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
    /*$config['dealer/dealer_model/is_valid'] = array(
        'd_id' => 'did',
        'd_des' => 'des',
        'd_shop' => 'shop',
        'd_debt1' => 'debt1',
        'd_debt2' => 'debt2',
        'd_balance' => 'balance',
        'concat(ifnull(d.a_province, ""), ifnull(d.a_city, ""), ifnull(d.a_county, ""), "-", ifnull(d_address,""))' => 'area',
        'A.dl_name' => 'linker',
        'if(strcmp(A.dl_mobilephone, ""),A.dl_mobilephone, A.dl_telephone)' => 'way'
    );*/
    foreach ($config as $key => $value) {
        $keys = explode('/', $key);
        $categories = array_shift($keys);
        if (!is_dir($categories)) {
            mkdir($categories, 0755);
        }

        $Model = array_shift($keys);
        $Models = explode('_', $Model);
        array_pop($Models);
        $Model = implode('_', $Models);
        $Model = $categories . '/' .$Model . '_dbtable.php';
        if (!file_exists($Model)) {
            touch($Model);
            $OpenFile = fopen($Model, 'a+');
            $Item = '<?php defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');';
            $fwrite = fwrite($OpenFile, $Item);
            $Item = "\r\n\r\n";
            $fwrite = fwrite($OpenFile, $Item);
            $Item = '$config[\'' . $key . '\'] = array(';
            $fwrite = fwrite($OpenFile, $Item);
            $Item = "\r\n";
            $fwrite = fwrite($OpenFile, $Item);
            // $Items = array();
            $i = 0;
            foreach ($value as $ikey => $ivalue) {
                if ($i++ < count($value)) {
                    $Item = "\t" . '\'' . $ikey . '\' => \'' . $ivalue . "',\r\n";
                } else {
                    $Item = "\t" . '\'' . $ikey . '\' => \'' . $ivalue . "'\r\n";
                }
                $fwrite = fwrite($OpenFile, $Item);
            }
            $Item = ');';
            $fwrite = fwrite($OpenFile, $Item);

            fclose($OpenFile);
            if ($fwrite === false) {
                echo 'Writen File Wrong on ' . $Model;
                break;
            }
        } else {
            $OpenFile = fopen($Model, 'a+');
            $Item = "\r\n";
            $fwrite = fwrite($OpenFile, $Item);
            $Item = '$config[\'' . $key . '\'] = array(';
            $fwrite = fwrite($OpenFile, $Item);
            $Item = "\r\n";
            $fwrite = fwrite($OpenFile, $Item);
            // $Items = array();
            $i = 0;
            foreach ($value as $ikey => $ivalue) {
                if ($i++ < count($value)) {
                    $Item = "\t" . '\'' . $ikey . '\' => \'' . $ivalue . "',\r\n";
                } else {
                    $Item = "\t" . '\'' . $ikey . '\' => \'' . $ivalue . "'\r\n";
                }
                $fwrite = fwrite($OpenFile, $Item);
            }
            $Item = ');';
            $fwrite = fwrite($OpenFile, $Item);

            fclose($OpenFile);
            if ($fwrite === false) {
                echo 'Writen File Wrong on ' . $Model;
                break;
            }
        }
    }
}
