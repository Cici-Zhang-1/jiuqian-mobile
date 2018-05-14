<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月9日
 * @author Zhangcc
 * @version
 * @des
 * 财务账户
 */
?>
    <div class="page-line" id="account">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="accountSearch" data-toggle="search" data-target="#accountTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
			<div class="col-md-offset-3 col-md-6 text-right" id="accountFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="accountTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#accountTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#accountModal" data-action="<?php echo site_url('finance/account/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#accountModal" data-action="<?php echo site_url('finance/account/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#accountTable" href="<?php echo site_url('finance/account/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="accountTable"  data-load="<?php echo site_url('finance/account/read') ?>">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th class="td-md" data-name="name">名称</th>
						<th class="td-md" data-name="host">户主</th>
						<th class="td-md" data-name="account">账号</th>
						<th class="td-md" >账户余额</th>
						<th class="td-md" data-name="in">收入金额</th>
						<th class="td-md" data-name="in_fee">收入手续费</th>
						<th class="td-md" data-name="out">支出金额</th>
						<th class="td-md" data-name="out_fee">支出手续费</th>
						<th class="hide" class="td-md" data-name="fee">费率</th>
						<th class="hide" class="td-md" data-name="fee_max">最高手续费</th>
						<th class="hide" class="td-md" data-name="intime">及时到账</th>
					</tr>
				</thead>
				<tbody>
					<tr class="loading"><td colspan="9">加载中...</td></tr>
                    <tr class="no-data"><td colspan="9">没有数据</td></tr>
                	<tr class="model">
                        <td ><input name="faid"  type="checkbox" value=""/></td>
                        <td name="name"></td>
                        <td name="host"></td>
                        <td name="account"></td>
                        <td name="balance"></td>
                        <td name="in"></td>
                        <td name="in_fee"></td>
                        <td name="out"></td>
                        <td name="out_fee"></td>
                        <td class="hide" name="fee"></td>
                        <td class="hide" name="fee_max"></td>
                        <td class="hide" name="intime"></td>
                	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="accountModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="accountModalForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="accountModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="名称" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >账号:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="account" type="text" placeholder="财务账号" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >户主:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="host" type="text" placeholder="户主" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >收入金额:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="in" type="text" placeholder="" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >收入手续费:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="in_fee" type="text" placeholder="" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >支出金额:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="out" type="text" placeholder="" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >支出手续费:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="out_fee" type="text" placeholder="" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >费率:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="fee" type="text" placeholder="费率" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >最高手续费:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="fee_max" type="text" placeholder="最高手续费" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >及时到账:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="intime">
			      			        <option value="1">及时到账</option>
			      			        <option value="0">非及时到账</option>
			      			    </select>
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
	<script>
		(function($){
			$('div#account').handle_page();
			$('div#accountModal').handle_modal_000();
		})(jQuery);
	</script>