<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月6日
 * @author Zhangcc
 * @version
 * @des
 * 差板子
 */
?>
    <div class="page-line" id="lack">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
				<div class="input-group" id="lackSearch" data-toggle="search" data-target="#lackTable">
		      		<input type="text" class="form-control" name="keyword"  placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="lackSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="lackFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="lackTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#lackTable">
		      			<li><a href="javascript:void(0);" data-toggle="child" data-target="#lackTable" data-action="<?php echo site_url('order/lack_detail/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;查看板块</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="lackTable" data-load="<?php echo site_url('order/lack/read');?>">
				<thead>
					<tr>
						<th class="td-sm" data-name="selected">#</th>
						<th >编号<i class="fa fa-sort"></i></th>
						<th >客户<i class="fa fa-sort"></i></th>
						<th >业主</th>
						<th >板材</th>
						<th >差数量</th>
						<th >开始时间<i class="fa fa-sort"></i></th>
						<th >最后时间<i class="fa fa-sort"></i></th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="18">加载中...</td></tr>
					<tr class="no-data"><td colspan="18">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opid"  type="checkbox" value=""/></td>
						<td name="order_product_num"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="board"></td>
						<td name="amount"></td>
						<td name="scan_start"></td>
						<td name="scan_end"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		(function($, window, undefined){
		    $('div#lack').handle_page();
		})(jQuery);
	</script>