<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Administrator
 * @version
 * @des
 */
?>
	<div class="page-line" id="bd" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="bdSearch" data-toggle="filter" data-target="#bdTable">
			        <span class="input-group-btn">
        				<button class="btn btn-default" type="button" data-toggle="modal" data-target="#bdFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="bd" value="1"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="bdFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    	      			共选中<span id="bdTableSelected" data-num="">0</span>项
    	      			<span class="caret"></span>
    	    		</button>
    	    		<ul class="dropdown-menu" role="menu" data-table="#bdTable">
    	      			<li><a href="<?php echo site_url('order/bd/download');?>" target="_blank" data-toggle="blank" data-target="#bdTable" data-multiple=true><i class="fa fa-download"></i>&nbsp;&nbsp;导出</a></li>
    	    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="bdTable" data-load="<?php echo site_url('order/bd/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >订单编号</th>
						<th >产品名称</th>
						<th >产品备注</th>
						<th >客户</th>
						<th >业主</th>
						<th >备注</th>
						<th >拆单人</th>
						<th >要求出厂</th>
						<th >确认时间</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="10">加载中...</td></tr>
					<tr class="no-data"><td colspan="10">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opid"  type="checkbox" value=""/></td>
			      		<td name="order_product_num"></td>
			      		<td name="product"></td>
			      		<td name="order_product_remark"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="remark"></td>
						<td name="dismantler"></td>
						<td name="request_outdate"></td>
						<td name="asure_datetime"></td>
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
	
	<div class="modal fade filter" id="bdFilterModal" tabindex="-1" role="dialog" aria-labelledby="bdFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="bdFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="bdFilterModalLabel">搜索</h4>
          			</div>
    		      	<div class="modal-body">
    		      	    <div class="form-group">
			      			<label class="control-label col-md-2">状态:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="bd" multiple="multiple">
    		      					<option value="1">--未导出--</option>
    		      					<option value="2">--已导出--</option>
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
			$('div#bd').handle_page();
			$('div#bdFilterModal').handle_modal_000();
		})(jQuery);
	</script>