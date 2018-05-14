<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月25日
 * @author Zhangcc
 * @version
 * @des
 * 销售预计
 */
 $Year = date('Y');
 $Month =date('m');
?>
	<div class="page-line" id="orderPredict">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="form-inline" id="orderPredictSearch" data-toggle="filter" data-target="#orderPredictTable">
			        <div class="form-group">
                        <label class="sr-only" for="orderPredictYear">orderPredictYear</label>
                        <select class="form-control" name="year" id="orderPredictYear"></select>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="orderPredictMonth">orderPredictMonth</label>
                        <select class="form-control" name="month" id="orderPredictMonth"></select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-default" type="submit">Go!</button>
                    </div>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="orderPredictFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderPredictTable" data-load="<?php echo site_url('order/order_predict/read');?>">
				<thead>
					<tr>
					    <th >#</th>
						<th >报价后</th>
						<th >报价后预计</th>
						<th >确认后</th>
						<th >确认后预计</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      	    <td name="name"></td>
						<td name="quote"></td>
						<td name="quote_predict"></td>
						<td name="asure"></td>
						<td name="asure_predict"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo base_url('js/dateselect.js');?>"></script>
	<script>
		(function($){
			$("#orderPredictSearch").DateSelector({
  	            ctlYearId: 'orderPredictYear',
  	            ctlMonthId: 'orderPredictMonth',
	            defYear: <?php echo $Year;?>,
                defMonth: <?php echo $Month;?>,
  	            minYear: 2015
  	    	});
			$('div#orderPredict').handle_page();
		})(jQuery);
	</script>