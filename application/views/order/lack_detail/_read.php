<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月6日
 * @author Zhangcc
 * @version
 * @des
*/
?>
    <div class="page-line" id="lackDetail" >
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="hide input-group" id="lackDetailSearch">
                    <input type="hidden" name="id" value="<?php echo $Id;?>"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="lackDetailSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="lackDetailFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-refresh"></i>&nbsp;&nbsp;返回</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="lackDetailTable">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th >二维码<i class="fa fa-sort"></i></th>
						<th >板材<i class="fa fa-sort"></i></th>
						<th >柜体位置</th>
						<th >名称</th>
						<th >宽度</th>
						<th >长度</th>
						<th >厚度<i class="fa fa-sort"></i></th>
						<th >封边</th>
						<th >打孔</th>
						<th >开槽</th>
						<th >备注</th>
					</tr>
				</thead>
				<tbody>
				    <?php
				    if(isset($Plate) && is_array($Plate) && count($Plate) > 0){
				        $K = 1;
				        foreach ($Plate as $key => $value){
				            echo <<<END
<tr>
	<td >$K</td>
	<td >$value[qrcode]</td>
	<td >$value[board]</td>
	<td >$value[cubicle_name]</td>
	<td >$value[plate_name]</td>
	<td >$value[width]</td>
	<td >$value[length]</td>
	<td >$value[thick]</td>
	<td >$value[edge]</td>
	<td >$value[punch]</td>
	<td >$value[slot]</td>
	<td >$value[remark]</td>
</tr>       
END;
				            $K++;
				        }
				    }
				    ?>
				</tbody>
			</table>
		</div>
    </div>
	<script type="text/javascript">
		(function($, window, undefined){
			$('div#lackDetail').handle_page();
			$('#lackDetailTable').tablesorter($(this).find('thead tr').getHeaders()); 
		})(jQuery);
	</script>
