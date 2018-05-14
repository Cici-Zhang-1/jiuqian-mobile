<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月28日
 * @author Zhangcc
 * @version
 * @des
 * 订单详情
 */
 if(isset($Info['dealer']) && '' != $Info['dealer']){
     $Dealer = explode('_', $Info['dealer']);
 }else{
     $Dealer = array_fill(0, 4, '');
 }
?>
    <div class="page-line" id="orderDetail" >
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="hide input-group" id="orderDetailSearch">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="orderDetailSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderDetailFunction">
	  		    <a href="<?php echo site_url('order/order_quote/index/read/order?id='.$Info['oid']);?>" class="btn btn-primary" data-toggle="blank" target="_blank" ><i class="fa fa-money"></i>&nbsp;&nbsp;报价单</a>
	  		    <a href="javascript:void(0);" class="btn btn-primary" data-toggle="mtab" data-action="<?php echo site_url('order/dismantle/index/redismantle/order?id='.$Info['oid']);?>" ><i class="fa fa-arrows"></i>&nbsp;&nbsp;拆单</a>
		        <a href="javascript:void(0);" class="btn btn-primary" data-toggle="mtab" data-action="<?php echo site_url('order/wait_check/index/recheck?id='.$Info['oid']);?>" ><i class="fa fa-money"></i>&nbsp;&nbsp;核价</a>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
		    <div class="col-md-3">
    		    <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderDetailBasicTable">
    				<tbody>
    			      	<tr><td class="td-sm">客户名称:</td><td ><a href="<?php echo site_url('dealer/dealer_debt/index/read?id='.$Info['did']);?>" target="_blank"><?php echo $Dealer[1];?></a></td></tr>
    			      	<tr><td>所在地区:</td><td ><?php echo $Dealer[0];?></td></tr>
    			      	<tr><td>联系人:</td><td ><?php echo $Dealer[2];?></td></tr>
    			      	<tr><td>联系电话:</td><td ><?php echo $Dealer[3];?></td></tr>
    			      	<tr><td>对单:</td><td ><?php echo $Info['checker'];?></td></tr>
    			      	<tr><td>对单电话:</td><td ><?php echo $Info['checker_phone'];?></td></tr>
    			      	<tr><td>支付:</td><td ><?php echo $Info['payer'];?></td></tr>
    			      	<tr><td>支付电话:</td><td ><?php echo $Info['payer_phone'];?></td></tr>
    			      	<tr><td>支付条款:</td><td ><?php echo $Info['payterms'];?></td></tr>
    			      	<tr><td>出厂方式:</td><td ><?php echo $Info['out_method'];?></td></tr>
    			      	<tr><td>要求物流:</td><td ><?php echo $Info['logistics'];?></td></tr>
    			      	<tr><td>收货地址:</td><td ><?php echo $Info['delivery_area'].$Info['delivery_address'];?></td></tr>
    			      	<tr><td>收货人:</td><td ><?php echo $Info['delivery_linker'];?></td></tr>
    			      	<tr><td>收货电话:</td><td ><?php echo $Info['delivery_phone'];?></td></tr>
    				</tbody>
    			</table>
		    </div>
		    <div class="col-md-9">
		        <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderDetailTable">
    				<tbody>
    			      	<tr>
    			      	    <td class="td-sm">编号:</td><td ><?php echo $Info['order_num'];?></td>
    			      	    <td class="td-sm">登记时间:</td><td ><?php echo $Info['create_datetime'];?></td>
    			      	    <td class="td-sm">确认时间:</td><td ><?php echo $Info['asure_datetime'];?></td>
    			      	</tr>
    			      	<tr>
    			      	    <td>要求出厂:</td><td><?php echo $Info['request_outdate'];?></td>
    			      	    <td>实际出厂:</td><td><?php echo $Info['end_datetime'];?></td>
    			      	    <td>当前状态:</td><td><?php echo $Info['workflow'];?></td>
    			      	</tr>
    			      	<tr><td>业主:</td><td><?php echo $Info['owner'];?></td>
    			      	    <td>金额:</td><td><?php echo $Info['sum'];?></td>
    			      	    <td>货号:</td><td><?php echo $Info['cargo_no'];?></td></tr>
    			      	<tr><td>备注:</td><td colspan="5"><?php echo $Info['remark'];?></td></tr>
    				</tbody>
    			</table>
    			<?php 
    			    if(isset($Detail) && is_array($Detail) && count($Detail) > 0){
    			        foreach ($Detail as $key => $value){
    			            $Code = strtolower($value['code']);
    			            if(is_array($value['detail']) && count($value['detail']) > 0){
    			                $Product = '' != $value['product']?$value['product']:$value['name'];
    			                switch ($Code){
    			                    case 'w':
    			                        $Url = site_url('order/order_product_board_plate/index/read/'.$Code.'?id='.$value['opid']);
    			                        echo board_table($value, $Product, $Url);
    			                        break;
    			                    case 'y':
    			                        $Url = site_url('order/order_product_board_plate/index/read/'.$Code.'?id='.$value['opid']);
    			                        echo board_table($value, $Product, $Url);
    			                        //echo board_table($value['detail'],$value['order_product_num'],$Product, $Url, $value['name']);
    			                        break;
    			                    case 'm':
    			                        $Url = site_url('order/order_product_board_door/index/read/'.$Code.'?id='.$value['opid']);
    			                        echo board_table($value, $Product, $Url);
    			                        //echo board_table($value['detail'],$value['order_product_num'],$Product, $Url, $value['name']);
    			                        break;
    			                    case 'k':
    			                        $Url = site_url('order/order_product_board_wood/index/read/'.$Code.'?id='.$value['opid']);
    			                        echo board_table($value, $Product, $Url);
    			                        //echo board_table($value['detail'],$value['order_product_num'],$Product, $Url, $value['name']);
    			                        break;
    			                    case 'p':
    			                        $Url = site_url('order/order_product_fitting/index/read/'.$Code.'?id='.$value['opid']);
    			                        echo fitting_table($value,$Product, $Url);
    			                        //echo fitting_table($value['detail'],$value['order_product_num'],$Product, $Url);
    			                        break;
    			                    case 'g':
    			                        $Url = site_url('order/order_product_other/index/read/'.$Code.'?id='.$value['opid']);
    			                        echo other_table($value,$Product, $Url);
    			                        //echo other_table($value['detail'],$value['order_product_num'],$Product, $Url);
    			                        break;
    			                    case 'f':
    			                        $Url = site_url('order/order_product_server/index/read/'.$Code.'?id='.$value['opid']);
    			                        echo server_table($value,$Product, $Url);
    			                        //echo server_table($value['detail'],$value['order_product_num'],$Product, $Url);
    			                        break;
    			                }
    			            }
    			        }
    			    }
    			?>
		    </div>
		</div>
    </div>
	<script type="text/javascript">
		(function($, window, undefined){
			$('a[data-toggle="tooltip"]').tooltip()
			$('#orderDetail').handle_page();
		})(jQuery);
	</script>