<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月24日
 * @author Administrator
 * @version
 * @des
 * 经销商欠款列表
 */
?>
	<div class="page-line" id="dealerMoney">
		<div class="my-tools col-md-12">
		    <div class="col-md-3">
				<div class="input-group" id="dealerMoneySearch" data-toggle="search" data-target="#dealerMoneyTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="dealerMoneyFilterBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="dealerMoneyFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12" >
			<table class="table table-bordered table-hover table-responsive table-condensed" id="dealerMoneyTable" >
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th>名称</th>
						<th>类型</th>
						<th>地址<i class="fa fa-sort"></i></th>
						<th>首要联系人</th>
						<th>联系方式</th>
						<th >支付条款</th>
						<th >等待生产<i class="fa fa-sort"></i></th>
						<th >正在生产<i class="fa fa-sort"></i></th>
						<th >账户余额<i class="fa fa-sort"></i></th>
						<th >发货金额<i class="fa fa-sort"></i></th>
						<th >收款金额<i class="fa fa-sort"></i></th>
						<th>备注</th>
						<th >创建人</th>
					</tr>
				</thead>
				    <?php
				    if(isset($content) && is_array($content) && count($content) > 0){
				        $K = 1;
				        $Html = '<tbody>';
				        $Debt1 = 0;
				        $Debt2 = 0;
				        $Balance = 0;
				        $Deliveried = 0;
				        $Received = 0;
				        foreach ($content as $key => $value){
				            $Html .= <<<END
 <tr>
	<td>$K</td>
	<td name="des">$value[des]</td>
	<td name="category">$value[category]</td>
	<td name="area">$value[area]</td>
	<td name="linker">$value[linker]</td>
	<td name="way">$value[way]</td>
	<td name="payterms">$value[payterms]</td>
	<td name="debt1">$value[debt1]</td>
	<td name="debt2">$value[debt2]</td>
	<td name="balance">$value[balance]</td>
	<td name="deliveried">$value[deliveried]</td>
	<td name="received">$value[received]</td>
	<td name="remark">$value[remark]</td>
	<td name="owner">$value[owner]</td>
</tr>       
END;
				            $Debt1 += $value['debt1'];
				            $Debt2 += $value['debt2'];
				            $Balance += $value['balance'];
				            $Deliveried += $value['deliveried'];
				            $Received += $value['received'];
				            $K++;
				        }
				        $Html .= <<<END
			    </tbody>
			    <tfoot>
				    <tr>
                    	<td></td>
                    	<td ></td>
                    	<td ></td>
                    	<td ></td>
                    	<td ></td>
                    	<td ></td>
                    	<td >合计</td>
                    	<td >$Debt1</td>
                    	<td >$Debt2</td>
                    	<td >$Balance</td>
                    	<td >$Deliveried</td>
                    	<td >$Received</td>
                    	<td ></td>
                    	<td ></td>
                    </tr>
			    </tfoot>
END;
				        echo $Html;
				    }
				    
				    ?>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		(function($, window, undefined){
		    $('div#dealerMoney').handle_page();
		    $('#dealerMoneyTable').tablesorter($(this).find('thead tr').getHeaders()); 
		})(jQuery);
	</script>