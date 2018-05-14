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
	<div class="page-line" id="financePay" >
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="financePaySearch" data-toggle="filter" data-target="#financePayTable">
				    <span class="input-group-btn">
        				<button class="btn btn-default" type="button" id="financePayFilterCon" data-toggle="modal" data-target="#financePayFilterModal"><i class="fa fa-search"></i></button>
      				</span>
      				<input type="hidden" name="type" value=""/>
      				<input type="hidden" name="start_date" value=""/>
      				<input type="hidden" name="end_date" value=""/>
      				<input type="hidden" name="account" value=""/>
		      		<input type="text" class="form-control" name="keyword" placeholder="经销商/备注">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="financePayFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="financePayFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="financePayTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#financePayTable">
		    		    <li><a href="javascript:void(0);"  data-toggle="modal" data-target="#financePayModal" data-action="<?php echo site_url('finance/finance_pay/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
		  		<button class="btn btn-primary" data-toggle="modal" data-target="#financePayModal" data-action="<?php echo site_url('finance/finance_pay/add');?>" type="button" value="新建"><i class="fa fa-plus"></i>&nbsp;&nbsp;新建</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#financePayTable" href="<?php echo site_url('finance/finance_pay/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="financePayTable" data-load="<?php echo site_url('finance/finance_pay/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >账号</th>
						<th >转入账号</th>
						<th data-name="type">支出类型</th>
						<th data-name="amount">支出金额</th>
						<th data-name="fee">手续费</th>
						<th data-name="bank_date">支出日期</th>
						<th data-name="supplier">供应商</th>
						<th data-name="remark">备注</th>
						<th >登记人</th>
						<th >登记时间</th>
						<th class="hide" data-name="faid">faid</th>
						<th class="hide" data-name="in_faid">in_faid</th>
						<th class="hide" data-name="supplier_id">supplier_id</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="fpid"  type="checkbox" value=""/></td>
			      		<td name="name"></td>
			      		<td name="in_name"></td>
			      		<td name="type"></td>
			      		<td name="amount"></td>
						<td name="fee"></td>
						<td name="bank_date"></td>
						<td name="supplier"></td>
						<td name="remark"></td>
						<td name="creator"></td>
						<td name="create_datetime"></td>
						<td class="hide" name="faid"></td>
						<td class="hide" name="in_faid"></td>
						<td class="hide" name="supplier_id"></td>
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
	
	<div class="modal fade" id="financePayModal" tabindex="-1" role="dialog" aria-labelledby="financePayModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="financePayModalForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="financePayModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >账号:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="faid"><option value="">--选择支出账户--</option></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >转入账号:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="in_faid"><option value="0">--选择转入账户--</option></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >支出类型:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="type"><option value="">--选择支出类型--</option></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >支出金额:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="amount" type="text" placeholder="支出金额" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >支出手续费:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="fee" type="text" placeholder="支出手续费" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >支出日期:</label>
			      			<div class="col-md-6">
			      				<input class="form-control datepicker" name="bank_date" type="text" placeholder="支出日期" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >供应商:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="supplier_id">
			      			        <option value="0">--选择供应商--</option>
			      			    </select>
			      			    <input type="hidden" name="supplier" />
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
	
    <div class="modal fade filter" id="financePayFilterModal" tabindex="-1" role="dialog" aria-labelledby="financePayFilterModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
			    <form  class="form-horizontal" id="financePayFilterForm" action="" method="post" role="form">
    			    <div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            			<h4 class="modal-title" id="financePayFilterModalLabel">搜索</h4>
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
				var Fee, FeeMax, Amount, $Fee, $Faid;
				$Fee = $('#financePayModal input[name="fee"]');
				$Faid = $('#financePayModal select[name="faid"]');
				var compute = function(){
					Amount = $('#financePayModal input[name="amount"]').val(),
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
						$Fee.val(Amount*Fee > FeeMax? FeeMax: Amount*Fee);
					}
				};
				$('#financePayModal select[name="faid"]').on('change', function(e){
					compute();
				});
				$('#financePayModal input[name="amount"]').on('blur', function(e){
					compute();
				});
			};
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
							$('#financePayFilterModal select[name="account"]').append(Item);
							$('#financePayModal select[name="faid"]').append(Item);
							$('#financePayModal select[name="in_faid"]').append(Item);
							compute_fee();
				            $.sessionStorage('account', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['faid']+'" data-fee="'+SessionData[i]['fee']+'" data-fee_max="'+SessionData[i]['fee_max']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#financePayFilterModal select[name="account"]').append(Item);
	            $('#financePayModal select[name="faid"]').append(Item);
	            $('#financePayModal select[name="in_faid"]').append(Item);
	            compute_fee();
			}
			if(!(SessionData = $.sessionStorage('income_pay_pay'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('finance/income_pay/read/pay');?>',
					success: function(msg){
						if(msg.error == 0){
							var Item = '', Content = msg.data.content;
							for(var i in Content){
								Item += '<option value="'+Content[i]['name']+'" >'+Content[i]['name']+'</option>';
							}
							$('#financePayFilterModal select[name="type"]').append(Item);
							$('#financePayModal select[name="type"]').append(Item);
				            $.sessionStorage('income_pay_pay', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['name']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#financePayFilterModal select[name="type"]').append(Item);
	            $('#financePayModal select[name="type"]').append(Item);
			}

			if(!(SessionData = $.sessionStorage('supplier'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('supplier/supplier/read/all');?>',
					success: function(msg){
							if(msg.error == 0){
								var Item = '', Content = msg.data.content;
								for(var i in Content){
									Item += '<option value="'+Content[i]['sid']+'" >'+Content[i]['name']+'</option>';
								};
					            $('#financePayModal select[name="supplier_id"]').append(Item);
					            $('#financePayModal select[name="supplier_id"]').on('change', function(e){
						            if(0 != $(this).val()){
							            $(this).next().val($(this).find('option:selected').text());
						            }
					            });
					            $.sessionStorage('supplier', Content);
					        }
						}
				});
			}else{
				var Item = '';
				for(var i=0 in SessionData){
					Item += '<option value="'+SessionData[i]['sid']+'" >'+SessionData[i]['name']+'</option>';
	            }
				$('#financePayModal select[name="supplier_id"]').append(Item);
				$('#financePayModal select[name="supplier_id"]').on('change', function(e){
		            if(0 != $(this).val()){
			            $(this).next().val($(this).find('option:selected').text());
		            }
	            });
			}
			
			$('div#financePay').handle_page();
		    $('div#financePayFilterModal').handle_modal_000();
		    $('div#financePayModal').handle_modal_000();
		})(jQuery);
	</script>