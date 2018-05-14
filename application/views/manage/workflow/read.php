<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-22
 * @author ZhangCC
 * @version
 * @description  
 */
?>
    <div class="page-line" id="workflow">
        <div class="my-tools col-md-12">
            <div class="col-md-3">
				<div class="input-group" id="workflowSearch" data-toggle="search" data-target="#workflowTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="workflowSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="workflowFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="workflowTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#workflowTable">
		      			<li><a id="workflowEdit" href="javascript:void(0);" data-toggle="modal" data-target="#workflowModal" data-action="<?php echo site_url('manage/workflow/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#workflowModal" data-action="<?php echo site_url('manage/workflow/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#workflowTable" href="<?php echo site_url('manage/workflow/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="workflowTable" data-load="<?php echo site_url('manage/workflow/read');?>">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th class="td-xs" data-name="class">层级</th>
						<th data-name="name">名称</th>
						<th data-name="code">代号</th>
						<th data-name="remark">备注</th>
						<th class="hide" data-name="parent">父级</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="9">加载中...</td></tr>
					<tr class="no-data"><td colspan="9">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="wid"  type="checkbox" value=""/></td>
						<td name="line"><input type="hidden" name="class" value="" /></td>
						<td name="name"></td>
						<td name="code"></td>
						<td name="remark"></td>
						<td class="hide" name="parent"></td>
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
    <div class="modal fade" id="workflowModal" tabindex="-1" role="dialog" aria-labelledby="workflowModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal" id="workflowForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="workflowModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<input type="hidden" name="class" value="0" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2">名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="名称" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">代号:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="code" type="text" placeholder="代号" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">父级:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="parent" data-filter="">
			      					<option value="0" data-class="-1">---</option>
			      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">备注:</label>
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
			var workflow_load_data_success = function(Table, Msg, Options){
				var $This = $(this),$Table = $(Table);
		    	if(0 == Msg.error && Msg.data != undefined && Msg.data.content != undefined){
		            var $Model = $Table.find('tbody tr.model').eq(0), Content = Msg.data.content, Id = Msg.data.id, 
		            k=0, line='', Line='', Item='';
		            var $ItemClone;
		            Options.Function.find('span#'+$Table[0].id+'Selected').text('0');
		            for(var i=0 in Content){
		            	Line = '|';
						for(var k=0; k < Content[i]['class']; k++){
							Line += '---';
						}
						Item += '<option value="'+Content[i]['wid']+'" data-class="'+Content[i]['class']+'" >'+Line+Content[i]['name']+'</option>';
						
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
		            $('#workflowModal select[name="parent"]').append(Item);
		            $('#workflowModal select[name="parent"]').on('change', function(e){
						$('#workflowModal input:hidden[name="class"]').val(parseInt($(this).find('option:selected').data('class'))+1);
			        });
		            $Model.prevUntil('.no-data').removeClass('model');
		        }else{
		            $Table.find('.no-data').show();
		        }
			};
			
			$('div#workflow').handle_page({Success:{workflowTable: workflow_load_data_success}});
		    $('div#workflowModal').handle_modal_000();
		})(jQuery);
	</script>