<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月16日
 * @author Administrator
 * @version
 * @des
 * 衣柜拆单
 */
?>
            <div class="col-md-12" id="dismantleY">
				<form class="form-horizontal" method="post" id="dismantleYForm">
					<div class="form-group col-md-12">
						<p class="form-control-static" id="dismantleYFormError"></p>
					</div>
					<input type="hidden" name="code" value="<?php echo $Code;?>" />
					<div class="form-group col-md-3">
						<label class="control-label  col-md-4">订单:</label>
						<div class="col-md-8">
							<select class="form-control" name="opid">
							    <option value="0">请选择具体订单</option>
                                <?php
							    if(isset($Dismantle) && is_array($Dismantle) && count($Dismantle) > 0){
							        foreach ($Dismantle as $key=>$value){
							            if($opid == $value['opid']){
							                 echo <<<END
<option value="$value[opid]" selected="selected" data-status="$value[status]" data-product="$value[product]" data-remarks="$value[remark]">$value[order_product_num]-$value[product]</option>
END;
							            }else{
							                 echo <<<END
<option value="$value[opid]" data-status="$value[status]" data-product="$value[product]" data-remarks="$value[remark]">$value[order_product_num]-$value[product]</option>
END;
							            }
							        }
							    }
							    if(isset($Dismantled) && is_array($Dismantled) && count($Dismantled) > 0){
							        foreach ($Dismantled as $key=>$value){
							            echo <<<END
<option value="$value[opid]" data-product="$value[product]" data-status="$value[status]">$value[order_product_num]-$value[product]</option>
END;
							        }
							    }
							    ?>
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
					<div class="form-group col-md-3">
						<label class="control-label  col-md-4">结构:</label>
						<div class="col-md-8">
							<select class="form-control" name="struct">
							    <option value="三合一">三合一</option>
	                            <option value="二合一">二合一</option>
	                            <option value="螺钉">螺钉</option>
	                            <option value="">--无--</option>
							</select>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="control-label  col-md-4">板材:</label>
						<div class="col-md-8">
							<input class="form-control" name="bid" type="hidden" value=""/>
							<input class="form-control" name="board" type="text" value="" placeholder="选择板材颜色" data-target="good"/>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="control-label  col-md-4">厚度颜色:</label>
						<div class="col-md-8">
							<select class="form-control" name="change_line">
							    <option value="*">*</option>
							    <option value="18">18mm</option>
							    <option value="5">5mm</option>
							    <option value="25">25mm</option>
							    <option value="36">36mm</option>
							</select>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="control-label col-md-4">备注:</label>
						<div class="col-md-8">
						    <input class="form-control" name="remarks" type="text" value="<?php echo $remark;?>"/>
						</div>
					</div>
					<div class="form-group col-md-6">
					    <input type="hidden" name="opwsid" value="">
					</div>
					<table	class="table-center table-form table table-bordered table-striped table-condensed col-md-12" id="dismantleYTable">
						<thead>
							<tr>
								<th class="td-xs" >#</th>
								<th class="td-md" >板块名称</th>
								<th >板材</th>
								<th class="td-xs" >长</th>
								<th class="td-xs" >宽</th>
								<th class="td-xs" >块</th>
								<th class="td-xs" >面积</th>
								<th class="td-sm" >封边</th>
								<th >开槽</th>
								<th >打孔</th>
								<th >尺判</th>
								<th >备注</th>
								<th class="hide"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td><input class="form-control input-sm" name="plate_name" list="yPlateName" type="text"/>
								    <datalist id="yPlateName"></datalist>
								</td>
								<td><input class="form-control input-sm" name="good" type="text" /></td>
								<td><input class="form-control input-sm" name="length" type="text" /></td>
								<td><input class="form-control input-sm" name="width" type="text" /></td>
								<td><input class="form-control input-sm" name="num" type="text" value="1"/></td>
								<td><input class="form-control input-sm" name="area" type="text" value="" readonly="readonly"/></td>
								<td><select class="form-control input-sm" name="edge">
										<option value="">--请选择--</option>
									</select></td>
								<td><select class="form-control input-sm" name="slot">
										<option value="">--请选择--</option>
									</select></td>
								<td><select class="form-control input-sm" name="punch">
										<option value="">--请选择--</option>
									</select></td>
								<td><input class="form-control input-sm" name="decide_size" type="text" /></td>
								<td><input class="form-control input-sm" list="yRemark" name="remark" type="text" value=""/>
								    <datalist id="yRemark"></datalist>
								</td>
								<td class="hide">
								    <input class="form-control input-sm" name="qrcode" type="text" value=""/>
								    <input class="form-control input-sm" name="cubicle_name" type="text" value=""/>
								    <input class="form-control input-sm" name="cubicle_num" type="text" value=""/>
								    <input class="form-control input-sm" name="plate_num" type="text" value=""/>
								    <input class="form-control input-sm" name="bd_file" type="text" value=""/>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
			<script>
				(function($){
					var clear_yigui_data = function(){
						$('#dismantleYTable tbody tr:gt(0)').remove();
						$('#dismantleYTable tbody tr:eq(0) input[name="plate_name"]').val('');
					};
					var load_yigui_data = function(Opid){
						$.ajax({
							async: true,
							url: '<?php echo site_url('order/dismantle_y/read');?>',
							data: {id: Opid},
							dataType: 'json',
							type: 'GET',
							beforeSend: function(x){
								clear_yigui_data();
							},
							success: function(msg){
								if(0 == msg.error){
									var Struct = msg.data.struct, BoardPlate = msg.data.board_plate,
										$Last, $LastClone, $This, $DTable, j, Autocomplete;
									if(false != Struct){
										$('#dismantleYForm input:hidden[name="opwsid"]').val(Struct.opwsid);
										$('#dismantleYForm input:hidden[name="bid"]').val(Struct.bid);
										$('#dismantleYForm input[name="board"]').val(Struct.board);
										$('#dismantleYForm input[name="struct"]').val(Struct.struct);
									}
									if(false != BoardPlate){
										for(var i in BoardPlate){
											$DTable = $('#dismantleYTable');
											$Last = $DTable.find('tbody tr:last');
											$LastClone = $Last.clone('true');
											$Last.find('input').val('');
											$Last.find('input,select').each(function(ii, vv){
												if(undefined != BoardPlate[i][this.name]){
													$.storedInputData(this, BoardPlate[i][this.name]);
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
					var Item, SessionData = undefined, index, A1,A2,A3,A4,A5;
					if(!(SessionData = $.sessionStorage('wardrobe_board'))){
						A1 = $.ajax({
							async: true,
							type: 'get',
							dataType: 'json',
							url: '<?php echo site_url('data/wardrobe_board/read');?>',
							success: function(msg){
								if(msg.error == 0){
									var Content = msg.data.content;
									Item = '';
									for(index in Content){
										Item += '<option value="'+Content[index]['name']+'">'+Content[index]['name']+'</option>';
									}
									$.sessionStorage('wardrobe_board', Content);
									$('#dismantleYTable datalist#yPlateName').append(Item);
						        }
							}
						});
					}else{
						Item = '';
						for(index in SessionData){
							Item += '<option value="'+SessionData[index]['name']+'">'+SessionData[index]['name']+'</option>';
						}
						$('#dismantleYTable datalist#yPlateName').append(Item);
					}
					if(!(SessionData = $.sessionStorage('wardrobe_edge'))){
						A2 = $.ajax({
							async: true,
							type: 'get',
							dataType: 'json',
							url: '<?php echo site_url('data/wardrobe_edge/read');?>',
							success: function(msg){
									if(msg.error == 0){
										var Content = msg.data.content;
										Item = '';
										for(index in Content){
											Item += '<option value="'+Content[index]['name']+'">'+Content[index]['name']+'</option>';
										}
										$.sessionStorage('wardrobe_edge', Content);
										$('#dismantleYTable select[name="edge"]').append(Item);
							        }
								}
						});
					}else{
						Item = '';
						for(index in SessionData){
							Item += '<option value="'+SessionData[index]['name']+'">'+SessionData[index]['name']+'</option>';
						}
						$('#dismantleYTable select[name="edge"]').append(Item);
					}
					if(!(SessionData = $.sessionStorage('wardrobe_slot'))){
						A3 = $.ajax({
							async: true,
							type: 'get',
							dataType: 'json',
							url: '<?php echo site_url('data/wardrobe_slot/read');?>',
							success: function(msg){
								if(msg.error == 0){
									var Content = msg.data.content;
									Item = '';
									for(index in Content){
										Item += '<option value="'+Content[index]['name']+'">'+Content[index]['name']+'</option>';
									}
									$.sessionStorage('wardrobe_slot', Content);
									$('#dismantleYTable select[name="slot"]').append(Item);
								}
							}
						});
					}else{
						Item = '';
						for(index in SessionData){
							Item += '<option value="'+SessionData[index]['name']+'">'+SessionData[index]['name']+'</option>';
						}
						$('#dismantleYTable select[name="slot"]').append(Item);
					}
					if(!(SessionData = $.sessionStorage('wardrobe_punch'))){
						A4 = $.ajax({
							async: true,
							type: 'get',
							dataType: 'json',
							url: '<?php echo site_url('data/wardrobe_punch/read');?>',
							success: function(msg){
								if(msg.error == 0){
									var Content = msg.data.content;
									Item = '';
									for(index in Content){
										Item += '<option value="'+Content[index]['name']+'">'+Content[index]['name']+'</option>';
									}
									$.sessionStorage('wardrobe_punch', Content);
									$('#dismantleYTable select[name="punch"]').append(Item);
								}
							}
						});
					}else{
						Item = '';
						for(index in SessionData){
							Item += '<option value="'+SessionData[index]['name']+'">'+SessionData[index]['name']+'</option>';
						}
						$('#dismantleYTable select[name="punch"]').append(Item);
					}
					if(!(SessionData = $.sessionStorage('abnormity'))){
						A5 = $.ajax({
							async: true,
							type: 'get',
							dataType: 'json',
							url: '<?php echo site_url('data/abnormity/read');?>',
							success: function(msg){
									if(msg.error == 0){
										var Content = msg.data.content;
										Item = '';
										for(index in Content){
											Item += '<option value="'+Content[index]['name']+'">'+Content[index]['name']+'</option>';
										}
										$.sessionStorage('abnormity', Content);
										$('#dismantleYTable datalist#yRemark').append(Item);
							        }
								}
						});
					}else{
						Item = '';
						for(index in SessionData){
							Item += '<option value="'+SessionData[index]['name']+'">'+SessionData[index]['name']+'</option>';
						}
						$('#dismantleYTable datalist#yRemark').append(Item);
					}
					
					$('#dismantleYForm input[name="board"]')
						.load_board_data({url:"<?php echo site_url('product/board/read')?>"});
					$('#dismantleYTable').table_form();
					$.when(A1,A2,A3,A4,A5).done(function(V1,V2,V3,V4,V5){
						if(0 != $('#dismantleYForm select[name="opid"]').val()){
							load_yigui_data($('#dismantleYForm select[name="opid"]').val());
						}
						$('#dismantleYForm input[name="board"]').on('focusout', function(e){
							var NameSuffix = $(this).val().replace(/(\d{1,2})(.*)/g, "$2"), ChooseLine = $('#dismantleYForm select[name="change_line"]').val(), Thick;
							$('#dismantleYForm').find('input[name="good"]').each(function(i, v){
								$(this).val(function(){
									Thick = parseInt(this.value);
									if(Thick){
										if('*' == ChooseLine){
											return Thick+NameSuffix;
										}else if(Thick == ChooseLine){
											return ChooseLine+NameSuffix;
										}else{
											return this.value;
										}
									}else{
										return 18+NameSuffix;
									}
								});
							});
						});
						$('#dismantleYTable tbody tr td input[name="width"],#dismantleYTable tbody tr td input[name="length"]').on('change', function(e){
							var Area, $Tr = $(this).parents('tr').eq(0), Width = $Tr.find('input[name="width"]').val(), Length = $Tr.find('input[name="length"]').val();
							Area = Math.ceil(Width*Length/M_ONE)/M_TWO;
							if (Area < MIN_AREA) {
								Area = MIN_AREA;
							}
							$Tr.find('input[name="area"]').val(Area);
							//$Tr.find('input[name="area"]').val(Math.ceil(Width*Length/1000)/1000);
						});
					});
					$('#dismantleYForm select[name="opid"]').on('change', function(e){
						if(0 != $(this).val()){
							var $Selected = $(this).find('option:selected'), Status = parseInt($Selected.data('status')),
    							Value = $Selected.val();
    						if(Status > 2){
    							if(confirm('该订单产品已经拆单完毕, 是否重新拆单?')){
    								$.redismantle({Url: '<?php echo site_url('order/dismantle/redismantle/order_product')?>', Data:{id:Value}});
    							}else{
    								return false;
    							}
    						}
    						$('#dismantleYForm input[name="product"]').val($Selected.data('product'));
    						$('#dismantleYForm input[name="remarks"]').val($Selected.data('remarks'));
    						load_yigui_data(Value);
						}else{
							clear_yigui_data();
						}
					});
				})(jQuery);
			</script>