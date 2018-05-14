<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年9月22日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="dealerOwner">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
				<div class="input-group" id="dealerOwnerSearch" data-toggle="filter" data-target="#dealerOwnerTable">
				    <input type="hidden" name="id" value="<?php echo $Id;?>"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="dealerOwnerFilterBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="dealerOwnerFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="dealerOwnerTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#dealerOwnerTable">
		      			<li><a href="<?php echo site_url('dealer/dealer_owner/primary');?>" data-toggle="backstage" data-target="#dealerOwnerTable" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;首要</a></li>
		      			<li><a href="<?php echo site_url('dealer/dealer_owner/general');?>" data-toggle="backstage" data-target="#dealerOwnerTable" data-multiple=true><i class="fa fa-pencil"></i>&nbsp;&nbsp;普通</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#dealerOwnerModal" data-action="<?php echo site_url('dealer/dealer_owner/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" data-action="<?php echo site_url('dealer/dealer/index/read')?>" type="button" value="返回"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#dealerOwnerTable" href="<?php echo site_url('dealer/dealer_owner/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="dealerOwnerTable" >
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th class="td-sm" data-name="name">属主</th>
						<th data-name="primary">销售经理</th>
					</tr>
				</thead>
				<tbody>
				    <?php
				    if(isset($content) && is_array($content) && count($content) > 0){
				        $Tr = '';
				        foreach ($content as $key => $value){
				            $Tr .= <<<END
<tr>
	<td ><input name="doid"  type="checkbox" value="$value[doid]"/></td>
	<td name="name">$value[truename]</td>
	<td name="icon"><input name="primary" type="hidden" value="$value[primarys]"/>$value[icon]</td>
</tr>
END;
				        }
				        echo $Tr;
				    }else{
				        echo <<<END
<tr><td colspan="9">$Error</td></tr>   
END;
				    }
				    ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal fade" id="dealerOwnerModal" tabindex="-1" role="dialog" aria-labelledby="dealerOwnerModalLabel" aria-hidden="true" >
	   <div class="modal-dialog">
	       <div class="modal-content">
	           <form class="form-horizontal" id="dealerOwnerForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="dealerOwnerModalLabel">客户属主</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<input type="hidden" name="dealer_id" value="<?php echo $Id;?>" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >属主:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="uid" multiple="multiple"></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" for="primary">首要负责人:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="primary">
			      				   <option value="0">否</option>
			      				   <option value="1">是</option>
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
	<script type="text/javascript">
		(function($, window, undefined){
			var SessionData;
			if(!(SessionData = $.sessionStorage('user'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('manage/user/read_all');?>',
					success: function(msg){
							if(msg.error == 0){
								Item = '';
								var Content = msg.data.content, Line = '';
								for(index in Content){
									Item += '<option value="'+Content[index]['uid']+'" >'+Content[index]['truename']+'</option>';
								}
					            $('#dealerOwnerModal select[name="uid"]').append(Item);
					            $.sessionStorage('user', Content);
					        }
						}
				});
			}else{
				Item = '';
				for(index in SessionData){
					Item += '<option value="'+SessionData[index]['uid']+'" >'+SessionData[index]['truename']+'</option>';
				}
				$('#dealerOwnerModal select[name="uid"]').append(Item);
			}
			
		    $('div#dealerOwner').handle_page();
		    $('div#dealerOwnerModal').handle_modal_000();
		})(jQuery);
	</script>