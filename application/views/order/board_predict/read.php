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
	<div class="page-line" id="boardPredict">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="form-inline" id="boardPredictSearch" data-toggle="filter" data-target="#boardPredictTable">
			        <div class="form-group">
                        <label class="sr-only" for="boardPredictYear">boardPredictYear</label>
                        <select class="form-control" name="year" id="boardPredictYear"></select>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="boardPredictMonth">boardPredictMonth</label>
                        <select class="form-control" name="month" id="boardPredictMonth"></select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-default" type="submit">Go!</button>
                    </div>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="boardPredictFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="boardPredictTable" data-load="<?php echo site_url('order/board_predict/read');?>">
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
			$("#boardPredictSearch").DateSelector({
  	            ctlYearId: 'boardPredictYear',
  	            ctlMonthId: 'boardPredictMonth',
	            defYear: <?php echo $Year;?>,
                defMonth: <?php echo $Month;?>,
  	            minYear: 2015
  	    	});
			$('div#boardPredict').handle_page();
		})(jQuery);
	</script>