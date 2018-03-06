<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年6月1日
 * @author Zhangcc
 * @version
 * @des
 * 等待生产预处理
 */
?>
	<div class="page-line" id="producing" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="producingSearch" data-toggle="filter" data-target="#producingTable">
      				<input type="hidden" name="status" value="4"/>
					<!--<input type="hidden" name="o_status" value="12"/>-->
      				<input type="hidden" name="product" value="1,2"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单产品编号/经销商/业主/产品备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="producingFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="producingFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="producingTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu">
		      			<li><a data-toggle="backstage" data-target="#producingTable" href="<?php echo site_url('order/producing/edit');?>" data-multiple=true><i class="fa fa-undo"></i>&nbsp;&nbsp;重新生产</a></li>
		    		</ul>
		  		</div>
				<a class="btn btn-primary" href="<?php echo site_url('order/order_product_board_plate/index/label');?>" target="_blank" data-toggle="blank" ><i class="fa fa-square"></i>&nbsp;&nbsp;打印板块标签</a>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="producingTable" data-load="<?php echo site_url('order/producing/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >订单产品编号</th>
						<th >产品名称</th>
						<th >客户</th>
						<th >业主</th>
						<th >备注</th>
						<th >拆单人</th>
						<th >确认时间</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opid"  type="checkbox" value=""/></td>
			      		<td name="num"></td>
			      		<td name="product"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="remark"></td>
						<td name="dismantler"></td>
						<td name="asure_datetime"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
	</div>
	<script>
		(function($){
			$('div#producing').handle_page();
		})(jQuery);
	</script>