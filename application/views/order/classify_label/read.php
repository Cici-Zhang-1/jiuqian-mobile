<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月18日
 * @author Zhangcc
 * @version
 * @des
 * 打包板块分类标签打印
 */
?>
        <style>
            .my-label{
	            font-size: 36px;
                font-weight: bold;
            }
            .my-label .my-label-title{
            	text-align:center; 
            }
            .radio label.my-label-enhance, .checkbox label.my-label-enhance{
	            font-weight: bold;
            }
            .radio label.my-label-enhance input[type="radio"], .checkbox label.my-label-enhance input[type="checkbox"]{
	            margin-top: 20px;
            }
            .my-label-color-1{
	            color: red;
            }
            .my-label-color-2{
	            color: green;    
            }
            .my-label-color-3{
	            color: fuchsia;
            }
            .my-label-color-4{
	            color: olive;
            }
            .my-label-color-5{
	            color: blue;
            }
            .my-label-color-6{
	            color: black;
            }
        </style>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#cabinet" aria-controls="classify" role="tab" data-toggle="tab">板块分类</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="classifyLabel">
                        <div role="tabpanel" class="tab-pane active" id="classify">
                            <form class="my-label-color-1 my-label form-horizontal" role="form" method="get" action="<?php echo site_url('order/classify_label/index/print')?>">
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-title form-control-static">板块分类标签</p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <select class="form-control" name="cid">
                                            <option value="0">--选择分类--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="order_product_num" placeholder="订单产品编号(X20160101001-Y1)" />
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="sn" placeholder="用户自己命名打印编号" value="1"/>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-enhance form-control-static" id="classfiyLabelSn"></p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-enhance form-control-static error"></p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary btn-lg" type="submit">打印</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('js/jquery.storage.js');?>"></script>
    <script type="text/javascript">
    	(function($){
        	var SessionData;
    		if(!(SessionData = $.sessionStorage('classify_label_read'))){
    			SessionData = 1;
                $('#classifyLabel input[name="sn"]').val(SessionData);
        		$.sessionStorage('classify_label_read', SessionData);
    		}else{
        		SessionData = parseInt(SessionData);
        		if(SessionData){
            		SessionData += 1;
            	}else{
                	SessionData = 1;
                }
                $('#classifyLabel input[name="sn"]').val(SessionData);
    			$.sessionStorage('classify_label_read', SessionData);
    		}
            $('#classifyLabel input[name="sn"]').on('focusout', function (e) {
                SessionData = parseInt($(this).val());
                if(!SessionData){
                    SessionData = 1;
                }
                $.sessionStorage('classify_label_read', SessionData);
            });
            $('input[name="order_product_num"]').on('focusout', function (e) {
                var OrderProductNum = $.trim($(this).val());
                if('' != OrderProductNum){
                    $.ajax({
                        async: true,
                        type: 'get',
                        dataType: 'json',
                        url: '<?php echo site_url('order/optimize/read_sn?order_product_num='); ?>'+OrderProductNum,
                        success: function(msg){
                            if(msg.error == 0){
                                var Item = '', Content = msg.data.content;
                                for(var i in Content){
                                    Item += '同一订单中'+Content[i]['name']+'批次号'+Content[i]['sn']+'<br />';
                                    if('标准板块' == Content[i]['name']){
                                        $('input[name="sn"]').val(Content[i]['sn']);
                                    }
                                }
                                $('#classfiyLabelSn').html(Item);
                            }
                        }
                    });
                }
            });
    		$.ajax({
				async: true,
				type: 'get',
				dataType: 'json',
				url: '<?php echo site_url('data/classify/get/label');?>',
				success: function(msg){
					if(msg.error == 0){
						var Item = '', Content = msg.data.content;
						for(var i in Content){
							Item += '<option value="'+Content[i]['cid']+'" >'+Content[i]['name']+'</option>';
						}
						$('#classify select[name="cid"]').append(Item);
			        }
				}
			});
    	})(jQuery);
	</script>
</html>