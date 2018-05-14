<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月11日
 * @author Zhangcc
 * @version
 * @des
 * 打印橱柜清单
 */
?>
        <div class="my-print-data container-fluid">
    		<div class="row">
    			<div class="col-md-offset-2 col-md-6">
    			    <p class='my-enhance-2'>订单来源：<?php echo $Info['dealer_address'].'&nbsp;&nbsp;'.$Info['dealer_linker'].'['.$Info['dealer'].']&nbsp;&nbsp;业主: '.$Info['owner'];?></p>
    			    <p>颜色厚度： 
    			    <?php
    			        if(isset($Product['board']) && is_array($Product['board']) && count($Product['board']) > 0){
    			            foreach ($Product['board'] as $key => $value){
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
    			    <p>订单编号: <span class="my-enhance-2"><?php echo $Info['order_num'];?></span></p>
    			    <p>要求出厂: <span class="my-enhance-2"><?php echo $Info['request_outdate'];?></span></p>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-md-offset-2 col-md-8">
    			    <p>柜体结构：<span class="my-enhance-1"><?php echo isset($Struct['struct'])?$Struct['struct']:'';?></span> | 地柜结构：<span class="my-enhance-1"><?php echo isset($Struct['dgjg'])?$Struct['dgjg']:'';?></span> | 地柜前撑：<?php echo isset($Struct['dgqc'])?$Struct['dgqc']:'';?> | 地柜后撑：<?php echo isset($Struct['dghc'])?$Struct['dghc']:'';?> | 封边：<span class="my-enhance-1"><?php echo isset($Struct['facefb'])?$Struct['facefb']:'';?></span> </p>
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
			                    if(isset($Product['plate']) && is_array($Product['plate']) && count($Product['plate']) > 0){
			                        foreach($Product['plate'] as $ikey => $ivalue){
			                            $Tmp = array_pop($ivalue);
			                            if(!empty($Tmp)){
			                                $Other[] = $Tmp;
			                            }
			                            $Product['plate'][$ikey] = '<td>'.implode('</td><td>', $ivalue).'</td>';
			                        }
			                        $Product['plate'] = '<tr>'.implode('</tr><tr>', $Product['plate']).'</tr>';
			                        echo $Product['plate'];
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
			                        $Other = '<tr><td>'.implode('</td></tr><tr><td>', $Other).'</td></tr>';
			                        echo $Other;
			                    }
			                ?>
    			        </tbody>
    			    </table>
    			</div>
    		</div>
    	</div>
	</body>
</html>