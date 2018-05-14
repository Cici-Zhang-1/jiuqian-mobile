<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月16日
 * @author Administrator
 * @version
 * @des
 * 外购
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
            <div class="col-md-12" id="postSaleG">
				<form class="form-horizontal" method="post" id="postSaleGForm">
					<div class="form-group col-md-12">
						<p class="form-control-static" id="postSaleGFormError"></p>
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
						<label class="control-label  col-md-4">数量:</label>
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
    					<table	class="table-center table-form table table-bordered table-striped table-condensed col-md-6" id="postSaleGTable">
    						<thead>
    							<tr>
    								<th class="td-xs" >#</th>
    								<th >名称</th>
    								<th >规格</th>
    								<th class="td-sm" >数量</th>
    								<th class="td-xs" >单位</th>
    								<th class="td-xs" >单价</th>
    								<th class="td-xs" >份数</th>
    								<th class="td-sm" >备注</th>
    								<th class="hide" >oid</th>
    							</tr>
    						</thead>
    						<tbody>
    							<tr>
    								<td>1</td>
    								<td><input class="form-control input-sm" name="name" type="text" value="" /></td>
    								<td><input class="form-control input-sm" name="spec" type="text" /></td>
    								<td><input class="form-control input-sm" name="amount" type="text" /></td>
    								<td><input class="form-control input-sm" name="unit" type="text" value="" /></td>
    								<td><input class="form-control input-sm" name="unit_price" type="text" value="" /></td>
    								<td><input class="form-control input-sm" name="num" type="text" value="1"/></td>
    								<td><input class="form-control input-sm" name="remark" type="text" value=""/></td>
    								<td class="hide" ><input name="oid" type="hide" /></td>
    							</tr>
    						</tbody>
    					</table>
					</div>
					<div class="col-md-6">
    					<table class="table table-bordered table-striped table-condensed" id="postSaleOtherTable">
    						<thead>
    							<tr>
    								<th class="td-xs" >#</th>
    								<th >名称<input id="postSaleOtherTableSearch" class="form-control input-sm"/></th>
    								<th >规格</th>
    								<th >单位</th>
    								<th class="hide">oid</th>
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
					var clear_other_data = function(){
						$('#postSaleGTable tbody tr:gt(0)').remove();
					};
					var load_other_data = function(Opid){
						$.ajax({
							async: true,
							url: '<?php echo site_url('order/post_sale_g/read');?>',
							data: {id: Opid},
							dataType: 'json',
							type: 'GET',
							beforeSend: function(x){
								clear_other_data();
							},
							success: function(msg){
								if(0 == msg.error){
									var Other = msg.data.other,
										$Last, $LastClone, $This, $DTable, j;
									if(false != Other){
										for(var i in Other){
											$DTable = $('#postSaleGTable');
											$Last = $DTable.find('tbody tr:last');
											$LastClone = $Last.clone('true');
											$Last.find('input').val('');
											$Last.find('input,select').each(function(ii, vv){
												if(undefined != Other[i][this.name]){
													$.storedInputData(this, Other[i][this.name]);
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
					var Space="", Item, Other = undefined, OtherType, A1,A2,V1,V2;
					if(!(OtherType = $.sessionStorage('other_type'))){
						A1 = $.ajax({
							async: true,
							type: 'get',
							dataType: 'json',
							url: '<?php echo site_url('product/product/read/g');?>',
							success: function(msg){
									if(msg.error == 0){
										OtherType = msg.data.content;
										$.sessionStorage('other_type', OtherType);
							        }
								}
						});
					}
					if(!(Other = $.sessionStorage('other'))){
						A2 = $.ajax({
							async: true,
							type: 'get',
							dataType: 'json',
							url: '<?php echo site_url('product/other/read');?>',
							success: function(msg){
									if(msg.error == 0){
										Other = msg.data.content;
										$.sessionStorage('other', Other);
							        }
								}
						});
					}
					$.when(A1, A2).done(function(V1, V2){
						var i, FormatOther = {}, FormatOtherType = new Array;
						for(i in Other){
							if(undefined == FormatOther[Other[i]['type']]){
								FormatOther[Other[i]['type']] = '<tr><td></td><td >'+Other[i]['name']+'</td><td>'+Other[i]['spec']+'</td><td>'+Other[i]['unit']+'</td><td class="hide">'+Other[i]['oid']+'</td><td class="hide">'+Other[i]['unit_price']+'</td></tr>';
							}else{
								FormatOther[Other[i]['type']] += '<tr><td></td><td >'+Other[i]['name']+'</td><td>'+Other[i]['spec']+'</td><td>'+Other[i]['unit']+'</td><td class="hide">'+Other[i]['oid']+'</td><td class="hide">'+Other[i]['unit_price']+'</td></tr>';
							}
						}
						for(i in OtherType){
							Space = '&nbsp;';
							for(var k=0; k < OtherType[i]['class']; k++){
								Space += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
							}
							FormatOtherType.push('<tr class="success"><td>'+Space+'<i class="fa fa-caret-up fa-2x"></i></td><td>'+OtherType[i]['name']+'</td><td></td><td></td><td class="hide"></td></tr>');
							if(undefined != FormatOther[OtherType[i]['pid']]){
								FormatOtherType.push(FormatOther[OtherType[i]['pid']]);
							}
						}
						$('#postSaleOtherTable tbody').html(FormatOtherType.reverse().join(''));
						$('#postSaleOtherTable').find('tbody tr').each(function(i, v){
							$(this).on('dblclick', function(e){
								var $This = $(this), $Last = $('#postSaleGTable tbody').children('tr:last'), $Clone = $Last.clone(true);
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
									$Clone.find('input[name="spec"]').val($This.children('td:eq(2)').text());
									$Clone.find('input[name="unit"]').val($This.children('td:eq(3)').text());
									$Clone.find('input[name="oid"]').val($This.children('td:eq(4)').text());
									$Clone.find('input[name="unit_price"]').val($This.children('td:eq(5)').text());
									$Last.before($Clone).children('td:first').text(function(ii, vv){return parseInt(vv) + 1;});
									$Clone.find('input[name="amount"]').focus();
								}
							});
						});

						$('#postSaleOtherTableSearch').keyup(function(){
							$('#postSaleOtherTable').find('tbody tr:not(.success)').hide().filter(":contains('"+( $(this).val() )+"')").show();
						});
						
						if(0 != $('#postSaleGForm select[name="opid"]').val()){
							load_other_data($('#postSaleGForm select[name="opid"]').val());
						}
					});
					$('#postSaleGTable').table_form({DeleteLine:true});
					
					$('#postSaleGForm select[name="parent"]').on('change', function(e){
						var Parent = $(this).val();
						$('#postSaleGForm select[name="opid"] option').show().not('[data-parent="'+Parent+'"]').hide();
					});
					$('#postSaleGForm select[name="opid"]').on('change', function(e){
						if(0 != $(this).val()){
							var $Selected = $(this).find('option:selected'), Status = parseInt($Selected.data('status')),
    							Value = $Selected.val();
    						$('#postSaleGForm input[name="product"]').val($Selected.data('product'));
    						$('#postSaleGForm input[name="remarks"]').val($Selected.data('remarks'));
    						load_other_data(Value);
						}else{
							clear_other_data();
						}
					});
				})(jQuery);
			</script>