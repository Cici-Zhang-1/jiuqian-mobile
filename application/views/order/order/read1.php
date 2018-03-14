<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  order
 * @author Cici
 * @version
 * @description
 * 权限许可视图
 */

?>
<div class="row j-page" id="order">
    <div class="col-md-3 j-page-search">
        <div class="input-group" id="orderSearch" data-toggle="filter" data-target="#orderTable">
                            <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#orderFilterModal"><i class="fa fa-search"></i></button>
                    </span>
                                <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">Go!</button>
                    </span>
                    </div>
    </div>
    <div class="col-md-9 text-right j-func" id="orderFunction">
                                        <div class="btn-group" role="group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    共选中<span id="orderTableSelected" data-num="">0</span>项
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu" data-table="#orderTable">
                    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#orderModal" data-action="<?php echo site_url('/order/order/edit');?>" data-multiple=><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>
                                                                    <li><a href="javascript:void(0);" data-toggle="" data-target="#orderTable" data-action="<?php echo site_url('/data/workflow_msg/index/read');?>" data-multiple=><i class="fa fa-hourglass-1"></i>&nbsp;&nbsp;进程图</a></li>
                                                </ul>
            </div>
                            <button type="button" class="btn btn-default" value="刷新" data-toggle="refresh" data-target=""  data-multiple=><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
                                                                                            <a class="btn btn-default" href="<?php echo site_url('/order/order/remove');?>" data-toggle="backstage" data-target="#orderTable" data-multiple=1><i class="fa fa-trash-o"></i>&nbsp;&nbsp;作废</a>
                                        </div>
                <div class="j-panel col-md-12">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-bordered table-striped table-hover table-condensed" id="orderTable" data-load="<?php echo site_url('/order/order/read');?>">
                    <thead>
                        <tr>
                            <th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
                                                                                                <th class="hide" data-name="oid">#</th>
                                                                                                                                <th class="" data-name="icon">等级</th>
                                                                                                                                <th class="" data-name="order_num">订单编号</th>
                                                                                                                                <th class="" data-name="owner">业主</th>
                                                                                                                                <th class="" data-name="dealer">客户</th>
                                                                                                                                <th class="" data-name="sum">金额</th>
                                                                                                                                <th class="" data-name="balance">账户余额</th>
                                                                                                                                <th class="" data-name="creator">创建人</th>
                                                                                                                                <th class="" data-name="status">状态</th>
                                                                                    </tr>
                    </thead>
                    <tbody>
                    <tr class="loading"><td colspan="15">加载中...</td></tr>
                    <tr class="no-data"><td colspan="15">没有数据</td></tr>
                    <tr class="model">
                        <td ><input name="v"  type="checkbox" value=""/></td>
                                                                                    <td class="hide" name="oid"></td>
                                                                                                                <td class="" name="icon"></td>
                                                                                                                <td class="" name="order_num"></td>
                                                                                                                <td class="" name="owner"></td>
                                                                                                                <td class="" name="dealer"></td>
                                                                                                                <td class="" name="sum"></td>
                                                                                                                <td class="" name="balance"></td>
                                                                                                                <td class="" name="creator"></td>
                                                                                                                <td class="" name="status"></td>
                                                                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
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
    </div>
    </div>

    <div class="modal fade" id="orderEditModal" tabindex="-1" role="dialog" aria-labelledby="orderEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" id="orderEditForm" action="" method="post" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orderEditModalLabel">编辑</h4>
                </div>
                <div class="modal-body">
                                                            <div class="form-group">
                            <label class="control-label col-md-2" >业主:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="owner" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="flag" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="checker" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="checker_phone" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="payer" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="payer_phone" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="payterms" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="out_method" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="logistics" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="delivery_area" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="delivery_address" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="delivery_linker" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="delivery_phone" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="request_outdate" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="remark" type="text"       placeholder="" value=""/>
                            </div>
                        </div>
                                                                                <div class="form-group">
                            <label class="control-label col-md-2" >:</label>
                            <div class="col-md-6">
                                <input class="form-control " name="dealer_remark" type="text"       placeholder="" value=""/>
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
                
<div class="modal fade filter" id="orderFilterModal" tabindex="-1" role="dialog" aria-labelledby="orderFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form  class="form-horizontal" id="orderFilterForm" action="" method="post" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orderFilterModalLabel">搜索</h4>
                </div>
                <div class="modal-body">
                                                                        <div class="form-group" data-url="/data/workflow/read/order">
                                <label class="control-label col-md-2" >状态:</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="status"></select>
                                </div>
                            </div>
                                                                                                <div class="form-group">
                                <label class="control-label col-md-2" >开始日期:</label>
                                <div class="col-md-6">
                                    <input class="form-control " name="start_date" type="text"       placeholder="" value=""/>
                                </div>
                            </div>
                                                                                                <div class="form-group">
                                <label class="control-label col-md-2" >截至日期:</label>
                                <div class="col-md-6">
                                    <input class="form-control " name="end_date" type="text"       placeholder="" value=""/>
                                </div>
                            </div>
                                                                                                <div class="form-group">
                                <label class="control-label col-md-2" >Search:</label>
                                <div class="col-md-6">
                                    <input class="form-control " name="keyword" type="text"       placeholder="" value=""/>
                                </div>
                            </div>
                                                                <div class="alert alert-danger alert-dismissible fade in serverError" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary" data-dismiss="modal">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    (function($){
        /*$('div#order').handle_page();
                $('div#orderFilterModal').handle_modal_000();*/
    })(jQuery);
</script>
