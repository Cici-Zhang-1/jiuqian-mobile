<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月16日
 * @author Zhangcc
 * @version
 * @des
 * 橱柜拆单
 */
?>
            <div class="col-md-12" id="dismantleW">
				<form class="form-horizontal" method="post" id="dismantleWForm">
					<div class="form-group col-md-12">
						<p class="form-control-static" id="dismantleWFormError"></p>
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
<option value="$value[opid]" selected="selected" data-product="$value[product]" data-status="$value[status]">$value[order_product_num]-$value[product]</option>
END;
							            }else{
							                 echo <<<END
<option value="$value[opid]" data-product="$value[product]" data-status="$value[status]">$value[order_product_num]-$value[product]</option>
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
							<input class="form-control" name="set" type="text" value="1" placeholder="共有几套同等规格的产品"/>
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
						<label class="control-label  col-md-4">正面:</label>
						<div class="col-md-8">
							<select class="form-control" name="facefb">
	                            <option value="0.8同色封边">0.8同色封边</option>
	                            <option value="正面刷银封边">正面刷银封边</option>
								<option value="单孔防撞条">单孔防撞条</option>
							</select>
						</div>
					</div>
					<div class="form-group col-md-2">
						<label class="control-label">结构:</label>
						<label class="radio-inline">
                        	<input type="radio" name="struct" value="三合一" checked="checked">三合一
                        </label>
                        <label class="radio-inline">
                        	<input type="radio" name="struct" value="螺钉">螺钉
                        </label>
					</div>
					<div class="form-group col-md-2">
						<label class="control-label">地柜:</label>
						<label class="radio-inline">
                        	<input type="radio" name="dgjg" value="旁夹底" checked="checked">旁夹底
                        </label>
                        <label class="radio-inline">
                        	<input type="radio" name="dgjg" value="底托旁">底托旁
                        </label>
					</div>
					<div class="form-group col-md-2">
						<label class="control-label">前撑:</label>
						<label class="radio-inline">
                        	<input type="radio" name="dgqc" value="竖装" checked="checked">竖装
                        </label>
                        <label class="radio-inline">
                        	<input type="radio" name="dgqc" value="平装">平装
                        </label>
                        <label class="radio-inline">
                        	<input type="radio" name="dgqc" value="无">无
                        </label>
					</div>
					<div class="form-group col-md-2">
						<label class="control-label">后撑:</label>
						<label class="radio-inline">
                        	<input type="radio" name="dghc" value="竖装" checked="checked">竖装
                        </label>
                        <label class="radio-inline">
                        	<input type="radio" name="dghc" value="平装">平装
                        </label>
					</div>
					<div class="form-group col-md-4">
					    <input type="hidden" name="opcsid" value="">
					</div>
					<div id="dismantleWTable"></div>

					<table class="table-center table-form table table-bordered table-striped table-condensed col-md-12" id="cabinetBoardEditTable">
						<thead>
							<tr>
								<td class="td-sm">其它</td>
								<td class="hide">柜体编号</td>
								<td class="hide">柜体名称</td>
								<td class="hide">Qrcode</td>
								<td class="hide">打孔分类</td>
								<td class="hide">尺寸判定</td>
								<td class="hide">Bd文件</td>
								<td>板材</td>
								<td>板块名称</td>
								<td>宽</td>
								<td>长</td>
								<td>块</td>
								<td>面积</td>
								<td>开槽方向</td>
								<td>防撞条</td>
								<td>其它工艺</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td class="hide"><input name="cubicle_num" type="hidden" value="" /></td>
								<td class="hide"><input name="cubicle_name" type="hidden" value="" /></td>
								<td class="hide"><input class="form-control input-sm" name="qrcode" type="text" value="" data-unique="true"/></td>
								<td class="hide"><input class="form-control input-sm" name="punch" type="text" value=""/></td>
								<td class="hide"><input class="form-control input-sm" name="decide_size" type="text" /></td>
								<td class="hide"><input class="form-control input-sm" name="bd_file" type="text"  data-unique="true"/></td>
								<td><input class="form-control input-sm" name="good" type="text" value="" /></td>
								<td><input class="form-control input-sm" name="plate_name" type="text" value="" /></td>
								<td><input class="form-control input-sm" name="width" type="text" value="" /></td>
								<td><input class="form-control input-sm" name="length" type="text" value="" /></td>
								<td><input class="form-control input-sm" name="num" type="text" value="" /></td>
								<td><input class="form-control input-sm" name="area" type="text" value="" readonly="readonly"/></td>
								<td><input class="form-control input-sm" name="slot" type="text" value="" /></td>
								<td><select class="form-control input-sm" name="edge">
										<option value="">--请选择--</option>
									</select></td>
								<td><input class="form-control input-sm" name="remark" type="text" value="" /></td>
							</tr>
						</tbody>
					</table>
				</form>
				<table>
				</table>
			</div>
			<script type="text/javascript" src="<?php echo base_url('js/cabdec.js?v=0.2');?>"></script>
			<script>
				(function($){
					var SessionData;
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
									$('#cabinetBoardEditTable select[name="edge"]').append(Item);
								}
							}
						});
					}else{
						Item = '';
						for(index in SessionData){
							Item += '<option value="'+SessionData[index]['name']+'">'+SessionData[index]['name']+'</option>';
						}
						$('#cabinetBoardEditTable select[name="edge"]').append(Item);
					}

					$('#cabinetBoardEditTable tbody tr td input[name="width"],#cabinetBoardEditTable tbody tr td input[name="length"]').on('change', function(e){
						var Area, $Tr = $(this).parents('tr').eq(0), Width = $Tr.find('input[name="width"]').val(), Length = $Tr.find('input[name="length"]').val();
						Area = Math.ceil(Width*Length/M_ONE)/M_TWO;
						if (Area < MIN_AREA) {
							Area = MIN_AREA;
						}
						$Tr.find('input[name="area"]').val(Area);
						//$Tr.find('input[name="area"]').val(Math.ceil(Width*Length/1000)/1000);
					});
					$('#cabinetBoardEditTable').table_form({NewLine: false});
					

					/**
					* 获取板材信息
					*/
					$('#dismantleWForm input[name="board"]')
						.load_board_data({url:"<?php echo site_url('product/board/read')?>"});
					var clear_cabinet_data = function(){
						$('#cabinetBoardEditTable tbody tr:gt(0)').remove();
						$('#cabinetBoardEditTable').prevUntil('#dismantleWTable').remove();
					};
				    var load_cabinet_data = function(Opid){
						$.ajax({
							async: true,
							url: '<?php echo site_url('order/dismantle_w/read');?>',
							data: {id: Opid},
							dataType: 'json',
							type: 'GET',
							beforeSend: function(x){
								clear_cabinet_data();
							},
							success: function(msg){
								if(0 == msg.error){
									var Struct = msg.data.struct, Cabinet = msg.data.cabinet, BoardPlate = msg.data.board_plate,
										$Last, $LastClone, $This, $Dismantle = new Array, $DTable, j;
									if(false != Struct){
										$('#dismantleWForm input:hidden[name="opcsid"]').val(Struct.opcsid);
										$('#dismantleWForm input:hidden[name="bid"]').val(Struct.bid);
										$('#dismantleWForm input[name="board"]').val(Struct.board);
										$('#dismantleWForm select[name="facefb"]').val(Struct.facefb);
										$('#dismantleWForm input[name="struct"][value="'+Struct.struct+'"]').prop('checked', true);
										$('#dismantleWForm input[name="dgjg"][value="'+Struct.dgjg+'"]').prop('checked', true);
										$('#dismantleWForm input[name="dgqc"][value="'+Struct.dgqc+'"]').prop('checked', true);
										$('#dismantleWForm input[name="dghc"][value="'+Struct.dghc+'"]').prop('checked', true);
									}

									for(var i in BoardPlate){
										if(undefined == $Dismantle[BoardPlate[i]['cubicle_num']]){
											if(0 == BoardPlate[i]['cubicle_num']){
												$Dismantle[BoardPlate[i]['cubicle_num']] = $('#cabinetBoardEditTable');
											}else{
												$Dismantle[BoardPlate[i]['cubicle_num']] = $('#cabinetBoardEditTable').clone(true);
												$Dismantle[BoardPlate[i]['cubicle_num']].find('tbody tr:gt(0)').remove();
												$Dismantle[BoardPlate[i]['cubicle_num']].attr('id', 'cabinetBoardEditTable'+BoardPlate[i]['cubicle_num']);
												$Dismantle[BoardPlate[i]['cubicle_num']].find('thead tr td:first').html('<a href="#box'+BoardPlate[i]['cubicle_num']+'">'+BoardPlate[i]['cubicle_num']+'号</a><a name="board'+BoardPlate[i]['cubicle_name']+'">'+BoardPlate[i]['cubicle_name']+'</a>');
												$('#cabinetBoardEditTable').before($Dismantle[BoardPlate[i]['cubicle_num']]);
											}
										}
										$DTable = $Dismantle[BoardPlate[i]['cubicle_num']];
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
										$LastClone.find('input[name="cubicle_name"]').val(BoardPlate[i]['cubicle_name']);
										$LastClone.find('input[name="cubicle_num"]').val(BoardPlate[i]['cubicle_num']);
										$DTable.append($LastClone);
									}
								}else{
									alert(msg.message);
								}
							},
							error: function(x, t, e){x.responseText}
						});
					};
					
					if(0 != $('#dismantleWForm select[name="opid"]').val()){
						load_cabinet_data($('#dismantleWForm select[name="opid"]').val());
					}

					$('#dismantleWForm input[name="board"]').on('focusout', function(e){
						var NameSuffix = $(this).val().replace(/(\d{1,2})(.*)/g, "$2"), ChooseLine = $('#dismantleWForm select[name="change_line"]').val(), Thick;
						$('#dismantleWForm').find('input[name="good"]').each(function(i, v){
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

					$('#dismantleWForm select[name="opid"]').on('change', function(e){
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
							$('#dismantleWForm input[name="product"]').val($Selected.data('product'));
							load_cabinet_data(Value);
						}else{
							clear_cabinet_data();
						}
					});
				})(jQuery);
			</script>