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
 $Month = date('m');
 $Day = date('d');
?>
	<div class="page-line" id="everydayAsured">
		<div class="my-tools col-md-12">
			<div class="col-md-6">
			    <div class="form-inline" id="everydayAsuredSearch" data-toggle="filter" data-target="#everydayAsuredTable">
			        <div class="form-group">
                        <label class="sr-only" for="everydayAsuredYear">everydayAsuredYear</label>
                        <select class="form-control" name="year" id="everydayAsuredYear"></select>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="everydayAsuredMonth">everydayAsuredMonth</label>
                        <select class="form-control" name="month" id="everydayAsuredMonth"></select>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="everydayAsuredDay">everydayAsuredDay</label>
                        <select class="form-control" name="day" id="everydayAsuredDay"></select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-default" type="submit">Go!</button>
                    </div>
		    	</div>
			</div>
	  		<div class="col-md-6 text-right" id="everydayAsuredFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="everydayAsuredTable" data-load="<?php echo site_url('order/everyday_asured/read');?>">
				<thead>
					<tr>
					    <th >订单编号</th>
						<th >客户</th>
						<th >业主</th>
						<th>厚板</th>
						<th >金额</th>
						<th >创建人</th>
						<th >确认时间</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      	    <td name="order_num"></td>
						<td name="dealer"></td>
						<td name="owner"></td>
						<td name="total"></td>
						<td name="sum"></td>
						<td name="creator"></td>
						<td name="asure_datetime"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo base_url('js/dateselect.js');?>"></script>
	<script>
		(function($){
			$("#everydayAsuredSearch").DateSelector({
  	            ctlYearId: 'everydayAsuredYear',
  	            ctlMonthId: 'everydayAsuredMonth',
  	            ctlDayId: 'everydayAsuredDay',
	            defYear: <?php echo $Year;?>,
                defMonth: <?php echo $Month;?>,
                defDay: <?php echo $Day;?>,
  	            minYear: 2015
  	    	});
			$('div#everydayAsured').handle_page();
		})(jQuery);
	</script>