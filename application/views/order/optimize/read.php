<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Zhangcc
 * @version
 * @des
 * 优化列表
 */
?>
	<div class="page-line" id="optimize">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="optimizeSearch" data-toggle="filter" data-target="#optimizeTable">
				    <span class="input-group-btn">
        				<button class="btn btn-default" type="button" data-toggle="modal" data-target="#optimizeFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="optimize" value="0"/>
      				<input type="hidden" name="product" value="1"/>
      				<input type="hidden" name="sort" value="num"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/客户/板材/优化时间">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="optimizeFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="optimizeTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#optimizeTable">
		    		    <li><a data-toggle="blank" data-target="#optimizeTable" href="<?php echo site_url('order/optimize/download/preview');?>" target="_blank" data-multiple=true><i class="fa fa-eye"></i>&nbsp;&nbsp;预览</a></li>
		      			<li><a data-toggle="blank" data-target="#optimizeTable" href="<?php echo site_url('order/optimize/download/optimize');?>" target="_blank" data-multiple=true><i class="fa fa-download"></i>&nbsp;&nbsp;给优化</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="optimizeTable" data-load="<?php echo site_url('order/optimize/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected">#</th>
						<th >板材</th>
						<th >订单编号</th>
						<th >板块归类</th>
						<th >面积</th>
						<th >板块数</th>
						<th >产品备注</th>
						<th >确认时间</th>
						<th >要求出厂</th>
						<th >客户</th>
						<th >业主</th>
						<th >备注</th>
						<th >拆单人</th>
						<th >优化人</th>
						<th >优化时间</th>
						<th >状态</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opcid"  type="checkbox" value=""/></td>
			      		<td name="board"></td>
			      		<td name="order_product_num"></td>
			      		<td name="classify"></td>
			      		<td name="area"></td>
			      		<td name="amount"></td>
			      		<td name="order_product_remark"></td>
			      		<td name="asure_datetime"></td>
			      		<td name="request_outdate"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="remark"></td>
						<td name="dismantler"></td>
						<td name="optimizer"></td>
						<td name="optimize_datetime"></td>
						<td name="status"></td>
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
	
    <div class="modal fade filter" id="optimizeFilterModal" tabindex="-1" role="dialog" aria-labelledby="optimizeFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="optimizeFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="optimizeFilterModalLabel">搜索</h4>
          			</div>
    		      	<div class="modal-body">
    		      	    <div class="form-group">
			      			<label class="control-label col-md-2">状态:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="optimize" multiple="multiple">
    		      					<option value="0" data-sort="num">--未优化--</option>
    		      					<option value="1" data-sort="datetime">--已优化--</option>
    		      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">产品类型:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="product" multiple="multiple">
    		      					<option value="1">--橱柜--</option>
    		      					<option value="2">--衣柜--</option>
    		      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">排序方式:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="sort" >
    		      					<option value="num">--订单编号--</option>
    		      					<option value="board">--板材--</option>
    		      					<option value="datetime">--优化时间--</option>
    		      				</select>
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
			$('#optimizeFilterModal select[name="optimize"]').on('change', function(e){
				if(1 == $(this).find('option:selected').length){
					var Sort = $(this).find('option:selected').data('sort');
					$('#optimizeFilterModal select[name="sort"] option[value="'+Sort+'"]').prop('selected', true);
				}
			});
			$('div#optimize').handle_page();
		    $('div#optimizeFilterModal').handle_modal_000();
		})(jQuery);
	</script>