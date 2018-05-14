<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月13日
 * @author Zhangcc
 * @version
 * @des
 * 等待拆单
 */
?>
	<div class="page-line" id="waitDismantle" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="waitDismantleSearch" data-toggle="filter" data-target="#waitDismantleTable">
				    <span class="input-group-btn">
        				<button class="btn btn-default" type="button" id="waitDismantleFilterCon" data-toggle="modal" data-target="#waitDismantleFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="status" value=""/>
      				<input type="hidden" name="product" value="1,2,3,4,5,6,7"/>
      				<input type="hidden" name="public" value="1"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单产品编号/经销商/业主/产品备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="waitDismantleFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="waitDismantleFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="waitDismantleTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu">
		      			<li><a href="javascript:void(0);" data-toggle="mtab" data-target="#waitDismantleTable" data-action="<?php echo site_url('order/dismantle/index/read/order_product');?>" data-multiple=false><i class="fa fa-arrows"></i>&nbsp;&nbsp;拆单</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a data-toggle="backstage" data-target="#waitDismantleTable" href="<?php echo site_url('order/wait_dismantle/edit');?>" data-multiple=true><i class="fa fa-gavel"></i>&nbsp;&nbsp;确认拆单</a></li>
		      			<!-- <li role="separator" class="divider"></li>
		      			<li><a href="javascript:void(0);" data-toggle="modal" data-target="#dismantlerModal" data-action="<?php echo site_url('order/wait_dismantle/index/edit_point');?>" data-multiple=true><i class="fa fa-hand-o-right"></i>&nbsp;&nbsp;指派拆单</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a href="javascript:void(0);" data-toggle="modal" data-target="#waitDismantleDealerModal" data-action="<?php echo site_url('order/order/edit');?>" data-multiple=true><i class="fa fa-pencil"></i>&nbsp;&nbsp;修改订单</a></li> -->
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#waitDismantleTable" href="<?php echo site_url('order/wait_dismantle/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;作废</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="waitDismantleTable" data-load="<?php echo site_url('order/wait_dismantle/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >订单产品编号</th>
						<th >产品名称</th>
						<th >客户</th>
						<th >业主</th>
						<th >备注</th>
						<th >创建人</th>
						<th >拆单人</th>
						<th >拆单时间</th>
						<th >状态</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opid"  type="checkbox" value=""/></td>
			      		<td name="num">
			      		    <a href="<?php echo site_url('order/order_detail/index/read/order_product');?>" title="订单详情" data-toggle="floatover" data-target="#dismantleFloatover" data-remote="<?php echo site_url('order/dismantle_detail/index/read_floatover');?>"></a>
			      		</td>
			      		<td name="product"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="remark"></td>
						<td name="creator"></td>
						<td name="dismantler"></td>
						<td name="dismantled_datetime"></td>
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
		<div class="floatover hide" id="dismantleFloatover"></div>
	</div>
	
    <div class="modal fade filter" id="waitDismantleFilterModal" tabindex="-1" role="dialog" aria-labelledby="waitDismantleFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="waitDismantleFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="waitDismantleFilterModalLabel">搜索</h4>
          			</div>
          			<div class="modal-body">
    		      	    <div class="form-group">
			      			<label class="control-label col-md-2">产品类型:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="product" multiple="multiple">
    		      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">状态:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="status" multiple="multiple">
    		      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">所有者:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="public">
			      				    <option value="1">所有</option>
			      				    <option value="0">我的</option>
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
			var SessionData = undefined;
			if(!(SessionData = $.sessionStorage('product'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('product/product/read/undelete');?>',
					success: function(msg){
						if(msg.error == 0){
							var Item = '', Content = msg.data.content;
							for(var i in Content){
								Item += '<option value="'+Content[i]['pid']+'" >'+Content[i]['name']+'</option>';
							}
							$('#waitDismantleFilterModal select[name="product"]').append(Item);
				            $.sessionStorage('product', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['pid']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#waitDismantleFilterModal select[name="product"]').append(Item);
			}

			if(!(SessionData = $.sessionStorage('workflow_wait_dismantle'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/workflow/read/wait_dismantle');?>',
					success: function(msg){
						if(msg.error == 0){
							var Item = '', Content = msg.data.content;
							for(var i in Content){
								Item += '<option value="'+Content[i]['no']+'" >'+Content[i]['name']+'</option>';
							}
							$('#waitDismantleFilterModal select[name="status"]').append(Item);
							$('#waitDismantleSearch input[name="status"]').val(Content[0]['no']);
				            $.sessionStorage('workflow_wait_dismantle', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['no']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#waitDismantleFilterModal select[name="status"]').append(Item);
	            $('#waitDismantleSearch input[name="status"]').val(SessionData[0]['no']);
			}
			$('div#waitDismantle').handle_page();
		    $('div#waitDismantleFilterModal').handle_modal_000();
		})(jQuery);
	</script>