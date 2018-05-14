<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月16日
 * @author Administrator
 * @version
 * @des
 * 木框门拆单
 */
?>
            <div class="col-md-12" id="dismantleK">
				<form class="form-horizontal" method="post" id="dismantleKForm">
					<div class="form-group col-md-12">
						<p class="form-control-static" id="dismantleKFormError"></p>
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
							<input class="form-control" name="board" type="text" value="" placeholder="选择板材颜色"/>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="control-label col-md-4">备注:</label>
						<div class="col-md-8">
						    <input class="form-control" name="remarks" type="text" value="<?php echo $remark?>"/>
						</div>
					</div>
					<div class="form-group col-md-9"></div>
					<table	class="table-center table-form table table-bordered table-striped table-condensed col-md-12" id="dismantleKTable">
						<thead>
							<tr>
								<th class="td-xs" >#</th>
								<th >木框门名称</th>
								<th >板材</th>
								<th >门芯</th>
								<th >宽</th>
								<th >高</th>
								<th class="td-xs" >块</th>
								<th class="td-sm" >面积</th>
								<th class="td-sm" >中横</th>
								<th class="td-md" >铰链孔方向</th>
								<th >备注</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td><input class="form-control" name="wood_name" type="text" placeholder="百叶/玻璃/平板"/></td>
								<td><input class="form-control" name="good" type="text" /></td>
								<td><input class="form-control" name="core" type="text" /></td>
								<td><input class="form-control" name="width" type="text" /></td>
								<td><input class="form-control" name="length" type="text" /></td>
								<td><input class="form-control" name="num" type="text" value="1"/></td>
								<td><input class="form-control" name="area" type="text" value="" readonly="readonly"/></td>
								<td><select class="form-control" name="center">
										<option value="">--无--</option>
										<option value="五五">五五</option>
										<option value="四六">四六</option>
									</select></td>
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
									</select></td>
								<td><input class="form-control" name="remark" type="text" value="" /></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
			<script>
				(function($){
					var clear_kuang_data = function(){
						$('#dismantleKTable tbody tr:gt(0)').remove();
						$('#dismantleKTable tbody tr:eq(0) input[name="wood_name"]').val('');
					};
					var load_kuang_data = function(Opid){
						$.ajax({
							async: true,
							url: '<?php echo site_url('order/dismantle_k/read');?>',
							data: {id: Opid},
							dataType: 'json',
							type: 'GET',
							beforeSend: function(x){
								clear_kuang_data();
							},
							success: function(msg){
								if(0 == msg.error){
									var BoardWood = msg.data.board_wood,
										$Last, $LastClone, $This, $DTable, j;
									if(false != BoardWood){
										for(var i in BoardWood){
											$DTable = $('#dismantleKTable');
											$Last = $DTable.find('tbody tr:last');
											$LastClone = $Last.clone('true');
											$Last.find('input').val('');
											$Last.find('input,select').each(function(ii, vv){
												if(undefined != BoardWood[i][this.name]){
													$.storedInputData(this, BoardWood[i][this.name]);
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
					$('#dismantleKForm input[name="board"]').load_board_data({url:"<?php echo site_url('product/board/read')?>"})
    				$('#dismantleKForm input[name="board"]').on('focusout', function(e){
    						$('#dismantleKTable').find('input[name="good"]').val($(this).val());
    					});
					$('#dismantleKTable').table_form();
					$('#dismantleKTable tbody tr td input[name="width"],#dismantleKTable tbody tr td input[name="length"]').on('change', function(e){
						var $Tr = $(this).parents('tr').eq(0), Width = $Tr.find('input[name="width"]').val(), Length = $Tr.find('input[name="length"]').val();
						var Area = Math.ceil(Width*Length/M_ONE)/M_TWO;
						//var Area = Math.ceil(Width*Length/1000)/1000;
						Area = Area > MIN_K_AREA ? Area: MIN_K_AREA;
						$Tr.find('input[name="area"]').val(Area);
					});
					if(0 != $('#dismantleKForm select[name="opid"]').val()){
						load_kuang_data($('#dismantleKForm select[name="opid"]').val());
					}
					$('#dismantleKForm select[name="opid"]').on('change', function(e){
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
    						$('#dismantleKForm input[name="product"]').val($Selected.data('product'));
    						$('#dismantleKForm input[name="remarks"]').val($Selected.data('remarks'));
    						load_kuang_data(Value);
						}else{
							clear_kuang_data();
						}
					});
				})(jQuery);
			</script>