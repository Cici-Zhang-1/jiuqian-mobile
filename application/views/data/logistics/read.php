<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-5-16
 * @author ZhangCC
 * @version
 * @description  
 */
?>
	<div class="page-line" id="logistics">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
				<div class="input-group" id="logisticsSearch" data-toggle="search" data-target="#logisticsTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="logisticsSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
			<div class="col-md-offset-3 col-md-6 text-right" id="logisticsFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="logisticsTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#logisticsTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#logisticsModal" data-action="<?php echo site_url('data/logistics/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#logisticsModal" data-action="<?php echo site_url('data/logistics/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#logisticsTable" href="<?php echo site_url('data/logistics/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="logisticsTable" data-load="<?php echo site_url('data/logistics/read') ?>">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th class="td-md" data-name="name">名称</th>
						<th class="td-md" data-name="phone">联系方式</th>
						<th data-name="area-address">所在地址</th>
						<th data-name="vip">VIP</th>
						<th class="hide" data-name="aid"></th>
					</tr>
				</thead>
				<tbody>
					<tr class="loading"><td colspan="9">加载中...</td></tr>
                    <tr class="no-data"><td colspan="9">没有数据</td></tr>
                	<tr class="model">
                        <td ><input name="lid"  type="checkbox" value=""/></td>
                        <td name="name"></td>
                        <td name="phone"></td>
                        <td name="area"></td>
                        <td name="vip"></td>
                        <td class="hide" name="aid"></td>
                	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="logisticsModal" tabindex="-1" role="dialog" aria-labelledby="logisticsModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="logisticsForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="logisticsModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="名称" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >地址:</label>
			      			<div class="col-md-4">
			      				<select class="form-control" name="aid" data-filter="">
			      					<option value="0">---</option>
			      				</select>
			      			</div>
			      			<div class="col-md-4">
			      				<input class="form-control" name="address" type="text" placeholder="街道" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >联系方式:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="phone" type="text" placeholder="电话/QQ/Email/" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">vip:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="vip" type="text" placeholder="vip" value=""/>
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
			var Area = undefined;
			if(!(Area = $.sessionStorage('area'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/area/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Item = '', Content = msg.data.content;
								for(var i in Content){
									Item += '<option value="'+Content[i]['aid']+'" >'+Content[i]['area']+'</option>';
					            }
								$.sessionStorage('area', Content);
								$('#logisticsModal select[name="aid"]').append(Item);
					        }
						}
				});
			}else{
				var Item = '';
				for(var i in Area){
					Item += '<option value="'+Area[i]['aid']+'" >'+Area[i]['area']+'</option>';
	            }
				$('#logisticsModal select[name="aid"]').append(Item);
			}
			$('div#logistics').handle_page();
			$('div#logisticsModal').handle_modal_000();
		})(jQuery);
	</script>