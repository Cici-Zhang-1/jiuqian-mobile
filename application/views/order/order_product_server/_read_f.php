<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月12日
 * @author Zhangcc
 * @version
 * @des
 * 服务
 */
?>
    <div class="page-line" id="orderProductServer">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="orderProductServerSearch" data-toggle="search" data-target="orderProductServerTable">
    			    <input type="hidden" class="form-control" name="id" value="<?php echo $Id;?>">
    			    <input type="hidden" class="form-control" name="product" value="<?php echo $Product;?>">
		      		<input type="text" class="form-control" name="keyword" data-toggle="search" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderProductServerFunction">
		  		<!-- <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			<i class="fa fa-eye"></i>&nbsp;&nbsp;查看<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" >
		    		    <li><a href="<?php echo site_url('chart/print_data/index/read?id='.$Id.'&&product='.$Product);?>" target="_blank" data-toggle="blank" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;生产清单</a></li>
		    		</ul>
		  		</div> -->
		  		<!-- <a href="javascript:void(0);" class="btn btn-primary" data-toggle="mtab" data-action="<?php echo site_url('order/dismantle/index/redismantle/order_product?id='.$Id);?>"><i class="fa fa-arrows"></i>&nbsp;&nbsp;拆单</a> -->
		  		<button class="btn btn-primary" type="button" value="生产后" data-toggle="mtab" data-target="#orderProductServerTable" data-action="<?php echo site_url('order/post_sale/index/read?id='.$Id);?>"><i class="fa fa-circle"></i>&nbsp;&nbsp;生产后</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderProductServerTable">
				<thead>
					<tr>
						<th >类别</th>
						<th >名称</th>
						<th >数量</th>
						<th >单位</th>
						<th >备注</th>
					</tr>
				</thead>
				<tbody>
				    <?php 
				        if(isset($Server) && is_array($Server) && count($Server) > 0){
				            foreach ($Server as $key => $value){
				                echo <<<END
<tr>
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
	<script>
		(function($){
			$('div#orderProductServer').handle_page();
			$('#orderProductServerTable').tablesorter($(this).find('thead tr').getHeaders()); /** 表格排序*/
		})(jQuery);
	</script>