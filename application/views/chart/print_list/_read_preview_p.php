<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月15日
 * @author Zhangcc
 * @version
 * @des
 */
?>
        <div class="my-print-data container-fluid">
    		<div class="row">
    			<div class="col-md-offset-2 col-md-5">
    			    <p class='my-enhance-1'>订单来源：<?php echo $Info['dealer'].'&nbsp;&nbsp;业主: '.$Info['owner'];?></p>
    			    <p><?php echo $Info['remark'];?></p>
    			    <p><?php echo $Info['order_product_remark'];?></p>
    			</div>
    			<div class="my-box col-md-2">
    			    <p>订单编号: <span class="my-enhance-2"><?php echo $Info['order_product_num'];?></span></p>
    			    <p>要求出厂: <span class="my-enhance-2"><?php echo $Info['request_outdate'];?></span></p>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-md-offset-2 col-md-8">
    			    <table class="table table-condensed" >
    			        <thead>
    			            <tr>
    			                <th>#</th>
    			                <th>类型</th>
    			                <th>名称</th>
    			                <th>数量</th>
    			                <th>单位</th>
    			                <th>备注</th>
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
    <td>$value[type]</td>
    <td>$value[name]</td>
    <td>$value[amount]</td>
    <td>$value[unit]</td>
    <td>$value[remark]</td>
</tr>
END;
			                            $K++;
			                        }
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