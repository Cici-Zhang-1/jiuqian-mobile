<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-20
 * @author ZhangCC
 * @version
 * @description  
 */
?>
    <div class="page-line" id="page">
        <div class="my-tools col-md-12">
			<div class="col-md-3"></div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="pageFunction">
	  		    <button class="btn btn-default" id="clearStorage" value="清除缓存" type="button">清除缓存</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
            <div class="col-md-4">
                <div class="panel-group" id="pageOrder" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-danger">
                        <div class="panel-heading" role="tab" id="pageOrderBt">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#pageOrder" href="#pageOrderBtBody" aria-expanded="true" aria-controls="pageOrderBtBody">
                                    BT急订单
                                </a>
                            </h4>
                        </div>
                        <div id="pageOrderBtBody" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="pageOrderBt">
                            <table class="table table-bordered table-striped table-hover table-responsive table-condensed" data-load="<?php echo site_url('order/order_bt/read');?>">
                                <thead>
                                    <tr>
                                        <th>编号<i class="fa fa-sort"></i></th>
                                        <th>状态<i class="fa fa-sort"></i></th>
                                        <th>要求出厂<i class="fa fa-sort"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="loading"><td colspan="15">加载中...</td></tr>
                					<tr class="no-data"><td colspan="15">没有数据</td></tr>
                                    <tr class="model">
                                        <td name="order_num"></td>
                                        <td name="status"></td>
                                        <td name="request_outdate"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel-group" id="pageOrderWarn" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-danger">
                        <div class="panel-heading" role="tab" id="pageOrderWarnHead">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#pageOrderWarn" href="#pageOrderWarnBody" aria-expanded="true" aria-controls="pageOrderWarnBody">
                                                                    订单预警
                                </a>
                            </h4>
                        </div>
                        <div id="pageOrderWarnBody" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="pageOrderWarnHead">
                            <table class="table table-bordered table-striped table-hover table-responsive table-condensed" data-load="<?php echo site_url('order/order_warn/read');?>">
                                <thead>
                                    <tr>
                                        <th>编号<i class="fa fa-sort"></i></th>
                                        <th>状态<i class="fa fa-sort"></i></th>
                                        <th>要求出厂<i class="fa fa-sort"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="loading"><td colspan="15">加载中...</td></tr>
                					<tr class="no-data"><td colspan="15">没有数据</td></tr>
                                    <tr class="model">
                                        <td name="order_num"></td>
                                        <td name="status"></td>
                                        <td name="request_outdate"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel-group" id="pageBoard" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="pageBoardHead">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#pageBoard" href="#pageBoardBody" aria-expanded="true" aria-controls="pageBoardBody">
                                                                    板材库存
                                </a>
                            </h4>
                        </div>
                        <div id="pageBoardBody" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="pageBoardHead">
                            <table class="table table-bordered table-striped table-hover table-responsive table-condensed" data-load="<?php echo site_url('product/board/read_stock');?>">
                                <thead>
                                    <tr>
                                        <th>名称<i class="fa fa-sort"></i></th>
                                        <th>库存量<i class="fa fa-sort"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="loading"><td colspan="15">加载中...</td></tr>
                					<tr class="no-data"><td colspan="15">没有数据</td></tr>
                                    <tr class="model">
                                        <td name="name"></td>
                                        <td name="amount"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
		(function($, window, undefined){
			$('div#page').handle_page();
			$('#clearStorage').on('click', function(e){
				e.preventDefault();
				$.localStorage().clear();
				$.sessionStorage().clear();
				$.post('<?php echo site_url('home/clear');?>');
			});
		})(jQuery, window, undefined);
	</script>
