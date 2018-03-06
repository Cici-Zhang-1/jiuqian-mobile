<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月5日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="dismantleDetail" >
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="hide input-group" id="dismantleDetailSearch">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="dismantleDetailSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="dismantleDetailFunction">
	  		    <a href="javascript:void(0);" class="btn btn-primary" data-toggle="mtab" data-action="<?php echo site_url('order/dismantle/index/read/order_product?id='.$Id);?>"><i class="fa fa-arrows"></i>&nbsp;&nbsp;拆单</a>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
		    <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="dismantleDetailBasicTable">
		        <thead>
		            <tr>
		                <th>编号</th>
		                <th>客户</th>
		                <th>业主</th>
		                <th>对单人</th>
		                <th>对单电话</th>
		                <th>要求出厂</th>
		                <th>备注</th>
		                <th>名称</th>
		                <th>拆单备注</th>
		                <th>拆单人</th>
		                <th class="hide">oid</th>
		             </tr>
		        </thead>
				<tbody>
				    <?php
				        if(isset($Info) && is_array($Info) && count($Info) > 0){
				            echo <<<END
<tr>
     <td>$Info[order_product_num]</td>
     <td>$Info[dealer]</td>
     <td>$Info[owner]</td>
     <td>$Info[checker]</td>
     <td>$Info[checker_phone]</td>
     <td>$Info[request_outdate]</td>
     <td>$Info[remark]</td>
     <td>$Info[product]</td>
     <td>$Info[order_product_remark]</td>
     <td>$Info[dismantler]</td>
     <td class="hide">$Info[oid]</td>
 </tr>
END;
				        }
				    ?>
				</tbody>
			</table>
		</div>
		<div class="col-md-12">
	        <table class="table table-bordered table-striped table-hover table-responsive table-condensed page-linker" id="dismantleDetailTable">
				<tbody>
			    <?php 
                    if(isset($Detail) && is_array($Detail) && count($Detail) > 0){
                        if(isset($Info['code'])){
                            $Id = 0;
                            switch ($Info['code']){
                                case 'W':
                                case 'Y':
                                    echo <<<END
<tr><td>#</td><td>板材</td><td>柜体编号</td><td>柜体名称</td><td>板块名称</td>
<td>长</td><td>宽</td><td>厚</td><td>开槽</td><td>打孔</td><td>封边</td><td>备注</td></tr>
END;
                                foreach ($Detail as $key => $value){
                                    $Id++;
                                    echo <<<END
<tr><td>$Id</td><td>$value[good]</td><td>$value[cubicle_num]</td><td>$value[cubicle_name]</td>
<td>$value[plate_name]</td><td>$value[length]</td><td>$value[width]</td><td>$value[thick]</td>
<td>$value[slot]</td><td>$value[punch]</td><td>$value[edge]</td><td>$value[remark]</td></tr>
END;
                                }
                                    break;
                                case 'M':
                                    echo <<<END
<tr><td>#</td><td>板材</td><td>长</td>
<td>宽</td><td>厚</td><td>打孔</td><td>拉手</td><td>打孔个数</td><td>隐形拉手</td></tr>
END;
                                    foreach ($Detail as $key => $value){
                                        $Id++;
                                        echo <<<END
<tr><td>$Id</td><td>$value[good]</td><td>$value[length]</td>
<td>$value[width]</td><td>$value[thick]</td><td>$value[punch]</td>
<td>$value[handle]</td><td>$value[open_hole]</td><td>$value[invisibility]</td>
<td>$value[remark]</td></tr>
END;
                                    }
                                    break;
                                case 'K':
                                    echo <<<END
<tr><td>#</td><td>板材</td><td>名称</td><td>长</td>
<td>宽</td><td>厚</td><td>打孔</td></tr>
END;
                                    foreach ($Detail as $key => $value){
                                        $Id++;
                                        echo <<<END
<tr><td>$Id</td><td>$value[good]</td><td>$value[wood_name]</td><td>$value[core]</td>
<td>$value[length]</td><td>$value[width]</td><td>$value[thick]</td><td>$value[punch]</td>
<td>$value[remark]</td></tr>
END;
                                    }
                                    break;
                                case 'P':
                                    echo <<<END
<tr><td>#</td><td>类别</td><td>名称</td><td>单位</td><td>数量</td></tr>
END;
                                    foreach ($Detail as $key => $value){
                                        $Id++;
                                        echo <<<END
<tr><td>$Id</td><td>$value[type]</td><td>$value[name]</td><td>$value[unit]</td><td>$value[amount]</td></tr>
END;
                                    }
                                    break;
                                case 'G':
                                    echo <<<END
<tr><td>#</td><td>类别</td><td>名称</td><td>规格</td><td>单位</td><td>数量</td></tr>
END;
                                    foreach ($Detail as $key => $value){
                                        $Id++;
                                        echo <<<END
<tr><td>$Id</td><td>$value[type]</td><td>$value[name]</td><td>$value[spec]</td><td>$value[unit]</td><td>$value[amount]</td></tr>
END;
                                    }
                                    break;
                                case 'F':
                                    echo <<<END
<tr><td>#</td><td>类别</td><td>名称</td><td>单位</td><td>数量</td></tr>
END;
                                    foreach ($Detail as $key => $value){
                                        $Id++;
                                        echo <<<END
<tr><td>$Id</td><td>$value[type]</td><td>$value[name]</td><td>$value[unit]</td><td>$value[amount]</td></tr>
END;
                                    }
                                    break;
                            }
                        }
                    }
    			?>
    			</tbody>
			</table>
	    </div>
    </div>
	<script type="text/javascript">
		(function($, window, undefined){
			$('#dismantleDetail').handle_page();
		})(jQuery);
	</script>