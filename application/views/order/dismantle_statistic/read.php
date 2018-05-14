<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月25日
 * @author Zhangcc
 * @version
 * @des
 * 自己拆单面积统计
 */

?>
	<div class="page-line" id="dismantleStatistic">
		<div class="my-tools col-md-12">
			<div class="col-md-9">
			    <div class="form-inline" id="dismantleStatisticSearch" data-toggle="filter" data-target="#dismantleStatisticTable">
			        <div class="form-group">
                        <label class="sr-only">StartDate</label>
						<input class="form-control datepicker" name="start_date" value=""  placeholder="开始日期"/>
                    </div>
                    <div class="form-group">
                        <label class="sr-only">EndDate</label>
						<input class="form-control datepicker" name="end_date" value=""  placeholder="截止日期"/>
                    </div>
					<div class="form-group">
						<label class="sr-only" for="dismantleStatisticUser">dismantleStatisticUser</label>
						<select class="form-control" name="uid" id="dismantleStatisticUser"><option value="0">员工</option></select>
					</div>
                    <div class="form-group">
                        <button class="btn btn-default" type="submit">Go!</button>
                    </div>
		    	</div>
			</div>
	  		<div class="col-md-3 text-right" id="dismantleStatisticFunction">
				<a class="btn btn-primary" href="<?php echo site_url('order/dismantle_statistic/index/detail');?>" target="_blank" id="dismantleStatisticDetailButton"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;明细</a>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="dismantleStatisticTable" data-load="<?php echo site_url('order/dismantle_statistic/read');?>">
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
			var SessionData, Uid = <?php echo $Uid;?>;
			if(!(SessionData = $.sessionStorage('user'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('manage/user/read_all');?>',
					success: function(msg){
						if(msg.error == 0){
							Item = '';
							var Content = msg.data.content, Line = '';
							for(index in Content){
								if (Uid == Content[index]['uid']) {
									Item += '<option value="'+Content[index]['uid']+'" selected>'+Content[index]['truename']+'</option>';
								}else {
									Item += '<option value="'+Content[index]['uid']+'" >'+Content[index]['truename']+'</option>';
								}
							}
							$('#dismantleStatisticUser').append(Item);
							$.sessionStorage('user', Content);
						}
					}
				});
			}else{
				Item = '';
				for(index in SessionData){
					if (Uid == SessionData[index]['uid']) {
						Item += '<option value="'+SessionData[index]['uid']+'" selected>'+SessionData[index]['truename']+'</option>';
					}else {
						Item += '<option value="'+SessionData[index]['uid']+'" >'+SessionData[index]['truename']+'</option>';
					}

				}
				$('#dismantleStatisticUser').append(Item);
			}

			$('#dismantleStatisticSearch').find('input.datepicker').each(function(i, v){
				$(this).datepicker({
					todayBtn: "linked",
					language: "zh-CN",
					orientation: "top auto",
					autoclose: true,
					todayHighlight: true
				});
			});
			$('div#dismantleStatistic').handle_page();

			$('#dismantleStatisticDetailButton').on('click', function(e){
				var $Button = $(this), Action = $(this).attr('href'),
					Data = {};
				Data['start_date'] = $('#dismantleStatisticSearch input[name="start_date"]').val(),
					Data['end_date'] = $('#dismantleStatisticSearch input[name="end_date"]').val(),
				Data['uid'] = $(('#dismantleStatisticSearch select[name="uid"]')).val();
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