<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月16日
 * @author Administrator
 * @version
 * @des
 */
?>
	<div class="page-line" id="tableSaw" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="tableSawSearch" data-toggle="search" data-target="#tableSawTable">
      				<input type="hidden" name="status" value="3"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="tableSawFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="tableSawTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#tableSawTable">
		      			<li><a href="<?php echo site_url('order/table_saw/edit');?>" data-toggle="backstage" data-target="#tableSawTable" data-multiple=true ><i class="fa fa-gavel"></i>&nbsp;&nbsp;确认下料</a></li>
		    		</ul>
		  		</div>
		  		<a class="btn btn-primary" href="<?php echo site_url('order/classify_label/index/read');?>" target="_blank" data-toggle="blank" ><i class="fa fa-archive"></i>&nbsp;&nbsp;分类标签</a>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="tableSawTable" data-load="<?php echo site_url('order/table_saw/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >订单编号</th>
						<th >分类码</th>
						<th >板块归类</th>
						<th >板材</th>
						<th >客户</th>
						<th >业主</th>
						<th >备注</th>
						<th >要求出厂</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opcid"  type="checkbox" value=""/></td>
			      		<td name="order_product_num"></td>
			      		<td name="status"></td>
			      		<td name="classify"></td>
			      		<td name="board"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="remark"></td>
						<td name="request_outdate"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="tableSawModal" tabindex="-1" role="dialog" aria-labelledby="tableSawModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="tableSawModalForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="tableSawModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2">要求出厂:</label>
			      			<div class="col-md-6">
			      			    <input class="form-control datepicker" name="request_outdate" placeholder="要求出厂日期"/>
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
			$('div#tableSaw').handle_page();
			$('div#tableSawModal').handle_modal_000();
		})(jQuery);
	</script>