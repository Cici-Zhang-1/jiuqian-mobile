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
    			                <th>宽(mm)</th>
    			                <th>高(mm)</th>
    			                <th>块</th>
    			                <th>面积(m<sup>2</sup>)</th>
    			                <th>铰链孔方向</th>
    			                <th>封边拉手</th>
    			                <th>开孔(个)</th>
    			                <th>隐形拉手(m)</th>
    			            </tr>
    			        </thead>
    			        <tbody>
			                <?php
			                    if(isset($Product['plate']) && is_array($Product['plate']) && count($Product['plate']) > 0){
			                        foreach($Product['plate'] as $key => $value){
			                            foreach ($value as $ikey => $ivalue){
			                                $value[$ikey] = '<td>'.implode('</td><td>', $ivalue).'</td>';
			                            }
			                            
			                            $Product['plate'][$key] = '<td class="my-enhance-2" colspan="9">'.$key.'</td></tr>
    			                                 <tr>'.implode('</tr><tr>', $value).'</tr></tr>
    			                                 <tr><td colspan="9">累计块数:【<span class="my-enhance-3">'.$Product['board'][$key]['amount'].'</span>】 合计面积:【<span class="my-enhance-3">'.$Product['board'][$key]['area'].'</span>】开孔:【<span class="my-enhance-3">'.$Product['board'][$key]['open_hole'].'</span>】隐形拉手:【<span class="my-enhance-3">'.$Product['board'][$key]['invisibility'].'</span>】</td>';
			                        }
			                        $Product['plate'] = '<tr>'.implode('</tr><tr>', $Product['plate']).'</tr>';
			                        echo $Product['plate'];
			                    }
			                ?>
    			        </tbody>
    			    </table>
    			</div>
    		</div>
    	</div>
	</body>
</html>