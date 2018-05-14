<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-23
 * @author ZhangCC
 * @version
 * @description  
 * 菜单管理
 */
?>
    <div class="page-line" id="classify">
        <div class="my-tools col-md-12">
            <div class="col-md-3">
				<div class="input-group" id="classifySearch" data-toggle="search" data-target="#classifyTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="classifySearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="classifyFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="classifyTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#classifyTable">
		      			<li><a href="javascript:void(0);" data-toggle="modal" data-target="#classifyModal" data-action="<?php echo site_url('data/classify/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		      			<li><a href="<?php echo site_url('data/classify/act/unable');?>" data-toggle="backstage" data-target="#classifyTable" data-multiple=true><i class="fa fa-pencil"></i>&nbsp;&nbsp;停用</a></li>
		      			<li><a href="<?php echo site_url('data/classify/act/enable');?>" data-toggle="backstage" data-target="#classifyTable" data-multiple=true><i class="fa fa-pencil"></i>&nbsp;&nbsp;起用</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#classifyModal" data-action="<?php echo site_url('data/classify/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#classifyTable" href="<?php echo site_url('data/classify/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="classifyTable" data-load="<?php echo site_url('data/classify/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected">#</th>
						<th class="td-xs" data-name="class">层级</th>
						<th data-name="name">名称</th>
						<th data-name="flag">标记</th>
						<th data-name="print_list">打印清单</th>
						<th data-name="label">打印标签</th>
						<th data-name="optimize">进优化</th>
						<th data-name="plate_name">板块名称</th>
						<th data-name="width_min">宽最小</th>
						<th data-name="width_max">宽最大</th>
						<th data-name="length_min">长最小</th>
						<th data-name="length_max">长最大</th>
						<th data-name="thick">厚度</th>
						<th data-name="edge">封边</th>
						<th data-name="slot">开槽</th>
						<th data-name="remark">备注</th>
						<th data-name="status">状态</th>
						<th class="hide" data-name="parent">父级</th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="cid"  type="checkbox" value=""/></td>
						<td name="line"><input type="hidden" name="class" value="" /></td>
						<td name="name"></td>
						<td name="flag"></td>
						<td name="print_list_flag"><input type="hidden" name="print_list" value="" /></td>
						<td name="label_flag"><input type="hidden" name="label" value="" /></td>
						<td name="optimize_flag"><input type="hidden" name="optimize" value="" /></td>
						<td name="plate_name"></td>
						<td name="width_min"></td>
						<td name="width_max"></td>
						<td name="length_min"></td>
						<td name="length_max"></td>
						<td name="thick"></td>
						<td name="edge"></td>
						<td name="slot"></td>
						<td name="remark"></td>
						<td name="status"></td>
						<td class="hide" name="parent"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
    </div>
    <div class="modal fade" id="classifyModal" tabindex="-1" role="dialog" aria-labelledby="classifyModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal" id="classifyForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="classifyModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<input type="hidden" name="class" value="0" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2">分类名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="分类名称" value=""/>
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
			      			<label class="control-label col-md-2">标记:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="flag" type="text" placeholder="标记, 优化时用于区分不同类型板块" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">打印清单:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="print_list" >
			      			        <option value="1">需要</option>
			      			        <option value="0">不需要</option>
			      			    </select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">打印标签:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="label" >
			      			        <option value="1">需要</option>
			      			        <option value="0">不需要</option>
			      			    </select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">进优化:</label>
			      			<div class="col-md-6">
			      			    <select class="form-control" name="optimize" >
			      			        <option value="1">需要</option>
			      			        <option value="0">不需要</option>
			      			    </select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">板块名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="plate_name" type="text" placeholder="板块名称" value=""/>
			      			</div>
			      		</div>
						<div class="form-group">
							<label class="control-label col-md-2">宽最小:</label>
							<div class="col-md-6">
								<input class="form-control" name="width_min" type="text" placeholder="宽最小尺寸" value=""/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">宽最大:</label>
							<div class="col-md-6">
								<input class="form-control" name="width_max" type="text" placeholder="宽最大尺寸" value=""/>
							</div>
						</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">长最小:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="length_min" type="text" placeholder="长最小尺寸" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">长最大:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="length_max" type="text" placeholder="长最大尺寸" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">厚度:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="thick" type="text" placeholder="厚度" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">封边:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="edge" type="text" placeholder="封边" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">开槽:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="slot" type="text" placeholder="开槽" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">备注:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="remark" type="text" placeholder="备注" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">状态:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="status" >
			      				   <option value="1">启用</option>
			      				   <option value="0">停用</option>
			      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">流程:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="process_list" >
			      				   <option value="0">--请选择--</option>
			      				</select>
			      				<span class="help-block"></span>
			      				<input class="form-control" name="process" type="hidden" value=""/>
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
			$.ajax({
				async: true,
				type: 'get',
				dataType: 'json',
				data: {parent: 0},
				url: '<?php echo site_url('data/classify/read');?>',
				success: function(msg){
						if(msg.error == 0){
							var Item = '', Line = '';
							var Content = msg.data.content;
							for(var i=0; i<Content.length; i++){
								if(0 == Content[i]['class']){
									Item += '<option value="'+Content[i]['cid']+'" data-class="'+Content[i]['class']+'">'+Content[i]['name']+'</option>';
								}
				            }
				            $('#classifyModal select[name="parent"]').append(Item);
				            $('#classifyModal select[name="parent"]').on('change', function(e){
								$('#classifyModal input[name="class"]').val(parseInt($(this).find('option:selected').data('class')) + 1);
					        });
				        }
					}
			});
			$.ajax({
				async: true,
				type: 'get',
				dataType: 'json',
				url: '<?php echo site_url('data/workflow/read/classify');?>',
				success: function(msg){
    					if(msg.error == 0){
    						var Content = msg.data.content, $Selected;
    						Item = '';
    						for(Index in Content){
    							Item += '<option value="'+Content[Index]['no']+'" >'+Content[Index]['name']+'</option>';
    			            }
    			            $('#classifyModal select[name="process_list"]').append(Item);
    			            $('#classifyModal select[name="process_list"]').off('change.process').on('change.process',function(e){
    			            	$Selected = $('#classifyModal select[name="process_list"] option:selected');
    			            	if(0 != $(this).val()){
    			            		$(this).next('span').append($Selected.text()+',');
            			            $(this).siblings('input:hidden').val(function(ii, vv){
                			            return vv+$Selected.val()+',';
            			            });
    			            	}
    				        }).next('span').on('dblclick', function(e){
        				        $(this).text('').next('inpug:hidden').val('');
    				        });
    			        }
					}
			});
			
			var classify_load_data_success = function(Table, Msg, Options){
				var $Table = $(Table);
		    	if(0 == Msg.error && Msg.data != undefined && Msg.data.content != undefined){
		            var $Model = $Table.find('tbody tr.model').eq(0), Content = Msg.data.content, k=0, line='';
		            var $ItemClone;
		            Options.Function.find('span#'+$Table[0].id+'Selected').text('0');
		            for(var i=0; i<Content.length; i++){
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
		                    }else{
			                    if('print_list_flag' == $(this).attr('name')){
				                    if(1 == Content[i]['print_list']){
				                    	$(this).append('<i class="fa fa-check"></i>');
				                    }else{
				                    	$(this).append('<i class="fa fa-times"></i>');
				                    }
			                    }
			                    if('label_flag' == $(this).attr('name')){
			                    	if(1 == Content[i]['label']){
				                    	$(this).append('<i class="fa fa-check"></i>');
				                    }else{
				                    	$(this).append('<i class="fa fa-times"></i>');
				                    }
			                    }
			                    if('optimize_flag' == $(this).attr('name')){
			                    	if(1 == Content[i]['optimize']){
				                    	$(this).append('<i class="fa fa-check"></i>');
				                    }else{
				                    	$(this).append('<i class="fa fa-times"></i>');
				                    }
			                    }
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
		            $Model.prevUntil('.no-data').removeClass('model');
		        }else{
		            $Table.find('.no-data').show();
		        }
			};
			
			$('div#classify').handle_page({Success:{classifyTable: classify_load_data_success}});
		    $('div#classifyModal').handle_modal_000();
		})(jQuery);
	</script>