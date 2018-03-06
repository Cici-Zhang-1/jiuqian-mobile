<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月19日
 * @author Zhangcc
 * @version
 * @des
 * 到厂自提
 */
?>
<div class="col-md-12" id="waitDelivery1Search" data-toggle="search" data-target="#waitDelivery1Table">
	<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/业主/备注">
</div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-12" id="waitDelivery1">
    <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="waitDelivery1Table">
		<thead>
			<tr>
				<th class="td-xs checkall" data-name="selected" data-checkall=false data-compute='{"pack":"amount"}'>#</th>
				<th >等级</th>
				<th >订单编号</th>
				<th >支付状态</th>
				<th >金额</th>
				<th >生产欠款</th>
				<th >业主</th>
				<th >货到地址</th>
				<th >联系人</th>
				<th >要求物流</th>
				<th >备注</th>
				<th >要求出厂</th>
				<th >厨</th>
				<th >衣</th>
				<th >门</th>
				<th >框</th>
				<th >配</th>
				<th >外</th>
				<th >总</th>
			</tr>
		</thead>
		<tbody>
		    <?php 
		    if(isset($Order) && is_array($Order) && count($Order) > 0){
		        $Html = '';
		        foreach ($Order as $key => $value){
		            $PackDetail = array(
		                'W' => 0,
		                'Y' => 0,
		                'M' => 0,
		                'K' => 0,
		                'P' => 0,
		                'G' => 0
		            );
		            $Tmp = json_decode($value['pack_detail'], true);
		            if(is_array($Tmp)){
		                $PackDetail = array_merge($PackDetail, $Tmp);
		            }
		            $OrderUrl = site_url('order/order_detail/index/read/order');
		            $OrderRemote = site_url('order/order_detail/index/read_floatover');
		            $Dealer = site_url('dealer/dealer_debt/index/read?id='.$value['did']);
		            if($value['balance'] < 0){
        				if('物流代收' == $value['payed'] || '按月结款' == $value['payed'] || '到厂付款' == $value['payed']){
        					$Danger = '';
        				}else{
		                	$Danger = 'class="danger1"';
				        }
		            }else{
		                $Danger = '';
		            }
		            $Html .= <<<END
<tr>
	<td $Danger><input   type="checkbox" value="$value[oid]"/></td>
	<td >$value[icon]</td>
	<td ><a href="$OrderUrl" title="订单详情" data-toggle="floatover" data-target="#waitDelivery1Floatover" data-remote="$OrderRemote">$value[order_num]</a></td>
	<td >$value[payed]</td>
	<td >$value[sum]</td>
	<td ><a href="$Dealer" target="_blank">$value[balance]</a></td>
	<td >$value[owner]</td>
	<td >$value[delivery_area]$value[delivery_address]</td>
	<td >$value[delivery_linker]-$value[delivery_phone]</td>
	<td >$value[logistics]</td>
	<td >$value[remark]</td>
	<td >$value[request_outdate]</td>
	<td >$PackDetail[W]</td>
    <td >$PackDetail[Y]</td>
	<td >$PackDetail[M]</td>
    <td >$PackDetail[K]</td>
    <td >$PackDetail[P]</td>
    <td >$PackDetail[G]</td>
    <td name="pack">$value[pack]</td>
</tr>
END;
		        }
		        echo $Html;
		    }
		    ?>
		</tbody>
	</table>
</div>
<div class="floatover hide" id="waitDelivery1Floatover"></div>