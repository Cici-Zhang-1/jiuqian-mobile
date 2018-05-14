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
	<div class="page-line" id="financeReceived" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="financeReceivedSearch" data-toggle="filter" data-target="#financeReceivedTable">
				    <span class="input-group-btn">
        				<button class="btn btn-default" type="button" id="financeReceivedFilterCon" data-toggle="modal" data-target="#financeReceivedFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="status" value="1"/>
      				<input type="hidden" name="type" value=""/>
      				<input type="hidden" name="start_date" value=""/>
      				<input type="hidden" name="end_date" value=""/>
      				<input type="hidden" name="account" value=""/>
		      		<input type="text" class="form-control" name="keyword" placeholder="经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="financeReceivedFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="financeReceivedFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="financeReceivedTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#financeReceivedTable">
		    		    <li><a href="javascript:void(0);"  data-toggle="modal" data-target="#financeReceivedModal" data-action="<?php echo site_url('finance/finance_received/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		    <li role="separator" class="divider"></li>
		      			<li><a href="javascript:void(0);" data-toggle="mtab" data-target="#financeReceivedTable" data-action="<?php echo site_url('finance/finance_received_pointer/index/add');?>" data-multiple=false><i class="fa fa-bank"></i>&nbsp;&nbsp;认领</a></li>
		    		</ul>
		  		</div>
		  		<button class="btn btn-primary" data-toggle="modal" data-target="#financeReceivedModal" data-action="<?php echo site_url('finance/finance_received/add');?>" type="button" value="新建"><i class="fa fa-plus"></i>&nbsp;&nbsp;新建</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#financeReceivedTable" href="<?php echo site_url('finance/finance_received/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="financeReceivedTable" data-load="<?php echo site_url('finance/finance_received/read');?>">
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
	
	<div class="modal fade" id="financeReceivedModal" tabindex="-1" role="dialog" aria-labelledby="financeReceivedModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="financeReceivedModalForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="financeReceivedModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >账号:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="faid"><option value="">--选择进账账户--</option></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >进账金额:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="amount" type="text" placeholder="进账金额" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >进账手续费:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="fee" type="text" placeholder="进账手续费" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >到账日期:</label>
			      			<div class="col-md-6">
			      				<input class="form-control datepicker" name="bank_date" type="text" placeholder="到账日期" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >货号:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="cargo_no" type="text" placeholder="货号" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >备注:</label>
			      			<div class="col-md-6">
			      			    <textarea class="form-control" rows="3" name="remark" ></textarea>
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
	
    <div class="modal fade filter" id="financeReceivedFilterModal" tabindex="-1" role="dialog" aria-labelledby="financeReceivedFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="financeReceivedFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="financeReceivedFilterModalLabel">搜索</h4>
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
			      				    <option value="1">未认领</option>
    		      					<option value="2">已认领</option>
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
			var compute_fee = function(){
				var Fee, FeeMax, Amount, $FeeIn, $Faid;
				$FeeIn = $('#financeReceivedModal input[name="fee"]');
				$Faid = $('#financeReceivedModal select[name="faid"]');
				var compute = function(){
					Amount = $('#financeReceivedModal input[name="amount"]').val(),
					Fee = $Faid.find('option:selected').data('fee'),
					FeeMax = $Faid.find('option:selected').data('fee_max');
					Fee = parseFloat(Fee);
					FeeMax = parseFloat(FeeMax);
					if(!Fee){
						Fee = 0;
					}
					if(!FeeMax) {
						FeeMax = 0;
					}
					Amount = parseFloat(Amount);
					if(!Amount){
						Amount = 0;
					}
					if(Amount >= 0){
						$FeeIn.val(Amount*Fee > FeeMax? FeeMax: Amount*Fee);
					}
				};
				$('#financeReceivedModal select[name="faid"]').on('change', function(e){
					compute();
				});
				$('#financeReceivedModal input[name="amount"]').on('blur', function(e){
					compute();
				});
			};
			var SessionData = undefined;
			if(!(SessionData = $.sessionStorage('account_intime'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('finance/account/read_intime');?>',
					success: function(msg){
						if(msg.error == 0){
							var Item = '', Content = msg.data.content;
							for(var i in Content){
								Item += '<option value="'+Content[i]['faid']+'" data-fee="'+Content[i]['fee']+'" data-fee_max="'+Content[i]['fee_max']+'">'+Content[i]['name']+'</option>';
							}
							$('#financeReceivedFilterModal select[name="account"]').append(Item);
							$('#financeReceivedModal select[name="faid"]').append(Item);
							compute_fee();
				            $.sessionStorage('account_intime', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['faid']+'" data-fee="'+SessionData[i]['fee']+'" data-fee_max="'+SessionData[i]['fee_max']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#financeReceivedFilterModal select[name="account"]').append(Item);
	            $('#financeReceivedModal select[name="faid"]').append(Item);
	            compute_fee();
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
							$('#financeReceivedFilterModal select[name="type"]').append(Item);
				            $.sessionStorage('income_pay', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['name']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#financeReceivedFilterModal select[name="type"]').append(Item);
			}
			
			$('div#financeReceived').handle_page();
		    $('div#financeReceivedFilterModal').handle_modal_000();
		    $('div#financeReceivedModal').handle_modal_000();
		})(jQuery);
	</script>