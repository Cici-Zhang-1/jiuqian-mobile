<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-25
 * @author ZhangCC
 * @version
 * @description
 * 经销商
 */
?>
	<div class="page-line" id="dealer">
		<div class="my-tools col-md-12">
		    <div class="col-md-3">
				<div class="input-group" id="dealerSearch" data-toggle="filter" data-target="#dealerTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="dealerFilterBtn" type="submit">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="dealerFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="dealerTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#dealerTable">
		      			<li><a href="javascript:void(0);" data-toggle="modal" data-target="#dealerModal" data-action="<?php echo site_url('dealer/dealer/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a href="javascript:void(0);" data-toggle="child" data-target="#dealerTable" data-action="<?php echo site_url('dealer/dealer_linker/index/read');?>" data-multiple=false><i class="fa fa-user"></i>&nbsp;&nbsp;联系人</a></li>
		      			<li><a href="javascript:void(0);" data-toggle="child" data-target="#dealerTable" data-action="<?php echo site_url('dealer/dealer_delivery/index/read');?>" data-multiple=false><i class="fa fa-truck"></i>&nbsp;&nbsp;收货</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a href="javascript:void(0);" data-toggle="child" data-target="#dealerTable" data-action="<?php echo site_url('dealer/dealer_owner/index/read');?>" data-multiple=false><i class="fa fa-hand-pointer-o"></i>&nbsp;&nbsp;属主</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a href="<?php echo site_url('dealer/dealer_trace/index/read');?>" data-toggle="child" data-target="#dealerTable" data-multiple=false><i class="fa fa-hand-o-right"></i>&nbsp;&nbsp;跟踪</a></li>
		      			<li role="separator" class="divider"></li>
		      			<li><a href="<?php echo site_url('dealer/dealer_debt/index/read')?>" data-toggle="blank" data-target="#dealerTable" target="_blank" data-multiple=false><i class="fa fa-money"></i>&nbsp;&nbsp;对账</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#dealerAddModal" data-action="<?php echo site_url('dealer/dealer/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-target="#dealerTable" href="<?php echo site_url('dealer/dealer/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12" >
			<table class="table table-bordered table-hover table-responsive table-condensed" id="dealerTable" data-load="<?php echo site_url('dealer/dealer/read');?>">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th data-name="des">名称</th>
						<th data-name="shop">店名</th>
						<th>类型</th>
						<th data-name="area-address">地址</th>
						<th data-name="linker">首要联系人</th>
						<th data-name="way">联系方式</th>
						<th >支付条款</th>
						<th >等待生产</th>
						<th >正在生产</th>
						<th >账户余额</th>
						<th >发货金额</th>
						<th >收款金额</th>
						<th data-name="remark">备注</th>
						<th >销售经理</th>
						<th class="hide" data-name="aid"></th>
						<th class="hide" data-name="dcid"></th>
						<th class="hide" data-name="pid"></th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td><input name="did"  type="checkbox" value=""/></td>
						<td name="des"></td>
						<td name="shop"></td>
						<td name="category"></td>
						<td name="area"></td>
						<td name="linker"></td>
						<td name="way"></td>
						<td name="payterms"></td>
						<td name="debt1"></td>
						<td name="debt2"></td>
						<td name="balance"></td>
						<td name="deliveried"></td>
						<td name="received"></td>
						<td name="remark"></td>
						<td name="owner"></td>
						<td class="hide" name="aid"></td>
						<td class="hide" name="dcid"></td>
						<td class="hide" name="pid"></td>
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
	<div class="modal fade" id="dealerModal" tabindex="-1" role="dialog" aria-labelledby="dealerModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form class="form-horizontal" id="dealerForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="dealerModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" for="des">名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="des" id="des" type="text" placeholder="名称" value=""/>
			      			</div>
			      		</div>
						<div class="form-group">
							<label class="control-label col-md-2" for="shop">店名:</label>
							<div class="col-md-6">
								<input class="form-control" name="shop" id="shop" type="text" placeholder="店名" value=""/>
							</div>
						</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" for="aid">所在地址:</label>
			      			<div class="col-md-4">
			      				<select class="form-control" name="aid" id='aid' data-filter="">
			      					<option value="0">---</option>
			      				</select>
			      			</div>
			      			<div class="col-md-4">
			      				<input class="form-control" name="address" id="address" type="text" placeholder="街道" value=""/>
			      			</div>
			      		</div>
						<div class="form-group">
			      			<label class="control-label col-md-2" for="dcid">类型:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="dcid" data-filter=""></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >支付条款:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="pid" data-filter="">
			      					<option value="0">---</option>
			      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" for="remark">备注:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="remark" id="remark" type="text" placeholder="备注" value=""/>
			      			</div>
			      		</div>
						<div class="form-group">
							<label class="control-label col-md-2" for="password">重置密码:</label>
							<div class="col-md-6">
								<input class="form-control" name="password" id="password" type="password" placeholder="密码" autocomplete="off"/>
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
	<div class="modal fade" id="dealerAddModal" tabindex="-1" role="dialog" aria-labelledby="dealerAddModalLabel" aria-hidden="true" >
	   <div class="modal-dialog modal-lg">
	       <div class="modal-content">
	           <form class="form-horizontal" id="dealerAddForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="dealerAddModalLabel">编辑</h4>
	      			</div>
			      	<div class="modal-body">
			      	    <div class="form-group">
			      			<label class="control-label col-md-2" >名称:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="des" type="text" placeholder="经销商名称" value=""/>
			      			</div>
			      		</div>
						<div class="form-group">
							<label class="control-label col-md-2">店名:</label>
							<div class="col-md-6">
								<input class="form-control" name="shop" type="text" placeholder="店名" value=""/>
							</div>
						</div>
			            <div class="form-group">
			      			<label class="control-label col-md-2" >所在地址:</label>
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
			      			<label class="control-label col-md-2" >类型:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="dcid"></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >支付条款:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="pid" data-filter="">
			      					<option value="0">---</option>
			      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >备注:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="remark"  type="text" placeholder="备注" value=""/>
			      			</div>
			      		</div>
			      		<hr />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >收货人:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="delivery_linker" type="text" placeholder="收货人" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >收货电话:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="delivery_phone" type="text" placeholder="收货电话" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >收货地址:</label>
			      			<div class="col-md-4">
			      				<select class="form-control" name="daid" >
			      					<option value="0">---</option>
			      				</select>
			      			</div>
			      			<div class="col-md-4">
			      				<input class="form-control" name="delivery_address" type="text" placeholder="街道" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >要求物流:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="lid" ><option value="0">---</option></select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >出厂方式:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="omid" ></select>
			      			</div>
			      		</div>
			      		<hr />
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >联系人姓名:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="name" type="text" placeholder="联系人姓名" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" for="name">移动电话:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="mobilephone" type="text" placeholder="移动电话" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >固话:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="telephone" type="text" placeholder="固话" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >Email:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="email" type="text" placeholder="email" value=""/>
			      			</div>
			      		</div>
						<div class="form-group">
			      			<label class="control-label col-md-2" >QQ:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="qq" type="text" placeholder="qq" value=""/>
			      			</div>
			      		</div>
						<div class="form-group">
			      			<label class="control-label col-md-2" >Fax:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="fax" type="text" placeholder="fax" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >员工类型:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="doid" data-filter="">
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
			var SessionData, Item, index;
			if(!(SessionData = $.sessionStorage('dealer_category'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('dealer/dealer_category/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Content = msg.data.content;
	    						Item = '';
								for(index in Content){
									Item += '<option value="'+Content[index]['dcid']+'" >'+Content[index]['name']+'</option>';
					            }
					            $('#dealerModal select[name="dcid"]').append(Item);
					            $('#dealerAddModal select[name="dcid"]').append(Item);
					            $.sessionStorage('dealer_category', Item);
					        }
						}
				});
			}else{
				$('#dealerModal select[name="dcid"]').append(SessionData);
	            $('#dealerAddModal select[name="dcid"]').append(SessionData);
			}

			if(!(SessionData = $.sessionStorage('payterms_pid'))){
				$.ajax({
    				async: true,
    				type: 'get',
    				dataType: 'json',
    				url: '<?php echo site_url('dealer/payterms/read');?>',
    				success: function(msg){
    						if(msg.error == 0){
    							var Content = msg.data.content;
	    						Item = '';
    							for(index in Content){
    								Item += '<option value="'+Content[index]['pid']+'" >'+Content[index]['name']+'</option>';
    				            }
    				            $('#dealerModal select[name="pid"]').append(Item);
    				            $('#dealerAddModal select[name="pid"]').append(Item);
    				            $.sessionStorage('payterms_pid', Item);
    				        }
    					}
				});
			}else{
				$('#dealerModal select[name="pid"]').append(SessionData);
	            $('#dealerAddModal select[name="pid"]').append(SessionData);
			}
			if(!(SessionData = $.sessionStorage('area'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/area/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Content = msg.data.content;
	    						Item = '';
								for(index in Content){
									Item += '<option value="'+Content[index]['aid']+'" >'+Content[index]['area']+'</option>';
					            }
								$.sessionStorage('area', Content);
								$('#dealerModal select[name="aid"]').append(Item);
								$('#dealerAddModal select[name="aid"]').append(Item);
								$('#dealerAddModal select[name="daid"]').append(Item);
					        }
						}
				});
			}else{
				Item = '';
				for(index in SessionData){
					Item += '<option value="'+SessionData[index]['aid']+'" >'+SessionData[index]['area']+'</option>';
	            }
				$('#dealerModal select[name="aid"]').append(Item);
				$('#dealerAddModal select[name="aid"]').append(Item);
				$('#dealerAddModal select[name="daid"]').append(Item);
			}

			if(!(SessionData = $.sessionStorage('dealer_organization'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('dealer/dealer_organization/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Content = msg.data.content;
	    						Item = '';
								for(index in Content){
									Item += '<option value="'+Content[index]['doid']+'" >'+Content[index]['name']+'</option>';
					            }
					            $('#dealerAddModal select[name="doid"]').append(Item);
					            $.sessionStorage('dealer_organization', Item);
					        }
						}
				});
			}else{
				$('#dealerAddModal select[name="doid"]').append(SessionData);
			}

			if(!(SessionData = $.sessionStorage('logistics'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/logistics/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Content = msg.data.content, Line = '';
	    						Item = '';
								for(index in Content){
									Item += '<option value="'+Content[index]['lid']+'" >'+Content[index]['name']+'</option>';
								}
					            $('#dealerAddModal select[name="lid"]').append(Item);
					            $.sessionStorage('logistics', Content);
					        }
						}
				});
			}else{
				Item = '';
				for(index in SessionData){
					Item += '<option value="'+SessionData[index]['lid']+'" >'+SessionData[index]['name']+'</option>';
				}
				$('#dealerAddModal select[name="lid"]').append(Item);
			}

			if(!(SessionData = $.sessionStorage('out_method'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/out_method/read');?>',
					success: function(msg){
							if(msg.error == 0){
								Item = '';
								var Content = msg.data.content, Line = '';
								for(index in Content){
									Item += '<option value="'+Content[index]['omid']+'" >'+Content[index]['name']+'</option>';
								}
					            $('#dealerAddModal select[name="omid"]').append(Item);
					            $.sessionStorage('out_method', Content);
					        }
						}
				});
			}else{
				Item = '';
				for(index in SessionData){
					Item += '<option value="'+SessionData[index]['omid']+'" >'+SessionData[index]['name']+'</option>';
				}
				$('#dealerAddModal select[name="omid"]').append(Item);
			}

		    $('div#dealer').handle_page();
		    $('div#dealerModal').handle_modal_000();
		    $('div#dealerAddModal').handle_modal_000();
		    $('div#dealerOwnerModal').handle_modal_000();
		})(jQuery);
	</script>