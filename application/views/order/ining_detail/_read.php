<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月19日
 * @author Zhangcc
 * @version
 * @des
 * 正在入库详情
 */
?>
    <div class="page-line" id="iningDetail" >
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="hide input-group" id="iningDetailSearch" data-toggle="search" data-target="#iningDetailTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="iningDetailFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
		    <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="iningDetailTable">
				<thead>
					<tr>
					    <th >#</th>
						<th >订单编号</th>
						<th >产品名称</th>
						<th >件数</th>
						<th >打印时间</th>
						<th >备注</th>
					</tr>
				</thead>
				<tbody>
				    <?php
				    if(isset($Detail) && is_array($Detail) && count($Detail) > 0){
				        $K = 1;
				        $Html = '';
				        foreach ($Detail as $key => $value){
				            $Html .= <<<END
<tr>
	<td >$K</td>
	<td >$value[order_product_num]</td>
	<td >$value[product]</td>
	<td >$value[pack]</td>
	<td >$value[pack_datetime]</td>
	<td >$value[remark]</td>
</tr>
END;
				            $K++;
				        }
				        echo $Html;
				    }
				    ?>
				</tbody>
			</table>
		</div>
    </div>
	<script type="text/javascript">
		(function($, window, undefined){
			$('#iningDetail').handle_page();
		})(jQuery);
	</script>