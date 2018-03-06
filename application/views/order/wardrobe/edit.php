<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-5-6
 * @author ZhangCC
 * @version
 * @description  
 */
?>
			<div class="col-md-offset-1 col-md-10" id="wardrobe">
				<br />
				<form class="form-horizontal" id="wardrobeForm" action="<?php echo site_url('order/wardrobe/edit');?>" method="post" role="form">
						<input type="hidden" name="oid" value="<?php echo $Oid;?>"/>
						<input type="hidden" name="pid" value="<?php echo $Pid;?>"/>
						<div class="form-group">
							<div class="col-md-2">
								<button class="btn btn-primary" type="submit" value="保存" data-save="ajax"><i class="fa fa-save"></i>&nbsp;&nbsp;保存</button>
							</div>
							<div class="col-md-2">
								<input class="form-control" name="odcount" placeholder="该产品数目" value="<?php if(isset($Dismantle['order_detail'])) echo $Dismantle['order_detail']['count']; else echo 0; ?>" data-max="num"/>
							</div>
							<div class="col-md-2">
								<input class="form-control" name="odamount" placeholder="该产品总额" value="<?php if(isset($Dismantle['order_detail'])) echo $Dismantle['order_detail']['amount']; else echo 0; ?>" data-compute="amount"/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<table class="table table-striped table-bordered table-condensed" id="wardrobeBoard" data-remote="<?php echo site_url('data/goods/read_json/board');?>">
									<thead>
										<tr>
											<td class="td-xs">柜体</td>
											<td class="td-sm">编号</td>
											<td class="td-md">板材</td>
											<td>板块名称</td>
											<td class="td-sm">宽度</td>
											<td class="td-sm">长度</td>
											<td class="td-xs">厚度</td>
											<td>面积</td>
											<td class="td-sm">数量</td>
											<td>单价</td>
											<td>金额</td>
											<td>备注</td>
										</tr>
									</thead>
									<tbody>
										<?php 
										$Num = 0;
										$Area = 0;
										$Count = 0;
										$Amount = 0;
										if(isset($Dismantle['board']) && is_array($Dismantle['board']) && count($Dismantle['board']) > 0){
											foreach ($Dismantle['board'] as $key=>$value){
										?>
												<tr data-change=true>
													<td><a class="plus" href="javascript:void(0);" title="加一行"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;<a class="minus" href="javascript:void(0);" title="减一行"><i class="fa fa-minus"></i></a></td>
													<td><input class="form-control" name="num" type="number" min="0" placeholder="产品内部编号" value="<?php $Num = max(array($Num, $value['num'])); echo $value['num'];?>"/></td>
													<td>
														<input type="hidden" name="board_id" value="<?php echo $value['board_id'];?>"/>
														<input class="model form-control" name="board_des" data-label="board_id,thick_real,unit_price"  />
														<span><?php echo $value['board_des']?></span>
													</td>
													<td><input class="form-control" name="board_name" type="text" placeholder="板块名称" value="<?php echo $value['board_name'];?>"/></td>
													<td><input class="form-control" name="width_real" type="text" placeholder="成型宽度" value="<?php echo $value['width_real']?>"/></td>
													<td><input class="form-control" name="length_real" type="text" placeholder="成型长度" value="<?php echo $value['length_real']?>"/></td>
													<td><input class="form-control" name="thick_real" type="text" placeholder="成型厚度" value="<?php echo $value['thick_real']?>"/></td>
													<td><input class="form-control" name="area_real" type="text" placeholder="成型面积" value="<?php $Area += $value['area_real']; echo $value['area_real']?>"/></td>
													<td><input class="form-control" name="count" type="number" min="0" placeholder="同种板块数量" value="<?php $Count += $value['count']; echo $value['count']?>"/></td>
													<td><input class="form-control" name="unit_price" type="text" placeholder="出售单价" value="<?php echo $value['unit_price']?>"/></td>
													<td><input class="form-control" name="amount" type="text" placeholder="金额" value="<?php $Amount += $value['amount']; echo $value['amount']?>" /></td>
													<td><input class="form-control" name="remark" type="text" placeholder="备注" value="<?php echo $value['remark']?>"/></td>
												</tr>
										<?php 
											}
										}?>
										<tr data-change=false>
											<td><a class="plus" href="javascript:void(0);" title="加一行"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;<a class="minus" href="javascript:void(0);" title="减一行"><i class="fa fa-minus"></i></a></td>
											<td><input class="form-control" name="num" type="number" min="0" placeholder="产品内部编号" value=""/></td>
											<td>
												<input type="hidden" name="board_id" value=""/>
												<input class="form-control" name="board_des" data-label="board_id,thick_real,unit_price"  />
											</td>
											<td><input class="form-control" name="board_name" type="text" placeholder="板块名称"/></td>
											<td><input class="form-control" name="width_real" type="text" placeholder="成型宽度" value="0"/></td>
											<td><input class="form-control" name="length_real" type="text" placeholder="成型长度" value="0"/></td>
											<td><input class="form-control" name="thick_real" type="text" placeholder="成型厚度"/></td>
											<td><input class="form-control" name="area_real" type="text" placeholder="成型面积" value="0"/></td>
											<td><input class="form-control" name="count" type="number" min="0" placeholder="同种板块数量" value="0"/></td>
											<td><input class="form-control" name="unit_price" type="text" placeholder="出售单价" value="0.00"/></td>
											<td><input class="form-control" name="amount" type="text" placeholder="金额" value="0.00"/></td>
											<td><input class="form-control" name="remark" type="text" placeholder="备注"/></td>
										</tr>
									</tbody>
