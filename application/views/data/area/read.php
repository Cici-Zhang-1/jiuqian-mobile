<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月16日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="area">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
				<div class="input-group" id="areaSearch" data-toggle="search" data-toggle="#areaTable">
		      		<input type="text" class="form-control" data-toggle="serach" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="areaSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
			<div class="col-md-offset-3 col-md-6 text-right" id="areaFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="areaTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#areaTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#areaModal" data-action="<?php echo site_url('data/area/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#areaModal" data-action="<?php echo site_url('data/area/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-toggle="#areaTable" href="<?php echo site_url('data/area/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="areaTable" data-load="<?php echo site_url('data/area/read') ?>">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th >地区</th>
						<th class="hide" data-name="province">省</th>
						<th class="hide" data-name="city">市</th>
						<th class="hide" data-name="county">县</th>
					</tr>
				</thead>
				<tbody>
					<tr class="loading"><td colspan="2">加载中...</td></tr>
                    <tr class="no-data"><td colspan="2">没有数据</td></tr>
                	<tr class="model">
                        <td ><input name="aid"  type="checkbox" value=""/></td>
                        <td name="area"></td>
                        <td class="hide" name="province"></td>
						<td class="hide" name="city"></td>
						<td class="hide" name="county"></td>
                	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="areaModal" tabindex="-1" role="dialog" aria-labelledby="areaModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="areaForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="areaModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >省:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="province" type="text" placeholder="省" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >市:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="city" type="text" placeholder="市" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >县/区:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="county" type="text" placeholder="县/区" value=""/>
			      			</div>
			      		</div>
			      		<div class="alert alert-danger alert-dismissible fade in serverError" role="alert"></div>
			      	</div>
			      	<div class="modal-footer">
			        	<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			        	<button type="submit" class="btn btn-primary" data-save="ajax.modal">保存</button>
			      	</div>
				</form>
    		</div>
  		</div>
	</div>
	<script>
		(function($){
			$('div#area').handle_page();
			$('div#areaModal').handle_modal_000();
		})(jQuery);
	</script>