<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-20
 * @author ZhangCC
 * @version
 * @description  
 */
?>
<!DOCTYPE HTML>
<html lang="zh_cn">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="renderer" content="webkit">
		<title><?php echo $title; ?></title>
		<link rel="shortcut icon" href="<?php echo base_url('style/ico/chu.ico');?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('style/tabs/panel.css');?>" />
		<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
		<link href="//cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css" type="text/css" rel="stylesheet">
		<link href="<?php echo base_url('style/datepicker/datepicker.css');?>" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('style/tabs/linkbutton.css');?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('style/tabs/tabs.css');?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('style/jquery/autocomplete.css');?>" />
        <?php if (isset($theme)) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo base_url('style/themes/' . $theme . '-bootstrap.css');?>" />
        <?php }?>
		<link rel="stylesheet" href="<?php echo base_url('style/common.css?v=1');?>" />
	</head>
	<body>
		<nav class="navbar-self navbar navbar-default" id="jqNav">
			<div class="container-fluid print-none">
			    <div class="navbar-header">
      				<a class="navbar-brand" href="#" style="font-weight: bold; font-size:20px;"><?php echo $title; ?></a>
    			</div>
    			<ul class="nav navbar-nav navbar-right">
    				<li><a href="javascript:void(0);"><?php echo $truename;?></a></li>
    				<li><a href="<?php echo site_url('sign/out');?>"><i class="fa fa-sign-out"></i>退出</a></li>
    			</ul>
		    </div>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-1 col-lg-1 print-none" id="side">
					<div class="side-left">
						<ul class="sidebar">
							<?php echo $nav; ?>
						</ul>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11 print-some-none" id="jqContent">
