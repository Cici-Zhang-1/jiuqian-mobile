<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月14日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="orderProductBoardDoor">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="orderProductBoardDoorSearch" data-toggle="search" data-target="orderProductBoardDoorTable">
    			    <input type="hidden" class="form-control" name="id" value="<?php echo $Id;?>">
    			    <input type="hidden" class="form-control" name="product" value="<?php echo $Product;?>">
		      		<input type="text" class="form-control" name="keyword" data-toggle="search" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderProductBoardDoorFunction">
		  		<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			<i class="fa fa-eye"></i>&nbsp;&nbsp;查看<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" >
		    		    <li><a href="<?php echo site_url('drawing/drawing/index/preview/list?id='.$Id);?>" target="_blank" data-toggle="blank" ><i class="fa fa-photo"></i>&nbsp;&nbsp;图纸</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="<?php echo site_url('chart/print_list/index/read/preview?id='.$Id);?>" target="_blank" data-toggle="blank" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;生产清单</a></li>
		    		</ul>
		  		</div>
		  		<!-- <a href="javascript:void(0);" class="btn btn-primary" data-toggle="mtab" data-action="<?php echo site_url('order/dismantle/index/redismantle/order_product?id='.$Id);?>"><i class="fa fa-arrows"></i>&nbsp;&nbsp;拆单</a> -->
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderProductBoardDoorTable">
				<thead>
					<tr>
						<th >二维码</th>
						<th >板材</th>
						<th >长</th>
						<th >宽</th>
						<th >厚<i class="fa fa-sort"></i></th>
						<th >面积<i class="fa fa-sort"></i></th>
						<th >打孔<i class="fa fa-sort"></i></th>
						<th >封边拉手<i class="fa fa-sort"></i></th>
						<th >开孔数<i class="fa fa-sort"></i></th>
						<th >隐形拉手<i class="fa fa-sort"></i></th>
						<th >备注<i class="fa fa-sort"></i></th>
					</tr>
				</thead>
				<tbody>
				    <?php 
				        if(isset($BoardDoor) && is_array($BoardDoor) && count($BoardDoor) > 0){
				            foreach ($BoardDoor as $key => $value){
				                echo <<<END
<tr>
	<td name="qrcode">$value[qrcode]</td>
	<td name="good">$value[good]</td>
	<td name="length">$value[length]</td>
	<td name="width">$value[width]</td>
	<td name="thick">$value[thick]</td>
	<td name="area">$value[area]</td>
	<td name="punch">$value[punch]</td>
	<td name="handle">$value[handle]</td>
	<td name="open_hole">$value[open_hole]</td>
	<td name="invisibility">$value[invisibility]</td>
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
			$('div#orderProductBoardDoor').handle_page();
			$('#orderProductBoardDoorTable').tablesorter($(this).find('thead tr').getHeaders()); /** 表格排序*/
		})(jQuery);
	</script>