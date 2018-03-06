<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月14日
 * @author Zhangcc
 * @version
 * @des
 * 图纸库
 */
?>
	<div class="page-line" id="drawing" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="drawingSearch" data-toggle="filter" data-target="#drawingTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="名称">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="drawingFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="drawingFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="drawingTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#drawingTable">
		      			<li><a href="<?php echo site_url('drawing/drawing/index/preview');?>" target="_blank" data-toggle="blank" data-target="#drawingTable" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;预览</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" href="<?php echo site_url('drawing/drawing/remove');?>" data-target="#drawingTable" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="drawingTable" data-load="<?php echo site_url('drawing/drawing/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th class="td-md">名称</th>
						<th class="td-md">订单产品</th>
						<th>产品名称</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="did"  type="checkbox" value=""/></td>
						<td name="name"></td>
						<td name="order_product_num"></td>
						<td name="product"></td>
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
		<div class="floatover hide" id="drawingFloatover"></div>
	</div>
	<script>
		(function($){
			$('div#drawing').handle_page();
		})(jQuery);
	</script>