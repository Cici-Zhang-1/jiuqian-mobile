<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 2017/10/15
 * Time: 08:29
 *
 * Desc: 库位管理
 */

?>
<div class="page-line" id="position">
    <div class="my-tools col-md-12">
        <div class="col-md-3">
            <div class="input-group" id="positionSearch" data-toggle="search" data-target="#positionTable">
                <input type="text" class="form-control" data-toggle="serach" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="positionSearchBtn" type="button">Go!</button>
		      		</span>
            </div>
        </div>
        <div class="col-md-offset-3 col-md-6 text-right" id="positionFunction">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    共选中<span id="positionTableSelected" data-num="">0</span>项
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu" data-table="#positionTable">
                    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#positionOrderProductModal" data-action="<?php echo site_url('position/position_order_product/add');?>" data-multiple=false><i class="fa fa-circle-o"></i>&nbsp;&nbsp;入库</a></li>
                    <li><a href="javascript:void(0);" data-toggle="child" data-target="#positionTable" data-action="<?php echo site_url('position/position_order_product/index/read');?>" data-multiple=false><i class="fa fa-history"></i>&nbsp;&nbsp;出入库史</a></li>
                    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#positionModal" data-action="<?php echo site_url('position/position/edit');?>" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑库位</a></li>
                </ul>
            </div>
            <button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#positionModal" data-action="<?php echo site_url('position/position/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
            <button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
            <a class="btn btn-default" data-toggle="backstage" data-target="#positionTable" href="<?php echo site_url('position/position/remove');?>" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;删除</a>
        </div>
    </div>
    <div class="my-table col-md-12">
        <table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="positionTable" data-load="<?php echo site_url('position/position/read') ?>">
            <thead>
                <tr>
                    <th class="td-xs" data-name="selected">#</th>
                    <th class="td-xs" data-name="icon">状态</th>
                    <th class="td-xs" data-name="name">名称</th>
                    <th data-name="order_product_num">订单号</th>
                    <th class="hide" data-name="status">状态</th>
                </tr>
            </thead>
            <tbody>
                <tr class="loading"><td colspan="2">加载中...</td></tr>
                <tr class="no-data"><td colspan="2">没有数据</td></tr>
                <tr class="model">
                    <td ><input name="pid"  type="checkbox" value=""/></td>
                    <td name="icon"></td>
                    <td name="name"></td>
                    <td name="order_product_num"></td>
                    <td class="hide" name="status"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="positionModal" tabindex="-1" role="dialog" aria-labelledby="positionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" id="positionForm" action="" method="post" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="positionModalLabel">编辑</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="selected" value="" />
                    <div class="form-group">
                        <label class="control-label col-md-2" >库位名称:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="name" type="text" placeholder="库位名称" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >库位状态:</label>
                        <div class="col-md-6">
                            <select class="form-control" name='status'>
                                <option value="0">空</option>
                                <option value="1">未装满</option>
                                <option value="2">满</option>
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

<div class="modal fade" id="positionOrderProductModal" tabindex="-1" role="dialog" aria-labelledby="positionOrderProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" id="positionOrderProductForm" action="" method="post" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="positionOrderProductModalLabel">编辑</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="selected" value="" />
                    <input class="form-control" name="opid" type="hidden" placeholder="订单id" value=""/>
                    <div class="form-group">
                        <label class="control-label col-md-2" >库位名称:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="name" type="text" readonly="readonly" placeholder="库位名称" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >已入库存订单:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="order_product_num" type="text" readonly="readonly" placeholder="已入库存订单" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >新入订单:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="new_order_product_num" type="text" placeholder="新入订单" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >入库件数:</label>
                        <div class="col-md-6">
                            <input class="form-control" name="count" type="text" placeholder="入库件数" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2" >库位状态:</label>
                        <div class="col-md-6">
                            <select class="form-control" name='status'>
                                <option value="1">未装满</option>
                                <option value="2">满</option>
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

<script>
    (function($){
        $('div#position').handle_page();
        $('div#positionModal').handle_modal_000();
        $('div#positionOrderProductModal').handle_modal_000();

        var formatItem = function(row){
            return row.name;
        };
        $.get("<?php echo site_url('order/order_product/read_wait_position');?>", function(msg){
            if(msg.error == 0){
                var Content = msg.data.content, j = 0, WaitPosition = {}, Data = new Array;
                for(var i in Content){
                    WaitPosition[Content[i]['opid']] = Content[i];
                }
                if(undefined != WaitPosition){
                    for(var i in WaitPosition){
                        Data[j++] = {
                            id: WaitPosition[i]['opid'],
                            name: WaitPosition[i]['order_product_num']
                        };
                    }
                    $('#positionOrderProductModal input[name="new_order_product_num"]').each(function(i,e){
                        $(this).autocomplete({
                            minLength: 0,
                            autoselect: true,
                            showHint: false,
                            source:[Data],
                            valueKey: 'name',
                            getValue: formatItem,
                            getTitle: formatItem
                        }).on('selected.xdsoft',function(e,row){
                            $('#positionOrderProductModal input[name="opid"]').val(row.id);
                        });
                    });
                }
            }
        }, 'json');
    })(jQuery);
</script>