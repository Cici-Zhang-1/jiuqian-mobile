<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月16日
 * @author Zhangcc
 * @version
 * @des
 */
?>
        <div class="my-print-data container-fluid">
    		<div class="row">
    			<div class="col-md-10">
    			    <p class='my-enhance-1'>订单来源：<?php echo $Info['dealer'];?></p>
    			    <p class='my-enhance-1'>业主: <?php echo $Info['owner'];?></p>
    			    <p><?php echo $Info['remark'];?></p>
    			    <p><?php echo $Info['order_remark'];?></p>
    			</div>
    			<div class="my-box col-md-2">
    			    <p>订单编号: <span class="my-enhance-2"><?php echo $Info['num'];?></span></p>
    			    <p>要求出厂: <span class="my-enhance-2"><?php echo $Info['request_outdate'];?></span></p>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-md-12">
    			    <table class="table table-condensed" >
    			        <thead>
    			            <tr>
    			                <th>#</th>
    			                <th>宽(mm)</th>
    			                <th>高(mm)</th>
    			                <th>块</th>
    			                <th>面积(m<sup>2</sup>)</th>
    			                <th>铰链孔方向</th>
    			                <th>封边拉手</th>
    			                <th>开孔(个)</th>
    			                <th>隐形拉手(m)</th>
    			                <th>备注</th>
    			            </tr>
    			        </thead>
    			        <tbody>
			                <?php
			                if(isset($Plate['List']) && is_array($Plate['List']) && count($Plate['List']) > 0){
			                    $List = $Plate['List'];
			                    $Html = '';
			                    $K = 1;
			                    $Good = '';
			                    foreach($List as $key => $value){
			                        if('' == $Good || $Good != $value['good']){
			                            $Html .= <<<END
<tr><td class="my-enhance-2" colspan="10">$value[good], $Struct[edge]</td></tr>
END;
			                            $Good = $value['good'];
			                        }
			                        $Html .= <<<END
<tr>
    <td>$K</td>
    <td>$value[width]</td>
    <td>$value[length]</td>
    <td>$value[num]</td>
    <td>$value[area]</td>
    <td>$value[punch]</td>
    <td>$value[handle]</td>
    <td>$value[open_hole]</td>
    <td>$value[invisibility]</td>
    <td>$value[remark]</td>
</tr>
END;
			                        $K++;
			                    }
			                    $Html .= <<<END
<tr><td colspan="10">累计块数:【<span class="my-enhance-3">$Plate[Amount]</span>】 合计面积:【<span class="my-enhance-3">$Plate[Area]</span>】开孔:【<span class="my-enhance-3">$Plate[OpenHole]</span>】隐形拉手:【<span class="my-enhance-3">$Plate[Invisibility]</span>】</td></tr>
END;
			                    echo $Html;
			                }
			                ?>
    			        </tbody>
    			    </table>
    			</div>
    		</div>
    	</div>
	</body>
</html>