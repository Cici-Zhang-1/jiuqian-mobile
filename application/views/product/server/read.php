<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月30日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="server">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="serverSearch" data-toggle="search" data-target="#serverTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
			<div class="col-md-offset-3 col-md-6 text-right" id="serverFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="serverTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#serverTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#serverModal" data-action="<?php echo site_url('product/server/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#serverUnitPriceModal" data-action="<?php echo site_url('product/server/unit_price');?>" data-multiple=true><i class="fa fa-pencil"></i>&nbsp;&nbsp;修改单价</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#serverModal" data-action="<?php echo site_url('product/server/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#serverTable" href="<?php echo site_url('product/server/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="serverTable"  data-load="<?php echo site_url('product/server/read') ?>">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th class="td-sm" data-name="type">类别</th>
						<th class="td-md" data-name="name">名称<i class="fa fa-sort"></i></th>
						<th class="td-xs" data-name="unit">单位</th>
						<th data-name="unit_price">单价</th>
					</tr>
				</thead>
				<tbody>
					<tr class="loading"><td colspan="9">加载中...</td></tr>
                    <tr class="no-data"><td colspan="9">没有数据</td></tr>
                	<tr class="model">
                        <td ><input name="sid"  type="checkbox" value=""/></td>
                        <td name="product"><input type="hidden" name="type" /></td>
                        <td name="name"></td>
                        <td name="unit"></td>
                        <td name="unit_price"></td>
                	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="serverModal" tabindex="-1" role="dialog" aria-labelledby="serverModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="serverForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="serverModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >类别:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="type"></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="服务名称" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >单位:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="unit" type="text" placeholder="计价单位" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >单价:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="unit_price" type="text" placeholder="服务单价" value=""/>
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
	<div class="modal fade" id="serverUnitPriceModal" tabindex="-1" role="dialog" aria-labelledby="serverUnitPriceModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="serverUnitPriceForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="serverUnitPriceModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >单价:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="unit_price" type="text" placeholder="定义服务产品单价" value=""/>
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
			var  ServerType, Supplier, Item = '';
			if(!(ServerType = $.sessionStorage('server_type'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('product/product/read/f');?>',
					success: function(msg){
							if(msg.error == 0){
								var Content = msg.data.content, Line='';
								Item = '';
					            for(var i=0 in Content){
					            	Line = '|';
									for(var k=0; k < Content[i]['class']; k++){
										Line += '---';
									}
									Item += '<option value="'+Content[i]['pid']+'" >'+Line+Content[i]['name']+'</option>';
					            }
					            $('#serverModal select[name="type"]').append(Item);
					            $.sessionStorage('server_type', Content);
					        }
						}
				});
			}else{
				var Line = '';
				Item = '';
				for(var i=0 in ServerType){
	            	Line = '|';
					for(var k=0; k < ServerType[i]['class']; k++){
						Line += '---';
					}
					Item += '<option value="'+ServerType[i]['pid']+'" >'+Line+ServerType[i]['name']+'</option>';
	            }
	            $('#serverModal select[name="type"]').append(Item);
			}
			$('div#server').handle_page();
			$('div#serverModal').handle_modal_000();
			$('div#serverUnitPriceModal').handle_modal_000();
		})(jQuery);
	</script>