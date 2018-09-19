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
    			<div class="col-md-12">
    			    <table class="my-table-condensed table table-bordered table-condensed" >
    			        <thead>
    			            <tr>
    			                <th colspan="11">木框门生产制造流程单</th>
    			            </tr>
    			            <tr>
    			                <th colspan="2">经销商:</th>
    			                <th colspan="3"><?php echo $Info['dealer'];?></th>
    			                <th colspan="2">图纸号:</th>
    			                <th></th>
    			                <th>订单号:</th>
    			                <th><?php echo $Info['num'];?></th>
    			            </tr>
    			            <tr>
    			                <th colspan="2">产品名称:</th>
    			                <th colspan="3"><?php echo $Info['product'];?></th>
    			                <th colspan="2">要求出厂:</th>
    			                <th><?php echo $Info['request_outdate'];?></th>
    			                <th>业主:</th>
    			                <th><?php echo $Info['owner'];?></th>
    			            </tr>
    			            <?php if(!empty($Info['remark'])){
    			                echo <<<END
<tr><th colspan="11">$Info[remark]</th></tr>
END;
    			            }?>
    			            <?php if(!empty($Info['order_remark'])){
    			                echo <<<END
<tr><th colspan="11">$Info[order_remark]</th></tr>
END;
    			            }?>
			            </thead>
		            </table>
		            <table class="my-table-condensed table table-bordered table-condensed" >
		                <thead>
    			            <tr>
    			                <th rowspan="2">编号</th>
    			                <th rowspan="2">板件名称</th>
    			                <th rowspan="2">门芯</th>
    			                <th rowspan="2">板材</th>
    			                <th colspan="7">开料规格</th>
    			                <th rowspan="2">中横</th>
    			                <th rowspan="2">打孔</th>
    			                <th rowspan="2">备注</th>
    			            </tr>
    			            <tr>
    			                <th>成行长</th>
    			                <th>成行宽</th>
								<th>物料长</th>
    			                <th>物料宽</th>
    			                <th>厚</th>
    			                <th>数量</th>
    			                <th>面积(m<sup>2</sup>)</th>
    			            </tr>
    			        </thead>
    			        <tbody>
			                <?php
			                if(isset($Plate['List']) && is_array($Plate['List']) && count($Plate['List']) > 0){
			                    $List = $Plate['List'];
			                    $Html = '';
			                    $K = 1;
			                    $H = array();
			                    $S = array();
			                    $X = array();
								$C = array();
			                    $BoardWidth = 70;
			                    $ItemWidth = 130;
			                    foreach($List as $key => $value){
			                        $Html .= <<<END
<tr>
    <td>$K</td>
    <td>$value[wood_name]</td>
    <td>$value[core]</td>
    <td>$value[good]</td>
    <td>$value[length]</td>
    <td>$value[width]</td>
    <td>$value[m_length]</td>
    <td>$value[m_width]</td>
    <td>$value[thick]</td>
    <td>$value[amount]</td>
    <td>$value[area]</td>
    <td>$value[center]</td>
    <td>$value[punch]</td>
    <td>$value[remark]</td>
</tr>
END;
									if (preg_match('/五五/', $value['center'])) {
										$C[] = array(
											'flag' => $K,
											'name' => '中横',
											'good' => $value['good'],
											'length' => $value['width'] - $BoardWidth*2,
											'width' => $BoardWidth,
											'amount' => $value['amount']
										);
										$CenterFlag = 1;
									}elseif (preg_match('/四六/', $value['center'])) {
										$C[] = array(
											'flag' => $K,
											'name' => '中横',
											'good' => $value['good'],
											'length' => $value['width'] - $BoardWidth*2,
											'width' => $BoardWidth,
											'amount' => $value['amount']
										);
										$CenterFlag = 2;
									}else {
										$CenterFlag = 0;
									}
			                        if(preg_match('/百叶/', $value['wood_name'])){
			                            $H[] = array(
			                                'flag' => $K,
			                                'name' => '木框横框',
			                                'good' => $value['good'],
			                                'length' => $value['width'] - $BoardWidth*2,
			                                'width' => $BoardWidth,
			                                'amount' => $value['amount']*2
			                            );
			                            $S[] = array(
			                                'flag' => $K,
			                                'name' => '木框竖框',
			                                'good' => $value['good'],
			                                'length' => $value['length'],
			                                'width' => $BoardWidth,
			                                'amount' => $value['amount']*2
			                            );

										if (1 == $CenterFlag) {
											$X[] = array(
												'flag' => $K,
												'name' => '小百叶',
												'good' => $value['good'],
												'length' => $value['width'] - $BoardWidth*2 + 12,
												'width' => $ItemWidth,
												'amount' => $value['amount'] * ceil((($value['length'] - $BoardWidth*3)/2 + 12)/$ItemWidth) * 2
											);
										}elseif (2 == $CenterFlag) {
											$X[] = array(
												'flag' => $K,
												'name' => '小百叶',
												'good' => $value['good'],
												'length' => $value['width'] - $BoardWidth*2 + 12,
												'width' => $ItemWidth,
												'amount' => $value['amount'] * (ceil((($value['length']*3/5 - $BoardWidth*3/2) + 12)/$ItemWidth) + ceil((($value['length']*2/5 - $BoardWidth*3/2) + 12)/$ItemWidth))
											);
										}else {
											$X[] = array(
												'flag' => $K,
												'name' => '小百叶',
												'good' => $value['good'],
												'length' => $value['width'] - $BoardWidth*2 + 12,
												'width' => $ItemWidth,
												'amount' => $value['amount']*ceil(($value['length'] - $BoardWidth*2 + 12)/$ItemWidth)
											);
										}
			                        }elseif (preg_match('/玻璃/', $value['wood_name'])){
			                            $H[] = array(
			                                'flag' => $K,
			                                'name' => '木框横框',
			                                'good' => $value['good'],
			                                'length' => $value['width'] - $BoardWidth*2,
			                                'width' => $BoardWidth,
			                                'amount' => $value['amount']*2
			                            );
			                            $S[] = array(
			                                'flag' => $K,
			                                'name' => '木框竖框',
			                                'good' => $value['good'],
			                                'length' => $value['length'],
			                                'width' => $BoardWidth,
			                                'amount' => $value['amount']*2
			                            );

										if (1 == $CenterFlag) {
											$X[] = array(
												'flag' => $K,
												'name' => '玻璃门芯',
												'good' => $value['core'],
												'length' => ceil(($value['length'] - $BoardWidth*3)/2 + 12),
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount'] * 2
											);
										}elseif (2 == $CenterFlag) {
											$X[] = array(
												'flag' => $K,
												'name' => '玻璃门芯',
												'good' => $value['core'],
												'length' => ceil(($value['length']*3/5 - $BoardWidth*3/2) + 12),
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount']
											);
											$X[] = array(
												'flag' => $K,
												'name' => '玻璃门芯',
												'good' => $value['core'],
												'length' => ceil(($value['length']*2/5 - $BoardWidth*3/2) + 12),
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount']
											);
										}else {
											$X[] = array(
												'flag' => $K,
												'name' => '玻璃门芯',
												'good' => $value['core'],
												'length' => $value['length'] - $BoardWidth*2 + 12,
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount']
											);
										}
			                        }elseif (preg_match('/平板/', $value['wood_name'])){
			                            $H[] = array(
			                                'flag' => $K,
			                                'name' => '木框横框',
			                                'good' => $value['good'],
			                                'length' => $value['width'] - $BoardWidth*2,
			                                'width' => $BoardWidth,
			                                'amount' => $value['amount']*2
			                            );
			                            $S[] = array(
			                                'flag' => $K,
			                                'name' => '木框竖框',
			                                'good' => $value['good'],
			                                'length' => $value['length'],
			                                'width' => $BoardWidth,
			                                'amount' => $value['amount']*2
			                            );

										if (1 == $CenterFlag) {
											$X[] = array(
												'flag' => $K,
												'name' => '平板门芯',
												'good' => $value['core'],
												'length' => ceil(($value['length'] - $BoardWidth*3)/2 + 12),
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount'] * 2
											);
										}elseif (2 == $CenterFlag) {
											$X[] = array(
												'flag' => $K,
												'name' => '平板门芯',
												'good' => $value['core'],
												'length' => ceil(($value['length']*3/5 - $BoardWidth*3/2) + 12),
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount']
											);
											$X[] = array(
												'flag' => $K,
												'name' => '平板门芯',
												'good' => $value['core'],
												'length' => ceil(($value['length']*2/5 - $BoardWidth*3/2) + 12),
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount']
											);
										}else {
											$X[] = array(
												'flag' => $K,
												'name' => '平板门芯',
												'good' => $value['core'],
												'length' => $value['length'] - $BoardWidth*2 + 12,
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount']
											);
										}
			                        }else{
			                            $H[] = array(
			                                'flag' => $K,
			                                'name' => '木框横框',
			                                'good' => $value['good'],
			                                'length' => $value['width'] - $BoardWidth*2,
			                                'width' => $BoardWidth,
			                                'amount' => $value['amount']*2
			                            );
			                            $S[] = array(
			                                'flag' => $K,
			                                'name' => '木框竖框',
			                                'good' => $value['good'],
			                                'length' => $value['length'],
			                                'width' => $BoardWidth,
			                                'amount' => $value['amount']*2
			                            );

										if (1 == $CenterFlag) {
											$X[] = array(
												'flag' => $K,
												'name' => '门芯',
												'good' => $value['core'],
												'length' => ceil(($value['length'] - $BoardWidth*3)/2 + 12),
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount'] * 2
											);
										}elseif (2 == $CenterFlag) {
											$X[] = array(
												'flag' => $K,
												'name' => '门芯',
												'good' => $value['core'],
												'length' => ceil(($value['length']*3/5 - $BoardWidth*3/2) + 12),
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount']
											);
											$X[] = array(
												'flag' => $K,
												'name' => '门芯',
												'good' => $value['core'],
												'length' => ceil(($value['length']*2/5 - $BoardWidth*3/2) + 12),
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount']
											);
										}else {
											$X[] = array(
												'flag' => $K,
												'name' => '门芯',
												'good' => $value['core'],
												'length' => $value['length'] - $BoardWidth*2 + 12,
												'width' => $value['width'] - $BoardWidth*2 + 12,
												'amount' => $value['amount']
											);
										}
			                        }

			                        $K++;
			                    }
			                    echo $Html;
			                }
			                ?>
    			        </tbody>
			        </table>
			        <table class="my-table-condensed table table-bordered table-condensed">
			            <thead>
		                    <tr>
		                        <th >编号</th>
    			                <th >板件名称</th>
    			                <th >板材颜色</th>
    			                <th >长</th>
    			                <th >宽</th>
    			                <th >数量</th>
		                    </tr>
    			        </thead>
    			        <tbody>
    			            <?php
    			            $Html = '';
                            if(isset($H) && is_array($H) && count($H) > 0){
			                    foreach($H as $key => $value){
			                        $Html .= <<<END
<tr>
    <td>$value[flag]</td>
    <td>$value[name]</td>
    <td>$value[good]</td>
    <td>$value[length]</td>
    <td>$value[width]</td>
    <td>$value[amount]</td>
</tr>
END;
			                    }
			                }
			                if(isset($S) && is_array($S) && count($S) > 0){
			                    foreach($S as $key => $value){
			                        $Html .= <<<END
<tr>
    <td>$value[flag]</td>
    <td>$value[name]</td>
    <td>$value[good]</td>
    <td>$value[length]</td>
    <td>$value[width]</td>
    <td>$value[amount]</td>
</tr>
END;
			                    }
			                }
							if(isset($C) && is_array($C) && count($C) > 0){
								foreach($C as $key => $value){
									$Html .= <<<END
<tr>
    <td>$value[flag]</td>
    <td>$value[name]</td>
    <td>$value[good]</td>
    <td>$value[length]</td>
    <td>$value[width]</td>
    <td>$value[amount]</td>
</tr>
END;
								}
							}
			                if(isset($X) && is_array($X) && count($X) > 0){
			                    foreach($X as $key => $value){
			                        $Html .= <<<END
<tr>
    <td>$value[flag]</td>
    <td>$value[name]</td>
    <td>$value[good]</td>
    <td>$value[length]</td>
    <td>$value[width]</td>
    <td>$value[amount]</td>
</tr>
END;
			                    }
			                }
			                echo $Html;
    			            ?>
    			        </tbody>
			        </table>
			        <table class="table table-bordered table-condensed" >
			            <thead>
			                <tr><th colspan="3">统计</th></tr>
			            </thead>
    			        <tbody>
							<?php
							if(isset($Plate['Amount']) && isset($Plate['Area'])){
								echo <<<END
<tr><td >合计</td><td >块数:【<span class="my-enhance-3">$Plate[Amount]</span>】</td><td>面积:【<span class="my-enhance-3">$Plate[Area]</span>】</td></tr>
END;
							}
							?>
    			        </tbody>
    			    </table>
    			</div>
    		</div>
    	</div>
	</body>
</html>