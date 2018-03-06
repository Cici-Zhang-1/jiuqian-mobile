<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-22
 * @author ZhangCC
 * @version
 * @description  
 */
?>
	<br />
	<div class="col-md-6">
		<form class="form-horizontal" id="selfForm" action="<?php echo $action;?>" method="post" role="form">
			<input type="hidden" name="selected" value="<?php echo $self['uid'];?>" />
			<div class="form-group">
				<label class="control-label col-md-2" for="username">用户名:</label>
				<div class="col-md-4">
					<input class="form-control" name="username" id="username" placeholder="用户名" value="<?php echo $self['username']?>"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="truename">真实姓名:</label>
				<div class="col-md-4">
					<input class="form-control" name="truename" id="truename" placeholder="真实姓名" value="<?php echo $self['truename']?>"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="mobilephone">移动电话:</label>
				<div class="col-md-4">
					<input class="form-control" name="mobilephone" id="mobilephone" placeholder="移动电话" value="<?php echo $self['mobilephone']?>"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="password">密码:</label>
				<div class="col-md-4">
					<input class="form-control" name="password" id="password" type="password" placeholder="密码" value="" autocomplete="off"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-4">
					<button class="btn btn-default" id="selfSave" type="submit" value="保存"><i class="fa fa-save"></i>&nbsp;&nbsp;保存</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-6"></div>
	<div class="col-md-12"><hr /></div>
