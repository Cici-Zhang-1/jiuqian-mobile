<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月16日
 * @author Zhangcc
 * @version
 * @des
 * 新增订单
 */
?>
    <div class="page-line" id="orderAdd" >
        <div class="my-tools col-md-12">
            <div class="col-md-3">
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderAddFunction">
	  		    <button class="btn btn-primary" data-toggle="save" data-target="#orderAddForm" data-action="<?php echo site_url('order/order/add');?>" type="button" value="新建"><i class="fa fa-save"></i>&nbsp;&nbsp;新建</button>
	  		    <button class="btn btn-primary" data-toggle="save" data-target="#orderAddForm" data-action="<?php echo site_url('order/order/add/dismantle');?>" type="button" value="新建并拆单"><i class="fa fa-save"></i>&nbsp;&nbsp;新建并拆单</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<form class="form-horizontal" id="orderAddForm" action="<?php echo site_url('order/order/add');?>" method="post" role="form">
        		<div class="form-group">
        			<label class="control-label col-md-2" >订单类型:</label>
        			<div class="col-md-4" id="orderAddOtid"></div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >任务等级:</label>
        			<div class="col-md-4" id="flag">
        			    <select class="form-control" name="flag"></select>
        			</div>
        			<div class="col-md-2">
        			    <p class="my-error form-control-static">*</p>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >客户:</label>
        			<div class="col-md-4" id="orderAddDealer">
        				<input type="hidden" name="did" value="" />
        				<input class="form-control" name="dealer" type="text" placeholder="客户"/>
        			</div>
        			<div class="col-md-2">
        			    <p class="my-error form-control-static">*</p>
        			</div>
        		</div>
        		<div class="form-group">
        			<div class="col-md-offset-2 col-md-4">
        				<button class="btn btn-default" id="orderAddModifyDealerInfo" type="button" value="修改客户信息"><i class="fa fa-pencil"></i>&nbsp;&nbsp;修改客户信息</button>
        			</div>
        		</div>
        		<fieldset class="hide" id="orderAddDealerInfo">
        			<div class="form-group">
        				<label class="control-label col-md-2">对单:</label>
        				<div class="col-md-2">
        					<input class="form-control" name="checker" type="text" placeholder="对单人" />
        				</div>
        				<div class="col-md-3">
        					<input class="form-control" name="checker_phone" type="text" placeholder="联系方式" />
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2">要求物流:</label>
        				<div class="col-md-4">
        					<select class="form-control" name='logistics'><option value="">--选择物流--</option></select>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2">出厂方式:</label>
        				<div class="col-md-4">
        					<select class="form-control" name='out_method'></select>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2">收货地址:</label>
        				<div class="col-md-3">
        					<select class="form-control" name="delivery_area">
        					   <option value="0">客户要求收货地区</option>
        					</select>
        				</div>
        				<div class="col-md-3">
        					<input class="form-control" name="delivery_address" type="text" placeholder="客户要求收货具体地址" />
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2">收货:</label>
        				<div class="col-md-2">
        					<input class="form-control" name="delivery_linker" type="text" placeholder="收货人" />
        				</div>
        				<div class="col-md-3">
        					<input class="form-control" name="delivery_phone" type="text" placeholder="收货人联系方式" />
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2">支付条款:</label>
        				<div class="col-md-4">
        					<select class="form-control" name="payterms">
        					</select>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2">付款:</label>
        				<div class="col-md-2">
        					<input class="form-control" name="payer" type="text" placeholder="付款人" />
        				</div>
        				<div class="col-md-3">
        					<input class="form-control" name="payer_phone" type="text" placeholder="付款人系方式" />
        				</div>
        			</div>
        		</fieldset>
        		<div class="form-group">
        			<label class="control-label col-md-2" >业主:</label>
        			<div class="col-md-4">
        				<input class="form-control" name="owner" placeholder="业主"/>
        			</div>
        			<div class="col-md-2">
        			    <p class="my-error form-control-static">*</p>
        			</div>
        		</div>
        		<div class="form-group">
        			<label class="control-label col-md-2" >产品:</label>
        			<div class="col-md-4" id="orderAddPid"></div>
        			<div class="col-md-2">
        			    <p class="my-error form-control-static">*</p>
        			</div>
        		</div>
        		
        		<div class="form-group">
        			<label class="control-label col-md-2" >要求出厂日期:</label>
        			<div class="col-md-4">
        				<input class="form-control" name="request_outdate" placeholder="要求出厂日期"/>
        			</div>
        		</div>
        		
        		<div class="form-group">
        			<label class="control-label col-md-2" >备注:</label>
        			<div class="col-md-4">
        				<input class="form-control" name="remark" placeholder="备注"/>
        			</div>
        		</div>
				
				<div class="form-group">
					<label class="control-label col-md-2" >客户备注:</label>
					<div class="col-md-4">
						<input class="form-control" name="dealer_remark" placeholder="客户备注"/>
					</div>
				</div>
        	</form>
		</div>
    </div>
