<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月25日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="financeReceivedOuttimeAdd" >
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="hide input-group" id="financeReceivedOuttimeAddSearch">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="financeReceivedOuttimeAddSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="financeReceivedOuttimeAddFunction">
	  		    <button class="btn btn-primary" data-toggle="save" data-target="#financeReceivedOuttimeAddForm" data-action="<?php echo site_url('finance/finance_received_outtime/add');?>" type="button" value="保存"><i class="fa fa-save"></i>&nbsp;&nbsp;保存</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
		    <form class="form-horizontal" id="financeReceivedOuttimeAddForm" action="<?php echo site_url('finance/finance_received_outtime/add');?>" method="post" role="form">
        		<div class="form-group">
        			<label class="control-label col-md-2">账户:</label>
        			<div class="col-md-4">
        			    <select class="form-control" name="faid"></select>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >客户:</label>
        			<div class="col-md-4" >
        				<input type="hidden" name="did" value="" />
        				<input class="form-control" name="dealer" type="text" placeholder="客户/经销商" value=""/>
        			</div>
        		</div>
        		<div class="form-group">
        		    <label class="control-label col-md-2">显示天数:</label>
        			<div class="col-md-4">
                        <input class="form-control" name="days" type="text" value="30" placeholder="要求显示多少天内的订单, 默认是30天"/>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >对应订单:</label>
        			<div class="col-md-4" id="financeReceivedOuttimeAddOrder"></div>
        		</div>
        		<div class="form-group">
        		    <label class="control-label col-md-2">进账类型</label>
        		    <div class="col-md-4" id="financeReceivedOuttimeAddType"></div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >到账金额:</label>
        			<div class="col-md-4">
        			    <input class="form-control" name="amount" value="" placeholder="0.00"/>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >手续费:</label>
        			<div class="col-md-4">
        			    <input class="form-control" name="fee" value="" placeholder="0.00"/>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >对应货款:</label>
        			<div class="col-md-4">
        			    <input class="form-control" name="corresponding" value="" placeholder="客户账户内进账金额"/>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >代收货号:</label>
        			<div class="col-md-4">
        			    <input class="form-control" name="cargo_no" value="" placeholder="对应代收货号"/>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >备注:</label>
        			<div class="col-md-4">
        			    <textarea name="remark" class="form-control"></textarea>
        			</div>
        		</div>
        	</form>
		</div>
    </div>
	<script type="text/javascript">
		(function($, window, undefined){
			var  Data = new Array(), Dealer = undefined, OrderType = undefined, 
				Product = undefined, Payterms = undefined, OutMethod = undefined, 
				Area = undefined, Logistics = undefined;
			var formatItem = function(row){
		    	return row.name;
		    }
			if(!(Dealer = $.sessionStorage('dealer'))){
		    	$.get("<?php echo site_url('dealer/dealer/read/all')?>", function(msg){
		            if(msg.error == 0){
		                var Content = msg.data.content, j = 0;
		                Dealer = {};
		                for(var i in Content){
			                Dealer[Content[i]['did']] = Content[i];
			            }
		                $.sessionStorage('dealer', Dealer);
		                if(undefined != Dealer){
		    	        	for(var i in Dealer){
		    	        		Data[j++] = {
	    	    	        		id: Dealer[i]['did'],
	    	    	        		name: Dealer[i]['dealer_address']+'_'+Dealer[i]['dealer']+'_'+Dealer[i]['dealer_linker']+'_'+Dealer[i]['dealer_phone']
		    	                };
		    	            }
		    	        	$('#financeReceivedOuttimeAddForm input[name="dealer"]').each(function(i,e){
		    	            	$(this).autocomplete({
		    	        			minLength: 0,
		    	        			autoselect: true,
		    	        			showHint: false,
		    	        			source:[Data],
		    	        			valueKey: 'name',
		    	        			getValue: formatItem,
		    	        			getTitle: formatItem
		    	        		}).on('selected.xdsoft',function(e,row){
		    	        			$(this).parent().prev().val(row.id);
		    	        		});
		    	            });
		    	        }
		            }
		        },'json');
			}else{
				if(undefined != Dealer){
					var j = 0;
		        	for(var i in Dealer){
		        		Data[j++] = {
		        				id: Dealer[i]['did'],
		        				name: Dealer[i]['dealer_address']+'_'+Dealer[i]['dealer']+'_'+Dealer[i]['dealer_linker']+'_'+Dealer[i]['dealer_phone']
		                };
		            }
		            $('#financeReceivedOuttimeAddForm input[name="dealer"]').each(function(i,e){
		            	$(this).autocomplete({
		        			minLength: 0,
		        			autoselect: true,
		        			showHint: false,
		        			source:[Data],
		        			valueKey: 'name',
		        			getValue: formatItem,
		        			getTitle: formatItem
		        		}).on('selected.xdsoft',function(e,row){
		        			$(this).parent().prev().val(row.id);
		        		});
		            });
		        }
			}
			$('#financeReceivedOuttimeAddForm input[name="dealer"]').on('focusout', function(e){
				var Data = {};
				Data['did'] = $(this).parent().prev().val();
				Data['days'] = $('#financeReceivedOuttimeAddForm input[name="days"]').val();
				$.ajax({
	                async: false,
	                data: Data,
	                url: '<?php echo site_url('order/order/read_order_num');?>',
	                type: 'get',
	                dataType: 'json',
	                success: function(msg){
	                    if(msg.error == 0){
	                        var Content = msg.data, Checkbox = '', SelectedMoney;
	                        for(var i in Content){
	                            if('0000-00-00 00:00:00' == Content[i]['payed_datetime'] || '' == Content[i]['payed_datetime']){
	                            	Checkbox += '<label class="checkbox-inline"><input type="checkbox" name="order_num" value="'+Content[i]['order_num']+'" title="'+Content[i]['order_num']+'">'+Content[i]['sum']+'</label>';
	                            }else{
	                            	Checkbox += '<label class="checkbox-inline has-light"><input type="checkbox" name="order_num" value="'+Content[i]['order_num']+'" title="'+Content[i]['order_num']+'">'+Content[i]['sum']+'</label>';
	                            }
	        	            }
	        	            $('#financeReceivedOuttimeAddOrder').html(Checkbox);
	        	            $('#financeReceivedOuttimeAddOrder input:checkbox[name="order_num"]').on('change', function(e){
		        	            if($('#financeReceivedOuttimeAddOrder').find('input:checkbox:checked').length > 0){
		        	            	SelectedMoney = eval($.map($('#financeReceivedOuttimeAddOrder').find('input:checkbox:checked'), function(n){return parseFloat($(n).parent().text());}).join('+')).toFixed(2);
		        	            }else{
		        	            	SelectedMoney = 0;
		        	            }
	        	            	$('#financeReceivedOuttimeAddForm input[name="corresponding"]').val(SelectedMoney);
	        	            	$('#financeReceivedOuttimeAddForm input[name="amount"]').val(SelectedMoney);
	            	        });
	                    }
	                },
	                error: function(x,t,e){
	                	alert(x.responseText);
	                }
	            });
			});

			var compute_fee = function(){
				var Fee, FeeMax, Amount, $FeeIn, $Faid;
				$FeeIn = $('#financeReceivedOuttimeAddForm input[name="fee"]');
				$Faid = $('#financeReceivedOuttimeAddForm select[name="faid"]');
				var compute = function(){
					Amount = $('#financeReceivedOuttimeAddForm input[name="amount"]').val(),
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
						$FeeIn.val(Amount*Fee > FeeMax? FeeMax: (Amount*Fee).toFixed(2));
					}
				};
				$('#financeReceivedOuttimeAddForm select[name="faid"]').on('change', function(e){
					compute();
				});
				$('#financeReceivedOuttimeAddForm input[name="amount"]').on('blur', function(e){
					compute();
				});
			};
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
							$('#financeReceivedOuttimeAddForm select[name="faid"]').append(Item);
							compute_fee();
				            $.sessionStorage('account_outtime', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['faid']+'" data-fee="'+SessionData[i]['fee']+'" data-fee_max="'+SessionData[i]['fee_max']+'" >'+SessionData[i]['name']+'</option>';
				}
	            $('#financeReceivedOuttimeAddForm select[name="faid"]').append(Item);
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
								Item += '<label class="radio-inline"><input type="radio" name="type" value="'+Content[i]['name']+'">'+Content[i]['name']+'</label>';
							}
							$('#financeReceivedOuttimeAddType').append(Item);
				            $.sessionStorage('income_pay', Content);
				        }
					}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<label class="radio-inline"><input type="radio" name="type" value="'+SessionData[i]['name']+'">'+SessionData[i]['name']+'</label>';
				}
				$('#financeReceivedOuttimeAddType').append(Item);
			}
			$('#financeReceivedOuttimeAdd').handle_page();
		})(jQuery);
	</script>