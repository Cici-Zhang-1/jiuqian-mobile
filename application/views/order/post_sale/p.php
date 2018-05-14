<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月16日
 * @author Zhangcc
 * @version
 * @des
 * 配件
 */
$Option1 = '';
$Option2 = '';
$Tmp = array();
if(isset($Dismantle) && is_array($Dismantle) && count($Dismantle) > 0){
    foreach ($Dismantle as $key=>$value){
        if($opid == $value['opid']){
            $Option1 .= '<option value="'.$value['opid'].'" data-status="'.$value['status'].'" data-parent="'.$value['parent'].'" data-product="'.$value['product'].'" data-remarks="'.$value['remark'].'" selected="selected">'.$value['order_product_num'].'-'.$value['product'].'</option>';
        }else{
            $Option1 .= '<option value="'.$value['opid'].'" data-status="'.$value['status'].'" data-parent="'.$value['parent'].'" data-product="'.$value['product'].'" data-remarks="'.$value['remark'].'">'.$value['order_product_num'].'-'.$value['product'].'</option>';
        }
        $Tmp[] = $value['opid'];
    }
}

if(isset($Dismantled) && is_array($Dismantled) && count($Dismantled) > 0){
    foreach ($Dismantled as $key=>$value){
        $Option1 .= <<<END
<option value="$value[opid]" data-product="$value[product]" data-status="$value[status]">$value[order_product_num]-$value[product]</option>
END;
    }
}

