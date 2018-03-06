<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author Zhangcc
 * @version
 * @des
 * 正在入库
 */
?>
    <div class="page-line" id="ining" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="iningSearch" data-toggle="filter" data-target="#iningTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="iningFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="iningTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#iningTable">
		      			<li><a href="javascript:void(0);" data-toggle="child" data-target="#iningTable" data-action="<?php echo site_url('order/ining_detail/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;入库详情</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="iningTable" data-load="<?php echo site_url('order/ining/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >订单编号</th>
						<th >总件数</th>
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
			      		<td ><input name="oid"  type="checkbox" value=""/></td>
			      		<td name="order_num"><a href="<?php echo site_url('order/order_detail/index/read/order');?>" title="订单详情" data-toggle="floatover" data-target="#iningFloatover" data-remote="<?php echo site_url('order/order_detail/index/read_floatover');?>"></a></td>
			      		<td name="pack"></td>
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
		<div class="floatover hide" id="iningFloatover"></div>
	</div>
	<script>
		(function($){
			$('div#ining').handle_page();
		})(jQuery);
	</script>