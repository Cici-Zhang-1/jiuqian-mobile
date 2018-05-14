<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月2日
 * @author Administrator
 * @version
 * @des
 */
?>
    <div class="my-print-data container-fluid">
        <div class="row hidden-print">
            <div class="col-md-12">
                <div class="col-md-6"></div>
    	  		<div class="col-md-6">
    	  		    <button class="btn btn-primary" type="button" id="printDeliveryButton" value="打印发货单"><i class="fa fa-print"></i>&nbsp;&nbsp;打印发货单</button>
    	  		    <a class="btn btn-primary" href="<?php echo site_url('stock/stock_outted_reprint/index/print');?>" target="_blank" id="printLabelButton" ><i class="fa fa-square"></i>&nbsp;&nbsp;打印发货标签</a>
    	  		</div>
            </div>
        </div>
        <div class="row">
			  	    <?php 
			  	    if(isset($Order) && is_array($Order) && count($Order) > 0){
			  	        $Html = <<<END
<div class="page-line col-md-12" id="printBoard">
    <div class="row">
        <div class="col-md-12"><p class="my-enhance-1"><span>$truck</span>,<span>$train</span>,<span>$end_datetime</span></p></div>
        <div class="col-md-12">
  	        <table class="my-table-ensize table table-bordered" id="printBoardTable" >
		        <thead>
		            <tr>
                        <th class="print-xxs">#</th>
		                <th class="print-md">货到地区</th>
		                <th class="print-md">收货人</th>
		                <th class="print-sm">联系方式</th>
		                <th class="print-sm">订单编号</th>
		                <th class="print-xxs">包装详情</th>
                        <th class="print-xxs">共</th>
                        <th class="print-sm">代收</th>
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
    <td rowspan="$Rowspan">$value[delivery_address]</td>
    <td rowspan="$Rowspan">$value[delivery_linker]</td>
    <td rowspan="$Rowspan">$value[delivery_phone]</td>
END;
			  	                $J = 1;
			  	                foreach ($value['detail'] as $ikey => $ivalue){
			  	                    $OrderNum = $ivalue['order_num'];
			  	                    unset($ivalue['order_num']);
			  	                    foreach ($ivalue as $iikey => $iivalue){
			  	                        $ivalue[$iikey] = implode(', ', $iivalue);
			  	                    }
			  	                    $ivalue = implode('<br />', $ivalue);
			  	                    
			  	                    if(1 == $J){
			  	                        $Html .= <<<END
    <td>$OrderNum<input type="hidden" name="oid[$K][$J]" value="$ikey"</td>
    <td>$ivalue</td>
    <td rowspan="$Rowspan">$value[amount]</td>
    <td rowspan="$Rowspan">$value[payed]</td>
    <td rowspan="$Rowspan">$value[logistics]</td>
</tr>
END;
			  	                    }else{
			  	                        $Html .= <<<END
<tr>
    <td>$OrderNum<input type="hidden" name="oid[$K][$J]" value="$ikey"</td>
    <td>$ivalue</td>
</tr>
END;
			  	                    }
			  	                    $J++;
			  	                }
			  	                $K++;
			  	            }
			  	        }
			  	        $Html .= <<<END
	  	        </tbody>
		        <tfoot>
			  	    <tr><td colspan="15">[打单时间: $create_datetime][数量共计: <span class="my-enhance-2">$amount</span> 件][代收总额: <span class="my-enhance-2">$Logistics</span> 元]</td></tr>
		        </tfoot>
		    </table>
        </div>
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
    		});
  	    })(jQuery);
  	    </script>
	</body>
</html>