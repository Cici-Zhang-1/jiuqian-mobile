<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月26日
 * @author Zhangcc
 * @version
 * @des
 * 板材
 */
?>
    <div class="page-line" id="board">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
				<div class="input-group" id="boardSearch" data-toggle="search" data-target="#boardTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
			<div class="col-md-offset-3 col-md-6 text-right" id="boardFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="boardTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#boardTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#boardModal" data-action="<?php echo site_url('product/board/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#boardUnitPriceModal" data-action="<?php echo site_url('product/board/unit_price');?>" data-multiple=true><i class="fa fa-pencil"></i>&nbsp;&nbsp;修改单价</a></li>
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#boardAmountModal" data-action="<?php echo site_url('product/board/edit_amount');?>" data-multiple=true><i class="fa fa-pencil"></i>&nbsp;&nbsp;修改数量</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#boardModal" data-action="<?php echo site_url('product/board/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#boardTable" href="<?php echo site_url('product/board/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="boardTable" data-load="<?php echo site_url('product/board/read') ?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th class="td-md" data-name="name">名称<i class="fa fa-sort"></i></th>
						<th data-name="length">长度</th>
						<th data-name="width">宽度</th>
						<th >环保级别</th>
						<th >供应商</th>
						<th data-name="remark">备注</th>
						<th data-name="unit_price">单价</th>
						<th data-name="amount">数量</th>
						<th class="hide">厚度</th>
						<th class="hide">颜色</th>
						<th class="hide">材质</th>
						<th class="hide" data-name="thick_id">厚度</th>
						<th class="hide" data-name="color_id">颜色</th>
						<th class="hide" data-name="nature_id">材质</th>
						<th class="hide" data-name="class_id">环保级别</th>
						<th class="hide" data-name="supplier_id">供应商</th>
					</tr>
				</thead>
				<tbody>
					<tr class="loading"><td colspan="15">加载中...</td></tr>
                    <tr class="no-data"><td colspan="15">没有数据</td></tr>
                	<tr class="model">
                        <td ><input name="bid"  type="checkbox" value=""/></td>
                        <td name="name"></td>
                        <td name="length"></td>
                        <td name="width"></td>
                        <td name="class"></td>
                        <td name="supplier"></td>
                        <td name="remark"></td>
                        <td name="unit_price"></td>
                        <td name="amount"></td>
                        <td class="hide" name="thick"></td>
                        <td class="hide" name="color"></td>
                        <td class="hide" name="nature"></td>
                        <td class="hide" name="thick_id"></td>
                        <td class="hide" name="color_id"></td>
                        <td class="hide" name="nature_id"></td>
                        <td class="hide" name="class_id"></td>
                        <td class="hide" name="supplier_id"></td>
                	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="boardModal" tabindex="-1" role="dialog" aria-labelledby="boardModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="boardForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="boardModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="可以自定义或自动化命名(厚度+材质+颜色+供应商)" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >长度:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="length" type="text" placeholder="长度" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >宽度:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="width" type="text" placeholder="宽度" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >厚度:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="thick_id"></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >板材颜色:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control input-lg" name="color_id" multiple="multiple"></select>
			      			    <span class="help-block"></span>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >板材材质:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control input-lg" name="nature_id"></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >环保等级:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control input-lg" name="class_id"></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >供应商:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control input-lg" name="supplier_id"></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >备注:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="remark" type="text" placeholder="备注" value=""/>
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
	<div class="modal fade" id="boardUnitPriceModal" tabindex="-1" role="dialog" aria-labelledby="boardUnitPriceModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="boardUnitPriceForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="boardUnitPriceModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >单价:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="unit_price" type="text" placeholder="定义板块的单价" value=""/>
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
	<div class="modal fade" id="boardAmountModal" tabindex="-1" role="dialog" aria-labelledby="boardAmountModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="boardAmountForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="boardAmountModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >数量:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="amount" type="text" placeholder="更改板块数量" value=""/>
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
			var SessionData;
			if(!(SessionData = $.sessionStorage('board_thick'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/board_thick/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Item = '', Content = msg.data.content;
								for(var i in Content){
									Item += '<option value="'+Content[i]['btid']+'-'+Content[i]['name']+'" >'+Content[i]['name']+'</option>';
								};
					            $('#boardModal select[name="thick_id"]').append(Item);
					            $.sessionStorage('board_thick', Item);
					        }
						}
				});
			}else{
				$('#boardModal select[name="thick_id"]').append(SessionData);
			}

			if(!(SessionData = $.sessionStorage('board_color'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/board_color/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Item = '', Content = msg.data.content;
								for(var i in Content){
									Item += '<option value="'+Content[i]['bcid']+'-'+Content[i]['name']+'" >'+Content[i]['name']+'</option>';
								};
					            $('#boardModal select[name="color_id"]').append(Item);
					            $('#boardModal select[name="color_id"]').off('change.color').on('change.color',function(e){
	    							$(this).next('span').text($.map($('#boardModal select[name="color_id"] option:selected'),function(s){return $(s).text();}).join(','));
	    				        });
					            $.sessionStorage('board_color', Item);
					        }
						}
				});
			}else{
				$('#boardModal select[name="color_id"]').append(SessionData);
			}

			if(!(SessionData = $.sessionStorage('board_class'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/board_class/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Item = '', Content = msg.data.content;
								for(var i in Content){
									Item += '<option value="'+Content[i]['bcid']+'-'+Content[i]['name']+'" >'+Content[i]['name']+'</option>';
								};
					            $('#boardModal select[name="class_id"]').append(Item);
					            $.sessionStorage('board_class', Item);
					        }
						}
				});
			}else{
				$('#boardModal select[name="class_id"]').append(SessionData);
			}

			if(!(SessioinData = $.sessionStorage('board_nature'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/board_nature/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Item = '', Content = msg.data.content;
								for(var i in Content){
									Item += '<option value="'+Content[i]['bnid']+'-'+Content[i]['name']+'" >'+Content[i]['name']+'</option>';
								};
					            $('#boardModal select[name="nature_id"]').append(Item);
					            $.sessionStorage('board_nature', Item);
					        }
						}
				});
			}else{
				$('#boardModal select[name="nature_id"]').append(SessioinData);
			}

			if(!(SessionData = $.sessionStorage('supplier'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('supplier/supplier/read/all');?>',
					success: function(msg){
							if(msg.error == 0){
								var Item = '', Content = msg.data.content;
								for(var i in Content){
									Item += '<option value="'+Content[i]['sid']+'-'+Content[i]['code']+'" >'+Content[i]['name']+Content[i]['code']+'</option>';
								};
					            $('#boardModal select[name="supplier_id"]').append(Item).next('input:hidden').val($.map($('#boardModal select[name="supplier_id"] option:selected'),function(s){return $(s).data('code')}).join(','));
					            $.sessionStorage('supplier', Content);
					        }
						}
				});
			}else{
				var Item = '';
				for(var i=0 in SessionData){
					Item += '<option value="'+SessionData[i]['sid']+'-'+SessionData[i]['code']+'" >'+SessionData[i]['name']+SessionData[i]['code']+'</option>';
	            }
				$('#boardModal select[name="supplier_id"]').append(Item).next('input:hidden').val($.map($('#boardModal select[name="supplier_id"] option:selected'),function(s){return $(s).data('code')}).join(','));
			}
			
			$('div#board').handle_page();
			$('div#boardModal').handle_modal_000();
			$('div#boardUnitPriceModal').handle_modal_000();
			$('div#boardAmountModal').handle_modal_000();
		})(jQuery);
	</script>