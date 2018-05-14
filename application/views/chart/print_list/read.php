<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月17日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="printList" data-load="<?php echo site_url('chart/print_list/read');?>">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="printListSearch">
			        <span class="input-group-btn">
        				<button class="btn btn-default" type="button" id="printListCon" data-toggle="modal" data-target="#printListFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="status" value="0"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="printListFilterBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="printListFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="printListTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#printListTable">
		    		    <li><a href="<?php echo site_url('chart/print_list/edit');?>"  data-toggle="backstage" data-multiple=true><i class="fa fa-flag"></i>&nbsp;&nbsp;标记已打印</a></li>
		    		</ul>
		  		</div>
		  		<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			<i class="fa fa-print"></i>&nbsp;&nbsp;打印图纸<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" >
		    		    <li><a href="<?php echo site_url('chart/print_drawing/index/read/q');?>" target="_blank" data-toggle="blank" data-multiple=false ><i class="fa fa-photo"></i>&nbsp;&nbsp;全图</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="<?php echo site_url('chart/print_drawing/index/read/w');?>" target="_blank" data-toggle="blank" data-multiple=false ><i class="fa fa-photo"></i>&nbsp;&nbsp;橱柜</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="<?php echo site_url('chart/print_drawing/index/read/y');?>" target="_blank" data-toggle="blank" data-multiple=false ><i class="fa fa-photo"></i>&nbsp;&nbsp;衣柜</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="<?php echo site_url('chart/print_drawing/index/read/m');?>" target="_blank" data-toggle="blank" data-multiple=false ><i class="fa fa-photo"></i>&nbsp;&nbsp;门板</a></li>
		    		</ul>
		  		</div>
		  		<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			<i class="fa fa-print"></i>&nbsp;&nbsp;打印清单<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" >
		    		    <li><a href="<?php echo site_url('chart/print_data/index/read/w');?>" target="_blank" data-toggle="blank" data-multiple=false ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;橱柜</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="<?php echo site_url('chart/print_data/index/read/y');?>" target="_blank" data-toggle="blank" data-multiple=false ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;衣柜</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="<?php echo site_url('chart/print_data/index/read/m');?>" target="_blank" data-toggle="blank" data-multiple=false ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;门板</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="<?php echo site_url('chart/print_data/index/read/k');?>" target="_blank" data-toggle="blank" data-multiple=false ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;木框门</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="<?php echo site_url('chart/print_data/index/read/a');?>" target="_blank" data-toggle="blank" data-multiple=false ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;异形</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="<?php echo site_url('chart/print_data/index/read/p');?>" target="_blank" data-toggle="blank" data-multiple=false ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;五金</a></li>
		    		    <li class="divider" role="separator"></li>
		    		    <li><a href="<?php echo site_url('chart/print_data/index/read/g');?>" target="_blank" data-toggle="blank" data-multiple=false ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;外购</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="printListTable">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected">#</th>
						<th >订单编号</th>
						<th >客户</th>
						<th >联系人</th>
						<th >联系方式</th>
						<th >联系地址</th>
						<th >业主</th>
						<th >要求出厂日期</th>
						<th >备注</th>
						<th >状态</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      	    <td ><input name="oid"  type="checkbox" value=""/></td>
			      		<td name="num"></td>
						<td name="dealer"></td>
						<td name="dealer_linker"></td>
						<td name="dealer_phone"></td>
						<td name="dealer_address"></td>
						<td name="owner"></td>
						<td name="request_outdate"></td>
						<td name="remark"></td>
						<td name="print"></td>
			      	</tr>
				</tbody>
			</table>
			<div class="btn-group pull-right">
			    <p class="footnote"></p>
				<ul class="hide pagination">
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
			      			<label class="control-label col-md-2">状态:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="status" multiple="multiple">
    		      					<option value="0">--未标记打印--</option>
    		      					<option value="1">--已标记打印--</option>
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
			$('div#printList').handle_page();
			$('div#printListFilterModal').handle_modal_000();
		})(jQuery);
	</script>