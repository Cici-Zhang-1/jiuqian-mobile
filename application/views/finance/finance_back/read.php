<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月23日
 * @author Zhangcc
 * @version
 * @des
 * 返款
 */
?>
	<div class="page-line" id="financeBack" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="financeBackSearch" data-toggle="filter" data-target="#financeBackTable">
				    <span class="input-group-btn">
        				<button class="btn btn-default" type="button" id="financeBackFilterCon" data-toggle="modal" data-target="#financeBackFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="status" value="2"/>
      				<input type="hidden" name="type" value="返款"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="financeBackFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="financeBackFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="financeBackTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#financeBackTable">
		    		    <li><a href="javascript:void(0);"  data-toggle="modal" data-target="#financeBackModal" data-action="<?php echo site_url('finance/finance_back/edit');?>" data-multiple=true><i class="fa fa-pencil"></i>&nbsp;&nbsp;返款</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="financeBackTable" data-load="<?php echo site_url('finance/finance_back/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >账号</th>
						<th >进账类型</th>
						<th >到账金额</th>
						<th >手续费</th>
						<th >到账日期</th>
						<th >客户</th>
						<th >备注</th>
						<th >登记人</th>
						<th >登记时间</th>
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
						<td name="fee"></td>
						<td name="bank_date"></td>
						<td name="dealer"></td>
						<td name="remark"></td>
						<td name="creator"></td>
						<td name="create_datetime"></td>
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
	
	<div class="modal fade" id="financeBackModal" tabindex="-1" role="dialog" aria-labelledby="financeBackModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="financeBackModalForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="financeBackModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >账号:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="faid"><option value="">--选择进账账户--</option></select>
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
			var SessionData = undefined;
			if(!(SessionData = $.sessionStorage('account'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('finance/account/read_name');?>',
					success: function(msg){
						if(msg.error == 0){
							var Item = '', Content = msg.data.content;
							for(var i in Content){
								Item += '<option value="'+Content[i]['faid']+'" data-fee="'+Content[i]['fee']+'" data-fee_max="'+Content[i]['fee_max']+'">'+Content[i]['name']+'</option>';
							}
							$('#financeBackModal select[name="faid"]').append(Item);
				            $.sessionStorage('account', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['faid']+'" data-fee="'+SessionData[i]['fee']+'" data-fee_max="'+SessionData[i]['fee_max']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#financeBackModal select[name="faid"]').append(Item);
			}
			
			$('div#financeBack').handle_page();
		    $('div#financeBackModal').handle_modal_000();
		})(jQuery);
	</script>