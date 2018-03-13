<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-mobile.
 * User: chuangchuangzhang
 * Date: 2018/3/13
 * Time: 11:24
 *
 * Desc:
 */
?>
<!DOCTYPE HTML>
<html lang="zh_cn">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="renderer" content="webkit" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,shrink-to-fit=no" />
        <title><?php echo $title; ?></title>
        <link rel="shortcut icon" href="<?php echo base_url('source/ico/chu.ico');?>" />
        <link rel="stylesheet" href="<?php echo base_url('source/node_modules/bootstrap/dist/css/bootstrap.min.css'); ?>" />
        <link href="<?php echo base_url('source/node_modules/font-awesome/css/font-awesome.min.css'); ?>" type="text/css" rel="stylesheet">
        <link href="<?php echo base_url('source/node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css');?>" rel="stylesheet" type="text/css">
        <?php if (isset($theme)) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo base_url('style/themes/' . $theme . '-bootstrap.css');?>" />
        <?php }?>
        <link rel="stylesheet" href="<?php echo base_url('source/css/common.css?v=0.00');?>" />
    </head>
    <body>
