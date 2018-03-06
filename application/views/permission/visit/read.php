<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/12
 * Time: 16:40
 *
 * Desc:
 */
?>
<div class="page-line" id="visit" data-load="<?php echo site_url('permission/visit/read') ?>">
    <div class="my-tools col-md-12">
        <div class="col-md-3">
            <div class="input-group" id="visitSearch" data-toggle="search" data-target="#visitTable">
                <input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="visitSearchBtn" type="button">Go!</button>
		      		</span>
            </div>
        </div>
        <div class="col-md-offset-3 col-md-6 text-right" id="visitFunction">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    共选中<span id="visitTableSelected" data-num="">0</span>项
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu" data-table="#visitTable">
                    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#visitModal" data-action="<?php echo site_url('permission/visit/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
                </ul>
            </div>
            <button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#visitModal" data-action="<?php echo site_url('permission/visit/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
            <button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
            <a class="btn btn-default" data-toggle="backstage" data-target="#visitTable" href="<?php echo site_url('permission/visit/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
        </div>
    </div>
    <div class="my-table col-md-12">
        <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="visitTable" data-load="<?php echo site_url('permission/visit/read') ?>">
            <thead>
            <tr>
                <th class="td-xs" data-name="selected">#</th>
                <th data-name="controller">控制器</th>
                <th data-name="name">名称</th>
                <th data-name="url">Url</th>
            </tr>
            </thead>
            <tbody>
            <tr class="loading"><td colspan="9">加载中...</td></tr>
            <tr class="no-data"><td colspan="9">没有数据</td></tr>
            <tr class="model">
                <td ><input name="vid"  type="checkbox" value=""/></td>
                <td name="controller"></td>
                <td name="name"></td>
                <td name="url"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="visitModal" tabindex="-1" role="dialog" aria-labelledby="visitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" id="visitForm" action="" method="post" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="visitModalLabel">编辑</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="selected" value="" />
                    <div class="form-group">
                        <label class="control-label col-md-2" >控制器:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="controller" type="text" placeholder="控制器" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-2" >名称:</label>
                      <div class="col-md-6">
                        <input class="form-control" name="name" type="text" placeholder="名称" value=""/>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-2" >Url:</label>
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
<script>
    (function($){
        $('div#visit').handle_page();
        $('div#visitModal').handle_modal_000();
    })(jQuery);
</script>
