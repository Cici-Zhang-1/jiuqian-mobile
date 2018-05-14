<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月27日
 * @author Administrator
 * @version
 * @des
 * 订单产品清理配件清单
 */
?>
        <div class="my-print-data container-fluid">
            <div class="row">
                <div class="col-md-12"><p class="text-center"><?php echo $StartDate;?>配件清单</p></div>
            </div>
    		<div class="row">
    			<div class="col-md-offset-2 col-md-8">
		            <table class="my-table-condensed table table-bordered table-condensed" >
		                <thead>
    			            <tr>
    			                <th>序号</th>
    			                <th>客户</th>
    			                <th>订单编号</th>
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
			                        $First = array_pop($value['child']);
			                        $Html .= <<<END
<tr>
    <td>$K</td>
    <td>$value[dealer]</td>
    <td>$key</td>
    <td>$First[name]</td>
    <td>$First[amount]</td>
    <td>$First[unit]</td>
    <td>$First[remark]</td>
</tr>
END;
			                        foreach ($value['child'] as $ikey => $ivalue){
			                            $Html .= <<<END
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td>$ivalue[name]</td>
    <td>$ivalue[amount]</td>
    <td>$ivalue[unit]</td>
    <td>$ivalue[remark]</td>
</tr>
END;
			                        }
			                        $Html .= <<<END
<tr>
    <td colspan="11">&nbsp;</td>
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