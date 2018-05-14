<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月13日
 * @author Zhangcc
 * @version
 * @des
 * 工作流
 */
?>
    <div class="page-line" id="workflow">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="workflowSearch" data-toggle="search" data-target="#workflowTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
			<div class="col-md-offset-3 col-md-6 text-right" id="workflowFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="workflowTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#workflowTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#workflowModal" data-action="<?php echo site_url('data/workflow/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#workflowModal" data-action="<?php echo site_url('data/workflow/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#workflowTable" href="<?php echo site_url('data/workflow/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="workflowTable"  data-load="<?php echo site_url('data/workflow/read') ?>">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th data-name="no">编号<i class="fa fa-sort"></i></th>
						<th data-name="type">类型<i class="fa fa-sort"></i></th>
						<th data-name="name">名称<i class="fa fa-sort"></i></th>
						<th data-name="name_en">代号<i class="fa fa-sort"></i></th>
						<th data-name="file">执行文件<i class="fa fa-sort"></i></th>
					</tr>
				</thead>
				<tbody>
					<tr class="loading"><td colspan="9">加载中...</td></tr>
                    <tr class="no-data"><td colspan="9">没有数据</td></tr>
                	<tr class="model">
                        <td ><input name="wid"  type="checkbox" value=""/></td>
                        <td name="no"></td>
                        <td name="type"></td>
                        <td name="name"></td>
                        <td name="name_en"></td>
                        <td name="file"></td>
                	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="workflowModal" tabindex="-1" role="dialog" aria-labelledby="workflowModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="workflowForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="workflowModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >编号:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="no" type="text" placeholder="流程节点编号" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >类型:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="type" type="text" placeholder="流程节点类型" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="流程节点名称" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >代号:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name_en" type="text" placeholder="流程对应代号" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >执行文件:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="file" type="text" placeholder="对应流程执行文件" value=""/>
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
			$('div#workflow').handle_page();
			$('div#workflowModal').handle_modal_000();
		})(jQuery);
	</script>