<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月12日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="orderProductDetailY" data-load="<?php echo site_url('order/order_product_detail/read');?>">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="orderProductDetailYSearch">
    			    <input type="hidden" class="form-control" name="id" value="<?php echo $id;?>">
    			    <input type="hidden" class="form-control" name="product" value="<?php echo $product;?>">
		      		<input type="text" class="form-control" name="keyword" data-toggle="search" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="orderProductDetailYSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderProductDetailYFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="orderProductDetailYTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#orderProductDetailYTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#orderProductDetailYModal" data-action="<?php echo site_url('order/order_product_board_plate/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#orderProductDetailYBoardModal" data-action="<?php echo site_url('order/order_product_board/edit');?>" data-multiple=true><i class="fa fa-pencil"></i>&nbsp;&nbsp;修改板材</a></li>
		    		</ul>
		  		</div>
		  		<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			<i class="fa fa-print"></i>&nbsp;&nbsp;打印<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" >
		    		    <li><a href="javascript:void(0);" data-toggle="child" data-action="<?php echo site_url('order/order_drawing/index/read?id='.$id.'&&product='.$product);?>" ><i class="fa fa-photo"></i>&nbsp;&nbsp;图纸</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="<?php echo site_url('order/print_data/index/read?id='.$id.'&&product='.$product);?>" target="_blank" data-toggle="blank" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;生产清单</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-refresh"></i>&nbsp;&nbsp;返回</button>
	  			<a class="btn btn-default" data-toggle="backstage" href="<?php echo site_url('order/order_product_board_plate/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderProductDetailYTable">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th name="num">二维码<i class="fa fa-sort"></i></th>
						<th >板材<i class="fa fa-sort"></i></th>
						<th >柜体位置<i class="fa fa-sort"></i></th>
						<th >柜体编号<i class="fa fa-sort"></i></th>
						<th >板块名称<i class="fa fa-sort"></i></th>
						<th >板块编号<i class="fa fa-sort"></i></th>
						<th data-name="length">长</th>
						<th data-name="width">宽</th>
						<th >厚<i class="fa fa-sort"></i></th>
						<th data-name="slot">开槽<i class="fa fa-sort"></i></th>
						<th data-name="punch">打孔<i class="fa fa-sort"></i></th>
						<th data-name="edge">封边<i class="fa fa-sort"></i></th>
						<th data-name="remark">备注<i class="fa fa-sort"></i></th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="13">加载中...</td></tr>
					<tr class="no-data"><td colspan="13">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opbpid"  type="checkbox" value=""/></td>
			      		<td name="num"></td>
			      		<td name="board"></td>
						<td name="cubicle_name"></td>
						<td name="cubicle_num"></td>
						<td name="plate_name"></td>
						<td name="plate_num"></td>
						<td name="length"></td>
						<td name="width"></td>
						<td name="thick"></td>
						<td name="slot"></td>
						<td name="punch"></td>
						<td name="edge"></td>
						<td name="remark"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-12" id="orderProductDetailYDrawing" ></div>
	</div>
	
	<div class="modal fade" id="orderProductDetailYModal" tabindex="-1" role="dialog" aria-labelledby="orderProductDetailYModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="orderProductDetailYForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="orderProductDetailYModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >长度:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="length" type="text" placeholder="长度, 2440" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >宽度:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="width" type="text" placeholder="宽度, 1220" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >开槽:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="slot" type="text" placeholder="开槽" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >打孔:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="punch" type="text" placeholder="打孔" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >封边:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="edge" type="text" placeholder="封边" value=""/>
			      			</div>
			      		</div>
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
	<div class="modal fade" id="orderProductDetailYBoardModal" tabindex="-1" role="dialog" aria-labelledby="orderProductDetailYBoardModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="orderProductDetailYBoardForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="orderProductDetailYBoardModalLabel">修改板材</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >板材:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="board" type="text" placeholder="板材" value=""/>
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
			$('div#orderProductDetailY').handle_page();
		    $('div#orderProductDetailYModal').handle_modal_000();
		    $('div#orderProductDetailYBoardModal').handle_modal_000();
		})(jQuery);
	</script>