<!-- 									<tfoot> -->
<!-- 										<tr> -->
<!-- 											<td colSpan="1">合计</td> -->
<!-- 											<td></td> -->
<!-- 											<td></td> -->
<!-- 											<td></td> -->
<!-- 											<td></td> -->
<!-- 											<td></td> -->
<!-- 											<td></td> -->
<!-- 											<td><input class="form-control" name="area_tmp" type="text" placeholder="总面积" readonly="readonly" value="<?php echo $Area;?>"/></td> -->
<!-- 											<td><input class="form-control" name="count_tmp" type="text" placeholder="总板块数量" readonly="readonly" value="<?php echo $Count;?>"/></td> -->
<!-- 											<td></td> -->
<!-- 											<td><input class="form-control" name="amount_tmp" type="text" min="0" placeholder="总额" readonly="readonly" value="<?php echo $Amount;?>"/></td> -->
<!-- 											<td></td> -->
<!-- 										</tr> -->
<!-- 									</tfoot> -->
								</table>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<table class="table table-striped table-bordered table-condensed" id="wardrobeHardware" data-remote="<?php echo site_url('data/goods/read_json/hardware');?>">
									<thead>
										<tr>
											<td class="td-xs">五金</td>
											<td class="td-sm">编号</td>
											<td class="td-md">五金名称</td>
											<td class="td-sm">数量</td>
											<td class="td-sm">单位</td>
											<td class="td-sm">单价</td>
											<td>金额</td>
											<td>备注</td>
										</tr>
									</thead>
									<tbody>
										<?php 
										$Count = 0;
										$Amount = 0;
										if(isset($Dismantle['hardware']) && is_array($Dismantle['hardware']) && count($Dismantle['hardware']) > 0){
											foreach ($Dismantle['hardware'] as $key=>$value){
										?>
												<tr data-change=true>
													<td><a class="plus" href="javascript:void(0);" title="加一行"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;<a class="minus" href="javascript:void(0);" title="减一行"><i class="fa fa-minus"></i></a></td>
													<td><input class="form-control" name="num" type="number" min="0" placeholder="产品内部编号" value="<?php $Num = max(array($Num, $value['num'])); echo $value['num'];?>"/></td>
													<td>
														<input type="hidden" name="hardware_id" value="<?php echo $value['hardware_id'];?>"/>
														<input class="model form-control" name="hardware_des" data-label="hardware_id,unit,unit_price" />
														<span><?php echo $value['hardware_des'];?></span>
													</td>
													<td><input class="form-control" name="count" type="number" min="0" placeholder="同种板块数量" value="<?php $Count += $value['count']; echo $value['count']?>"/></td>
													<td><input class="form-control" name="unit_price" type="text" placeholder="出售单价" value="<?php echo $value['unit_price']?>"/></td>
													<td><input class="form-control" name="unit" type="text" placeholder="单位" value="<?php echo $value['unit']?>"/></td>
													<td><input class="form-control" name="amount" type="text" placeholder="金额" value="<?php $Amount += $value['amount']; echo $value['amount']?>"/></td>
													<td><input class="form-control" name="remark" type="text" placeholder="备注" value="<?php echo $value['remark']?>"/></td>
												</tr>
										<?php 
											}
										}?>
										<tr data-change=false>
											<td><a class="plus" href="javascript:void(0);" title="加一行"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;<a class="minus" href="javascript:void(0);" title="减一行"><i class="fa fa-minus"></i></a></td>
											<td><input class="form-control" name="num" type="number" min="0" placeholder="内部编号" value=""/></td>
											<td>
												<input type="hidden" name="hardware_id" value=""/>
												<input class="form-control" name="hardware_des" data-label="hardware_id,unit,unit_price" />
											</td>
											<td><input class="form-control" name="count" type="number" min="0" placeholder="数量" value="0"/></td>
											<td><input class="form-control" name="unit" type="text" placeholder="单位" value=""/></td>
											<td><input class="form-control" name="unit_price" type="text" placeholder="出售单价" value="0.00"/></td>
											<td><input class="form-control" name="amount" type="text" placeholder="金额" value="0.00"/></td>
											<td><input class="form-control" name="remark" type="text" placeholder="备注"/></td>
										</tr>
									</tbody>