<script type="text/javascript">
	(function($, window, undefined){
		var  Data = new Array, SessionData = undefined, Item = '', index;
		if(!(SessionData = $.sessionStorage('task_level'))){
    		$.ajax({
    			async: true,
    			type: 'get',
    			dataType: 'json',
    			url: '<?php echo site_url('data/task_level/read');?>',
    			success: function(msg){
    					if(msg.error == 0){
    						var Content = msg.data.content;
    						Item = '';
    						for(index in Content){
    							Item += '<option value="'+Content[index]['tlid']+'" >'+Content[index]['name']+'</option>';
    			            }
    						$.sessionStorage('task_level', Item);
    			            $('#orderAddForm select[name="flag"]').append(Item);
    			        }
    				}
    		});
		}else{
			$('#orderAddForm select[name="flag"]').append(SessionData);
		}
		if(!(SessionData = $.sessionStorage('order_type'))){
			$.ajax({
				async: true,
				type: 'get',
				dataType: 'json',
				url: '<?php echo site_url('data/order_type/read');?>',
				success: function(msg){
						if(msg.error == 0){
							var Content = msg.data.content;
    						Item = '';
							for(index in Content){
								Item += '<label  class="radio-inline"><input type="radio" name="otid" value="'+Content[index]['code']+'" >'+Content[index]['name']+'</label>';
							}
				            $('#orderAddForm #orderAddOtid').append(Item);
				            $('#orderAddForm #orderAddOtid').find('input:radio:first').prop('checked', true);

				            $.sessionStorage('order_type', Item);
				        }
					}
			});
		}else{
            $('#orderAddForm #orderAddOtid').append(SessionData);
            $('#orderAddForm #orderAddOtid').find('input:radio:first').prop('checked', true);
		}

		if(!(SessionData = $.sessionStorage('product'))){
			$.ajax({
				async: true,
				type: 'get',
				dataType: 'json',
				url: '<?php echo site_url('product/product/read/undelete');?>',
				success: function(msg){
						if(msg.error == 0){
							var Content = msg.data.content;
    						Item = '';
							for(index in Content){
								Item += '<div class="checkbox"><label><input checked="checked" name="pid" type="checkbox" value="'+Content[index]['pid']+'" />'+Content[index]['name']+'</label></div>';
							}
				            $('#orderAddForm #orderAddPid').append(Item);
				            $.sessionStorage('product', Content);
				        }
					}
			});
		}else{
			Item = '';
			for(index in SessionData){
				Item += '<div class="checkbox"><label><input checked="checked" name="pid" type="checkbox" value="'+SessionData[index]['pid']+'" />'+SessionData[index]['name']+'</label></div>';
			}
            $('#orderAddForm #orderAddPid').append(Item);
		}

		if(!(SessionData = $.sessionStorage('payterms'))){
    		$.ajax({
    			async: true,
    			type: 'get',
    			dataType: 'json',
    			url: '<?php echo site_url('dealer/payterms/read');?>',
    			success: function(msg){
    					if(msg.error == 0){
    						var Content = msg.data.content;
    						Item = '';
    						for(index in Content){
    							Item += '<option value="'+Content[index]['name']+'" >'+Content[index]['name']+'</option>';
    			            }
    			            $('#orderAddForm select[name="payterms"]').append(Item);
    			            $.sessionStorage('payterms', Item);
    			        }
    				}
    		});
		}else{
			$('#orderAddForm select[name="payterms"]').append(SessionData);
		}
		if(!(SessionData = $.sessionStorage('out_method'))){
			$.ajax({
				async: true,
				type: 'get',
				dataType: 'json',
				url: '<?php echo site_url('data/out_method/read');?>',
				success: function(msg){
						if(msg.error == 0){
							var Content = msg.data.content;
    						Item = '';
							for(index in Content){
								Item += '<option value="'+Content[index]['name']+'" >'+Content[index]['name']+'</option>';
				            }
				            $('#orderAddForm select[name="out_method"]').append(Item);
				            $.sessionStorage('out_method', Content);
				        }
					}
			});
		}else{
			Item = '';
			for(index in SessionData){
				Item += '<option value="'+SessionData[index]['name']+'" >'+SessionData[index]['name']+'</option>';
			}
			$('#orderAddForm select[name="out_method"]').append(Item);
		}

		if(!(SessionData = $.sessionStorage('area'))){
			$.ajax({
				async: true,
				type: 'get',
				dataType: 'json',
				url: '<?php echo site_url('data/area/read');?>',
				success: function(msg){
						if(msg.error == 0){
							var Content = msg.data.content;
    						Item = '';
							for(index in Content){
								Item += '<option value="'+Content[index]['area']+'" >'+Content[index]['area']+'</option>';
				            }
							$.sessionStorage('area', Content);
				            $('#orderAddForm select[name="delivery_area"]').append(Item);
				        }
					}
			});
		}else{
			Item = '';
			for(index in SessionData){
				Item += '<option value="'+SessionData[index]['area']+'" >'+SessionData[index]['area']+'</option>';
            }
            $('#orderAddForm select[name="delivery_area"]').append(Item);
		}

		if(!(SessionData = $.sessionStorage('logistics'))){
			$.ajax({
				async: true,
				type: 'get',
				dataType: 'json',
				url: '<?php echo site_url('data/logistics/read');?>',
				success: function(msg){
						if(msg.error == 0){
							var Content = msg.data.content;
    						Item = '';
							for(index in Content){
								Item += '<option value="'+Content[index]['name']+'" >'+Content[index]['name']+'</option>';
				            }
				            $('#orderAddForm select[name="logistics"]').append(Item);
				            $.sessionStorage('logistics', Content);
				        }
					}
			});
		}else{
			Item = '';
			for(index in SessionData){
				Item += '<option value="'+SessionData[index]['name']+'" >'+SessionData[index]['name']+'</option>';
			}
			$('#orderAddForm select[name="logistics"]').append(Item);
		}
		
		var formatItem = function(row){
	    	return row.name;
	    }
	    if(!(Dealer = $.sessionStorage('dealer_owner'))){
		    $.get("<?php echo site_url('dealer/dealer_owner/read');?>", function(msg){
		    	if(msg.error == 0){
	                var Content = msg.data.content, j = 0;
	                Dealer = {};
	                for(var i in Content){
		                Dealer[Content[i]['did']] = Content[i];
		            }
	                $.sessionStorage('dealer_owner', Dealer);
	                if(undefined != Dealer){
	    	        	for(var i in Dealer){
	    	        		Data[j++] = {
    	    	        		id: Dealer[i]['did'],
    	    	        		name: Dealer[i]['dealer_address']+'_'+Dealer[i]['dealer']+'_'+Dealer[i]['dealer_linker']+'_'+Dealer[i]['dealer_phone']
	    	                };
	    	            }
	    	        	$('#orderAddDealer input:text').each(function(i,e){
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
		    }, 'json');
		}else{
			if(undefined != Dealer){
				var j = 0;
	        	for(var i in Dealer){
	        		Data[j++] = {
	        				id: Dealer[i]['did'],
	        				name: Dealer[i]['dealer_address']+'_'+Dealer[i]['dealer']+'_'+Dealer[i]['dealer_linker']+'_'+Dealer[i]['dealer_phone']
	                };
	            }
	            $('#orderAddDealer input:text').each(function(i,e){
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
        
        $('#orderAddForm input[name="request_outdate"]').datepicker({
			todayBtn: "linked",
            language: "zh-CN",
            orientation: "bottom auto",
            autoclose: true,
            todayHighlight: true
		});
		$('#orderAddForm button#orderAddModifyDealerInfo').on('click', function(e){
			$('#orderAddForm fieldset#orderAddDealerInfo').toggleClass('hide');
        });
        $('div#orderAdd').handle_page();
	})(jQuery);
</script>