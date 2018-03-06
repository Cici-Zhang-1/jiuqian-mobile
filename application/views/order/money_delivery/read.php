<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月21日
 * @author Zhangcc
 * @version
 * @des
 */
?>
	<div class="page-line" id="moneyDelivery" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="moneyDeliverySearch" data-toggle="filter" data-target="#moneyDeliveryTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="moneyDeliveryFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="moneyDeliveryFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<!-- <a class="btn btn-default" data-toggle="backstage" href="<?php echo site_url('order/money_delivery/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;作废</a> -->
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="moneyDeliveryTable" data-load="<?php echo site_url('order/money_delivery/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >订单编号</th>
						<th >客户</th>
						<th >业主</th>
						<th >备注</th>
						<th >支付人</th>
						<th >联系电话</th>
						<th >金额</th>
						<th >等待生产</th>
						<th >正在生产</th>
						<th >账户余额</th>
						<th >要求出厂</th>
						<th >确认时间</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="oid"  type="checkbox" value=""/></td>
			      		<td name="order_num"><a href="<?php echo site_url('order/order_detail/index/read/order');?>" title="订单详情" data-toggle="floatover" data-target="#moneyDeliveryFloatover" data-remote="<?php echo site_url('order/order_detail/index/read_floatover');?>"></a></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="remark"></td>
						<td name="payer"></td>
						<td name="payer_phone"></td>
						<td name="sum"></td>
						<td name="debt1"></td>
						<td name="debt2"></td>
						<td name="balance"></td>
						<td name="request_outdate"></td>
						<td name="asure_datetime"></td>
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
		<div class="floatover hide" id="moneyDeliveryFloatover"></div>
	</div>
	
	<script>
		(function($){
			$('div#moneyDelivery').handle_page();
		})(jQuery);
	</script>