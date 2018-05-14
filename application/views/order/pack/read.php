<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月6日
 * @author Zhangcc
 * @version
 * @des
 * 扫描列表
 */
?>
    <div class="page-line" id="pack">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
				<div class="input-group" id="packSearch" data-toggle="filter" data-target="#packTable">
				    <span class="input-group-btn">
        				<button class="btn btn-default" type="button" data-toggle="modal" data-target="#packFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="start_date" value=""/>
      				<input type="hidden" name="end_date" value=""/>
      				<input type="hidden" name="scan" value="0,1,2"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="packFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="packTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#packTable">
		      			<li><a href="javascript:void(0);" data-toggle="child" data-target="#packTable" data-action="<?php echo site_url('order/pack_detail/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;扫描状态</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="packTable" data-load="<?php echo site_url('order/pack/read');?>">
				<thead>
					<tr>
						<th class="td-sm" data-name="selected">#</th>
						<th >编号</th>
						<th >产品</th>
						<th >客户</th>
						<th >业主</th>
						<th >开始日期</th>
						<th >最后日期</th>
						<th >状态</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="9">加载中...</td></tr>
					<tr class="no-data"><td colspan="9">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opid"  type="checkbox" value=""/></td>
			      		<td name="order_product_num"></td>
			      		<td name="product"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="start_date"></td>
						<td name="end_date"></td>
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
	<div class="modal fade filter" id="packFilterModal" tabindex="-1" role="dialog" aria-labelledby="packFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="packFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="packFilterModalLabel">搜索</h4>
          			</div>
    		      	<div class="modal-body">
    		      	    <div class="form-group">
			      			<label class="control-label col-md-2">状态:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="scan" multiple>
			      				    <option value="0">--未开始--</option>
    		      					<option value="1">--正在扫--</option>
    		      					<option value="2">--已扫完--</option>
    		      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >开始日期:</label>
			      			<div class="col-md-6">
			      				<input class="form-control datepicker" name="start_date" type="text" value="" /> 
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >结束日期:</label>
			      			<div class="col-md-6">
			      				<input class="form-control datepicker" name="end_date" type="text" value="" /> 
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
	<script type="text/javascript">
		(function($, window, undefined){
		    $('div#pack').handle_page();
		    $('div#packFilterModal').handle_modal_000();
		})(jQuery);
	</script>