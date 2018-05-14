<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-25
 * @author ZhangCC
 * @version
 * @description  
 */
?>
    <div class="page-line" id="supplier">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
				<div class="input-group" id="supplierSearch" data-toggle="filter" data-target="#supplierTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
			<div class="col-md-offset-3 col-md-6 text-right" id="supplierFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="supplierTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#supplierTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#supplierModal" data-action="<?php echo site_url('supplier/supplier/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#supplierModal" data-action="<?php echo site_url('supplier/supplier/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#supplierTable" href="<?php echo site_url('supplier/supplier/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="supplierTable" data-load="<?php echo site_url('supplier/supplier/read') ?>">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th class="td-md" data-name="name">名称</th>
						<th class="td-md" data-name="code">代号</th>
						<th class="td-md" data-name="remark">备注</th>
					</tr>
				</thead>
				<tbody>
					<tr class="loading"><td colspan="9">加载中...</td></tr>
                    <tr class="no-data"><td colspan="9">没有数据</td></tr>
                	<tr class="model">
                        <td ><input name="sid"  type="checkbox" value=""/></td>
                        <td name="name"></td>
                        <td name="code"></td>
                        <td name="remark"></td>
                	</tr>
				</tbody>
			</table>
			<div class="hide btn-group pull-right paging">
			    <p class="footnote"></p>
				<ul class="pagination">
				    <li><a href="1">首页</a></li>
					<li class=""><a href="javascript:void(0);" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
					<li><a href=""></a></li>
					<li class=""><a href="" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
					<li><a href="">尾页</a></li>
	  			</ul>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="supplierModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="supplierForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="supplierModalLabel">编辑</h4>
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
			      			<label class="control-label col-md-2" >代号:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="code" type="text" placeholder="代号" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >备注:</label>
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
	<script>
		(function($){
			$('div#supplier').handle_page();
			$('div#supplierModal').handle_modal_000();
		})(jQuery);
	</script>