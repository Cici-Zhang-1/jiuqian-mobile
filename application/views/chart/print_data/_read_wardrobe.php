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
    			<div class="col-md-offset-2 col-md-8">
    			    <table class="table table-bordered table-condensed" >
    			        <thead>
    			            <tr>
    			                <th colspan="11">柜体生产制造流程单</th>
    			            </tr>
    			            <tr>
    			                <th colspan="2">经销商:</th>
    			                <th colspan="3"><?php echo $Info['dealer'];?></th>
    			                <th colspan="2">图纸号:</th>
    			                <th><?php echo $Info['product_no'];?></th>
    			                <th>订单号:</th>
    			                <th><?php echo $Info['order_num'];?></th>
    			            </tr>
    			            <tr>
    			                <th colspan="2">产品名称:</th>
    			                <th colspan="3"><?php echo $Info['product'];?></th>
    			                <th colspan="2">要求出厂:</th>
    			                <th><?php echo $Info['request_outdate'];?></th>
    			                <th>业主:</th>
    			                <th><?php echo $Info['owner'];?></th>
    			            </tr>
    			            <tr><th colspan="11"><?php echo $Info['remark'];?></th></tr>
    			            <tr>
    			                <th rowspan="2">编号</th>
    			                <th rowspan="2">板件名称</th>
    			                <th colspan="4">开料规格</th>
    			                <th rowspan="2">数量</th>
    			                <th rowspan="2">封边</th>
    			                <th rowspan="2">颜色</th>
    			                <th rowspan="2">备注</th>
    			            </tr>
    			            <tr>
    			                <th>长</th>
    			                <th>宽</th>
    			                <th>厚</th>
    			                <th>面积</th>
    			            </tr>
    			        </thead>
    			        <tbody>
			                <?php
			                    if(isset($Product['plate']) && is_array($Product['plate']) && count($Product['plate']) > 0){
			                        foreach($Product['plate'] as $ikey => $ivalue){
			                            $Product['plate'][$ikey] = '<td>'.implode('</td><td>', $ivalue).'</td>';
			                        }
			                        $Product['plate'] = '<tr>'.implode('</tr><tr>', $Product['plate']).'</tr>';
			                        echo $Product['plate'];
			                    }
			                ?>
    			        </tbody>
    			        <tfoot>
    			            <tr><td colspan="11">统计</td></tr>
    			            <?php 
    			            if(isset($Product['board']) && is_array($Product['board']) && count($Product['board']) > 0){
    			                $Num = count($Product['board']);
    			                foreach ($Product['board'] as $key => $value){
    			                    $Product['board'][$key] = '<td>'.implode('</td><td>', $value).'</td>';
    			                }
    			                $Product['board'] = '<tr>'.implode('</tr><tr>', $Product['board']).'</tr>';
    			                echo $Product['board'];
    			            }
    			            ?>
    			        </tfoot>
    			    </table>
    			</div>
    		</div>
    	</div>
	</body>
</html>