<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月14日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="orderProductDetailP" data-load="<?php echo site_url('order/order_product_detail/read');?>">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="orderProductDetailPSearch">
    			    <input type="hidden" class="form-control" name="id" value="<?php echo $id;?>">
    			    <input type="hidden" class="form-control" name="product" value="<?php echo $product;?>">
    			    <input type="text" class="form-control" name="keyword" data-toggle="search" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="orderProductDetailPSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderProductDetailPFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="orderProductDetailPTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#orderProductDetailPTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#orderProductDetailPModal" data-action="<?php echo site_url('order/order_product_fitting/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
		  		<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			<i class="fa fa-print"></i>&nbsp;&nbsp;打印<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" >
		    		    <li><a href="<?php echo site_url('order/print_data/index/read?id='.$id.'&&product='.$product);?>" target="_blank" data-toggle="blank" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;配件清单</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-refresh"></i>&nbsp;&nbsp;返回</button>
	  			<a class="btn btn-default" data-toggle="backstage" href="<?php echo site_url('order/order_product_fitting/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderProductDetailPTable">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th >类别</th>
						<th >名称</th>
						<th >数量</th>
						<th >单位</th>
						<th data-name="remark">备注</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="9">加载中...</td></tr>
					<tr class="no-data"><td colspan="9">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opfid"  type="checkbox" value=""/></td>
			      		<td name="type"></td>
						<td name="name"></td>
						<td name="amount"></td>
						<td name="unit"></td>
						<td name="remark"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="orderProductDetailPModal" tabindex="-1" role="dialog" aria-labelledby="orderProductDetailPModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="dealerForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="orderProductDetailPModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >备注:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="remark" type="text" placeholder="备注" value=""/>
			      			</div>
			      		</div>
			      		<div class="alert alert-danger alert-dismissible fade in serverError" role="alert"></div>
			      	</div>
			      	<div class="modal-footer">
			        	<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			        	<button type="submit" class="btn btn-primary" data-save="ajax.modal">保存</button>
			      	</div>
				</form>
    		</div>
  		</div>
	</div>
	<script>
		(function($){
			$('div#orderProductDetailP').handle_page();
		    $('div#orderProductDetailPModal').handle_modal_000();
		})(jQuery);
	</script>