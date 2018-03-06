<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月27日
 * @author Administrator
 * @version
 * @des
 */
 
$StartDate = date('Y-m-d', time()-DAYS);
?>
    <div class="page-line" id="orderClearFitting">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="orderClearFittingSearch" data-toggle="filter" data-target="#orderClearFittingTable">
		      		<input type="text" class="form-control" name="start_date" id="orderClearFittingDate" placeholder="选择日期" value="<?php echo $StartDate;?>">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderClearFittingFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="orderClearFittingTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#orderClearFittingTable">
		      			<li><a href="<?php echo site_url('order/order_clear_fitting/index/edit');?>" data-toggle="blank" data-target="#orderClearFittingTable" target="_blank" data-multiple=true><i class="fa fa-print"></i>&nbsp;&nbsp;打印</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderClearFittingTable" data-load="<?php echo site_url('order/order_clear_fitting/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >等级</th>
						<th >订单编号</th>
						<th >客户</th>
						<th >业主</th>
						<th >备注</th>
						<th >确认时间</th>
						<th >要求出厂</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opid"  type="checkbox" value=""/></td>
			      		<td name="icon"></td>
			      		<td name="order_product_num"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="remark"></td>
						<td name="asure_datetime"></td>
						<td name="request_outdate"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
	</div>
	<script>
		(function($){
			$('#orderClearFittingDate').datepicker({
				todayBtn: "linked",
                language: "zh-CN",
                orientation: "top auto",
                autoclose: true,
                todayHighlight: true
			});
			$('div#orderClearFitting').handle_page();
		})(jQuery);
	</script>