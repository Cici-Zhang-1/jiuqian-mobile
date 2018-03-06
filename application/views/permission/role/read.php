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
<div class="page-line" id="role" data-load="<?php echo site_url('permission/role/read') ?>">
    <div class="my-tools col-md-12">
        <div class="col-md-3">
            <div class="input-group" id="roleSearch" data-toggle="search" data-target="#roleTable">
                <input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="roleSearchBtn" type="button">Go!</button>
		      		</span>
            </div>
        </div>
        <div class="col-md-offset-3 col-md-6 text-right" id="roleFunction">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    共选中<span id="roleTableSelected" data-num="">0</span>项
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu" data-table="#roleTable">
                    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#roleModal" data-action="<?php echo site_url('permission/role/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="javascript:void(0);" data-toggle="child" data-target="#roleTable" data-action="<?php echo site_url('permission/role_menu/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;角色菜单权限</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="javascript:void(0);" data-toggle="child" data-target="#roleTable" data-action="<?php echo site_url('permission/role_visit/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;角色访问控制权限</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="javascript:void(0);" data-toggle="child" data-target="#roleTable" data-action="<?php echo site_url('permission/role_func/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;角色功能权限</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="javascript:void(0);" data-toggle="child" data-target="#roleTable" data-action="<?php echo site_url('permission/role_form/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;角色表单权限</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="javascript:void(0);" data-toggle="child" data-target="#roleTable" data-action="<?php echo site_url('permission/role_card/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;角色卡片权限</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="javascript:void(0);" data-toggle="child" data-target="#roleTable" data-action="<?php echo site_url('permission/role_element/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;角色元素权限</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="javascript:void(0);" data-toggle="child" data-target="#roleTable" data-action="<?php echo site_url('permission/role_page_search/index/read');?>" data-multiple=false><i class="fa fa-eye"></i>&nbsp;&nbsp;角色页面搜索权限</a></li>
                </ul>
            </div>
            <button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#roleModal" data-action="<?php echo site_url('permission/role/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
            <button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
            <a class="btn btn-default" data-toggle="backstage" data-target="#roleTable" href="<?php echo site_url('permission/role/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
        </div>
    </div>
    <div class="my-table col-md-12">
        <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="roleTable" data-load="<?php echo site_url('permission/role/read') ?>">
            <thead>
            <tr>
                <th class="td-xs" data-name="selected">#</th>
                <th data-name="name">角色名称</th>
            </tr>
            </thead>
            <tbody>
            <tr class="loading"><td colspan="9">加载中...</td></tr>
            <tr class="no-data"><td colspan="9">没有数据</td></tr>
            <tr class="model">
                <td ><input name="rid"  type="checkbox" value=""/></td>
                <td name="name"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" id="roleForm" action="" method="post" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="roleModalLabel">编辑</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="selected" value="" />
                    <div class="form-group">
                        <label class="control-label col-md-2" >名称:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="name" type="text" placeholder="角色名称" value=""/>
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
        $('div#role').handle_page();
        $('div#roleModal').handle_modal_000();
    })(jQuery);
</script>
