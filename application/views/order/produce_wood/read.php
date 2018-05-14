<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 2015年7月8日
 * @author Administrator
 * @version
 * @description
 * 橱柜生产清单
 */
 $StartDate = date('Y-m-d', time()-MONTHS);
?>
    <div class="page-line" id="produceWood">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="produceWoodSearch" data-toggle="filter" data-target="#produceWoodTable">
		      		<span class="input-group-btn">
        				<button class="btn btn-default" type="button" data-toggle="modal" data-target="#produceWoodFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="product" value="4"/>
      				<input type="hidden" name="start_date" value="<?php echo $StartDate;?>"/>
      				<input type="hidden" name="end_date" value=""/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/业主/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="produceWoodFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="produceWoodTable" data-load="<?php echo site_url('order/produce_wood/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >板材</th>
						<th >订单编号</th>
						<th >面积</th>
						<th >确认时间</th>
						<th >要求出厂</th>
						<th >客户</th>
						<th >业主</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opbid"  type="checkbox" value=""/></td>
			      		<td name="board"></td>
			      		<td name="order_product_num"></td>
			      		<td name="area"></td>
			      		<td name="asure_datetime"></td>
			      		<td name="request_outdate"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal fade filter" id="produceWoodFilterModal" tabindex="-1" role="dialog" aria-labelledby="produceWoodFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="produceWoodFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="produceWoodFilterModalLabel">搜索</h4>
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
			$('div#produceWood').handle_page();
			$('div#produceWoodFilterModal').handle_modal_000();
		})(jQuery);
	</script>