<!-- 									<tfoot> -->
<!-- 										<tr> -->
<!-- 											<td>合计</td> -->
<!-- 											<td></td> -->
<!-- 											<td></td> -->
<!-- 											<td><input class="form-control" name="count_tmp" type="text" placeholder="总板块数量" readonly="readonly" value="<?php echo $Count;?>"/></td> -->
<!-- 											<td></td> -->
<!-- 											<td></td> -->
<!-- 											<td><input class="form-control" name="amount_tmp" type="text" min="0" placeholder="总额" readonly="readonly" value="<?php echo $Amount;?>"/></td> -->
<!-- 											<td></td> -->
<!-- 										</tr> -->
<!-- 									</tfoot> -->
								</table>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<table class="table table-striped table-bordered table-condensed" id="wardrobeCraft" data-remote="<?php echo site_url('data/craft/read_json');?>">
									<thead>
										<tr>
											<td class="td-xs">工艺</td>
											<td class="td-sm">编号</td>
											<td class="td-md">工艺名称</td>
											<td class="td-sm">数量</td>
											<td class="td-sm">单位</td>
											<td class="td-sm">单价</td>
											<td>金额</td>
											<td>备注</td>
										</tr>
									</thead>
									<tbody>
										<?php 
										$Count = 0;
										$Amount = 0;
										if(isset($Dismantle['craft']) && is_array($Dismantle['craft']) && count($Dismantle['craft']) > 0){
											foreach ($Dismantle['craft'] as $key=>$value){
										?>
												<tr data-change=true>
													<td><a class="plus" href="javascript:void(0);" title="加一行"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;<a class="minus" href="javascript:void(0);" title="减一行"><i class="fa fa-minus"></i></a></td>
													<td><input class="form-control" name="num" type="number" min="0" placeholder="内部编号" value="<?php $Num = max(array($Num, $value['num'])); echo $value['num'];?>"/></td>
													<td>
														<input type="hidden" name="craft_id" value="<?php echo $value['craft_id'];?>"/>
														<input class="model form-control" name="craft_des" data-label="craft_id,unit,unit_price"/>
														<span><?php echo $value['craft_des'];?></span>
													</td>
													<td><input class="form-control" name="count" type="number" min="0" placeholder="同种板块数量" value="<?php $Count += $value['count']; echo $value['count']?>"/></td>
													<td><input class="form-control" name="unit" type="text" placeholder="单位" value="<?php echo $value['unit']?>"/></td>
													<td><input class="form-control" name="unit_price" type="text" placeholder="出售单价" value="<?php echo $value['unit_price']?>"/></td>
													<td><input class="form-control" name="amount" type="text" placeholder="金额" value="<?php $Amount += $value['amount']; echo $value['amount']?>"/></td>
													<td><input class="form-control" name="remark" type="text" placeholder="备注" value="<?php echo $value['remark']?>"/></td>
												</tr>
										<?php 
											}
										}?>
										<tr data-change=false>
											<td><a class="plus" href="javascript:void(0);" title="加一行"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;<a class="minus" href="javascript:void(0);" title="减一行"><i class="fa fa-minus"></i></a></td>
											<td><input class="form-control" name="num" type="number" min="0" placeholder="内部编号" value=""/></td>
											<td>
												<input type="hidden" name="craft_id" value=""/>
												<input class="form-control" name="craft_des"  data-label="craft_id,unit,unit_price"/>
											</td>
											<td><input class="form-control" name="count" type="number" min="0" placeholder="数量" value="0"/></td>
											<td><input class="form-control" name="unit" type="text" placeholder="单位" value=""/></td>
											<td><input class="form-control" name="unit_price" type="text" placeholder="出售单价" value="0.00"/></td>
											<td><input class="form-control" name="amount" type="text" placeholder="金额" value="0.00"/></td>
											<td><input class="form-control" name="remark" type="text" placeholder="备注"/></td>
										</tr>
									</tbody>
