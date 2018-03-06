<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月12日
 * @author Zhangcc
 * @version
 * @des
 * 配件
 */
?>
    <div class="page-line" id="orderProductFitting">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="orderProductFittingSearch" data-toggle="search" data-target="orderProductFittingTable">
    			    <input type="hidden" class="form-control" name="id" value="<?php echo $Id;?>">
    			    <input type="hidden" class="form-control" name="product" value="<?php echo $Product;?>">
		      		<input type="text" class="form-control" name="keyword" data-toggle="search" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderProductFittingFunction">
		  		<button class="btn btn-primary" type="button" value="生产后" data-toggle="mtab" data-target="#orderProductFittingTable" data-action="<?php echo site_url('order/post_sale/index/read?id='.$Id);?>"><i class="fa fa-circle"></i>&nbsp;&nbsp;生产后</button>
		  		<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			<i class="fa fa-eye"></i>&nbsp;&nbsp;查看<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" >
		    		    <li><a href="<?php echo site_url('chart/print_list/index/read/preview?id='.$Id);?>" target="_blank" data-toggle="blank" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;生产清单</a></li>
		    		</ul>
		  		</div>
		  		<!-- <a href="javascript:void(0);" class="btn btn-primary" data-toggle="mtab" data-action="<?php echo site_url('order/dismantle/index/redismantle/order_product?id='.$Id);?>"><i class="fa fa-arrows"></i>&nbsp;&nbsp;拆单</a> -->
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderProductFittingTable">
				<thead>
					<tr>
					    <th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th data-name="type" >类别</th>
						<th data-name="name" >名称</th>
						<th data-name="amount" >数量</th>
						<th >单位</th>
						<th data-name="remark" >备注</th>
					</tr>
				</thead>
				<tbody>
				    <?php 
				        if(isset($Fitting) && is_array($Fitting) && count($Fitting) > 0){
				            foreach ($Fitting as $key => $value){
				                echo <<<END
<tr>
    <td ><input name="opfid"  type="checkbox" value="$value[opfid]"/></td>
	<td name="type">$value[type]</td>
	<td name="name">$value[name]</td>
	<td name="amount">$value[amount]</td>
	<td name="unit">$value[unit]</td>
	<td name="remark">$value[remark]</td>
</tr>
END;
				            }
				        }
				    ?>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="orderProductFittingModal" tabindex="-1" role="dialog" aria-labelledby="orderProductFittingModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="orderProductFittingForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="orderProductFittingModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<input type="hidden" name="opid" value="<?php echo $Id;?>" />
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
			$('div#orderProductFitting').handle_page();
			$('#orderProductFittingTable').tablesorter($(this).find('thead tr').getHeaders()); /** 表格排序*/
		})(jQuery);
	</script>