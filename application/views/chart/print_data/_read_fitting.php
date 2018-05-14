<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月11日
 * @author Zhangcc
 * @version
 * @des
 */
?>
        <div class="my-print-data container-fluid">
    		<div class="row">
    			<div class="col-md-offset-2 col-md-5">
    			    <p class='my-enhance-1'>订单来源：<?php echo $Info['dealer_address'].'&nbsp;&nbsp;'.$Info['dealer_linker'].'['.$Info['dealer'].']&nbsp;&nbsp;业主: '.$Info['owner'];?></p>
    			    <p><?php echo $Info['remark'];?></p>
    			</div>
    			<div class="my-box col-md-2">
    			    <p>订单编号: <span class="my-enhance-2"><?php echo $Info['order_num'];?></span></p>
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
			                    if(isset($Product) && is_array($Product) && count($Product) > 0){
			                        foreach($Product as $ikey => $ivalue){
			                            $Product[$ikey] = '<td>'.implode('</td><td>', $ivalue).'</td>';
			                        }
			                        $Product = '<tr>'.implode('</tr><tr>', $Product).'</tr>';
			                        echo $Product;
			                    }
			                ?>
    			        </tbody>
    			    </table>
    			</div>
    		</div>
    	</div>
	</body>
</html>