<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月23日
 * @author Administrator
 * @version
 * @des
 * 橱柜生产清单预览
 */
?>
        <div class="my-print-data container-fluid">
    		<div class="row">
    			<div class="col-md-offset-2 col-md-6">
    			    <p class='my-enhance-2'>订单来源：<?php echo $Info['dealer'].'&nbsp;&nbsp;业主: '.$Info['owner'];?></p>
    			    <p>颜色厚度： 
    			    <?php
    			        if(isset($Statistic) && is_array($Statistic) && count($Statistic) > 0){
    			            foreach ($Statistic as $key => $value){
    			                echo <<<END
[<span class="my-enhance-3">$key</span>:&nbsp;&nbsp;$value[area]平方&nbsp;&nbsp;$value[amount]块]
END;
    			            }
    			        }
    			    ?>
    			    </p>
    			    <p><?php echo $Info['remark'];?></p>
    			</div>
    			<div class="my-box col-md-2">
    			    <p>订单编号: <span class="my-enhance-2"><?php echo $Info['order_product_num'];?></span></p>
    			    <p>要求出厂: <span class="my-enhance-2"><?php echo $Info['request_outdate'];?></span></p>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-md-offset-2 col-md-8">
    			    <p>柜体结构：
    			        <?php 
    			            if(isset($CabinetStruct) && is_array($CabinetStruct) && count($CabinetStruct) > 0){
    			                echo <<<END
<span class="my-enhance-1">$CabinetStruct[struct]</span> 
| 地柜结构：<span class="my-enhance-1">$CabinetStruct[dgjg]</span> 
| 地柜前撑：$CabinetStruct[dgqc]
| 地柜后撑：$CabinetStruct[dghc]
| 封边：<span class="my-enhance-1">$CabinetStruct[facefb]</span> 
END;
    			            }
    			        ?>
    			    </p>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-md-offset-2 col-md-6">
    			    <table class="table table-condensed" >
    			        <thead>
    			            <tr>
    			                <th rowspan="2">柜号名称</th>
    			                <th>立板</th>
    			                <th>顶底板</th>
    			                <th>活动隔板</th>
    			                <th>固定隔板</th>
    			                <th>连接条</th>
    			                <th>背板</th>
    			            </tr>
    			            <tr>
    			                <th>宽X长X块</th>
    			                <th>宽X长X块</th>
    			                <th>宽X长X块</th>
    			                <th>宽X长X块</th>
    			                <th>宽X长X块</th>
    			                <th>宽X长X块</th>
    			            </tr>
    			        </thead>
    			        <tbody>
			                <?php
			                    if(isset($List) && is_array($List) && count($List) > 0){
			                        $Html = '';
			                        foreach ($List  as $key=> $value){
			                            $Html .= <<<END
<tr>
    <td>$key</td>
    <td>$value[li]</td>
    <td>$value[dingdi]</td>
    <td>$value[huo]</td>
    <td>$value[gu]</td>
    <td>$value[lian]</td>
    <td>$value[bei]</td>
</tr>
END;
			                        }
			                        echo $Html;
			                    }
			                ?>
    			        </tbody>
    			    </table>
    			</div>
    			<div class="col-md-2">
    			    <table class="table table-condensed" >
    			        <thead>
    			            <tr>
    			                <th>其它板块</th>
    			            </tr>
    			            <tr>
    			                <th>宽X长X块</th>
    			            </tr>
    			        </thead>
    			        <tbody>
			                <?php
			                    if(isset($Other) && is_array($Other) && count($Other) > 0){
			                        $K = 1;
			                        $Html = '';
			                        foreach ($Other as $key => $value){
			                            $Html .= <<<END
<tr><td>$value</td></tr>
END;
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