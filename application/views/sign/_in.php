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
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="renderer" content="webkit" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,shrink-to-fit=no" />
        <title><?php echo $title; ?>-登陆</title>
        <link rel="shortcut icon" href="<?php echo base_url('source/ico/chu.ico');?>" />
        <link rel="stylesheet" href="<?php echo base_url('source/node_modules/bootstrap/dist/css/bootstrap.min.css'); ?>" />
        <link href="<?php echo base_url('source/node_modules/font-awesome/css/font-awesome.min.css'); ?>" type="text/css" rel="stylesheet">
        <link href="<?php echo base_url('source/node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css');?>" rel="stylesheet" type="text/css">
        <?php if (isset($theme)) { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo base_url('style/themes/' . $theme . '-bootstrap.css');?>" />
        <?php }?>
        <link rel="stylesheet" href="<?php echo base_url('source/css/common.css?v=0.00');?>" />
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
        <div class="container-fluid">
            <div class="row">
                <br><br><br><br>
                <div class="col-lg-offset-4 col-lg-4 col-md-offset-4 col-md-4 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="typo-h3">登录
                                <small><?php echo $title; ?></small>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" id="signInForm" action="<?php echo $action;?>" method="post">
                                <div class="form-group">
                                    <label for="username">用户名</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="用户名" required pattern="[\w\d]{1,64}" autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="password">密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="长度为6-16位任意字符" required pattern=".{6,16}" >
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
        <script src="<?php echo base_url('source/node_modules/jquery/dist/jquery.js'); ?>"></script>
        <script src="<?php echo base_url('source/node_modules/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
        <script type="module">
            (function ($, window, undefined) {
                $('#signInForm').on('submit', function (e) {
                    e.preventDefault();
                    var Data = {
                        username: $('#username').val(),
                        password: $('#password').val()
                    };
                    $.ajax({
                        async: true,
                        type: 'POST',
                        data: Data,
                        url:  $('#signInForm').attr('action'),
                        dataType: 'json',
                        beforeSend: function (x) {
                            $('#signIn').attr('disabled', true);
                        },
                        success: function (msg) {
                            if (msg.code == 0) {
                                window.location.href = msg.location;
                            } else {
                                $('#signInForm').find('.serverError').html(msg.message).show();
                                $('#signInForm').find('input,select,textarea').on('focus.servererror',function(e){
                                    $('#signInForm').find('.serverError').html('').hide();
                                    $('#signInForm').find('input,select,textarea').off('focus.servererror');
                                });
                            }
                        },
                        error: function (x, e, y) {
                            $('#signInForm').find('.serverError').html('登录失败, 请重新登录!').show();
                            $('#signInForm').find('input,select,textarea').on('focus.servererror',function(e){
                                $('#signInForm').find('.serverError').html('').hide();
                                $('#signInForm').find('input,select,textarea').off('focus.servererror');
                            });
                        },
                        complete: function (x, t) {
                            $('#signIn').attr('disabled', false);
                        }
                    });
                });
            })(jQuery, window, undefined);
        </script>
	</body>
</html>
