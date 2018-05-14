<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author Zhangcc
 * @version
 * @des
 * 打印清单
 */
?>
    <div class="page-line" id="printList" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="printListSearch" data-toggle="filter" data-target="#printListTable">
			        <span class="input-group-btn">
        				<button class="btn btn-default" type="button" data-toggle="modal" data-target="#printListFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="product" value="1,2,3,4,6"/>
      				<input type="hidden" name="print" value="print"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="printListFunction">
	  		    <a class="btn btn-primary" href="<?php echo site_url('chart/print_list/index/read/preview');?>" target="_blank" data-toggle="blank" data-target="#printListTable" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;预览</a>
	  		    <a class="btn btn-primary" href="<?php echo site_url('drawing/drawing/index/preview/print');?>" target="_blank" data-toggle="blank" data-target="#printListTable" data-multiple=false><i class="fa fa-image"></i>&nbsp;&nbsp;打印图纸</a>
		  		<a class="btn btn-primary" href="<?php echo site_url('chart/print_list/index/read/print');?>" target="_blank" data-toggle="blank" data-target="#printListTable" data-multiple=false id="printListAsureButton"><i class="fa fa-print"></i>&nbsp;&nbsp;打印</a>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="printListTable" data-load="<?php echo site_url('order/print_list/read');?>">
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
						<th >打印时间</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
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
						<td name="print_datetime"></td>
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
	
	<div class="modal fade filter" id="printListFilterModal" tabindex="-1" role="dialog" aria-labelledby="printListFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="printListFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="printListFilterModalLabel">搜索</h4>
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
			      				<select class="form-control" name="print" >
    		      					<option value="print">--未标记打印--</option>
    		      					<option value="printed">--已标记打印--</option>
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
							$('#printListFilterModal select[name="product"]').append(Item);
				            $.sessionStorage('product', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['pid']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#printListFilterModal select[name="product"]').append(Item);
			}
			$('a#printListAsureButton').on('click.print',function(e){
				setTimeout(function(){$('#printListTable').find('input:checkbox:checked').eq(0).parents('tr').remove();}, 1000);
			});
			$('div#printList').handle_page();
			$('div#printListFilterModal').handle_modal_000();
		})(jQuery);
	</script>