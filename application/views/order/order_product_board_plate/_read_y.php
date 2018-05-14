<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月12日
 * @author Zhangcc
 * @version
 * @des
 * 衣柜板材结构
 */
?>
    <div class="page-line" id="orderProductBoardPlateY">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="orderProductBoardPlateYSearch" data-toggle="search" data-target="#orderProductBoardPlateYTable">
    			    <input type="hidden" class="form-control" name="id" value="<?php echo $Id;?>">
    			    <input type="hidden" class="form-control" name="product" value="<?php echo $Product;?>">
		      		<input type="text" class="form-control" name="keyword" data-toggle="search" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderProductBoardPlateYFunction">
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
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderProductBoardPlateYTable">
				<thead>
					<tr>
						<th name="num">二维码<i class="fa fa-sort"></i></th>
						<th >柜体位置<i class="fa fa-sort"></i></th>
						<th >板块名称<i class="fa fa-sort"></i></th>
						<th class="td-xs">板块编号<i class="fa fa-sort"></i></th>
						<th data-name="length">长</th>
						<th data-name="width">宽</th>
						<th >厚<i class="fa fa-sort"></i></th>
						<th data-name="slot">开槽<i class="fa fa-sort"></i></th>
						<th data-name="punch">打孔<i class="fa fa-sort"></i></th>
						<th data-name="edge">封边<i class="fa fa-sort"></i></th>
						<th data-name="decide_size">尺寸判定<i class="fa fa-sort"></i></th>
						<th data-name="remark">备注<i class="fa fa-sort"></i></th>
						<th >异形<i class="fa fa-sort"></i></th>
					</tr>
				</thead>
				<tbody>
				    <?php 
				        if(isset($BoardPlate) && is_array($BoardPlate) && count($BoardPlate) > 0){
				            foreach ($BoardPlate as $key => $value){
				                $value['abnormity'] = (1 == $value['abnormity'])?'是':'';
				                echo <<<END
<tr>
	<td name="qrcode">$value[qrcode]</td>
	<td name="cubicle_name">$value[cubicle_num]$value[cubicle_name]</td>
	<td name="plate_name">$value[plate_name]</td>
	<td name="plate_num">$value[plate_num]</td>
	<td name="length">$value[length]</td>
	<td name="width">$value[width]</td>
	<td name="thick">$value[thick]</td>
	<td name="slot">$value[slot]</td>
	<td name="punch">$value[punch]</td>
	<td name="edge">$value[edge]</td>
	<td name="decide_size">$value[decide_size]</td>
	<td name="remark">$value[remark]</td>
	<td name="abnormity">$value[abnormity]</td>
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
			$('div#orderProductBoardPlateY').handle_page();
			$('#orderProductBoardPlateYTable').tablesorter($(this).find('thead tr').getHeaders()); /** 表格排序*/
		})(jQuery);
	</script>