<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月6日
 * @author Zhangcc
 * @version
 * @des
 * 订单扫描详情
 */
?>
    <div class="page-line" id="producePrehandled">
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="input-group" id="producePrehandledSearch" data-toggle="search" data-target="#producePrehandledTable">
                    <input type="hidden" name="id" value="<?php echo $Id;?>"/>
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="producePrehandledSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="producePrehandledFunction">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        共选中<span id="producePrehandledTableSelected" data-num="">0</span>项
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" data-table="#producePrehandledTable">
                        <!-- <li><a href="javascript:void(0);" data-toggle="modal" data-target="#producePrehandledModal" data-action="<?php echo site_url('order/produce_prehandled/edit');?>" data-multiple=true><i class="fa fa-pencil"></i>&nbsp;&nbsp;重新分类</a></li>
                        <li role="separator" class="divider"></li>  -->
                        <li><a data-toggle="blank" data-target="#producePrehandledTable" href="<?php echo site_url('order/optimize/produce_prehandled');?>" target="_blank" data-multiple=true><i class="fa fa-download"></i>&nbsp;&nbsp;给Cutrite</a></li>
                    </ul>
                </div>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回</button>
	  		</div>
        </div>
		<div class="my-table col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="producePrehandledTable">
				<thead>
					<tr>
						<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>
						<th >分类<i class="fa fa-sort"></i></th>
						<th >二维码<i class="fa fa-sort"></i></th>
						<th >板材<i class="fa fa-sort"></i></th>
						<th >柜体位置<i class="fa fa-sort"></i></th>
						<th >名称<i class="fa fa-sort"></i></th>
						<th >宽度<i class="fa fa-sort"></i></th>
						<th >长度<i class="fa fa-sort"></i></th>
						<th >厚度<i class="fa fa-sort"></i></th>
						<th >封边<i class="fa fa-sort"></i></th>
						<th >打孔<i class="fa fa-sort"></i></th>
						<th >开槽<i class="fa fa-sort"></i></th>
						<th >备注<i class="fa fa-sort"></i></th>
						<th data-name="classify_id" class="td-hide"></th>
					</tr>
				</thead>
				<tbody>
				    <?php
				    if(isset($Plate) && is_array($Plate) && count($Plate) > 0){
				        $K = 1;
				        foreach ($Plate as $key => $value){
				            echo <<<END
<tr>
	<td ><input name="opbpid" type="checkbox" value="$value[opbpid]" /></td>
	<td >$value[classify]</td>
	<td >$value[qrcode]</td>
	<td >$value[board]</td>
	<td >$value[cubicle_name]</td>
	<td >$value[plate_name]</td>
	<td >$value[width]</td>
	<td >$value[length]</td>
	<td >$value[thick]</td>
	<td >$value[edge]</td>
	<td >$value[punch]</td>
	<td >$value[slot]</td>
	<td >$value[remark]</td>
	<td class="td-hide">$value[classify_id]</td>
</tr>
END;
				            $K++;
				        }
				    }
				    ?>
				</tbody>
			</table>
		</div>
    </div>
    <div class="modal fade" id="producePrehandledModal" tabindex="-1" role="dialog" aria-labelledby="dealerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal" id="producePrehandledForm" action="" method="post" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="producePrehandledModalLabel">编辑</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="selected" value="" />
                        <div class="form-group">
                            <label class="control-label col-md-2">分类:</label>
                            <div class="col-md-6">
                                <select class="form-control" name="classify_id" ></select>
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
            if(!(SessionData = $.sessionStorage('classify_id'))){
                $.ajax({
                    async: true,
                    type: 'get',
                    dataType: 'json',
                    url: '<?php echo site_url('data/classify/get/parents');?>',
                    success: function(msg){
                        if(msg.error == 0){
                            var Content = msg.data.content;
                            Item = '';
                            for(index in Content){
                                Item += '<option value="'+Content[index]['cid']+'" >'+Content[index]['name']+'</option>';
                            }
                            $('#producePrehandledModal select[name="classify_id"]').append(Item);
                            $.sessionStorage('classify_id', Item);
                        }
                    }
                });
            }else{
                $('#producePrehandledModal select[name="classify_id"]').append(SessionData);
            }
			$('div#producePrehandled').handle_page();
            $('div#producePrehandledModal').handle_modal_000();
			$('#producePrehandledTable').tablesorter($(this).find('thead tr').getHeaders()); /** 表格排序*/
		})(jQuery);
	</script>