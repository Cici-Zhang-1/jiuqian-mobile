<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月25日
 * @author Administrator
 * @version
 * @des
 * 状态节点
 */
?>
    <div class="page-line" id="workflowNode" data-load="<?php echo site_url('manage/workflow_node/read');?>">
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="hide input-group" id="workflowNodeSearch" data-toggle="search" data-target="#workflowNodeTable">
                    <input type="hidden" name="id" value="<?php echo $Id;?>"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="workflowNodeSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="workflowNodeFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="workflowNodeTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#workflowNodeTable">
		      			<li><a id="workflowEdit" href="javascript:void(0);" data-toggle="modal" data-target="#workflowNodeModal" data-action="<?php echo site_url('manage/workflow_node/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#workflowNodeModal" data-action="<?php echo site_url('manage/workflow_node/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#workflowNodeTable" href="<?php echo site_url('manage/workflow_node/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="workflowNodeTable">
				<thead>
					<tr>
						<th class="td-xs" >#</td>
						<th class="td-xs" >层级</th>
						<th >名称</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="9">加载中...</td></tr>
					<tr class="no-data"><td colspan="9">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="mid"  type="checkbox" value=""/></td>
						<td name="line"></td>
						<td name="des"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
    </div>
    <div class="modal fade" id="workflowModal" tabindex="-1" role="dialog" aria-labelledby="workflowModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal" id="workflowForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="workflowModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      	    <input type="hidden" name="workflow_id" value="<?php echo $Id;?>" />
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2">名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="名称" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">代号:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="code" type="text" placeholder="代号" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">父级:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="parent" data-filter="">
			      					<option value="0" data-class="-1">---</option>
			      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">备注:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="remark" type="text" placeholder="备注" value=""/>
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
		(function($, window, undefined){
			$('div#workflowNode').handle_page();
		})(jQuery);
	</script>