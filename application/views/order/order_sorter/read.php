<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月24日
 * @author Administrator
 * @version
 * @des
 * 下单排行榜
 */
?>
	<div class="page-line" id="orderSorter">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="orderSorterSearch" data-toggle="filter" data-target="#orderSorterTable">
				    <span class="input-group-btn">
        				<button class="btn btn-default" type="button" data-toggle="modal" data-target="#orderSorterFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="start_date" value=""/>
      				<input type="hidden" name="end_date" value=""/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/业主/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderSorterFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderSorterTable" data-load="<?php echo site_url('order/order_sorter/read');?>">
				<thead>
					<tr>
					    <th >#</th>
						<th >客户</th>
						<th >订单数量</th>
						<th >金额<i class="fa fa-sort"></i></th>
						<th >橱柜<i class="fa fa-sort"></i></th>
						<th >衣柜<i class="fa fa-sort"></i></th>
						<th >门板<i class="fa fa-sort"></i></th>
						<th >木框门<i class="fa fa-sort"></i></th>
						<th >配件<i class="fa fa-sort"></i></th>
						<th >外购<i class="fa fa-sort"></i></th>
						<th >服务<i class="fa fa-sort"></i></th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      	    <td name="key"></td>
						<td name="dealer"></td>
						<td name="amount"></td>
						<td name="sum"></td>
						<td name="cabinet"></td>
						<td name="wardrobe"></td>
						<td name="door"></td>
						<td name="kuang"></td>
						<td name="fitting"></td>
						<td name="other"></td>
						<td name="server"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
		<div class="floatover hide" id="orderFloatover"></div>
	</div>
	
    <div class="modal fade filter" id="orderSorterFilterModal" tabindex="-1" role="dialog" aria-labelledby="orderSorterFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="orderSorterFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="orderSorterFilterModalLabel">搜索</h4>
          			</div>
    		      	<div class="modal-body">
			      		<div class="form-group">
			      			<label class="control-label col-md-2">开始日期:</label>
			      			<div class="col-md-6">
			      			    <input class="form-control datepicker" name="start_date" value="" />
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">结束日期:</label>
			      			<div class="col-md-6">
			      			    <input class="form-control datepicker" name="end_date" value="" />
			      			</div>
			      		</div>
    		      	</div>
    		      	<div class="modal-footer">
    		        	<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    		        	<button type="submit" class="btn btn-primary" data-dismiss="modal">保存</button>
    		      	</div>
			    </form>
    		</div>
  		</div>
	</div>
	<script>
		(function($){
			$('div#orderSorter').handle_page();
		    $('div#orderSorterFilterModal').handle_modal_000();
		})(jQuery);
	</script>