<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月26日
 * @author Zhangcc
 * @version
 * @des
 * 产品类型
 */
?>
    <div class="page-line" id="product">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
				<div class="input-group" id="productSearch" data-toggle="search" data-target="productTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="productSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
			<div class="col-md-offset-3 col-md-6 text-right" id="productFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="productTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#productTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#productModal" data-action="<?php echo site_url('product/product/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#productModal" data-action="<?php echo site_url('product/product/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="productTable" href="<?php echo site_url('product/product/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="productTable" data-load="<?php echo site_url('product/product/read') ?>">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th class="td-xs" data-name="class">层级</th>
						<th class="td-md" data-name="name">名称</th>
						<th class="td-md" data-name="remark">备注</th>
						<th class="hide" data-name="parent">父级</th>
						<th class="hide" data-name="code">代码</th>
					</tr>
				</thead>
				<tbody>
					<tr class="loading"><td colspan="9">加载中...</td></tr>
                    <tr class="no-data"><td colspan="9">没有数据</td></tr>
                	<tr class="model">
                        <td ><input name="pid"  type="checkbox" value=""/></td>
                        <td name="line"><input type="hidden" name="class" value="" /></td>
                        <td name="name"></td>
                        <td name="remark"></td>
                        <td class="hide" name="parent"></td>
                        <td class="hide" name="code"></td>
                	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="productForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="productModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<input type="hidden" name="class" value="0" />
			      		<input type="hidden" name="code" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="名称" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">父级:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="parent" >
			      					<option value="0" data-class="-1">---</option>
			      				</select>
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
	<script>
		(function($){
			var product_load_data_success = function(Table, Msg, Options){
				var $This = $(this),$Table = $(Table);
		    	if(0 == Msg.error && Msg.data != undefined && Msg.data.content != undefined){
		            var $Model = $Table.find('tbody tr.model').eq(0), Content = Msg.data.content, Id = Msg.data.id, 
		            Class = Msg.data['class'], k=0, line='', Line='', Item='';
		            var $ItemClone;
		            Options.Function.find('span#'+$Table[0].id+'Selected').text('0');
		            for(var i=0 in Content){
		            	Line = '|';
						for(var k=0; k < Content[i]['class']; k++){
							Line += '---';
						}
						Item += '<option value="'+Content[i]['pid']+'" data-class="'+Content[i]['class']+'" data-code="'+Content[i]['code']+'">'+Line+Content[i]['name']+'</option>';
						
		                $ItemClone = $Model.clone(true);
		                $Model.before($ItemClone);
		                $ItemClone.children().each(function(ii, iv){
		                    if($(this).find('input:checkbox').length > 0){
		                        $(this).find('input:checkbox').val(function(){return Content[i][this.name];});
		                    }else if($(this).find('input:hidden').length > 0){
		                        $(this).find('input:hidden').val(function(){return Content[i][this.name];});
		                    }
		                    if($(this).attr('name') != undefined && Content[i][$(this).attr('name')] != undefined){
		                    	$(this).append(Content[i][$(this).attr('name')]);
		                    }
		                    if('line' == $(this).attr('name')){
			                    line = '|';
								for(var k=0; k < Content[i]['class']; k++){
									line += '---';
								}
								$(this).append(line);
			                }
		                });
		            }
		            $('#productModal select[name="parent"]').append(Item);
		            $('#productModal select[name="parent"]').on('change', function(e){
						$('#productModal input:hidden[name="class"]').val(parseInt($(this).find('option:selected').data('class'))+1);
						$('#productModal input:hidden[name="code"]').val($(this).find('option:selected').data('code'));
			        });
		            $Model.prevUntil('.no-data').removeClass('model');
		        }else{
		            $Table.find('.no-data').show();
		        }
			};
			$('div#product').handle_page({Success:{productTable: product_load_data_success}});
			$('div#productModal').handle_modal_000();
		})(jQuery);
	</script>