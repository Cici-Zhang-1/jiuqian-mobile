<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-23
 * @author ZhangCC
 * @version
 * @description  
 * 用户管理
 */
?>
    <div class="page-line" id="user">
        <div class="my-tools col-md-12">
            <div class="col-md-3">
				<div class="input-group" id="userSearch" data-search="search" data-target="#userTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="userFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="userTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#userTable">
		      			<li><a href="javascript:void(0);" data-toggle="modal" data-target="#userModal" data-action="<?php echo site_url('manage/user/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#userModal" data-action="<?php echo site_url('manage/user/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#userTable" href="<?php echo site_url('manage/user/remove');?>" data-multiple=true ><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="userTable" data-load="<?php echo site_url('manage/user/read');?>">
				<thead>
					<tr>
					    <th class="td-xs" data-name="selected">#</th>
						<th data-name="name">用户名</th>
						<th data-name="truename">真实姓名</th>
						<th data-name="mobilephone">移动电话</th>
						<th >创建者</th>
						<th >创建时间</th>
						<th data-name="ugid">所在用户组</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="9">加载中...</td></tr>
					<tr class="no-data"><td colspan="9">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="uid"  type="checkbox" value=""/></td>
						<td name="username"></td>
						<td name="truename"></td>
						<td name="mobilephone"></td>
						<td name="creator"></td>
						<td name="create_datetime"></td>
						<td name="usergroup"><input name="ugid" type="hidden" value="" /></td>
			      	</tr>
				</tbody>
			</table>
		</div>
    </div>
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal" id="userForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="userModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >用户名:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="用户名" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >真实姓名:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="truename" type="text" placeholder="真实姓名" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >重置密码:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="password" type="password" placeholder="重置密码" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >移动电话:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="mobilephone" type="tel" placeholder="移动电话" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >所在用户组:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="ugid"></select>
			      			</div>
			      		</div>
			      		<div class="alert alert-danger alert-dismissible fade in serverError" role="alert"></div>
			      	</div>
			      	<div class="modal-footer">
			        	<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			        	<button type="submit" class="btn btn-primary" data-save="ajax.modal">保存</button>
			      	</div>
				</form>
    		</div>
  		</div>
	</div>
	<script type="text/javascript">
		(function($){
			$.ajax({
				async: true,
				type: 'get',
				dataType: 'json',
				url: '<?php echo site_url('manage/usergroup/read');?>',
				success: function(msg){
						if(msg.error == 0){
							var Item = '', Line = '';
							var Content = msg.data.content;
							for(var i in Content){
								Line = '|';
								for(var k=0; k < Content[i]['class']; k++){
									Line += '---';
								}
								Item += '<option value="'+Content[i]['uid']+'" >'+Line+Content[i]['name']+'</option>';
							}
				            $('#userModal select[name="ugid"]').append(Item);
				        }
					}
			});

			$('div#user').handle_page();
		    $('div#userModal').handle_modal_000();
		})(jQuery);
	</script>