<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 2015年7月4日
 * @author Zhangcc
 * @version
 * @description
 * 异形列表
 */
?>
    <div class="page-line" id="abnormity">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="abnormitySearch" data-toggle="search" data-target="#abnormityTable">
		      		<input type="text" class="form-control" name="keyword" data-toggle="search" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="abnormitySearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
			<div class="col-md-offset-3 col-md-6 text-right" id="abnormityFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="abnormityTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#abnormityTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#abnormityModal" data-action="<?php echo site_url('data/abnormity/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#abnormityModal" data-action="<?php echo site_url('data/abnormity/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#abnormityTable" href="<?php echo site_url('data/abnormity/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="abnormityTable" data-load="<?php echo site_url('data/abnormity/read') ?>">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th data-name="name">名称<i class="fa fa-sort"></i></th>
						<th data-name="print_list">打印清单<i class="fa fa-sort"></i></th>
						<th data-name="scan">扫描<i class="fa fa-sort"></i></th>
					</tr>
				</thead>
				<tbody>
					<tr class="loading"><td colspan="9">加载中...</td></tr>
                    <tr class="no-data"><td colspan="9">没有数据</td></tr>
                	<tr class="model">
                        <td ><input name="aid"  type="checkbox" value=""/></td>
                        <td name="name"></td>
                        <td name="print_list"></td>
                        <td name="scan"></td>
                	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="abnormityModal" tabindex="-1" role="dialog" aria-labelledby="abnormityModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="abnormityForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="abnormityModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="异形名称" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >打印清单:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="print_list">
			      			        <option value="1">是</option>
			      			        <option value="0">否</option>
			      			    </select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >扫描:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="scan">
			      			        <option value="1">是</option>
			      			        <option value="0">否</option>
			      			    </select>
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
			$('div#abnormity').handle_page();
			$('div#abnormityModal').handle_modal_000();
		})(jQuery);
	</script>