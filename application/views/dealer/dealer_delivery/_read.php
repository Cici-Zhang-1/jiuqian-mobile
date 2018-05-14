<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月19日
 * @author Zhangcc  
 * @version
 * @des
 */
?>
    <div class="page-line" id="dealerDelivery">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
				<div class="input-group" id="dealerDeliverySearch" data-toggle="filter" data-target="#dealerDeliveryTable">
				    <input type="hidden" name="id" value="<?php echo $Id;?>"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="dealerDeliveryFilterBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="dealerDeliveryFunction">
	  			<div class="btn-group" role="group">
		    		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			共选中<span id="dealerDeliveryTableSelected" data-num="">0</span>项
		      			<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" data-table="#dealerDeliveryTable">
		      			<li><a href="javascript:void(0);" data-toggle="modal" data-target="#dealerDeliveryModal" data-action="<?php echo site_url('dealer/dealer_delivery/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
		    		</ul>
		  		</div>
	  			<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#dealerDeliveryModal" data-action="<?php echo site_url('dealer/dealer_delivery/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" data-action="<?php echo site_url('dealer/dealer/index/read')?>" type="button" value="返回"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回</button>
	  			<a class="btn btn-default" data-toggle="backstage" data-toggle="#dealerDeliveryTable" href="<?php echo site_url('dealer/dealer_delivery/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
	  		</div>
		</div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="dealerDeliveryTable">
				<thead>
					<tr>
                        <th class="td-xs" data-name="selected">#</th>
						<th >地区</th>
						<th data-name="delivery_address">地址</th>
						<th >出厂方式</th>
						<th >要求物流</th>
						<th data-name="delivery_linker">联系人</th>
						<th data-name="delivery_phone">联系方式</th>
						<th class="td-xs" >默认</th>
						<th class="hide" data-name="daid"></th>
						<th class="hide" data-name="lid"></th>
						<th class="hide" data-name="omid"></th>
						<th class="hide" data-name="default"></th>
					</tr>
				</thead>
				<tbody>
				    <?php
				    if(isset($DealerDelivery) && is_array($DealerDelivery) && count($DealerDelivery) > 0){
				        $Tr = '';
				        foreach ($DealerDelivery as $key => $value){
				            $Tr .= <<<END
<tr>
	<td ><input name="ddid"  type="checkbox" value="$value[ddid]"/></td>
	<td>$value[area]</td>
	<td>$value[delivery_address]</td>
	<td>$value[out_method]</td>
	<td>$value[logistics]</td>
	<td >$value[delivery_linker]</td>
	<td >$value[delivery_phone]</td>
	<td >$value[icon]</td>
	<td class="hide">$value[daid]</td>
	<td class="hide">$value[lid]</td>
	<td class="hide">$value[omid]</td>
	<td class="hide">$value[defaults]</td>
</tr>
END;
				        }
				        echo $Tr;
				    }else{
				        echo <<<END
<tr><td colspan="15">$Error</td></tr>   
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
	<div class="modal fade" id="dealerDeliveryModal" tabindex="-1" role="dialog" aria-labelledby="dealerDeliveryModalLabel" aria-hidden="true" >
	   <div class="modal-dialog">
	       <div class="modal-content">
	           <form class="form-horizontal" id="dealerDeliveryForm" action="" method="post" role="form">
					<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        			<h4 class="modal-title" id="dealerDeliveryModalLabel">联系人</h4>
	      			</div>
			      	<div class="modal-body">
			      		<input type="hidden" name="selected" value="" />
			      		<input type="hidden" name="dealer_id" value="<?php echo $Id;?>" />
			        	<div class="form-group">
			      			<label class="control-label col-md-2" >所在地址:</label>
			      			<div class="col-md-4">
			      				<select class="form-control" name="daid" data-filter="">
			      					<option value="0">---</option>
			      				</select>
			      			</div>
			      			<div class="col-md-4">
			      				<input class="form-control" name="delivery_address" type="text" placeholder="街道" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >出厂方式:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="omid" >
			      				   <option value="0">---</option>
			      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >要求物流:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="lid" >
			      				   <option value="0">---</option>
			      				</select>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >联系人:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="delivery_linker" type="text" placeholder="联系人" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2" >联系方式:</label>
			      			<div class="col-md-6">
			      				<input class="form-control" name="delivery_phone" type="text" placeholder="联系方式" value=""/>
			      			</div>
			      		</div>
			      		<div class="form-group">
			      			<label class="control-label col-md-2">默认发货:</label>
			      			<div class="col-md-6">
			      				<select class="form-control" name="default" >
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
								$('#dealerDeliveryModal select[name="daid"]').append(Item);
					        }
						}
				});
			}else{
				var Item = '';
				for(var i in Area){
					Item += '<option value="'+Area[i]['aid']+'" >'+Area[i]['area']+'</option>';
	            }
				$('#dealerDeliveryModal select[name="daid"]').append(Item);
			}

			if(!(SessionData = $.sessionStorage('logistics'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/logistics/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Item = '', Line = '';
								var Content = msg.data.content;
								for(var i in Content){
									Item += '<option value="'+Content[i]['lid']+'" >'+Content[i]['name']+'</option>';
								}
								$('#dealerDeliveryModal select[name="lid"]').append(Item);
					            $.sessionStorage('logistics', Content);
					        }
						}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['lid']+'" >'+SessionData[i]['name']+'</option>';
				}
				$('#dealerDeliveryModal select[name="lid"]').append(Item);
			}

			if(!(SessionData = $.sessionStorage('out_method'))){
				$.ajax({
					async: true,
					type: 'get',
					dataType: 'json',
					url: '<?php echo site_url('data/out_method/read');?>',
					success: function(msg){
							if(msg.error == 0){
								var Item = '', Line = '';
								var Content = msg.data.content;
								for(var i in Content){
									Item += '<option value="'+Content[i]['omid']+'" >'+Content[i]['name']+'</option>';
								}
								$('#dealerDeliveryModal select[name="omid"]').append(Item);
					            $.sessionStorage('out_method', Content);
					        }
						}
				});
			}else{
				var Item = '';
				for(var i in SessionData){
					Item += '<option value="'+SessionData[i]['omid']+'" >'+SessionData[i]['name']+'</option>';
				}
				$('#dealerDeliveryModal select[name="omid"]').append(Item);
			}
		    $('div#dealerDelivery').handle_page();
		    $('div#dealerDeliveryModal').handle_modal_000();
		})(jQuery);
	</script>