<!-- 									<tfoot> -->
<!-- 										<tr> -->
<!-- 											<td>合计</td> -->
<!-- 											<td></td> -->
<!-- 											<td></td> -->
<!-- 											<td><input class="form-control" name="count_tmp" type="text" placeholder="总数量" readonly="readonly" value="<?php echo $Count;?>"/></td> -->
<!-- 											<td></td> -->
<!-- 											<td></td> -->
<!-- 											<td><input class="form-control" name="amount_tmp" type="text" min="0" placeholder="总额" readonly="readonly" value="<?php echo $Amount;?>"/></td> -->
<!-- 											<td></td> -->
<!-- 										</tr> -->
<!-- 									</tfoot> -->
								</table>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<table class="table table-striped table-bordered table-condensed" id="wardrobeOutsourcing" data-remote="<?php echo site_url('data/goods/read_json/outsourcing');?>">
									<thead>
										<tr>
											<td class="td-xs">外购</td>
											<td class="td-sm">编号</td>
											<td class="td-md">外购产品名称</td>
											<td class="td-sm">数量</td>
											<td class="td-sm">单位</td>
											<td class="td-sm">单价</td>
											<td>金额</td>
											<td>备注</td>
										</tr>
									</thead>
									<tbody>
										<?php 
										$Count = 0;
										$Amount = 0;
										if(isset($Dismantle['outsourcing']) && is_array($Dismantle['outsourcing']) && count($Dismantle['outsourcing']) > 0){
											foreach ($Dismantle['outsourcing'] as $key=>$value){
										?>
												<tr data-change=true>
													<td><a class="plus" href="javascript:void(0);" title="加一行"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;<a class="minus" href="javascript:void(0);" title="减一行"><i class="fa fa-minus"></i></a></td>
													<td><input class="form-control" name="num" type="number" min="0" placeholder="内部编号" value="<?php $Num = max(array($Num, $value['num'])); echo $value['num'];?>"/></td>
													<td>
														<input type="hidden" name="outsourcing_id" value="<?php echo $value['outsourcing_id'];?>"/>
														<input class="model form-control" name="outsourcing_des"  data-label="outsourcing_id,unit,unit_price" />
														<span><?php echo $value['outsourcing_des'];?></span>
													</td>
													<td><input class="form-control" name="count" type="number" min="0" placeholder="同种板块数量" value="<?php $Count += $value['count']; echo $value['count']?>"/></td>
													<td><input class="form-control" name="unit" type="text" placeholder="单位" value="<?php echo $value['unit']?>"/></td>
													<td><input class="form-control" name="unit_price" type="text" placeholder="出售单价" value="<?php echo $value['unit_price']?>"/></td>
													<td><input class="form-control" name="amount" type="text" placeholder="金额" value="<?php $Amount += $value['amount']; echo $value['amount']?>"/></td>
													<td><input class="form-control" name="remark" type="text" placeholder="备注" value="<?php echo $value['remark']?>"/></td>
												</tr>
										<?php 
											}
										}?>
										<tr data-change=false>
											<td><a class="plus" href="javascript:void(0);" title="加一行"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;<a class="minus" href="javascript:void(0);" title="减一行"><i class="fa fa-minus"></i></a></td>
											<td><input class="form-control" name="num" type="number" min="0" placeholder="内部编号" value=""/></td>
											<td>
												<input type="hidden" name="outsourcing_id" value=""/>
												<input class="form-control" name="outsourcing_des" data-label="outsourcing_id,unit,unit_price"/>
											</td>
											<td><input class="form-control" name="count" type="number" min="0" placeholder="数量" value="0"/></td>
											<td><input class="form-control" name="unit" type="text" placeholder="单位" value=""/></td>
											<td><input class="form-control" name="unit_price" type="text" placeholder="出售单价" value="0.00"/></td>
											<td><input class="form-control" name="amount" type="text" placeholder="金额" value="0.00"/></td>
											<td><input class="form-control" name="remark" type="text" placeholder="备注"/></td>
										</tr>
									</tbody>
