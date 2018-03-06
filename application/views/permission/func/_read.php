<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月20日
 * @author Administrator
 * @version
 * @des
 */
?>
    <div class="page-line" id="func">
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="hide input-group" data-toggle="search" data-target="#funcTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="funcSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="funcFunction">
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						共选中<span id="funcTableSelected" data-num="">0</span>项
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu" data-table="#funcTable">
						<li><a id="funcEdit" href="javascript:void(0);" data-toggle="modal" data-target="#funcModal" data-action="<?php echo site_url('permission/func/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="javascript:void(0);" data-toggle="child" data-target="#funcTable" data-action="<?php echo site_url('permission/form/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;表单</a></li>
					</ul>
				</div>
				<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#funcModal" data-action="<?php echo site_url('permission/func/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
				<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
				<a class="btn btn-default" data-toggle="backstage" data-target="#funcTable" href="<?php echo site_url('permission/func/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
				<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-refresh"></i>&nbsp;&nbsp;返回</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="funcTable">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</td>
						<th data-name="name">名称</th>
						<th data-name="url">Url</th>
						<th data-name="displayorder">显示顺序</th>
						<th data-name="img">图像</th>
						<th data-name="group_no">组号</th>
						<th data-name="toggle">Toggle</th>
						<th data-name="target">Target</th>
						<th data-name="tag">Tag</th>
						<th data-name="multiple">多选</th>
						<th class="hide" data-name="mid">Menu</th>
					</tr>
				</thead>
				<tbody>
				    <?php
				    if(isset($Error)){
				        echo <<<END
<tr class="no-data"><td colspan="9">$Error</td></tr>  
END;
				    }else{
				        $Tr = '';
				        foreach ($content as $key => $value){
							$Tr .= <<<END
<tr>
	<td><input name="fid"  type="checkbox" value="$value[fid]"/></td>
	<td name="name">$value[name]</td>
	<td name="url">$value[url]</td>
	<td name="displayorder">$value[displayorder]</td>
	<td name="img">$value[img]</td>
	<td name="group_no">$value[group_no]</td>
	<td name="toggle">$value[toggle]</td>
	<td name="target">$value[target]</td>
	<td name="tag">$value[tag]</td>
	<td name="multiple">$value[multiple]</td>
	<td class="hide" name="mid">$value[mid]</td>
</tr>
END;
				        }
				        echo $Tr;
				    }
				    ?>
				</tbody>
			</table>
		</div>
    </div>
	<div class="modal fade" id="funcModal" tabindex="-1" role="dialog" aria-labelledby="funcModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal" id="funcForm" action="" method="post" role="form">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="funcModalLabel">编辑</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="selected" value="" />
						<input type="hidden" name="mid" value="<?php echo $Id;?>" />
						<div class="form-group">
							<label class="control-label col-md-2">名称:</label>
							<div class="col-md-6">
								<input class="form-control" name="name" type="text" placeholder="名称" value=""/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">URL:</label>
							<div class="col-md-6">
								<input class="form-control" name="url" type="text" placeholder="URL" value=""/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">显示顺序:</label>
							<div class="col-md-6">
								<input class="form-control" name="displayorder" type="text" placeholder="显示顺序" value=""/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">图片:</label>
							<div class="col-md-6">
								<input class="form-control" name="img" type="text" placeholder="图片" value=""/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">组号</label>
							<div class="col-md-6">
								<input class="form-control" name="group_no" type="text" placeholder="填写组号" value=""/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">Toggle</label>
							<div class="col-md-6">
								<select class="form-control" name="toggle">
									<option value="">---</option>
									<option value="modal">Modal</option>
									<option value="backstage">Backstage</option>
									<option value="refresh">Refresh</option>
									<option value="reply">Reply</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">Target</label>
							<div class="col-md-6">
								<input class="form-control" name="target" type="text" placeholder="填写target" value=""/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">Tag</label>
							<div class="col-md-6">
								<select class="form-control" name="tag">
									<option value="button">BUTTON</option>
									<option value="a">A</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2">多选</label>
							<div class="col-md-6">
								<select class="form-control" name="multiple">
									<option value="0">FALSE</option>
									<option value="1">TRUE</option>
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
			$('div#func').handle_page();
			$('div#funcModal').handle_modal_000();
		})(jQuery);
	</script>
