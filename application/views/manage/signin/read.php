<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-28
 * @author ZhangCC
 * @version
 * @description  
 * 订单列表
 */
 
?>
	<div class="page-line" id="signin">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="signinSearch" data-toggle="filter" data-target="#signinTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="signinFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="signinTable" data-load="<?php echo site_url('manage/signin/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >登录时间</th>
						<th >登录Ip</th>
						<th >登录主机</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="sid"  type="checkbox" value=""/></td>
						<td name="create_datetime"></td>
						<td name="ip"></td>
						<td name="host"></td>
			      	</tr>
				</tbody>
			</table>
			<div class="hide btn-group pull-right paging">
			    <p class="footnote"></p>
				<ul class="pagination">
				    <li><a href="1">首页</a></li>
					<li class=""><a href="javascript:void(0);" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
					<li><a href=""></a></li>
					<li class=""><a href="" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
					<li><a href="">尾页</a></li>
	  			</ul>
			</div>
		</div>
	</div>
	<script>
		(function($){
			$('div#signin').handle_page();
		})(jQuery);
	</script>