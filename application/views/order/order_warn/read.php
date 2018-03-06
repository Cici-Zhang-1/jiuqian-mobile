<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-28
 * @author ZhangCC
 * @version
 * @description  
 * 订单列表
 */
?>
	<div class="page-line" id="orderWarn">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="orderWarnSearch" data-toggle="filter" data-target="#orderWarnTable">
				    <span class="input-group-btn">
        				<button class="btn btn-default" type="button" data-toggle="modal" data-target="#orderWarnFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="days" value="3"/>
      				<input type="hidden" name="area" value="1,2"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/业主/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderWarnFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderWarnTable" data-load="<?php echo site_url('order/order_warn/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >要求出厂<i class="fa fa-sort"></i></th>
						<th >等级<i class="fa fa-sort"></i></th>
						<th >确认时间<i class="fa fa-sort"></i></th>
						<th >订单编号<i class="fa fa-sort"></i></th>
						<th >客户</th>
						<th >业主</th>
						<th >备注</th>
						<th >金额</th>
						<th >创建人</th>
						<th >创建时间</th>
						<th >状态<i class="fa fa-sort"></i></th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="oid"  type="checkbox" value=""/></td>
			      		<td name="request_outdate"></td>
			      		<td name="icon"></td>
			      		<td name="asure_datetime"></td>
			      		<td name="order_num"><a href="<?php echo site_url('order/order_detail/index/read/order');?>" title="订单详情" data-toggle="floatover" data-target="#orderWarnFloatover" data-remote="<?php echo site_url('order/order_detail/index/read_floatover');?>"></a></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="remark"></td>
						<td name="sum"></td>
						<td name="creator"></td>
						<td name="create_datetime"></td>
						<td name="status"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
		<div class="floatover hide" id="orderWarnFloatover"></div>
	</div>
	
    <div class="modal fade filter" id="orderWarnFilterModal" tabindex="-1" role="dialog" aria-labelledby="orderWarnFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="orderWarnFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="orderWarnFilterModalLabel">搜索</h4>
          			</div>
    		      	<div class="modal-body">
    		      	    <div class="form-group">
			      			<label class="control-label col-md-2">天数:</label>
			      			<div class="col-md-6">
			      			    <input class="form-control" name="days" value="" />
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">区域:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="area">
			      			        <option value="1">武汉区域</option>
			      			        <option value="2">外地区域</option> 
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
			var SessionData = undefined, Item, Index;
			$('div#orderWarn').handle_page();
		    $('div#orderWarnFilterModal').handle_modal_000();
		})(jQuery);
	</script>