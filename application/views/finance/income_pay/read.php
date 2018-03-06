<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月19日
 * @author Zhangcc
 * @version
 * @des
 * 收支类型
 */
?>
    <div class="page-line" id="incomePay">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="incomePaySearch" data-toggle="search" data-target="#incomePayTable">
		      		<input type="text" class="form-control" name="keyword" data-toggle="search" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
			<div class="col-md-offset-3 col-md-6 text-right" id="incomePayFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="incomePayTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#incomePayTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#incomePayModal" data-action="<?php echo site_url('finance/income_pay/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#incomePayModal" data-action="<?php echo site_url('finance/income_pay/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="incomePayTable" data-load="<?php echo site_url('finance/income_pay/read') ?>">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th >类型<i class="fa fa-sort"></i></th>
						<th data-name="name">名称</th>
						<th class="hide" data-name="type"></th>
					</tr>
				</thead>
				<tbody>
					<tr class="loading"><td colspan="9">加载中...</td></tr>
                    <tr class="no-data"><td colspan="9">没有数据</td></tr>
                	<tr class="model">
                        <td ><input name="ipid"  type="checkbox" value=""/></td>
                        <td name="type_cn"></td>
                        <td name="name"></td>
                        <td class="hide" name="type"></td>
                	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="incomePayModal" tabindex="-1" role="dialog" aria-labelledby="incomePayModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="incomePayForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="incomePayModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >类型:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="type">
    			      			    <option value="income">收入</option>
    			      			    <option value="pay">支出</option>
			      			    </select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="名称" value=""/>
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
			$('div#incomePay').handle_page();
			$('div#incomePayModal').handle_modal_000();
		})(jQuery);
	</script>