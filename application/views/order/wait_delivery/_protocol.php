<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月21日
 * @author Zhangcc
 * @version
 * @des
 * 预发货
 */
?>
    <div class="my-print-data container-fluid">
        <div class="row hidden-print">
            <div class="col-md-12">
                <div class="col-md-6"></div>
    	  		<div class="col-md-6">
    	  		    <button class="btn btn-primary" data-action="<?php echo site_url('order/wait_delivery/edit');?>" type="button" id="printDeliveryButton" value="打印发货单"><i class="fa fa-print"></i>&nbsp;&nbsp;打印发货单</button>
    	  		    <a class="btn btn-primary" href="<?php echo site_url('order/wait_delivery/index/print');?>" target="_blank" id="printLabelButton" ><i class="fa fa-square"></i>&nbsp;&nbsp;打印发货标签</a>
    	  		</div>
            </div>
        </div>
        <div class="row">
			  	    <?php 
			  	    if(isset($Order) && is_array($Order) && count($Order) > 0){
			  	        $Html = <<<END
<div class="page-line col-md-12" id="printBoard">
    <div class="row">
        <div class="col-md-12"><p class="my-enhance-2"><span>$Truck</span>,<span>$Train</span>,<span>$EndDatetime</span></p></div>
        <div class="col-md-12">
  	        <table class="table table-bordered" id="printBoardTable" >
		        <thead>
		            <tr>
                    	<th class="print-xxs">#</th>
		                <th class="print-sm">客户</th>
		                <th class="print-xs">订单编号</th>
		                <th class="print-sm">业主</th>
		                <th>包装详情</th>
                        <th class="print-xxs">共</th>
                        <th class="print-xs">代收</th>
                        <th class="print-sm">物流</th>
		            </tr>
		        </thead>
		        <tbody>
END;
			  	        $K = 1;
			  	        if(isset($Order) && is_array($Order) && count($Order) > 0){
			  	            $K = 1;
			  	            foreach ($Order as $key => $value){
			  	                $Rowspan = count($value['detail']);
			  	                $Html .= <<<END
<tr>
    <td rowspan="$Rowspan">$K</td>
    <td rowspan="$Rowspan">$value[delivery_address]<br />$value[delivery_linker]<br />$value[delivery_phone]</td>
END;
			  	                $J = 1;
								$DefaultRow = 4;
			  	                foreach ($value['detail'] as $ikey => $ivalue){
									$InsideTable = '<table class="table table-bordered"><tbody>';
			  	                    $OrderNum = $ivalue['order_num'];
									$Owner = $ivalue['owner'];
			  	                    unset($ivalue['order_num'], $ivalue['owner']);
									foreach ($ivalue as $iikey => $iivalue) {
										$iivalues = '';
										$RowCount = 0;
										foreach ($iivalue as $iiikey => $iiivalue) {
											$RowCount++;
											if ($RowCount == 1) {
												$iivalues .= '<tr><td class="print-xs">'. $iiivalue . '</td>';
											}elseif ($RowCount == $DefaultRow) {
												$iivalues .= '<td class="print-xs">'. $iiivalue . '</td></tr>';
												$RowCount = 0;
											}else {
												$iivalues .= '<td class="print-xs">'. $iiivalue . '</td>';
											}
										}
										if ($RowCount != 0) {
											for ($RowCount++; $RowCount <= $DefaultRow; $RowCount++) {
												$iivalues .= '<td></td>';
											}
											$iivalues .= '</tr>';
										}
										$InsideTable .= $iivalues;
									}
									$InsideTable .= '</tbody></table>';
			  	                    /*foreach ($ivalue as $iikey => $iivalue){
			  	                        $ivalue[$iikey] = implode(', ', $iivalue);
			  	                    }
			  	                    $ivalue = implode('<br />', $ivalue);*/
			  	                    
			  	                    if(1 == $J){
			  	                        $Html .= <<<END
    <td>$OrderNum<input type="hidden" name="oid[$K][$J]" value="$ikey" /></td>
    <td>$Owner</td>
    <td class="has-inside-table">$InsideTable</td>
END;
			  	                        $Html .= <<<END
    <td rowspan="$Rowspan">$value[amount]</td>
    <td rowspan="$Rowspan">$value[payed]</td>
    <td rowspan="$Rowspan">$value[logistics]</td>
</tr>
END;
			  	                    }else{
			  	                        $Html .= <<<END
<tr>
    <td>$OrderNum<input type="hidden" name="oid[$K][$J]" value="$ikey" /></td>
    <td>$Owner</td>
    <td class="has-inside-table">$InsideTable</td>
</tr>
END;
			  	                    }
			  	                    $J++;
			  	                }
			  	                $K++;
			  	            }
			  	        }
			  	        $Datetime = date('Y-m-d H:i:s');
			  	        $Html .= <<<END
	  	        </tbody>
		        <tfoot>
			  	    <tr><td colspan="15">[打单时间: $Datetime][数量共计: <span class="my-enhance-2">$Amount</span> 件][代收总额: <span class="my-enhance-2">$Logistics</span> 元]</td></tr>
		        </tfoot>
		    </table>
        </div>
    </div>
    <div class="row">
    	<div class="col-xs-6 col-sm-6 col-md-3">出库：</div><div class="col-xs-6 col-sm-6 col-md-3">收货：</div>
    </div>
</div>
END;
			  	        echo $Html;
			  	    }
		  	    ?>
      	    </div>
  	    </div>
        <script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
  	    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
  	    <script>
  	    (function($){
  	  	    $('#printDeliveryButton').on('click', function(e){
  	  	  	    window.print();
  	  	  	  	$.ajax({
  	  	  	  	  async: false,
  	              type: 'post',
  	              url: $(this).data('action'),
  	              beforeSend: function(ie){},
  	              dataType: 'json',
  	              success: function(msg){
  	                  if(msg.error == 0){
  	                      return true;
  	                  }else if(msg.error == 1){
  	  	                  alert(msg.message);
  	                  }
  	              },
  	              error: function(x,t,e){
  	              	if(x.responseText.length > 0){
  	  	              	alert(x.responseText);
  	              	}else{
  	              		alert('服务器执行错误, 提交失败!');
  	              	}
  	              }
  	           });
   	  	    });
  	    })(jQuery);
  	    </script>
	</body>
</html>