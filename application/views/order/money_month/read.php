<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月23日
 * @author Zhangcc
 * @version
 * @des
 * 打款生产
 */
?>
	<div class="page-line" id="moneyMonth" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="moneyMonthSearch" data-toggle="filter" data-target="#moneyMonthTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/备注/车辆/车次">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="moneyMonthFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="moneyMonthFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="moneyMonthTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#moneyMonthTable">
		      			<li><a href="javascript:void(0);" data-toggle="backstage" data-target="#moneyMonthTable" data-action="<?php echo site_url('order/money_month/redelivery');?>" data-multiple=true><i class="fa fa-reply"></i>&nbsp;&nbsp;重新发货</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="moneyMonthTable" data-load="<?php echo site_url('order/money_month/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >出厂日期</th>
						<th >货车</th>
						<th >车次</th>
						<th >安排人</th>
						<th >订单编号</th>
						<th >金额</th>
						<th >件数</th>
						<th >物流</th>
						<th >收货地区</th>
						<th >收货地址</th>
						<th >收货人</th>
						<th >联系电话</th>
						<th >客户</th>
						<th >业主</th>
						<th >备注</th>
						<th class="hide">soid</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="oid"  type="checkbox" value=""/></td>
			      		<td name="end_datetime"></td>
			      		<td name="truck"></td>
						<td name="train"></td>
						<td name="creator"></td>
						<td name="order_num"><a href="<?php echo site_url('order/order_detail/index/read/order');?>" title="订单详情" data-toggle="floatover" data-target="#moneyMonthFloatover" data-remote="<?php echo site_url('order/order_detail/index/read_floatover');?>"></a></td>
			      		<td name="sum"></td>
						<td name="pack"></td>
						<td name="logistics"></td>
						<td name="delivery_area"></td>
						<td name="delivery_address"></td>
						<td name="delivery_linker"></td>
						<td name="delivery_phone"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="remark"></td>
						<td class="hide" name="soid"></td>
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
		<div class="floatover hide" id="moneyMonthFloatover"></div>
	</div>
	<script>
		(function($){
			$('div#moneyMonth').handle_page();
		})(jQuery);
	</script>