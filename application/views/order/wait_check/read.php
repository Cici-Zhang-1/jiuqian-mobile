<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月23日
 * @author Zhangcc
 * @version
 * @des
 * 等待核价
 */
?>
	<div class="page-line" id="waitCheck" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="waitCheckSearch" data-toggle="filter" data-target="#waitCheckTable">
			        <span class="input-group-btn">
        				<button class="btn btn-default" type="button" data-toggle="modal" data-target="#waitCheckFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="status" value="4"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/业主/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="waitCheckFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="waitCheckTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#waitCheckTable">
		      			<li><a href="javascript:void(0);" data-toggle="mtab" data-target="#waitCheckTable" data-action="<?php echo site_url('order/wait_check/index/edit');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;核价</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a href="javascript:void(0);" data-toggle="mtab" data-target="#waitCheckTable" data-action="<?php echo site_url('order/dismantle/index/redismantle/order');?>" data-multiple=false><i class="fa fa-arrows"></i>&nbsp;&nbsp;拆单</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a data-toggle="backstage" data-target="#waitCheckTable" href="<?php echo site_url('order/wait_check/edit_asure');?>" data-multiple=true><i class="fa fa-gavel"></i>&nbsp;&nbsp;确认核价</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<!-- <a class="btn btn-default" data-toggle="backstage" href="<?php echo site_url('order/wait_check/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;作废</a> -->
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="waitCheckTable" data-load="<?php echo site_url('order/wait_check/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >等级</th>
						<th >订单编号</th>
						<th >客户</th>
						<th >业主</th>
						<th data-name="remark">备注</th>
						<th >要求出厂</th>
						<th >拆单时间</th>
						<th >金额</th>
						<th >厨</th>
						<th >衣</th>
						<th >门</th>
						<th >框</th>
						<th >配</th>
						<th >外购</th>
						<th >服务</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="oid"  type="checkbox" value=""/></td>
			      		<td name="icon"></td>
			      		<td name="order_num"><a href="<?php echo site_url('order/order_detail/index/read/order');?>" title="订单详情" data-toggle="floatover" data-target="#waitCheckFloatover" data-remote="<?php echo site_url('order/order_detail/index/read_floatover');?>"></a></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="remark"></td>
						<td name="request_outdate"></td>
						<td name="dismantled_datetime"></td>
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
		<div class="floatover hide" id="waitCheckFloatover"></div>
	</div>
	
    <div class="modal fade filter" id="waitCheckFilterModal" tabindex="-1" role="dialog" aria-labelledby="waitCheckFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="waitCheckFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="waitCheckFilterModalLabel">搜索</h4>
          			</div>
    		      	<div class="modal-body">
    		      	   <div class="form-group">
			      			<label class="control-label col-md-2">状态:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="status" multiple="multiple">
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
			var SessionData;
			if(!(SessionData = $.sessionStorage('workflow_wait_check'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/workflow/read/wait_check');?>',
					success: function(msg){
						if(msg.error == 0){
							var Item = '', Content = msg.data.content;
							for(var i in Content){
								Item += '<option value="'+Content[i]['no']+'" >'+Content[i]['name']+'</option>';
							}
							$('#waitCheckFilterModal select[name="status"]').append(Item);
							$('#waitCheckSearch input[name="status"]').val(Content[0]['no']);
				            $.sessionStorage('workflow_wait_check', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['no']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#waitCheckFilterModal select[name="status"]').append(Item);
	            $('#waitCheckSearch input[name="status"]').val(SessionData[0]['no']);
			}
			$('div#waitCheck').handle_page();
			$('div#waitCheckFilterModal').handle_modal_000();
		})(jQuery);
	</script>