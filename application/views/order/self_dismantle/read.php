<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月25日
 * @author Zhangcc
 * @version
 * @des
 * 自己拆单面积统计
 */
 $Year = date('Y');
 $Month =date('m');
?>
	<div class="page-line" id="selfDismantle">
		<div class="my-tools col-md-12">
			<div class="col-md-6">
			    <div class="form-inline" id="selfDismantleSearch" data-toggle="filter" data-target="#selfDismantleTable">
			        <div class="form-group">
                        <label class="sr-only">StartDate</label>
						<input class="form-control datepicker" name="start_date" value=""  placeholder="开始日期"/>
                    </div>
                    <div class="form-group">
                        <label class="sr-only">EndDate</label>
						<input class="form-control datepicker" name="end_date" value=""  placeholder="截止日期"/>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-default" type="submit">Go!</button>
                    </div>
		    	</div>
			</div>
	  		<div class="col-md-6 text-right" id="selfDismantleFunction">
				<a class="btn btn-primary hide" href="<?php echo site_url('order/self_dismantle/index/detail');?>" target="_blank" id="selfDismantleDetailButton"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;明细</a>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="selfDismantleTable" data-load="<?php echo site_url('order/self_dismantle/read');?>">
				<thead>
					<tr>
					    <th >#</th>
						<th >18mm</th>
						<th >25mm</th>
						<th >36mm</th>
						<th >5mm</th>
						<th >9mm</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      	    <td name="name"></td>
						<td name="eighteen"></td>
						<td name="twentyfive"></td>
						<td name="thirtysix"></td>
						<td name="five"></td>
						<td name="nine"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
	</div>
	<script>
		(function($){
			$('#selfDismantleSearch').find('input.datepicker').each(function(i, v){
				$(this).datepicker({
					todayBtn: "linked",
					language: "zh-CN",
					orientation: "top auto",
					autoclose: true,
					todayHighlight: true
				});
			});
			$('div#selfDismantle').handle_page();

			$('#selfDismantleDetailButton').on('click', function(e){
				var $Button = $(this), Action = $(this).attr('href'),
					Data = {};
				Data['start_date'] = $('#selfDismantleSearch input[name="start_date"]').val(),
					Data['end_date'] = $('#selfDismantleSearch input[name="end_date"]').val();
				if(Action.lastIndexOf('?') >= 0){
					Action = Action.substr(0,Action.lastIndexOf('?'))+'?'+$.param(Data);
				}else{
					Action = Action+'?'+$.param(Data);
				}
				$Button.attr('href', Action);
				return true;
			});
		})(jQuery);
	</script>