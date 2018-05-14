<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月22日
 * @author Zhangcc
 * @version
 * @des
 * 出厂订单详情
 */
?>
	<div class="page-line" id="stockOuttedDetail" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="stockOuttedDetailSearch" data-toggle="search" data-target="#stockOuttedDetailTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="stockOuttedDetailFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="stockOuttedDetailFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="stockOuttedDetailTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#stockOuttedDetailTable">
		      			<li><a href="javascript:void(0);" data-toggle="mtab" data-target="#stockOuttedDetailTable" data-action="<?php echo site_url('order/cargo_no/index/edit');?>"><i class="fa fa-edit"></i>&nbsp;&nbsp;填写货号</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a href="javascript:void(0);" data-toggle="backstage" data-target="#stockOuttedDetailTable" data-action="<?php echo site_url('stock/stock_outted_detail/redelivery');?>" data-multiple=true><i class="fa fa-reply"></i>&nbsp;&nbsp;重新发货</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="stockOuttedDetailTable" >
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >货号</th>
						<th >订单编号</th>
						<th >金额</th>
						<th >件数</th>
						<th >物流</th>
						<th >收货地区</th>
						<th >收货地址</th>
						<th >收货人</th>
						<th >联系电话</th>
						<th >客户</th>
						<th >业主</th>
						<th >备注</th>
					</tr>
				</thead>
				<tbody>
				    <?php
				    if(isset($Detail) && is_array($Detail) && count($Detail) > 0){
				        foreach ($Detail as $key => $value){
				            echo <<<END
<tr>
	<td ><input name="oid"  type="checkbox" value="$value[oid]"/></td>
	<td >$value[cargo_no]</td>
	<td >$value[order_num]</td>
	<td >$value[sum]</td>
	<td >$value[pack]</td>
	<td >$value[logistics]</td>
	<td >$value[delivery_area]</td>
	<td >$value[delivery_address]</td>
	<td >$value[delivery_linker]</td>
	<td >$value[delivery_phone]</td>
	<td >$value[dealer]</td>
	<td >$value[owner]</td>
	<td >$value[remark]</td>
</tr> 
END;
				        }
				    }
				    ?>
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
	<script>
		(function($){
			$('div#stockOuttedDetail').handle_page();
		})(jQuery);
	</script>