<!-- 									<tfoot> -->
<!-- 										<tr> -->
<!-- 											<td>合计</td> -->
<!-- 											<td></td> -->
<!-- 											<td></td> -->
<!-- 											<td><input class="form-control" name="count_tmp" type="text" placeholder="总数量" readonly="readonly" value="<?php echo $Count;?>"/></td> -->
<!-- 											<td></td> -->
<!-- 											<td></td> -->
<!-- 											<td><input class="form-control" name="amount_tmp" type="text" min="0" placeholder="总额" readonly="readonly" value="<?php echo $Amount;?>"/></td> -->
<!-- 											<td></td> -->
<!-- 										</tr> -->
<!-- 									</tfoot> -->
								</table>
							</div>
						</div>
				</form>
			</div>
			<script>
				(function($){
					$('table#wardrobeBoard').tableAutoComplete({
						Inputname: "board_des",
						label: ['des', 'gid', 'height', 'unit_price']
					});
					$('table#wardrobeHardware').tableAutoComplete({
						Inputname: "hardware_des",
						label: ['des', 'gid', 'unit', 'unit_price']
					});
					$('table#wardrobeCraft').tableAutoComplete({
						Inputname: "craft_des",
						label: ['des', 'cid', 'unit', 'unit_price']
					});
					$('table#wardrobeOutsourcing').tableAutoComplete({
						Inputname: "outsourcing_des",
						label: ['des', 'gid', 'unit', 'unit_price']
					});
					$('div#wardrobe').formRegister();
					$('input[name="count"],input[name="unit_price"]').on('change', function(e){
						var $Tr = $(this).parents('tr').eq(0);
						var Count = parseFloat($Tr.find('input[name="count"]').val());
						var UnitPrice = parseFloat($Tr.find('input[name="unit_price"]').val());
						if($Tr.find('input[name="area_real"]').length > 0){
							var AreaReal = parseFloat($Tr.find('input[name="area_real"]').val());
						}else{
							var AreaReal = 1;
						}
						if(!Count){
							Count = 0;
						}
						if(!UnitPrice){
							UnitPrice = 0;
						}
						if(!AreaReal){
							AreaReal = 0;
						}
						$Tr.find('input[name="amount"]').val((Count*UnitPrice*AreaReal).toFixed(2)).trigger('change');
						
					});
					$('div#wardrobe table td span').on('dblclick', function(e){
						$(this).prev().removeClass('model').val($(this).text());
						$(this).remove();
					});
				})(jQuery);
			</script>