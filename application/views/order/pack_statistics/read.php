<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月18日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="packStatistics" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="packStatisticsSearch" data-toggle="filter" data-target="#packStatisticsTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="packStatisticsFunction">
	  		    <a class="btn btn-primary" href="<?php echo site_url('order/delivery_label/index/read');?>" target="_blank" data-toggle="blank" ><i class="fa fa-square"></i>&nbsp;&nbsp;打印发货标签</a>
		  		<a class="btn btn-primary" href="<?php echo site_url('order/pack_label/index/read');?>" target="_blank" data-toggle="blank" ><i class="fa fa-square"></i>&nbsp;&nbsp;打印包装标签</a>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="packStatisticsTable" data-load="<?php echo site_url('order/pack_statistics/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >订单编号</th>
						<th >产品名称</th>
						<th >件数</th>
						<th >打印人</th>
						<th >打印时间</th>
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
			      		<td name="order_product_num"></td>
			      		<td name="product"></td>
			      		<td name="pack"></td>
			      		<td name="packer"></td>
			      		<td name="pack_datetime"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="remark"></td>
						<td name="asure_datetime"></td>
						<td name="request_outdate"></td>
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
	<script>
		(function($){
			$('div#packStatistics').handle_page();
		})(jQuery);
	</script>