<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月21日
 * @author Zhangcc
 * @version
 * @des
 * 已出厂
 */
?>
	<div class="page-line" id="stockOutted" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="stockOuttedSearch" data-toggle="filter" data-target="#stockOuttedTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="车辆/车次">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="stockOuttedFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="stockOuttedFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="stockOuttedTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#stockOuttedTable">
		      			<li><a href="javascript:void(0);" data-toggle="mtab" data-target="#stockOuttedTable" data-action="<?php echo site_url('order/cargo_no/index/edit');?>" data-multiple=false><i class="fa fa-edit"></i>&nbsp;&nbsp;填写货号</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a href="javascript:void(0);" data-toggle="backstage" data-target="#stockOuttedTable" data-action="<?php echo site_url('stock/stock_outted/redelivery');?>" data-multiple=true><i class="fa fa-reply"></i>&nbsp;&nbsp;重新发货</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a href="javascript:void(0);" data-toggle="child" data-target="#stockOuttedTable" data-action="<?php echo site_url('stock/stock_outted_detail/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;查看详情</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a href="<?php echo site_url('stock/stock_outted_reprint/index/read');?>" data-toggle="blank" data-target="#stockOuttedTable" target="_blank" data-multiple=false><i class="fa fa-print"></i>&nbsp;&nbsp;重新打印</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="stockOuttedTable" data-load="<?php echo site_url('stock/stock_outted/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >出厂日期</th>
						<th >货车</th>
						<th >车次</th>
						<th >件数</th>
						<th >代收总额</th>
						<th >安排人</th>
						<th >打印时间</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="soid"  type="checkbox" value=""/></td>
			      		<td name="end_datetime"></td>
			      		<td name="truck"></td>
						<td name="train"></td>
						<td name="amount"></td>
						<td name="logistics"></td>
						<td name="creator"></td>
			      		<td name="create_datetime"></td>
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
	
	<div class="modal fade" id="stockOuttedModal" tabindex="-1" role="dialog" aria-labelledby="stockOuttedModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="stockOuttedModalForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="stockOuttedModalLabel">填写货号</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2">货号:</label>
			      			<div class="col-md-6">
			      			    <input class="form-control" name="cargo_no" value="" placeholder="货号"/>
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
			$('div#stockOutted').handle_page();
			$('div#stockOuttedModal').handle_modal_000();
		})(jQuery);
	</script>