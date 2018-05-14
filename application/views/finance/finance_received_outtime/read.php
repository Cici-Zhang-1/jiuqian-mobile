<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月9日
 * @author Zhangcc
 * @version
 * @des
 * 进账登记
 */
?>
	<div class="page-line" id="financeReceivedOuttime" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="financeReceivedOuttimeSearch" data-toggle="filter" data-target="#financeReceivedOuttimeTable">
				    <span class="input-group-btn">
        				<button class="btn btn-default" type="button" id="financeReceivedOuttimeFilterCon" data-toggle="modal" data-target="#financeReceivedOuttimeFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="status" value="1"/>
      				<input type="hidden" name="type" value=""/>
      				<input type="hidden" name="start_date" value=""/>
      				<input type="hidden" name="end_date" value=""/>
      				<input type="hidden" name="account" value=""/>
		      		<input type="text" class="form-control" name="keyword" placeholder="经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="financeReceivedOuttimeFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="financeReceivedOuttimeFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="financeReceivedOuttimeTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#financeReceivedOuttimeTable">
		    		    <!-- <li><a href="javascript:void(0);"  data-toggle="child" data-target="#financeReceivedOuttimeTable" data-action="<?php echo site_url('finance/finance_received_outtime/index/edit');?>" data-multiple=false ><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		    <li role="separator" class="divider"></li> -->
		    		    <li><a href="javascript:void(0);"  data-toggle="modal" data-target="#financeReceivedOuttimeModal" data-action="<?php echo site_url('finance/finance_received_outtime/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;已到账</a></li>
		    		</ul>
		  		</div>
		  		<a href="javascript:void(0);" class="btn btn-primary" data-toggle="child" data-action="<?php echo site_url('finance/finance_received_outtime/index/add');?>" ><i class="fa fa-plus"></i>&nbsp;&nbsp;新建</a>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#financeReceivedOuttimeTable" href="<?php echo site_url('finance/finance_received_outtime/remove');?>" data-multiple=false><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="financeReceivedOuttimeTable" data-load="<?php echo site_url('finance/finance_received_outtime/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >账号</th>
						<th >进账类型</th>
						<th data-name="amount">到账金额</th>
						<th >货款金额</th>
						<th data-name="fee">手续费</th>
						<th data-name="bank_date">到账日期</th>
						<th data-name="cargo_no">货号</th>
						<th >客户</th>
						<th data-name="remark">备注</th>
						<th >登记人</th>
						<th >登记时间</th>
						<th class="hide" data-name="faid">faid</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="frid"  type="checkbox" value=""/></td>
			      		<td name="name"></td>
			      		<td name="type"></td>
			      		<td name="amount"></td>
						<td name="corresponding"></td>
						<td name="fee"></td>
						<td name="bank_date"></td>
						<td name="cargo_no"></td>
						<td name="dealer"></td>
						<td name="remark"></td>
						<td name="creator"></td>
						<td name="create_datetime"></td>
						<td class="hide" name="faid"></td>
			      	</tr>
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
	
	<div class="modal fade" id="financeReceivedOuttimeModal" tabindex="-1" role="dialog" aria-labelledby="financeReceivedOuttimeModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="financeReceivedOuttimeModalForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="financeReceivedOuttimeModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >手续费:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="fee" type="text" placeholder="手续费" value=""/>
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
	
    <div class="modal fade filter" id="financeReceivedOuttimeFilterModal" tabindex="-1" role="dialog" aria-labelledby="financeReceivedOuttimeFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="financeReceivedOuttimeFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="financeReceivedOuttimeFilterModalLabel">搜索</h4>
          			</div>
          			<div class="modal-body">
    		      	    <div class="form-group">
			      			<label class="control-label col-md-2">类型:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="type" multiple="multiple"></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">开始日期:</label>
			      			<div class="col-md-6">
			      				<input class="form-control datepicker" type="text" name="start_date" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">结束日期:</label>
			      			<div class="col-md-6">
			      				<input class="form-control datepicker" type="text" name="end_date" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">账号:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="account" multiple="multiple"></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">状态:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="status" multiple="multiple">
			      				    <option value="1">未到账</option>
    		      					<option value="2">已到账</option>
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
			var SessionData = undefined;
			if(!(SessionData = $.sessionStorage('account_outtime'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('finance/account/read_outtime');?>',
					success: function(msg){
						if(msg.error == 0){
							var Item = '', Content = msg.data.content;
							for(var i in Content){
								Item += '<option value="'+Content[i]['faid']+'" data-fee="'+Content[i]['fee']+'" data-fee_max="'+Content[i]['fee_max']+'">'+Content[i]['name']+'</option>';
							}
							$('#financeReceivedOuttimeFilterModal select[name="account"]').append(Item);
				            $.sessionStorage('account_outtime', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['faid']+'" data-fee="'+SessionData[i]['fee']+'" data-fee_max="'+SessionData[i]['fee_max']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#financeReceivedOuttimeFilterModal select[name="account"]').append(Item);
			}
			if(!(SessionData = $.sessionStorage('income_pay'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('finance/income_pay/read/income');?>',
					success: function(msg){
						if(msg.error == 0){
							var Item = '', Content = msg.data.content;
							for(var i in Content){
								Item += '<option value="'+Content[i]['name']+'" >'+Content[i]['name']+'</option>';
							}
							$('#financeReceivedOuttimeFilterModal select[name="type"]').append(Item);
				            $.sessionStorage('income_pay', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['name']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#financeReceivedOuttimeFilterModal select[name="type"]').append(Item);
			}
			
			$('div#financeReceivedOuttime').handle_page();
		    $('div#financeReceivedOuttimeFilterModal').handle_modal_000();
		    $('div#financeReceivedOuttimeModal').handle_modal_000();
		})(jQuery);
	</script>