<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月9日
 * @author Zhangcc
 * @version
 * @des
 * 指派收款
 */
 
$Checkbox = '';
$Did = '';
$Dealer = '';
$Sum = 0;
if(isset($order) && is_array($order) && count($order) > 0){
    foreach ($order as $key => $value){
        if('0000-00-00 00:00:00' == $value['payed_datetime'] || '' == $value['payed_datetime']){
            $Checkbox .= <<<END
<label class="checkbox-inline"><input type="checkbox" name="order_num" value="$value[order_num]" title="$value[order_num]" checked>$value[sum]</label>
END;
            $Sum += $value['sum'];
        }else{
            $Checkbox .= <<<END
<label class="checkbox-inline has-light"><input type="checkbox" name="order_num" value="$value[order_num]" title="$value[order_num]">$value[sum]</label>
END;
        }
        
        if(empty($Did)){
            $Did = $value['did'];
            $Dealer = $value['dealer'];
        }
    }
}
?>
    <div class="page-line" id="financeReceivedPointer" >
        <div class="my-tools col-md-12">
            <div class="col-md-3">
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="financeReceivedPointerFunction">
	  		    <button class="btn btn-primary" data-toggle="save" data-target="#financeReceivedPointerForm" data-action="<?php echo site_url('finance/finance_received_pointer/add');?>" type="button" value="保存"><i class="fa fa-save"></i>&nbsp;&nbsp;保存</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<form class="form-horizontal" id="financeReceivedPointerForm" action="" method="post" role="form">
			    <input type="hidden" name="frid" value="<?php echo $frid;?>" />
			    <div class="form-group">
        			<label class="control-label col-md-2">账户:</label>
        			<div class="col-md-4">
        				<p class="form-control-static"><?php echo $name;?></p>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >客户:</label>
        			<div class="col-md-4" >
        				<input type="hidden" name="did" value="<?php echo $Did;?>" />
        				<input class="form-control" name="dealer" type="text" placeholder="客户/经销商" value="<?php echo $Dealer;?>"/>
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
        			<div class="col-md-4" id="financeReceivedPointerOrder"></div>
        		</div>
        		<div class="form-group">
        		    <label class="control-label col-md-2">认领类型</label>
        		    <div class="col-md-4" id="financeReceivedPointerType"></div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >账户到账:</label>
        			<div class="col-md-4">
        			    <input class="form-control" name="amount" value="<?php echo $amount;?>" readonly/>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >手续费:</label>
        			<div class="col-md-4">
        			    <p class="form-control-static"><?php echo $fee;?></p>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >货款金额:</label>
        			<div class="col-md-4">
        			    <input class="form-control" name="corresponding" value="<?php echo $amount;?>" placeholder="客户账户内进账金额"/>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >代收货号:</label>
        			<div class="col-md-4">
        			    <p class="form-control-static"><?php echo $cargo_no;?></p>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >备注:</label>
        			<div class="col-md-4">
        			    <textarea name="remark" class="form-control"><?php echo $remark;?></textarea>
        			</div>
        		</div>
        	</form>
		</div>
    </div>
<script type="text/javascript">
	(function($, window, undefined){
		var  Data = new Array(), Dealer = undefined, OrderType = undefined, Product = undefined, Payterms = undefined, OutMethod = undefined, Area = undefined, Logistics = undefined;
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
	    	        	$('#financeReceivedPointerForm input[name="dealer"]').each(function(i,e){
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
	    	        			$('#orderAddDealerInfo').find('input, select').each(function(i, v){
	    	        				this.reset;
	    	        				$(this).val(Dealer[row.id][this.name]);
	    	        		    });
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
	            $('#financeReceivedPointerForm input[name="dealer"]').each(function(i,e){
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
	        			$('#orderAddDealerInfo').find('input, select').each(function(i, v){
		        			this.reset;
	        				$(this).val(Dealer[row.id][this.name]);
	        		    });
	        		});
	            });
	        }
		}
		$('#financeReceivedPointerForm input[name="dealer"]').on('focusout', function(e){
			var Data = {};
			Data['did'] = $(this).parent().prev().val();
			Data['days'] = $('#financeReceivedPointerForm input[name="days"]').val();
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
        	            $('#financeReceivedPointerOrder').html(Checkbox);
                    }
                },
                error: function(x,t,e){
                	alert(x.responseText);
                }
            });
		});
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
						$('#financeReceivedPointerType').append(Item);
			            $.sessionStorage('income_pay', Content);
			        }
				}
			});
		}else{
			var Item = '';
			for(var i in SessionData){
				Item += '<label class="radio-inline"><input type="radio" name="type" value="'+SessionData[i]['name']+'">'+SessionData[i]['name']+'</label>';
			}
			$('#financeReceivedPointerType').append(Item);
		}
        $('div#financeReceivedPointer').handle_page();
	})(jQuery);
</script>