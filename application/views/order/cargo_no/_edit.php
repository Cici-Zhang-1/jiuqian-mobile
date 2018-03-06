<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author Zhangcc
 * @version
 * @des
 * 登记货号
 */
?>
    <div class="page-line" id="cargoNo" >
        <div class="my-tools col-md-12">
            <div class="col-md-3">
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="cargoNoFunction">
	  		    <button class="btn btn-primary" data-toggle="save" data-target="#cargoNoForm" data-action="<?php echo site_url('order/cargo_no/edit');?>" type="button" value="登记"><i class="fa fa-save"></i>&nbsp;&nbsp;登记</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<form class="form-horizontal" id="cargoNoForm" action="" method="post" role="form">
			    <table class="table table-bordered">
			        <thead>
			            <tr>
			                <th>#</th>
			                <th>客户</th>
			                <th>地区</th>
			                <th>收货人</th>
			                <th>联系方式</th>
			                <th>订单编号</th>
			                <th>厨</th>
			                <th>衣</th>
			                <th>门</th>
			                <th>框</th>
			                <th>配</th>
			                <th>外</th>
			                <th>共</th>
			                <th>代收</th>
			                <th>物流名称</th>
			                <th>货号</th>
			            </tr>
			        </thead>
			        <tbody>
			            <?php 
			            if(isset($Order) && is_array($Order) && count($Order) > 0){
			                $K = 1;
			                foreach ($Order as $key => $value){
			                    $Rowspan = count($value['detail']);
			                    echo <<<END
<tr>
    <td rowspan="$Rowspan">$K<input type="hidden" name="did[$K]" value="$value[did]" /><input type="hidden" name="soid[$K]" value="$value[soid]" /></td>
    <td rowspan="$Rowspan">$value[dealer]</td>
    <td rowspan="$Rowspan">$value[delivery_address]</td>
    <td rowspan="$Rowspan">$value[delivery_linker]</td>
    <td rowspan="$Rowspan">$value[delivery_phone]</td>
END;
			                    $J = 1;
			                    foreach ($value['detail'] as $ikey => $ivalue){
			                        echo <<<END
    <td>$ivalue[order_num]<input type="hidden" name="oid[$K][$J]" value="$ivalue[oid]"</td>
    <td>$ivalue[W]</td>
    <td>$ivalue[Y]</td>
    <td>$ivalue[M]</td>
    <td>$ivalue[K]</td>
    <td>$ivalue[P]</td>
    <td>$ivalue[G]</td>
END;
			                        if(1 == $J){
			                            echo <<<END
    <td rowspan="$Rowspan">$value[amount]</td>
    <td rowspan="$Rowspan">$value[payed]</td>
    <td rowspan="$Rowspan">$value[logistics]</td>
    <td rowspan="$Rowspan"><input class="form-control" name="cargo_no[$K]" value="$value[cargo_no]" /></td>
</tr>
END;
			                        }
			                        $J++;
			                    }
			                    $K++;
			                }
			            }
			            ?>
			        </tbody>
			    </table>
        	</form>
		</div>
    </div>
<script type="text/javascript">
	(function($, window, undefined){
        $('div#cargoNo').handle_page();
	})(jQuery);
</script>