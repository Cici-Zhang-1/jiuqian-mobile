<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月30日
 * @author Zhangcc
 * @version
 * @des
 * 衣柜打孔名称
 */
?>
    <div class="page-line" id="face">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
			    <div class="input-group" id="faceSearch" data-toggle="search" data-target="#faceTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
			<div class="col-md-offset-3 col-md-6 text-right" id="faceFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="faceTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#faceTable">
		    		    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#faceModal" data-action="<?php echo site_url('data/face/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#faceModal" data-action="<?php echo site_url('data/face/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#faceTable" href="<?php echo site_url('data/face/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="faceTable"  data-load="<?php echo site_url('data/face/read') ?>">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th data-name="wardrobe_punch">打孔<i class="fa fa-sort"></i></th>
						<th data-name="wardrobe_slot">开槽<i class="fa fa-sort"></i></th>
						<th data-name="flag">标记<i class="fa fa-sort"></i></th>
					</tr>
				</thead>
				<tbody>
					<tr class="loading"><td colspan="9">加载中...</td></tr>
                    <tr class="no-data"><td colspan="9">没有数据</td></tr>
                	<tr class="model">
                        <td ><input name="fid"  type="checkbox" value=""/></td>
                        <td name="wardrobe_punch"></td>
                        <td name="wardrobe_slot"></td>
                        <td name="flag"></td>
                	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="modal fade" id="faceModal" tabindex="-1" role="dialog" aria-labelledby="faceModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="faceForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="faceModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
						<div class="form-group">
							<label class="control-label col-md-2" >开槽:</label>
							<div class="col-md-6">
								<select class="form-control" name='wardrobe_slot'><option value="">--选择开槽--</option></select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2" >打孔:</label>
							<div class="col-md-6">
								<select class="form-control" name='wardrobe_punch'><option value="">--选择打孔--</option></select>
							</div>
						</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >标记:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="flag" type="text" placeholder="标记"/>
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
			var Item, SessionData = undefined, index, A1,A2;
			if(!(SessionData = $.sessionStorage('wardrobe_punch'))){
				A1 = $.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/wardrobe_punch/read');?>',
					success: function(msg){
						if(msg.error == 0){
							var Content = msg.data.content;
							Item = '';
							for(index in Content){
								Item += '<option value="'+Content[index]['name']+'">'+Content[index]['name']+'</option>';
							}
							$.sessionStorage('wardrobe_punch', Content);
							$('#faceModal select[name="wardrobe_punch"]').append(Item);
						}
					}
				});
			}else{
				Item = '';
				for(index in SessionData){
					Item += '<option value="'+SessionData[index]['name']+'">'+SessionData[index]['name']+'</option>';
				}
				$('#faceModal select[name="wardrobe_punch"]').append(Item);
			}

			if(!(SessionData = $.sessionStorage('wardrobe_slot'))){
				A2 = $.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/wardrobe_slot/read');?>',
					success: function(msg){
						if(msg.error == 0){
							var Content = msg.data.content;
							Item = '';
							for(index in Content){
								Item += '<option value="'+Content[index]['name']+'">'+Content[index]['name']+'</option>';
							}
							$.sessionStorage('wardrobe_slot', Content);
							$('#faceModal select[name="wardrobe_slot"]').append(Item);
						}
					}
				});
			}else{
				Item = '';
				for(index in SessionData){
					Item += '<option value="'+SessionData[index]['name']+'">'+SessionData[index]['name']+'</option>';
				}
				$('#faceModal select[name="wardrobe_slot"]').append(Item);
			}
			$.when(A1,A2).done(function(V1,V2){
			});
			$('div#face').handle_page();
			$('div#faceModal').handle_modal_000();
		})(jQuery);
	</script>