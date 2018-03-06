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
				</select>
			</div>
		</div>
		<div class="form-group col-md-3">
			<label class="control-label  col-md-4">正面:</label>
			<div class="col-md-8">
				<select class="form-control" name="facefb">
					<option value="单孔防撞条">单孔防撞条</option>
					<option value="0.8同色封边">0.8同色封边</option>
					<option value="正面刷银封边">正面刷银封边</option>
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
		<table	class="table-center table-form table table-bordered table-striped table-condensed col-md-12" id="dismantleWTable">
			<thead>
			<tr>
				<th class="td-xs" rowspan="2">#</th>
				<th class="td-md" rowspan="2">柜体名称</th>
				<th colspan="3">外形尺寸</th>
				<th class="td-xs" rowspan="2">数量</th>
				<th colspan="2">隔板</th>
				<th class="td-xs" rowspan="2">背板</th>
				<th colspan="2">转角开门</th>
				<th colspan="2">左切角</th>
				<th colspan="2">右切角</th>
				<th colspan="2">避炉</th>
				<th colspan="2">水槽地柜</th>
				<!-- <th colspan="3">水槽地柜</th> -->
				<th class="td-xs" rowspan="2">气瓶柜</th>
				<th class="td-xs" rowspan="2">微波炉</th>
				<th class="td-xs" rowspan="2">分解</th>
			</tr>
			<tr>
				<th class="td-xs">宽</th>
				<th class="td-xs">深</th>
				<th class="td-xs">高</th>
				<th class="td-xs">活</th>
				<th class="td-xs">固</th>
				<th class="td-xs">尺寸</th>
				<th class="td-xs">活隔减</th>
				<th class="td-xs">宽</th>
				<th class="td-xs">深</th>
				<th class="td-xs">宽</th>
				<th class="td-xs">深</th>
				<th class="td-xs">左</th>
				<th class="td-xs">右</th>
				<th class="td-xs">铝箔</th>
				<th class="td-xs">多层板</th>
				<!-- <th class="td-xs">白板</th>
                <th class="td-xs">灰板</th> -->
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><input type="hidden" value="1" name="no"/>1</td>
				<td>
					<select class="form-control input-sm" name="name">
						<option value="0" selected>--请选择柜体--</option>
						<option value="吊柜">吊柜</option>
						<option value="地柜">地柜</option>
						<option value="二节滑道屉箱" >二节滑道(屉箱)</option>
						<option value="隐藏滑轨屉箱" >隐藏滑轨(屉箱)</option>
					</select>
				</td>
				<td><input class="form-control input-sm" name="width" type="text" /></td>
				<td><input class="form-control input-sm" name="depth" type="text" /></td>
				<td><input class="form-control input-sm" name="height" type="text" /></td>
				<td><input class="form-control input-sm" name="num" type="text" value="1"/></td>
				<td><input class="form-control input-sm" name="gbh" type="checkbox" value="h" /></td>
				<td><input class="form-control input-sm" name="gbg" type="checkbox" value="g" /></td>
				<td><input class="form-control input-sm" name="bb" type="checkbox" value="1" checked="checked"/></td>
				<td><input class="form-control input-sm" name="size" type="text" /></td>
				<td>
					<select class="form-control input-sm" name="gbj">
						<option value="50">50</option>
						<option value="110">110</option>
					</select></td>
				<td><input class="form-control input-sm" name="zqjwidth" type="text" /></td>
				<td><input class="form-control input-sm" name="zqjdepth" type="text" /></td>
				<td><input class="form-control input-sm" name="yqjwidth" type="text" /></td>
				<td><input class="form-control input-sm" name="yqjdepth" type="text" /></td>
				<td><input class="form-control input-sm" name="zbl" type="checkbox" value="z" /></td>
				<td><input class="form-control input-sm" name="ybl" type="checkbox" value="y" /></td>
				<td><input class="form-control input-sm" name="diguiabnormity" type="checkbox" value="1" /></td>
				<td><input class="form-control input-sm" name="diguiabnormity" type="checkbox" value="2" /></td>
				<!-- <td><input class="form-control input-sm" name="diguiabnormity" type="checkbox" value="3" /></td> -->
				<td><input class="form-control input-sm" name="diguiabnormity" type="checkbox" value="4" /></td>
				<td><input class="form-control input-sm" name="wbl" type="checkbox" value="1" /></td>
				<td class="dec"><a href="#board" >...</a><a name="box"></a></td>
			</tr>
			</tbody>
		</table>
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
				<td class="hide"><input class="form-control input-sm" name="qrcode" type="text" value=""/></td>
				<td class="hide"><input class="form-control input-sm" name="punch" type="text" value=""/></td>
				<td class="hide"><input class="form-control input-sm" name="decide_size" type="text" /></td>
				<td class="hide"><input class="form-control input-sm" name="bd_file" type="text" /></td>
				<td><input class="form-control input-sm" name="good" type="text" value="" /></td>
				<td><input class="form-control input-sm" name="plate_name" type="text" value="" /></td>
				<td><input class="form-control input-sm" name="width" type="text" value="" /></td>
				<td><input class="form-control input-sm" name="length" type="text" value="" /></td>
				<td><input class="form-control input-sm" name="num" type="text" value="" /></td>
				<td><input class="form-control input-sm" name="area" type="text" value="" readonly="readonly"/></td>
				<td><input class="form-control input-sm" name="slot" type="text" value="" /></td>
				<td><input class="form-control input-sm" name="edge" type="text" value="" /></td>
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
		/**
		 * 防水板与气瓶柜和贴铝箔是互斥的
		 */
		$('#dismantleWTable input[name="diguiabnormity"]').each(function(i, v){
			$(this).on('click', function(e){
				if($(this).prop('checked')){
					var Value = $(this).val();
					$(this).parents('tr').eq(0).find('input:checkbox[name="diguiabnormity"]:not([value="'+Value+'"])').prop('checked', false);
				}
			});
		});
		$('#cabinetBoardEditTable tbody tr td input[name="width"],#cabinetBoardEditTable tbody tr td input[name="length"]').on('change', function(e){
			var Area, $Tr = $(this).parents('tr').eq(0), Width = $Tr.find('input[name="width"]').val(), Length = $Tr.find('input[name="length"]').val();
			Area = Math.ceil(Width*Length/M_ONE)/M_TWO;
			if (Area < MIN_AREA) {
				Area = MIN_AREA;
			}
			$Tr.find('input[name="area"]').val(Area);
			//$Tr.find('input[name="area"]').val(Math.ceil(Width*Length/1000)/1000);
		});
		$('#dismantleWTable').table_form();
		$('#cabinetBoardEditTable').table_form({NewLine: false});

		$('#dismantleWTable tbody tr td input, #dismantleWTable tbody tr td select').each(function(i, v){
			$(this).blur(function(e){
				dec_show($(this).parents('tr').eq(0));
			});
		});

		function dec_show($Tr){
			var $Cubicle = $Tr.find('select[name="name"]'),CubicleName = $Cubicle.val(),
				CubicleNum = $Tr.children('td:first').text(), Base = {}, Platesarr = new Array(),
				$New = undefined, $NewTbodyTr = undefined, $Line = undefined;
			$Tr.find('td.dec').html('<a href="#board'+CubicleNum+'">...</a><a name="box'+CubicleNum+'"></a>');
			if($Cubicle.val() != 0){
				$Tr.find('input:text, input:checkbox:checked, select').each(function(i, v){
					Base[this.name] = $(this).val();
				});
				Base['thick'] = parseInt($('#dismantleWForm input[name="board"]').val());
				Base['good'] = $('#dismantleWForm input[name="board"]').val();
				Base['edge'] = $('#dismantleWForm select[name="facefb"]').val();
				Base['dgjg'] = $('#dismantleWForm input[name="dgjg"]:checked').val();
				Base['dgqc'] = $('#dismantleWForm input[name="dgqc"]:checked').val();
				Base['dghc'] = $('#dismantleWForm input[name="dghc"]:checked').val();
				switch(CubicleName){
					case '吊柜':
						Platesarr = diaogui(Base);
						break;
					case '地柜':
						Platesarr = digui(Base);
						break;
					case '二节滑道屉箱':
						Platesarr = ejhd(Base);
						break;
					case '隐藏滑轨屉箱':
						Platesarr = ychg(Base);
						break;
				}
				if($('#cabinetBoardEditTable'+CubicleNum).length > 0){
					$New = $('#cabinetBoardEditTable'+CubicleNum).eq(0);
					$New.find('tbody tr:gt(0)').remove();
				}else{
					$New = $('#cabinetBoardEditTable').clone(true);
					$New.attr('id', 'cabinetBoardEditTable'+CubicleNum);
					$('#cabinetBoardEditTable').before($New);
				}
				$New.find('thead tr td:first').html('<a href="#box'+CubicleNum+'">'+CubicleNum+'号</a><a name="board'+CubicleNum+'">'+CubicleName+'</a>');
				$NewTbodyTr = $New.find('tbody tr:first').clone(true);

				var j = 1, Area;
				for(var i in Platesarr){
					$Line = $New.find('tbody tr:last');
					$Line.find('input').val('');
					$Line.children('td:first').text(j++);
					for(var key in Platesarr[i]){
						$Line.find('input[name="'+key+'"]').val(Platesarr[i][key]);
					}
					$Line.find('input[name="cubicle_name"]').val(CubicleName);
					$Line.find('input[name="cubicle_num"]').val(CubicleNum);
					$Line.find('input[name="plate_name"]').val(Platesname[i]);
					Area = Math.ceil(Platesarr[i]['width']*Platesarr[i]['length']*Platesarr[i]['num']/M_ONE)/M_TWO;
					if (Area < MIN_AREA) {
						Area = MIN_AREA;
					}
					$Line.find('input[name="area"]').val(Area);
					//$Line.find('input[name="area"]').val(Math.ceil(Platesarr[i]['width']*Platesarr[i]['length']*Platesarr[i]['num']/1000)/1000);

					$New.find('tbody').append($NewTbodyTr);
					$NewTbodyTr = $New.find('tbody tr:last').clone(true);
				}
				$New.find('tbody tr:last').remove();
			}else{
				if($('#cabinetBoardEditTable'+CubicleNum).length > 0){
					$('#cabinetBoardEditTable'+CubicleNum).remove();
				}
			}
		};
		/**
		 * 获取板材信息
		 */
		$('#dismantleWForm input[name="board"]')
			.load_board_data({url:"<?php echo site_url('product/board/read')?>"});
		var clear_cabinet_data = function(){
			$('#dismantleWTable tbody tr:gt(0)').remove();
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
						for(var i in Cabinet){
							$Last = $('#dismantleWTable tbody tr:last');
							$LastClone = $Last.clone(true);
							$Last.find('input:text').val('');
							$Last.find('select')[0].reset;
							$Last.find('radio,checkbox').prop('checked', false);
							$Last.find('input,select').each(function(ii, vv){
								if(undefined != Cabinet[i][this.name]){
									$.storedInputData(this, Cabinet[i][this.name]);
								}
							});
							$Last.find('td.dec').html('<a href="#board'+Cabinet[i]['no']+'">...</a><a name="box'+Cabinet[i]['no']+'"></a>');
							$Last.children('td:first').html(function(n){
								j = parseInt($(this).text());
								return '<input type="hidden" name="no" value="'+j+'" />'+j;
							});
							$LastClone.children('td:first').html(function(n){
								j = parseInt($(this).text())+1;
								return '<input type="hidden" name="no" value="'+j+'" />'+j;
							});
							$('#dismantleWTable tbody').append($LastClone);
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