if(isset($OrderProductQuery) && is_array($OrderProductQuery) && count($OrderProductQuery) > 0){
    $OptionTmp = array();
    foreach ($OrderProductQuery as $key=>$value){
        if(!in_array($value['opid'], $Tmp)){
            $OptionTmp[] = '<option value="'.$value['opid'].'" >'.$value['order_product_num'].'-'.$value['product'].'</option>';
        }
    }
    $Option2 = implode('', $OptionTmp);
    unset($OptionTmp);
}
?>
            <div class="col-md-12" id="postSaleP">
				<form class="form-horizontal" method="post" id="postSalePForm">
				    <div class="form-group col-md-12">
						<p class="form-control-static" id="postSalePFormError"></p>
					</div>
					<input type="hidden" name="code" value="<?php echo $Code;?>" />
					<div class="form-group col-md-3">
						<label class="control-label  col-md-4">相关订单:</label>
						<div class="col-md-8">
							<select class="form-control" name="parent">
							    <option value="0">请选择具体订单</option>
                                <?php echo $Option2;?>
							</select>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="control-label  col-md-4">选择订单:</label>
						<div class="col-md-8">
							<select class="form-control" name="opid">
							    <option value="0">请选择具体订单</option>
                                <?php echo $Option1;?>
							</select>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="control-label  col-md-4">名称:</label>
						<div class="col-md-8">
							<input class="form-control" name="product" type="text" value="<?php echo $product;?>" placeholder="产品名称"/>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="control-label col-md-4">数量:</label>
						<div class="col-md-8">
							<input class="form-control" name="set" type="text" value="1" placeholder="套数"/>
						</div>
					</div>
					<div class="form-group col-md-12">
						<label class="control-label col-md-2">备注:</label>
						<div class="col-md-8">
						    <input class="form-control" name="remarks" type="text" value="<?php echo $remark;?>"/>
						</div>
					</div>
					
					<div class="col-md-6">
    					<table	class="table-center table-form table table-bordered table-striped table-condensed col-md-6" id="postSalePTable">
    						<thead>
    							<tr>
    								<th class="td-xs" >#</th>
    								<th >名称</th>
    								<th class="td-xs" >数量</th>
    								<th class="td-xs" >单位</th>
    								<th class="td-sm" >单价</th>
    								<th class="td-xs" >份数</th>
    								<th class="td-sm" >备注</th>
    								<th class="hide" >fid</th>
    							</tr>
    						</thead>
    						<tbody>
    							<tr>
    								<td>1</td>
    								<td><input class="form-control input-sm" name="name" type="text" value="" /></td>
    								<td><input class="form-control input-sm" name="amount" type="text" /></td>
    								<td><input class="form-control input-sm" name="unit" type="text" value="" /></td>
    								<td><input class="form-control input-sm" name="unit_price" type="text" value="" /></td>
    								<td><input class="form-control input-sm" name="num" type="text" value="1"/></td>
    								<td><input class="form-control input-sm" name="remark" type="text" value=""/></td>
    								<td class="hide"><input type="hidden" name="fid" /></td>
    							</tr>
    						</tbody>
    					</table>
					</div>
					<div class="col-md-6">
    					<table class="table table-bordered table-striped table-condensed" id="postSaleFittingTable">
    						<thead>
    							<tr>
    							    <th>#</th>
    								<th>名称<input id="postSaleFittingTableSearch" class="form-control input-sm"/></th>
    								<th>单位</th>
    								<th class="hide">id</th>
    							</tr>
    						</thead>
    						<tbody>
    						</tbody>
    					</table>
					</div>
				</form>
			</div>
			<script>
				(function($){
					/*在载入配件信息时需要先清除已经载入的配件信息*/
					var clear_peijian_data = function(){
						$('#postSalePTable tbody tr:gt(0)').remove();
					};
					var load_peijian_data = function(Opid){
						$.ajax({
							async: true,
							url: '<?php echo site_url('order/post_sale_p/read');?>',
							data: {id: Opid},
							dataType: 'json',
							type: 'GET',
							beforeSend: function(x){
								clear_peijian_data();
							},
							success: function(msg){
								if(0 == msg.error){
									var Fitting = msg.data.fitting,
										$Last, $LastClone, $This, $DTable, j;
									if(false != Fitting){
										for(var i in Fitting){
											$DTable = $('#postSalePTable');
											$Last = $DTable.find('tbody tr:last');
											$LastClone = $Last.clone('true');
											$Last.find('input').val('');
											$Last.find('input,select').each(function(ii, vv){
												if(undefined != Fitting[i][this.name]){
													$.storedInputData(this, Fitting[i][this.name]);
												}
											});
											$LastClone.children('td:first').html(function(n){
							                	j = parseInt($(this).text())+1;
							                	return j;
							                });
											$DTable.append($LastClone);
										}
									}
								}else{
									alert(msg.message);
								}
							},
							error: function(x, t, e){x.responseText}
						});
					};
					var Space="", Item, Fitting = undefined, FittingType, A1,A2;
					if(!(FittingType = $.sessionStorage('fitting_type'))){
						A1 = $.ajax({
							async: true,
							type: 'get',
							dataType: 'json',
							url: '<?php echo site_url('product/product/read/p');?>',
							success: function(msg){
									if(msg.error == 0){
										FittingType = msg.data.content;
										$.sessionStorage('fitting_type', FittingType);
							        }
								}
						});
					}
					if(!(Fitting = $.sessionStorage('fitting'))){
						A2 = $.ajax({
							async: true,
							type: 'get',
							dataType: 'json',
							url: '<?php echo site_url('product/fitting/read');?>',
							success: function(msg){
									if(msg.error == 0){
										Fitting = msg.data.content;
										$.sessionStorage('fitting', Fitting);
							        }
								}
						});
					}
					$.when(A1, A2).done(function(V1, V2){
						var i, FormatFitting = {}, FormatFittingType = new Array;
						console.log(Fitting);
						for(i in Fitting){
							if(undefined == FormatFitting[Fitting[i]['type']]){
								FormatFitting[Fitting[i]['type']] = '<tr><td></td><td >'+Fitting[i]['name']+'</td><td>'+Fitting[i]['unit']+'</td><td class="hide">'+Fitting[i]['fid']+'</td><td class="hide">'+Fitting[i]['unit_price']+'</td></tr>';
							}else{
								FormatFitting[Fitting[i]['type']] += '<tr><td></td><td >'+Fitting[i]['name']+'</td><td>'+Fitting[i]['unit']+'</td><td class="hide">'+Fitting[i]['fid']+'</td><td class="hide">'+Fitting[i]['unit_price']+'</td></tr>';
							}
						}
						for(i in FittingType){
							Space = '&nbsp;';
							for(var k=0; k < FittingType[i]['class']; k++){
								Space += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
							}
							FormatFittingType.push('<tr class="success"><td>'+Space+'<i class="fa fa-caret-up fa-2x"></i></td><td>'+FittingType[i]['name']+'</td><td></td><td class="hide"></td></tr>');
							if(undefined != FormatFitting[FittingType[i]['pid']]){
								FormatFittingType.push(FormatFitting[FittingType[i]['pid']]);
							}
						}
						$('#postSaleFittingTable tbody').html(FormatFittingType.reverse().join(''));

						$('#postSaleFittingTable').find('tbody tr').each(function(i, v){
							$(this).on('dblclick', function(e){
								var $This = $(this), $Last = $('#postSalePTable tbody').children('tr:last'), $Clone = $Last.clone(true);
								if($This.find('i').length > 0){
									var $I = $This.find('i'), Space = $This.children('td:first').text().length, 
										$Tmp = $This.prev(), $Select;
									while($Tmp.length > 0 && (!$Tmp.hasClass('success') || $Tmp.children('td:first').text().length > Space)){
										$Tmp = $Tmp.prev();
									}
									if(0 !== $Tmp.length){
										$Tmp.addClass('select');
										$Select = $This.prevUntil('.select');
										$Tmp.removeClass('select');
									}else{
										$Select = $This.prevAll();
									}
									if($I.hasClass('fa-caret-up')){
										$I.removeClass('fa-caret-up').addClass('fa-caret-right');
										$Select.addClass('hide').find('i').removeClass('fa-caret-up').addClass('fa-caret-right');
									}else{
										$I.removeClass('fa-caret-right').addClass('fa-caret-up');
										$Select.removeClass('hide').find('i').removeClass('fa-caret-right').addClass('fa-caret-up');
									}
								}else{
									$Clone.find('input[name="name"]').val($This.children('td:eq(1)').text());
									$Clone.find('input[name="unit"]').val($This.children('td:eq(2)').text());
									$Clone.find('input[name="fid"]').val($This.children('td:eq(3)').text());
									$Clone.find('input[name="unit_price"]').val($This.children('td:eq(4)').text());
									$Last.before($Clone).children('td:first').text(function(ii, vv){return parseInt(vv) + 1;});
									$Clone.find('input[name="amount"]').focus();
								}
							});
						});

						$('#postSaleFittingTableSearch').keyup(function(){
							$('#postSaleFittingTable').find('tbody tr:not(.success)').hide().filter(":contains('"+( $(this).val() )+"')").show();
						});
						
						if(0 != $('#postSalePForm select[name="opid"]').val()){
							load_peijian_data($('#postSalePForm select[name="opid"]').val());
						}
					});
					$('#postSalePTable').table_form({DeleteLine:true});
					
					$('#postSalePForm select[name="parent"]').on('change', function(e){
						var Parent = $(this).val();
						$('#postSalePForm select[name="opid"] option').show().not('[data-parent="'+Parent+'"]').hide();
					});
					$('#postSalePForm select[name="opid"]').on('change', function(e){
						if(0 != $(this).val()){
							var $Selected = $(this).find('option:selected'), Status = parseInt($Selected.data('status')),
    							Value = $Selected.val();
    						$('#postSalePForm input[name="product"]').val($Selected.data('product'));
    						$('#postSalePForm input[name="remarks"]').val($Selected.data('remarks'));
    						load_peijian_data(Value);
						}else{
							clear_peijian_data();
						}
					});
				})(jQuery);
			</script>