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
	<div class="page-line" id="producePrehandle" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="producePrehandleSearch" data-toggle="filter" data-target="#producePrehandleTable">
      				<input type="hidden" name="status" value="3"/>
      				<input type="hidden" name="product" value="1,2"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单产品编号/经销商/业主/产品备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="producePrehandleFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="producePrehandleFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="producePrehandleTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu">
						<li><a href="javascript:void(0);" data-toggle="child" data-target="#producePrehandleTable" data-action="<?php echo site_url('order/produce_prehandled/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;查看预处理</a></li>
						<li role="separator" class="divider"></li>
						<li><a data-toggle="blank" data-target="#producePrehandleTable" href="<?php echo site_url('order/optimize/produce_prehandle');?>" target="_blank" data-multiple=true><i class="fa fa-download"></i>&nbsp;&nbsp;导出Excel</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a data-toggle="backstage" data-target="#producePrehandleTable" href="<?php echo site_url('order/produce_prehandle/edit');?>" data-multiple=true><i class="fa fa-gavel"></i>&nbsp;&nbsp;重新分类</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="producePrehandleTable" data-load="<?php echo site_url('order/produce_prehandle/read');?>">
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
			$('div#producePrehandle').handle_page();
		})(jQuery);
	</script>