<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年9月22日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="dealerLinker">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
				<div class="input-group" id="dealerLinkerSearch" data-toggle="filter" data-target="#dealerLinkerTable">
				    <input type="hidden" name="id" value="<?php echo $Id;?>"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="dealerLinkerFilterBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="dealerLinkerFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="dealerLinkerTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#dealerLinkerTable">
		      			<li><a href="javascript:void(0);" data-toggle="modal" data-target="#dealerLinkerModal" data-action="<?php echo site_url('dealer/dealer_linker/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#dealerLinkerModal" data-action="<?php echo site_url('dealer/dealer_linker/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" data-action="<?php echo site_url('dealer/dealer/index/read')?>" type="button" value="返回"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#dealerLinkerTable" href="<?php echo site_url('dealer/dealer_linker/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="dealerLinkerTable" >
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th class="td-sm" data-name="name">姓名</th>
						<th class="td-xs" data-name="primary">首要</th>
						<th data-name="mobilephone">移动电话</th>
						<th data-name="telephone">固话</th>
						<th data-name="email">Email</th>
						<th data-name="qq">QQ</th>
						<th data-name="fax">Fax</th>
						<th data-name="oid">类型</th>
					</tr>
				</thead>
				<tbody>
				    <?php
				    if(isset($DealerLinker) && is_array($DealerLinker) && count($DealerLinker) > 0){
				        $Tr = '';
				        foreach ($DealerLinker as $key => $value){
				            $Tr .= <<<END
<tr>
	<td ><input name="dlid"  type="checkbox" value="$value[dlid]"/></td>
	<td name="name">$value[name]</td>
	<td name="icon"><input name="primary" type="hidden" value="$value[primarys]"/>$value[icon]</td>
	<td name="mobilephone">$value[mobilephone]</td>
	<td name="telephone">$value[telephone]</td>
	<td name="email">$value[email]</td>
	<td name="qq">$value[qq]</td>
	<td name="fax">$value[fax]</td>
	<td name="organization"><input type="hidden" name="doid" value="$value[doid]"/>$value[organization]</td>
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
	<div class="modal fade" id="dealerLinkerModal" tabindex="-1" role="dialog" aria-labelledby="dealerLinkerModalLabel" aria-hidden="true" >
	   <div class="modal-dialog">
	       <div class="modal-content">
	           <form class="form-horizontal" id="dealerLinkerForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="dealerLinkerModalLabel">联系人</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<input type="hidden" name="dealer_id" value="<?php echo $Id;?>" />
			        	<div class="form-group">
			      			<label class="control-label col-md-2" for="name">姓名:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="姓名" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" for="primary">首要联系人:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="primary" data-filter="">
			      				   <option value="0">否</option>
			      				   <option value="1">是</option>
			      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >移动电话:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="mobilephone" type="text" placeholder="移动电话" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">固话:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="telephone" type="text" placeholder="固话" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">Email:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="email" type="text" placeholder="email" value=""/>
			      			</div>
			      		</div>
						<div class="form-group">
			      			<label class="control-label col-md-2">QQ:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="qq" type="text" placeholder="qq" value=""/>
			      			</div>
			      		</div>
						<div class="form-group">
			      			<label class="control-label col-md-2">Fax:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="fax" type="text" placeholder="fax" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >类型:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="doid" data-filter="">
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
	<script type="text/javascript">
		(function($, window, undefined){
			var SessionData;

			if(!(SessionData = $.sessionStorage('dealer_organization'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('dealer/dealer_organization/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Item = '', Line = '';
								var Content = msg.data.content;
								for(var i=0; i<Content.length; i++){
									Item += '<option value="'+Content[i]['doid']+'" >'+Content[i]['name']+'</option>';
					            }
					            $('#dealerLinkerModal select[name="doid"]').append(Item);
					            $.sessionStorage('dealer_organization', Item);
					        }
						}
				});
			}else{
				$('#dealerLinkerModal select[name="doid"]').append(SessionData);
			}
			
		    $('div#dealerLinker').handle_page();
		    $('div#dealerLinkerModal').handle_modal_000();
		})(jQuery);
	</script>