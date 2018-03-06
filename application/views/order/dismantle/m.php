<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月16日
 * @author Administrator
 * @version
 * @des
 * 门板拆单
 */
?>
            <div class="col-md-12" id="dismantleM">
				<form class="form-horizontal" method="post" id="dismantleMForm">
					<div class="form-group col-md-12">
						<p class="form-control-static" id="dismantleMFormError"></p>
					</div>
					<input type="hidden" name="code" value="<?php echo $Code;?>" />
					<div class="form-group col-md-3">
						<label class="control-label  col-md-4">选择订单:</label>
						<div class="col-md-8">
							<select class="form-control" name="opid">
							    <option value="0">请选择具体订单</option>
                                <?php
							    if(isset($Dismantle) && is_array($Dismantle) && count($Dismantle) > 0){
							        foreach ($Dismantle as $key=>$value){
							            if($opid == $value['opid']){
							                 echo <<<END
<option value="$value[opid]" selected="selected" data-status="$value[status]" data-product="$value[product]">$value[order_product_num]-$value[product]</option>
END;
							            }else{
							                 echo <<<END
<option value="$value[opid]" data-status="$value[status]" data-product="$value[product]">$value[order_product_num]-$value[product]</option>
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
						<label class="control-label  col-md-4">板材:</label>
						<div class="col-md-8">
							<input class="form-control" name="bid" type="hidden" value=""/>
							<input class="form-control" name="board" type="text" value="" placeholder="选择板材颜色"/>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="control-label  col-md-4">封边:</label>
						<div class="col-md-8">
							<select class="form-control" name="edge">
							    <option value="">---请选择封边---</option>
	                            <option value="PVC白色封边">PVC白色封边</option>
	                            <option value="黑白刷银">黑白刷银</option>
	                            <option value="红白刷银">红白刷银</option>
	                            <option value="亮光圆边">亮光圆边</option>
                                <option value=刷银封边">刷银封边</option>
                                <option value="同色PVC">同色PVC</option>
                                <option value="土豪金双色亚克力">土豪金双色亚克力</option>
                                <option value="香槟碰角封边">香槟碰角封边</option>
                                <option value="哑光窄边">哑光窄边</option>
                                <option value="哑光窄边带封头">哑光窄边带封头</option>
							</select>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="control-label col-md-4">备注:</label>
						<div class="col-md-8">
						    <input class="form-control" name="remarks" type="text" value="<?php echo $remark?>"/>
						</div>
					</div>
					<div class="form-group col-md-6">
					    <input class="form-control" name="opdid" type="hidden" value=""/>
					</div>
					<div class="form-group col-md-8"></div>
					<table	class="table-center table-form table table-bordered table-striped table-condensed col-md-12" id="dismantleMTable">
						<thead>
							<tr>
								<th class="td-xs" >#</th>
								<th >板材</th>
								<th >宽</th>
								<th >高</th>
								<th class="td-xs" >块</th>
								<th class="td-sm" >面积</th>
								<th class="td-md" >铰链孔方向</th>
								<th class="td-md" >封边拉手</th>
								<th >开孔(个)</th>
								<th >隐形拉手(米)</th>
								<th >备注</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td><input class="form-control" name="good" type="text" /></td>
								<td><input class="form-control" name="width" type="text" /></td>
								<td><input class="form-control" name="length" type="text" /></td>
								<td><input class="form-control" name="num" type="text" value="1"/></td>
								<td><input class="form-control" name="area" type="text" value="" readonly="readonly"/></td>
								<td><select class="form-control" name="punch">
										<option value="">--请选择--</option>
                                        <option value="抽屉">抽屉</option>
                                        <option value="地柜对开">地柜对开</option>
                                        <option value="地柜右开">地柜右开</option>
                                        <option value="地柜左开">地柜左开</option>
                                        <option value="吊柜对开">吊柜对开</option>
                                        <option value="吊柜右开">吊柜右开</option>
                                        <option value="吊柜左开">吊柜左开</option>
                                        <option value="上翻门">上翻门</option>
                                        <option value="开孔">开孔</option>
									</select></td>
								<td><select class="form-control" name="handle">
										<option value="">--请选择--</option>
                                        <option value="L型拉手" data-invisibility=true>L型拉手</option>
                                        <option value="暗扣128孔距" data-open-hole=true>暗扣128孔距</option>
                                        <option value="暗扣160孔距" data-open-hole=true>暗扣160孔距</option>
                                        <option value="定尺拉手" >定尺拉手</option>
                                        <option value="加厚G型拉手" data-invisibility=true>加厚G型拉手</option>
                                        <option value="亮光G型拉手" data-invisibility=true>亮光G型拉手</option>
                                        <option value="香槟G型拉手" data-invisibility=true>香槟G型拉手</option>
                                        <option value="哑光G型拉手" data-invisibility=true>哑光G型拉手</option>
									</select></td>
								<td><input class="form-control" name="open_hole" type="text" /></td>
								<td><input class="form-control" name="invisibility" type="text" /></td>
								<td><input class="form-control" name="remark" type="text" value="" /></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
			<script>
				(function($){
					var clear_menban_data = function(){
						$('#dismantleMTable tbody tr:gt(0)').remove();
						$('#dismantleMTable tbody tr:eq(0) input[name="width"]').val('');
						};
					var load_menban_data = function(Opid){
						$.ajax({
							async: true,
							url: '<?php echo site_url('order/dismantle_m/read');?>',
							data: {id: Opid},
							dataType: 'json',
							type: 'GET',
							beforeSend: function(x){
								clear_menban_data();
							},
							success: function(msg){
								if(0 == msg.error){
									var Struct = msg.data.struct, BoardDoor = msg.data.board_door,
										$Last, $LastClone, $This, $DTable, j;
									if(false != Struct){
										$('#dismantleMForm input:hidden[name="opdid"]').val(Struct.opdid);
										$('#dismantleMForm input:hidden[name="bid"]').val(Struct.bid);
										$('#dismantleMForm input[name="board"]').val(Struct.board);
										$('#dismantleMForm select[name="edge"]').val(Struct.edge);
									}
									if(false != BoardDoor){
										for(var i in BoardDoor){
											$DTable = $('#dismantleMTable');
											$Last = $DTable.find('tbody tr:last');
											$LastClone = $Last.clone('true');
											$Last.find('input').val('');
											$Last.find('input,select').each(function(ii, vv){
												if(undefined != BoardDoor[i][this.name]){
													$.storedInputData(this, BoardDoor[i][this.name]);
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
					$('#dismantleMForm input[name="board"]').load_board_data({url:"<?php echo site_url('product/board/read')?>"});
					$('#dismantleMForm input[name="board"]').on('focusout', function(e){
    						$('#dismantleMForm').find('input[name="good"]').val($(this).val());
    					});
					$('#dismantleMForm').table_form();
					$('#dismantleMTable tbody tr td input[name="width"],#dismantleMTable tbody tr td input[name="length"]').on('change', function(e){
						var $Tr = $(this).parents('tr').eq(0), Width = $Tr.find('input[name="width"]').val(), Length = $Tr.find('input[name="length"]').val(), Area;
						Area = Math.ceil(Width*Length/M_ONE)/M_TWO;
						//Area = Math.ceil(Width*Length/1000)/1000;
						if(Area < MIN_M_AREA){
							Area = MIN_M_AREA
						}
						$Tr.find('input[name="area"]').val(Area);
					});
					$('#dismantleMTable select[name="handle"]').on('focusout', function(e){
						var $Tr = $(this).parents('tr').eq(0), OpenHole = $(this).children('option:selected').data('open-hole'), Invisibility = $(this).children('option:selected').data('invisibility');
						if(OpenHole){
							$Tr.find('input[name="open_hole"]').val(1);
						}else{
							$Tr.find('input[name="open_hole"]').val(0);
						}
						if(Invisibility){
							$Tr.find('input[name="invisibility"]').val(parseInt($Tr.find('input[name="width"]').val())/1000);
						}else{
							$Tr.find('input[name="invisibility"]').val(0);
						}
					});
					if(0 != $('#dismantleMForm select[name="opid"]').val()){
						load_menban_data($('#dismantleMForm select[name="opid"]').val());
					}
					$('#dismantleMForm select[name="opid"]').on('change', function(e){
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
    						$('#dismantleMForm input[name="product"]').val($Selected.data('product'));
    						$('#dismantleMForm input[name="remarks"]').val($Selected.data('remarks'));
    						load_menban_data(Value);
						}else{
							clear_menban_data();
						}
					});
				})(jQuery);
			</script>