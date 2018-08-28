<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月16日
 * @author Zhangcc
 * @version
 * @des
 * 橱柜生产清单
 */
?>
    <div class="my-print-data container-fluid">
			<div class="row hidden-print">
				<div class="col-md-12">
					<div class="col-md-6"></div>
					<div class="col-md-6">
						<a class="btn btn-primary" href="<?php echo site_url('chart/print_list/prints?old=0&v=' . $Info['v'] . '&type=' . $Info['type']);?>" target="_blank"><i class="fa fa-refresh"></i>&nbsp;&nbsp;切换格式</a>
					</div>
				</div>
			</div>
            <div class="page-line row">
                <?php
                if(isset($Cabinet) && is_array($Cabinet) && count($Cabinet) > 0){
                    $Html = '';
                    $Html .= <<<END
<div class="col-md-8">
    <p class='my-enhance-2'>订单来源：$Info[dealer]&nbsp;&nbsp;业主: $Info[owner]</p>
    <p>颜色厚度: 
END;
		  	        if(isset($Cabinet['Statistic']) && is_array($Cabinet['Statistic']) && count($Cabinet['Statistic']) > 0){
		  	            foreach ($Cabinet['Statistic'] as $key => $value){
			  	                $Html .= <<<END
[<span class="my-enhance-3">$key</span>:&nbsp;&nbsp;$value[area]平方&nbsp;&nbsp;$value[amount]块]
END;
		  	            }
		  	        }
		  	        $Html .= <<<END
    </p>
    <p>$Info[remark]$Info[order_remark]</p>
</div>
<div class="my-box col-md-4">
    <p>订单编号: <span class="my-enhance-2">$Info[num]</span></p>
    <p>要求出厂: <span class="my-enhance-2">$Info[request_outdate]</span></p>
</div>
<div class="col-md-12">
    <p>柜体结构: 
END;
		  	        if(isset($Struct) && is_array($Struct) && count($Struct) > 0){
		  	            $Html .= <<<END
        <span class="my-enhance-1">$Struct[struct]</span>
        | 封边：<span class="my-enhance-1">$Struct[facefb]</span>
END;
		  	        }
		  	        $Html .= <<<END
    </p>
</div>
<div class="col-md-10">
    <table class="my-table-ensize table table-condensed" >
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
END;
		  	        foreach ($Cabinet['List']  as $key=> $value){
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
		  	        $Html .= <<<END
        </tbody>
    </table>
</div>
<div class="col-md-2">
    <table class="my-table-ensize table table-condensed" >
        <thead>
            <tr>
                <th>其它板块</th>
            </tr>
            <tr>
                <th>宽X长X块</th>
            </tr>
        </thead>
        <tbody>
END;
		  	        if(isset($Cabinet['Other']) && is_array($Cabinet['Other']) && count($Cabinet['Other']) > 0){
		  	            foreach ($Cabinet['Other'] as $key => $value){
		  	                $Html .= <<<END
            <tr><td>$value</td></tr>
END;
		  	            }
		  	        }
		  	        $Html .= <<<END
        </tbody>
    </table>
</div>
END;
		  	        echo $Html;
                }
                ?>
            </div>
            <div class="row hidden-print"><br/><br/><br/><br/><br/><br/><br/><br/></div>
                <?php
                if(isset($Abnormity) && is_array($Abnormity) && count($Abnormity) > 0){
                    $Html = <<<END
<div class="page-line row">
<div class="col-md-12">
    <table class="my-table-ensize table table-bordered table-condensed" >
        <thead>
            <tr>
                <th colspan="10">橱柜柜体生产制造流程单-异形单</th>
            </tr>
            <tr>
	  	        <th colspan="2">经销商:</th>
                <th colspan="9">$Info[dealer]</th>
            </tr>
            <tr>
                <th colspan="2">业主:</th>
                <th colspan="3">$Info[owner]</th>
                <th colspan="2">订单号:</th>
                <th colspan="3">$Info[num]</th>
            </tr>
            <tr>
                <th colspan="2">产品名称:</th>
                <th colspan="3">$Info[product]</th>
                <th colspan="2">要求出厂:</th>
                <th colspan="3">$Info[request_outdate]</th>
            </tr>
            <tr><th colspan="10">$Info[remark]<br />$Info[order_remark]</th></tr>
            <tr>
                <th class="print-xxs" rowspan="2">编号</th>
                <th class="print-md" rowspan="2">板件名称</th>
                <th colspan="5">开料规格</th>
                <th class="print-sm" rowspan="2">封边</th>
                <th class="print-md" rowspan="2">颜色</th>
                <th class="print-md" rowspan="2">备注</th>
            </tr>
            <tr>
                <th class="print-sm">长</th>
                <th class="print-sm">宽</th>
                <th class="print-xs">厚</th>
                <th class="print-xs">块</th>
                <th class="print-sm">面积(m<sup>2</sup>)</th>
            </tr>
        </thead>
        <tbody>
END;
                    $K = 1;
                    foreach($Abnormity['List'] as $key => $value){
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
                <td>$value[remark]</td>
            </tr>
END;
                        $K++;
                    }
                    $Html .= <<<END
        </tbody>
        <tfoot>
  	        <tr><td colspan="11">统计</td></tr>
END;
                    if(isset($Abnormity['Statistic']) && is_array($Abnormity['Statistic']) && count($Abnormity['Statistic']) > 0){
                        foreach ($Abnormity['Statistic'] as $key => $value){
                            $Html .= <<<END
            <tr><td >{$key}mm</td><td >$value[amount]</td><td colspan="2">$value[area]</td></tr>
END;
                        }
                        $Html .= <<<END
            <tr><td >合计</td><td >块数:【<span class="my-enhance-3">$Abnormity[Amount]</span>】</td><td colspan="2">面积:【<span class="my-enhance-3">$Abnormity[Area]</span>】</td></tr>
END;
                    }
                    $Html .= <<<END
        </tfoot>
    </table>
</div>
</div>
END;
                    echo $Html;
                }
                ?>
            <!-- </div> -->
        <?php
		if(isset($Classify) && is_array($Classify) && count($Classify) > 0){
		    foreach ($Classify as $ClassifyName => $ClassifyPrintList){
        ?>
        <div class="page-line row">
            <div class="col-md-offset-2 col-md-8" >
                <table class="my-table-condensed table table-bordered table-condensed" >
			        <thead>
			            <tr>
			                <th colspan="11">衣柜柜体生产制造流程单-<?php echo $ClassifyName;?>清单</th>
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
			                if(isset($ClassifyPrintList['List']) && is_array($ClassifyPrintList['List']) && count($ClassifyPrintList['List']) > 0){
			                    $List = $ClassifyPrintList['List'];
			                    $Html = '';
			                    $K = 1;
			                    foreach($List as $key => $value){
			                        $Html .= <<<END
<tr>
    <td>$value[cubicle_name]</td>
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
    			            if(isset($ClassifyPrintList['Statistic']) && is_array($ClassifyPrintList['Statistic']) && count($ClassifyPrintList['Statistic']) > 0){
    			                $Statistic = $ClassifyPrintList['Statistic'];
    			                $Html = '';
    			                foreach ($Statistic as $key => $value){
    			                    $Html .= <<<END
<tr><td >{$key}</td><td >$value[amount]</td><td>$value[area]</td></tr>
END;
    			                }
    			                $Html .= <<<END
<tr><td >合计</td><td >块数:【<span class="my-enhance-3">$ClassifyPrintList[Amount]</span>】</td><td>面积:【<span class="my-enhance-3">$ClassifyPrintList[Area]</span>】</td></tr>
END;
    			                echo $Html;
    			            }
    			            ?>
		            </tbody>
			    </table>
		    </div>
		</div>
        <?php 
		    }
		}
		?>
        </div>
    </body>
</html>