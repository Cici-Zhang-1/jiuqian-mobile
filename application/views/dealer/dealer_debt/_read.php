<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月22日
 * @author Zhangcc
 * @version
 * @des
 * 经销商对账
 */
?>
    <div class="my-print-data container-fluid">
        <div class="row hidden-print">
            <div class="col-md-12">
                <div class="col-md-offset-1 col-md-10" id="dealerDebt">
                    <form class="form-inline" action="<?php echo site_url('dealer/dealer_debt/index/read');?>" method="get">
                        <input type="hidden" name="dealer_id" value="<?php echo $Id;?>" />
                        <strong><?php echo $Info['unique_name'];?></strong>
                        <div class="form-group">
                            <label class="sr-only" for="startDate">startDate</label>
                            <input class="form-control" type="date" name="start_date" id="startDate" value="<?php echo $StartDate; ?>" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="endDate">endDate</label>
                            <input class="form-control" type="date" name="end_date" id="endDate" value="<?php echo $EndDate; ?>"/>
                        </div>
                        <button class="btn btn-primary" data-action="" type="submit" id="dealerDebtBtn" value="查询">查询</button>
                        <strong>余额:<?php echo $Info['balance'];?></strong>
                        <strong>生产欠款:<?php echo $Info['received'] - ($Info['delivered'] + $Info['produce']);?></strong>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-md-offset-1 col-md-5">
                <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="dealerDebtOrderTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>确认时间</th>
                            <th>订单</th>
                            <th>金额</th>
                            <th>业主</th>
                            <th>备注</th>
                            <th>发货日期</th>
                            <th>付款日期</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $Sum = 0;
                        if(!empty($Order) && is_array($Order)){
                            $Tr = '';
                            $Count = 1;
                            foreach ($Order as $key => $value){
                                $Tr .= <<<END
<tr>
    <td>$Count</td>
    <td>$value[sure_datetime]</td>
    <td name="order_num">$value[order_num]</td>
    <td>$value[sum]</td>
    <td>$value[owner]</td>
    <td>$value[remark]</td>
    <td>$value[delivery_datetime]</td>
    <td>$value[payed_datetime]</td>
</tr>                                
END;
                                $Count++;
                                $Sum += $value['sum'];
                            }
                            echo $Tr;
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>合计:</td>
                            <td><?php echo $Sum;?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-5">
                <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="dealerDebtFinanceTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>登记时间</th>
                            <th>账户</th>
                            <th>进账</th>
                            <th>货款</th>
                            <th>备注</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $Amount = 0;
                        $Sum = 0;
                        if(!empty($Received) && is_array($Received)){
                            $Tr = '';
                            $Count = 1;
                            foreach ($Received as $key => $value){
                                $Tr .= <<<END
<tr>
    <td>$Count</td>
    <td>$value[create_datetime]</td>
    <td>$value[name]</td>
    <td>$value[amount]</td>
    <td>$value[corresponding]</td>
    <td name="remark">$value[remark]</td>
</tr>                                
END;
                                $Count++;
                                $Amount += $value['amount'];
                                $Sum += $value['corresponding'];
                            }
                            echo $Tr;
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>合计:</td>
                            <td><?php echo $Amount;?></td>
                            <td><?php echo $Sum;?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
  	    </div>
    </div>
        <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
  	    <script src="https://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
  	    <script type="text/javascript" src="<?php echo base_url('js/dateselect.js');?>"></script>
  	    <script>
  	    (function($){
  	    	var $Table1 = $('#dealerDebtOrderTable tbody tr'),
  	    	$Table2 = $('#dealerDebtFinanceTable tbody tr');
  	    	$Table1.on('click', function(e){
  	  	    	var OrderNum = $(this).find('td[name="order_num"]').text();
  	  	  	    $Table2.removeClass('success').filter(":contains('"+OrderNum+"')").addClass('success');
  	    	});
  	    	$('#dealerDebtFinanceTable tbody tr').on('click', function(e){
  	    		var Remark = $(this).find('td[name="remark"]').text(), Reg = /[XB][\d]{10,10}/g;
  	    		$Table1.removeClass('success');
  	    		do{
  	  	  	        results = Reg.exec(Remark);
  	  	  	        if(results == null){
  	  	  	  	    	break;
  	  	  	        }else{
  	  	  	  	      	$Table1.filter(":contains('"+results[0]+"')").addClass('success');
  	  	  	        }
  	  	  	    }while(1)
  	    	});
  	    })(jQuery);
  	    </script>
	</body>
</html>