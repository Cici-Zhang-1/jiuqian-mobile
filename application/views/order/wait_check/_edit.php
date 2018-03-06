<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月5日
 * @author Zhangcc
 * @version
 * @des
 * 核价
 */
?>
    <div class="page-line" id="waitCheckEdit" >
        <div class="my-tools col-md-12">
            <div class="col-md-3"><input type="hidden" name="oid" value="<?php echo $Id;?>" /></div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="waitCheckEditFunction">
	  		    <button class="btn btn-primary" data-action="<?php echo site_url('order/wait_check/edit/check');?>" type="button" id="waitCheckEditButton1"><i class="fa fa-arrows"></i>&nbsp;&nbsp;暂存</button>
	  		    <button class="btn btn-primary" data-action="<?php echo site_url('order/wait_check/edit/checked');?>" type="button" id="waitCheckEditButton2"><i class="fa fa-arrows"></i>&nbsp;&nbsp;确认</button>
	  		    <!-- <a href="javascript:void(0);" class="btn btn-primary" data-toggle="mtab" data-action="<?php echo site_url('order/dismantle/index/read/order?id='.$Id);?>"><i class="fa fa-arrows"></i>&nbsp;&nbsp;重新拆单</a> -->
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
		    <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="waitCheckEditBasicTable">
		        <thead>
		            <tr>
		                <th class="td-sm">编号</th>
		                <th>客户</th>
		                <th>业主</th>
		                <th>备注</th>
		                <th >金额</th>
						<th >厨</th>
						<th >衣</th>
						<th >门</th>
						<th >框</th>
						<th >配</th>
						<th >外</th>
						<th >服</th>
		                <th>创建时间</th>
		             </tr>
		        </thead>
				<tbody>
				    <?php
				        if(isset($Info) && is_array($Info) && count($Info) > 0){
				            $Info['cabinet'] = isset($Info['cabinet'])?$Info['cabinet']:0;
				            $Info['wardrobe'] = isset($Info['wardrobe'])?$Info['wardrobe']:0;
				            $Info['door'] = isset($Info['door'])?$Info['door']:0;
				            $Info['kuang'] = isset($Info['kuang'])?$Info['kuang']:0;
				            $Info['fitting'] = isset($Info['fitting'])?$Info['fitting']:0;
				            $Info['other'] = isset($Info['other'])?$Info['other']:0;
				            $Info['server'] = isset($Info['server'])?$Info['server']:0;
				            echo <<<END
<tr>
     <td>$Info[order_num]</td>
     <td>$Info[dealer]</td>
     <td>$Info[owner]</td>
     <td>$Info[remark]</td>
    <td>$Info[sum]</td>
    <td>$Info[cabinet]</td>
    <td>$Info[wardrobe]</td>
    <td>$Info[door]</td>
    <td>$Info[kuang]</td>
    <td>$Info[fitting]</td>
    <td>$Info[other]</td>
    <td>$Info[server]</td>
        <td>$Info[create_datetime]</td>
 </tr>
END;
				        }
				    ?>
				</tbody>
			</table>
		</div>
		    <?php
		         if(isset($W) && is_array($W) && count($W) > 0){
		             $Id = 1;
		             echo <<<END
<div class="col-md-12">
<table class="table-center table-form table table-bordered table-striped table-hover table-responsive table-condensed" name="cabinet" id="waitCheckEditWTable">
	<tbody>
        <tr>
            <td class="td-sm">厨</td>
            <td class="td-md">编号</td>
            <td class="td-md">板材</td>
            <td class="td-xs">数量</td>
            <td class="td-sm">面积</td>
            <td>单价</td><td>金额</td>
            <td class="td-sm">差面</td>
            <td>差额</td>
            <td class="hide"></td><td class="hide"></td></tr>
END;
		             foreach ($W as $key => $value){
		                 $value['unit_price'] = floatval($value['unit_price']);
		                 if($Info['status'] > 4){
		                     $Sum = floatval($value['sum']);
		                     $SumDiff = floatval($value['sum_diff']);
		                 }else{
		                     $Sum = floatval($value['sum']) > 0 ?$value['sum']:$value['unit_price']>0?$value['unit_price']*$value['area']:0;
		                     $SumDiff = floatval($value['sum_diff']) > 0 ?$value['sum_diff']:$value['unit_price']>0?$value['unit_price']*$value['area_diff']:0;
		                 }
		                 echo <<<END
<tr>
    <td>$Id</td>
    <td>$value[order_product_num]</td>
    <td>$value[board]</td>
    <td>$value[amount]</td>
    <td name="amount"><input class="form-control input-sm" type="text" value="$value[area]" name="area"/></td>
    <td><input class="form-control input-sm" type="text" value="$value[unit_price]" name="unit_price"/></td>
    <td><input class="form-control input-sm" type="text" value="$Sum" name="sum"/></td>
    <td><input class="form-control input-sm" type="text" value="$value[area_diff]" name="area_diff"/></td>
    <td><input class="form-control input-sm" type="text" value="$SumDiff" name="sum_diff"/></td>
    <td class="hide"><input type="hidden" value="$value[opbid]" name="opbid"/></td>
    <td class="hide"><input type="hidden" value="$value[opid]" name="opid"/></td>
</tr>
END;
		                 $Id++;
		             }
		             echo <<<END
    </tbody>
</table>
</div>
END;
		         }
		    ?>
		    <?php
		         if(isset($Y) && is_array($Y) && count($Y) > 0){
		             $Id = 1;
		             echo <<<END
<div class="col-md-12">
<table class="table-center table-form table table-bordered table-striped table-hover table-responsive table-condensed" name="wardrobe" id="waitCheckEditYTable">
	<tbody>
        <tr><td class="td-sm">衣</td><td class="td-md">编号</td><td class="td-md">板材</td><td>备注</td>
        <td class="td-xs">数量</td><td class="td-sm">面积</td><td>单价</td><td>金额</td>
        <td class="td-sm">差面</td><td>差额</td><td class="hide"></td></tr>
END;
		             foreach ($Y as $key => $value){
		                 $value['unit_price'] = floatval($value['unit_price']);
		                 if($Info['status'] > 4){
		                     $Sum = floatval($value['sum']);
		                     $SumDiff = floatval($value['sum_diff']);
		                 }else{
		                     $Sum = floatval($value['sum']) > 0 ?$value['sum']:$value['unit_price']>0?$value['unit_price']*$value['area']:0;
		                     $SumDiff = floatval($value['sum_diff']) > 0 ?$value['sum_diff']:$value['unit_price']>0?$value['unit_price']*$value['area_diff']:0;
		                 }
		                 echo <<<END
<tr><td>$Id</td><td>$value[order_product_num]</td><td>$value[board]</td><td>$value[remark]</td><td>$value[amount]</td>
<td name="amount"><input class="form-control input-sm" type="text" value="$value[area]" name="area"/></td>
<td><input class="form-control input-sm" type="text" value="$value[unit_price]" name="unit_price"/></td>
<td><input class="form-control input-sm" type="text" value="$Sum" name="sum"/></td>
<td><input class="form-control input-sm" type="text" value="$value[area_diff]" name="area_diff"/></td>
<td><input class="form-control input-sm" type="text" value="$SumDiff" name="sum_diff"/></td>
<td class="hide"><input type="hidden" value="$value[opbid]" name="opbid"/></td>
	  		    <td class="hide"><input type="hidden" value="$value[opid]" name="opid"/></td></tr>
END;
		                 $Id++;
		             }
		             echo <<<END
    </tbody>
</table>
</div>
END;
		         }
		    ?>
		    <?php
		         if(isset($M) && is_array($M) && count($M) > 0){
		             $Id = 1;
		             echo <<<END
<div class="col-md-12">
<table class="table-center table-form table table-bordered table-striped table-hover table-responsive table-condensed" name="door" id="waitCheckEditMTable">
	<tbody>
        <tr><td class="td-sm">门</td><td class="td-md">编号</td><td class="td-md">板材</td><td>备注</td>
        <td class="td-xs">数量</td><td class="td-sm">面积</td><td>单价</td>
        <td class="td-sm">打孔</td><td>单价</td><td class="td-sm">拉手</td><td>单价</td>
        <td>金额</td><td class="td-sm">差面</td><td>差额</td><td class="hide"></td></tr>
END;
		             foreach ($M as $key => $value){
		                 $value['unit_price'] = floatval($value['unit_price']);
		                 $value['sum'] = floatval($value['sum']);
		                 if($Info['status'] > 4){
		                     $Sum = $value['sum'];
							 $SumDiff = floatval($value['sum_diff']);
		                 }else{
		                     if($value['sum'] > 0){
		                         $Sum = $value['sum'];
		                         $SumDiff = $value['sum_diff'];
		                     }else{
		                         $value['unit_price'] = floatval($value['unit_price']);
		                         $value['open_hole_unit_price'] = floatval($value['open_hole_unit_price']);
		                         $value['invisibility_unit_price'] = floatval($value['invisibility_unit_price']);
		                         $Sum = $value['unit_price']*$value['area'] + $value['open_hole']*$value['open_hole_unit_price'] + $value['invisibility']*$value['invisibility_unit_price'];
								 $SumDiff = floatval($value['sum_diff']) > 0 ?$value['sum_diff']:$value['unit_price']>0?$value['unit_price']*$value['area_diff']:0;
		                     }
		                 }
		                 echo <<<END
<tr><td>$Id</td><td>$value[order_product_num]</td><td>$value[board]</td><td>$value[remark]</td><td>$value[amount]</td>
<td name="amount">$value[area]</td>
<td><input class="form-control input-sm" type="text" value="$value[unit_price]" name="unit_price"/></td>
<td name="open_hole">$value[open_hole]</td>
<td><input class="form-control input-sm" type="text" value="$value[open_hole_unit_price]" name="open_hole_unit_price"/></td>
<td name="invisibility">$value[invisibility]</td>
<td><input class="form-control input-sm" type="text" value="$value[invisibility_unit_price]" name="invisibility_unit_price"/></td>
<td><input class="form-control input-sm" type="text" value="$Sum" name="sum"/></td>
<td><input class="form-control input-sm" type="text" value="$value[area_diff]" name="area_diff"/></td>
<td><input class="form-control input-sm" type="text" value="$SumDiff" name="sum_diff"/></td>
<td class="hide"><input type="hidden" value="$value[opbid]" name="opbid"/></td>
	  			    <td class="hide"><input type="hidden" value="$value[opid]" name="opid"/></td></tr>
END;
		                 $Id++;
		             }
		             echo <<<END
    </tbody>
</table>
</div>
END;
		         }
		    ?>
		    <?php
		         if(isset($K) && is_array($K) && count($K) > 0){
		             $Id = 1;
		             echo <<<END
<div class="col-md-12">
<table class="table-center table-form table table-bordered table-striped table-hover table-responsive table-condensed" name="kuang" id="waitCheckEditKTable">
	<tbody>
        <tr><td class="td-sm">框</td><td class="td-md">编号</td><td class="td-md">板材</td><td>备注</td>
        <td class="td-xs">数量</td><td class="td-sm">面积</td><td>单价</td><td>金额</td>
        <td class="td-sm">差面</td><td>差额</td><td class="hide"></td></tr>
END;
		             foreach ($K as $key => $value){
		                 $value['unit_price'] = floatval($value['unit_price']);
		                 if($Info['status'] > 4){
		                     $Sum = floatval($value['sum']);
		                     $SumDiff = floatval($value['sum_diff']);
		                 }else{
		                     $Sum = floatval($value['sum']) > 0 ?$value['sum']:$value['unit_price']>0?$value['unit_price']*$value['area']:0;
		                     $SumDiff = floatval($value['sum_diff']) > 0 ?$value['sum_diff']:$value['unit_price']>0?$value['unit_price']*$value['area_diff']:0;
		                 }
		                 echo <<<END
<tr><td>$Id</td><td>$value[order_product_num]</td><td>$value[board]</td><td>$value[remark]</td><td>$value[amount]</td>
<td name="amount">$value[area]</td>
<td><input class="form-control input-sm" type="text" value="$value[unit_price]" name="unit_price"/></td>
<td><input class="form-control input-sm" type="text" value="$Sum" name="sum"/></td>
<td><input class="form-control input-sm" type="text" value="$value[area_diff]" name="area_diff"/></td>
<td><input class="form-control input-sm" type="text" value="$SumDiff" name="sum_diff"/></td>
<td class="hide"><input type="hidden" value="$value[opbid]" name="opbid"/></td>
	  		    <td class="hide"><input type="hidden" value="$value[opid]" name="opid"/></td></tr>
END;
		                 $Id++;
		             }
		             echo <<<END
    </tbody>
</table>
</div>
END;
		         }
		    ?>
		    <?php
		         if(isset($P) && is_array($P) && count($P) > 0){
		             $Id = 1;
		             echo <<<END
<div class="col-md-12">
<table class="table-center table-form table table-bordered table-striped table-hover table-responsive table-condensed" name="fitting" id="waitCheckEditPTable">
	<tbody>
        <tr><td class="td-sm">配</td><td class="td-md">编号</td><td class="td-md">分类</td>
        <td class="td-md">名称</td><td class="td-md">备注</td><td class="td-xs">单位</td>
        <td class="td-sm">数量</td><td>单价</td><td>金额</td><td class="hide"></td></tr>
END;
		             foreach ($P as $key => $value){
		                 $value['unit_price'] = floatval($value['unit_price']);
		                 if($Info['status'] > 4){
		                     $Sum = floatval($value['sum']);
		                 }else{
		                     $Sum = $value['sum'] > 0 ?$value['sum']:$value['unit_price']>0?$value['unit_price']*$value['amount']:0;
		                 }
		                 echo <<<END
<tr><td>$Id</td><td>$value[order_product_num]</td><td>$value[type]</td><td>$value[fitting]</td>
<td>$value[remark]</td>
<td>$value[unit]</td><td name="amount">$value[amount]</td>
<td><input class="form-control input-sm" type="text" value="$value[unit_price]" name="unit_price"/></td>
<td><input class="form-control input-sm" type="text" value="$Sum" name="sum"/></td>
<td class="hide"><input type="hidden" value="$value[opfid]" name="opfid"/></td>
		    <td class="hide"><input type="hidden" value="$value[opid]" name="opid"/></td></tr>
END;
		                 $Id++;
		             }
		             echo <<<END
    </tbody>
</table>
</div>
END;
		         }
		    ?>
		    <?php
		         if(isset($G) && is_array($G) && count($G) > 0){
		             $Id = 1;
		             echo <<<END
<div class="col-md-12">
<table class="table-center table-form table table-bordered table-striped table-hover table-responsive table-condensed" name="other" id="waitCheckEditGTable">
	<tbody>
        <tr><td class="td-sm">外</td><td class="td-md">编号</td><td class="td-md">分类</td>
        <td class="td-md">名称</td><td class="td-md">规格</td><td class="td-md">备注</td><td class="td-xs">单位</td>
        <td class="td-sm">数量</td><td>单价</td><td>金额</td><td class="hide"></td></tr>
END;
		             foreach ($G as $key => $value){
		                 $value['unit_price'] = floatval($value['unit_price']);
		                 if($Info['status'] > 4){
		                     $Sum = floatval($value['sum']);
		                 }else{
		                     $Sum = $value['sum'] > 0 ?$value['sum']:$value['unit_price']>0?$value['unit_price']*$value['amount']:0;
		                 }
		                 echo <<<END
<tr><td>$Id</td><td>$value[order_product_num]</td><td>$value[type]</td><td>$value[other]</td>
<td>$value[spec]</td><td>$value[remark]</td><td>$value[unit]</td><td name="amount">$value[amount]</td>
<td><input class="form-control input-sm" type="text" value="$value[unit_price]" name="unit_price"/>
</td><td><input class="form-control input-sm" type="text" value="$Sum" name="sum"/></td>
<td class="hide"><input type="hidden" value="$value[opoid]" name="opoid"/></td>
		        <td class="hide"><input type="hidden" value="$value[opid]" name="opid"/></td></tr>
END;
		                 $Id++;
		             }
		             echo <<<END
    </tbody>
</table>
</div>
END;
		         }
		    ?>
		    <?php
		         if(isset($F) && is_array($F) && count($F) > 0){
		             $Id = 1;
		             echo <<<END
<div class="col-md-12">
<table class="table-center table-form table table-bordered table-striped table-hover table-responsive table-condensed" name="server" id="waitCheckEditFTable">
	<tbody>
        <tr><td class="td-sm">服</td><td class="td-md">编号</td><td class="td-md">分类</td class="td-md">
        <td class="td-md">名称</td><td class="td-md">备注</td><td class="td-xs">单位</td>
        <td class="td-sm">数量</td><td>单价</td><td>金额</td><td class="hide"></td></tr>
END;
		             foreach ($F as $key => $value){
		                 $value['unit_price'] = floatval($value['unit_price']);
		                 if($Info['status'] > 4){
		                     $Sum = floatval($value['sum']);
		                 }else{
		                     $Sum = $value['sum'] > 0 ?$value['sum']:$value['unit_price']>0?$value['unit_price']*$value['amount']:0;
		                 }
		                 echo <<<END
<tr><td>$Id</td><td>$value[order_product_num]</td><td>$value[type]</td>
<td>$value[server]</td><td>$value[remark]</td><td>$value[unit]</td><td name="amount">$value[amount]</td>
<td><input class="form-control input-sm" type="text" value="$value[unit_price]" name="unit_price"/></td>
<td><input class="form-control input-sm" type="text" value="$Sum" name="sum"/></td>
<td class="hide"><input type="hidden" value="$value[opsid]" name="opsid"/></td>
		        <td class="hide"><input type="hidden" value="$value[opid]" name="opid"/></td></tr>
END;
		                 $Id++;
		             }
		             echo <<<END
    </tbody>
</table>
</div>
END;
		         }
		    ?>
    </div>
	<script type="text/javascript">
		(function($, window, undefined){
			$('#waitCheckEdit').find('input[name="area"], input[name="area_diff"], input[name="unit_price"],input[name="open_hole_unit_price"],' +
				'input[name="invisibility_unit_price"]').each(function(i, v){
				$(this).on('focusout', function(e){
					var UnitPrice, $Tr = $(this).parents('tr').eq(0), Amount = 0, AreaDiff = 0,
					OpenHole, Invisibility, UnitPrice1, UnitPrice2;
					UnitPrice = parseFloat($Tr.find('input[name="unit_price"]').val());
					if($Tr.find('input[name="area"]').length > 0){
						Amount = parseFloat($Tr.find('input[name="area"]').val());
					}else{
						Amount = parseFloat($Tr.find('td[name="amount"]').text());
					}
					if($Tr.find('input[name="area_diff"]').length > 0){
						AreaDiff = parseFloat($Tr.find('input[name="area_diff"]').val());
					}else{
						AreaDiff = 0;
					}
					if($Tr.find('td[name="open_hole"]').length > 0){
						OpenHole = parseInt($Tr.find('td[name="open_hole"]').text());
						UnitPrice1 = parseFloat($Tr.find('input[name="open_hole_unit_price"]').val());
					}else{
						OpenHole = 0;
						UnitPrice1 = 0;
					}
					if($Tr.find('td[name="invisibility"]').length > 0){
						Invisibility = parseFloat($Tr.find('td[name="invisibility"]').text());
						UnitPrice2 = parseFloat($Tr.find('input[name="invisibility_unit_price"]').val());
					}else{
						Invisibility = 0;
						UnitPrice2 = 0;
					}
					$Tr.find('input[name="sum"]').val((UnitPrice*Amount + OpenHole*UnitPrice1 + Invisibility*UnitPrice2).toFixed(3));
					if ($Tr.find('input[name="sum_diff"]').length > 0) {
						$Tr.find('input[name="sum_diff"]').val((UnitPrice*AreaDiff).toFixed(3));
					}
				});
			});
			$('#waitCheckEditButton1, #waitCheckEditButton2').each(function(i, v){
				$(this).on('click', function(e){
					var $Button = $(this), Action = $(this).data('action'), Data = {};
					$('#waitCheckEdit').find('table.table-form').each(function(ii, vv){
						var Name = $(this).attr('name');
						if(undefined == Data[this.name]){
							Data[Name] = {};
						}
						$(this).find('tr').each(function(iii, vvv){
							$(this).find('input').each(function(iiii, vvvv){
								if(undefined == Data[Name][iii]){
									Data[Name][iii] = {};
								}
								Data[Name][iii][this.name] = this.value;
							});
						});
					});
					Data.oid = $('#waitCheckEdit').find('input[name="oid"]').val();
					if(Data){
						$.ajax({
							async: true,
							dataType: 'json',
							type: 'post',
							url: Action,
							data: Data,
							beforeSend: function(){
									$Button.prop('disabled', true);
								},
							success: function(msg){
									if(msg.error == 0){
										alert('核价保存成功!');
										if('waitCheckEditButton2' == $Button.attr('id')){
											$.tabDelete();
										}else{
											$.tabRefresh();
										}
										return ;
									}else{
										alert(msg.message);
									}
								},
							error: function(x, t, e){
									alert(x.responseText);
								},
							complete: function(){
									$Button.prop('disabled', false);
								}
						});
					}
				});
			});
			$('#waitCheckEdit').handle_page();
		})(jQuery);
	</script>