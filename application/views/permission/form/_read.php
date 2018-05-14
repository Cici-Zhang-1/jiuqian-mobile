<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月20日
 * @author Administrator
 * @version
 * @des
 */
?>
    <div class="page-line" id="formR">
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="hide input-group" data-toggle="search" data-target="#formRTable">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="formRSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="formRFunction">
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						共选中<span id="formRTableSelected" data-num="">0</span>项
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu" data-table="#formRTable">
						<li><a id="formREdit" href="javascript:void(0);" data-toggle="modal" data-target="#formRModal" data-action="<?php echo site_url('permission/form/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="javascript:void(0);" data-toggle="child" data-target="#formRTable" data-action="<?php echo site_url('permission/form/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;表单</a></li>
					</ul>
				</div>
				<button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#formRModal" data-action="<?php echo site_url('permission/form/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
				<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
				<a class="btn btn-default" data-toggle="backstage" data-target="#formRTable" href="<?php echo site_url('permission/form/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
				<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-refresh"></i>&nbsp;&nbsp;返回</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="formRTable">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</td>
						<th data-name="name">名称</th>
						<th data-name="label">Label</th>
						<th data-name="type">类型</th>
						<th data-name="url">Url</th>
						<th class="hide" data-name="func_id">功能</th>
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
	<td name="label">$value[label]</td>
	<td name="type">$value[type]</td>
	<td name="url">$value[url]</td>
	<td class="hide" name="func_id">$value[func_id]</td>
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
	<div class="modal fade" id="formRModal" tabindex="-1" role="dialog" aria-labelledby="formRModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal" id="formRForm" action="" method="post" role="form">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="formRModalLabel">编辑</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="selected" value="" />
						<input type="hidden" name="func_id" value="<?php echo $Id;?>" />
						<div class="form-group">
							<label class="control-label col-md-2">名称:</label>
							<div class="col-md-6">
								<input class="form-control" name="name" type="text" placeholder="名称" value=""/>
							</div>
						</div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Label:</label>
                            <div class="col-md-6">
                                <input class="form-control" name="label" type="text" placeholder="Label" value=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">类型:</label>
                            <div class="col-md-6">
                                <input class="form-control" name="type" type="text" placeholder="类型" value=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Url:</label>
                            <div class="col-md-6">
                                <input class="form-control" name="url" type="text" placeholder="Url" value=""/>
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
			$('div#formR').handle_page();
			$('div#formRModal').handle_modal_000();
		})(jQuery);
	</script>
