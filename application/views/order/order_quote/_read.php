<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月25日
 * @author Administrator
 * @version
 * @des
 * 报价单
 */
if(isset($Info['dealer']) && '' != $Info['dealer']){
    $Dealer = explode('_', $Info['dealer']);
}else{
    $Dealer = array_fill(0, 4, '');
}
?>
        <div class="my-print-data container-fluid">
            <div class="row">
                <div class="col-md-3">
        		    <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderDetailBasicTable">
        				<tbody>
        			      	<tr><td class="td-sm">客户名称:</td><td ><?php echo $Dealer[1];?></td></tr>
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
        			      	<tr><td>收货地址:</td><td ><?php echo $Info['delivery_address'];?></td></tr>
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
        			      	    <td class="td-sm"></td><td ></td>
        			      	</tr>
        			      	<tr>
        			      	    <td>要求出厂:</td><td><?php echo $Info['request_outdate'];?></td>
        			      	    <td>实际出厂:</td><td></td>
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
        			                        $Url = site_url('chart/print_list/index/read/quote?id='.$value['opid']);
        			                        echo board_table($value, $Product, $Url);
        			                        //echo board_table($value['detail'],$value['order_product_num'],$Product, $Url, $value['name']);
        			                        break;
        			                    case 'y':
        			                        $Url = site_url('chart/print_list/index/read/quote?id='.$value['opid']);
        			                        echo board_table($value, $Product, $Url);
        			                        //echo board_table($value['detail'],$value['order_product_num'],$Product, $Url, $value['name']);
        			                        break;
        			                    case 'm':
        			                        $Url = site_url('chart/print_list/index/read/quote?id='.$value['opid']);
        			                        echo board_table($value, $Product, $Url);
        			                        //echo board_table($value['detail'],$value['order_product_num'],$Product, $Url, $value['name']);
        			                        break;
        			                    case 'k':
        			                        $Url = site_url('chart/print_list/index/read/quote?id='.$value['opid']);
        			                        echo board_table($value, $Product, $Url);
        			                        //echo board_table($value['detail'],$value['order_product_num'],$Product, $Url, $value['name']);
        			                        break;
        			                    case 'p':
        			                        $Url = site_url('chart/print_list/index/read/quote?id='.$value['opid']);
        			                        echo fitting_table($value,$Product, $Url);
        			                        //echo fitting_table($value['detail'],$value['order_product_num'],$Product, $Url);
        			                        break;
        			                    case 'g':
        			                        $Url = site_url('chart/print_list/index/read/quote?id='.$value['opid']);
        			                        echo other_table($value,$Product, $Url);
        			                        //echo other_table($value['detail'],$value['order_product_num'],$Product, $Url);
        			                        break;
        			                    case 'f':
        			                        $Url = site_url('chart/print_list/index/read/quote?id='.$value['opid']);
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
        <script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
  	    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	</body>
</html>