<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月15日
 * @author Zhangcc
 * @version
 * @des
 * 
 */
?>
        <div class="my-print-data container-fluid">
    		<div class="row">
    			<div class="col-md-offset-2 col-md-8">
    			    <table class="my-table-condensed table table-bordered table-condensed" >
    			        <thead>
    			            <tr>
    			                <th colspan="11">衣柜柜体生产制造流程单</th>
    			            </tr>
    			            <tr>
    			                <th colspan="2">经销商:</th>
    			                <th colspan="3"><?php echo $Info['dealer'];?></th>
    			                <th colspan="2">图纸号:</th>
    			                <th></th>
    			                <th>订单号:</th>
    			                <th><?php echo $Info['order_product_num'];?></th>
    			            </tr>
    			            <tr>
    			                <th colspan="2">产品名称:</th>
    			                <th colspan="3"><?php echo $Info['product'];?></th>
    			                <th colspan="2">要求出厂:</th>
    			                <th><?php echo $Info['request_outdate'];?></th>
    			                <th>业主:</th>
    			                <th><?php echo $Info['owner'];?></th>
    			            </tr>
    			            <?php if(!empty($Info['order_product_remark'])){
    			                echo <<<END
<tr><th colspan="11">$Info[order_product_remark]</th></tr>
END;
    			            }?>
    			            <?php if(!empty($Info['remark'])){
    			                echo <<<END
<tr><th colspan="11">$Info[remark]</th></tr>
END;
    			            }?>
			            </thead>
		            </table>
		            <table class="my-table-condensed table table-bordered table-condensed" >
    			        <tbody>
    			            <?php 
    			            if(isset($All) && is_array($All) && count($All) > 0){
    			                $Html = '';
    			                foreach ($All as $key => $value){
    			                    $OrderProductNum = implode(', ', $value);
    			                    $Html .= <<<END
<tr><td class="td-sm">$key</td><td >$OrderProductNum</td></tr>
END;
    			                }
    			                echo $Html;
    			            }
    			            ?>
    			        </tbody>
    			    </table>
					<?php
					if(isset($Face) && $Face != ''){
						echo <<<END
					<table class="my-table-condensed table table-bordered table-condensed" >
						<tbody>
<tr><td >$Face</td></tr>
						</tbody>
					</table>
END;
					}
					?>
		            <table class="my-table-condensed table table-bordered table-condensed" >
		                <thead>
    			            <tr>
    			                <th rowspan="2">编号</th>
    			                <th rowspan="2">板件名称</th>
    			                <th colspan="5">开料规格</th>
    			                <th rowspan="2">封边</th>
    			                <th rowspan="2">颜色</th>
    			                <th rowspan="2">备注</th>
    			            </tr>
    			            <tr>
    			                <th>长</th>
    			                <th>宽</th>
    			                <th>厚</th>
    			                <th>数量</th>
    			                <th>面积(m<sup>2</sup>)</th>
    			            </tr>
    			        </thead>
    			        <tbody>
			                <?php
			                if(isset($List) && is_array($List) && count($List) > 0){
			                    $Html = '';
			                    $K = 1;
			                    foreach($List as $key => $value){
			                        $Html .= <<<END
<tr>
    <td>$K</td>
    <td>$value[plate_name]</td>
    <td>$value[length]</td>
    <td>$value[width]</td>
    <td>$value[thick]</td>
    <td>$value[num]</td>
    <td>$value[area]</td>
    <td>$value[edge]</td>
    <td>$value[good]</td>
    <td>$value[slot]$value[remark]</td>
</tr>
END;
			                        $K++;
			                    }
			                    echo $Html;
			                }
			                ?>
    			        </tbody>
			        </table>
			        <table class="table table-bordered table-condensed" >
			            <thead>
			                <tr><th colspan="3">统计</th></tr>
			            </thead>
    			        <tbody>
    			            <?php 
    			            if(isset($Statistic) && is_array($Statistic) && count($Statistic) > 0){
    			                $Html = '';
    			                foreach ($Statistic as $key => $value){
    			                    $Html .= <<<END
<tr><td >{$key}mm</td><td >$value[amount]</td><td>$value[area]</td></tr>
END;
    			                }
    			                $Html .= <<<END
<tr><td >合计</td><td >块数:【<span class="my-enhance-3">$Amount</span>】</td><td>面积:【<span class="my-enhance-3">$Area</span>】</td></tr>
END;
    			                echo $Html;
    			            }
							if(isset($FourH)){
								$Html = <<<END
<tr><td >4H合计</td><td >块数:【<span class="my-enhance-3">$FourH[Amount]</span>】</td><td>面积:【<span class="my-enhance-3">$FourH[Area]</span>】</td></tr>
END;
								echo $Html;
							}
    			            ?>
    			        </tbody>
    			    </table>
					<div>
						开槽:YXC（异型槽用160开槽） 后进19KA、后进19KB（单面通槽） 后进19:（双面通槽）打孔 :A、B（单面孔） C:（双面孔）  ★:（双面加工）
					</div>
    			</div>
    		</div>
    	</div>
	</body>
</html>
