<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月14日
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
    			                <th colspan="10">生产清单（木框门）</th>
    			            </tr>
    			            <tr>
    			                <th colspan="2">订单号:</th>
    			                <th colspan="3"><?php echo $Info['num'];?></th>
    			                <th colspan="2">要求出厂:</th>
    			                <th colspan="3"><?php echo $Info['request_outdate'];?></th>
    			            </tr>
    			            <tr>
    			                <th colspan="2">经销商:</th>
    			                <th colspan="3"><?php echo $Info['dealer'];?></th>
    			                <th colspan="2">业主:</th>
    			                <th colspan="3"><?php echo $Info['owner'];?></th>
    			            </tr>
    			            <tr>
    			                <th >编号</th>
    			                <th >板件名称</th>
    			                <th >颜色</th>
    			                <th>长</th>
    			                <th>宽</th>
    			                <th>厚</th>
    			                <th >数量</th>
    			                <th >拉槽</th>
    			                <th >规格</th>
    			                <th >面积</th>
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
    			                $Product['board'] = '<tr><td>'.implode('</td><td>', $Product['board']).'</td></tr>';
    			                echo $Product['board'];
    			            }
    			            ?>
    			        </tfoot>
    			    </table>
    			</div>
    		</div>
    	</div>