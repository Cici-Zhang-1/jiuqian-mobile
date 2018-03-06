<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-20
 * @author ZhangCC
 * @version
 * @description  
 */
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<title>九千定制管理系统-登录</title>
		
		<link rel="shortcut icon" href="<?php echo base_url('style/ico/chu.ico');?>">
		<link rel="stylesheet" type="text/css" href="http://cdn.bootcss.com/twitter-bootstrap/3.3.0/css/bootstrap.min.css" />
		<link href="http://cdn.bootcss.com/font-awesome/4.1.0/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('style/common.css');?>" />
		
		<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
		<!--[if lt IE 9]><link href="<?php echo base_url('style/bootstrap.min.css');?>" rel="stylesheet" type="text/css"/><![endif]-->
		<!--[if lt IE 9]><script src="<?php echo base_url('js/respond.src.js');?>" type="text/javascript"></script><![endif]-->
		<style type="text/css">
			.user{
				border: 1px solid #4C9ED9;
				-webkit-border-radius: 3px;
				-moz-border-radius: 3px;
				border-radius: 3px;
			}
			.form-group a{
				margin-top: 22px;
			}
			.form-group a:hover{
				text-decoration: none;
				color: #000;
				cursor: pointer;
			}
			.serverError{
				display: none;
			}
		</style>
	</head>
	<body>
	<div class="container">
		<div class="row">
			<br><br><br><br><br>   
			<div class="col-lg-offset-4 col-lg-4 col-xs-4 col-md-4">
			    <div class="panel panel-default">
			        <div class="panel-heading">
			        <h3 class="typo-h3">登录
			        <small>九千定制</small>
			        </h3>
			        </div>
			        <div class="panel-body">
			        	<form role="form" id="signInForm" action="<?php echo $action;?>" method="post">
			              	<div class="form-group">
			                	<label for="username">用户名</label>
			                	<input type="text" class="form-control" name="username" id="username" placeholder="用户名"  input-length="1,64" input-name="用户名" error-message="格式不正确" autofocus>
			              	</div>
			              	<div class="form-group">
			                	<label for="password">密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码</label>
			                	<input type="password" class="form-control" name="password" id="password" placeholder="密码" input-length="6,16" input-name="登录密码">
			              	</div>
			              	<span id="errorId" class="help-block"></span>
			              	<div class="alert alert-danger alert-dismissible fade in serverError" role="alert"></div>                   
			              	<div class="form-group ">
			                	<div class="text-center">
			                  	<button type="submit" id="signIn" class="btn btn-warning btn-lg btn-block ">登&nbsp;&nbsp;&nbsp;&nbsp;录</button>
			                	</div>
			              	</div>
			            </form>
					</div>
			  	</div>
			</div>
		</div>
	</div>
		<script type="text/javascript" src="<?php echo base_url('js/formValidator.js');?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/sign_in.js');?>"></script>
	</body>
</html>