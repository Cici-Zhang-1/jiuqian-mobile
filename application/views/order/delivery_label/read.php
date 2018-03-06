<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月18日
 * @author Zhangcc
 * @version
 * @des
 * 打包标签打印
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
                            <a href="#deliveryLabel" aria-controls="deliveryLabel" role="tab" data-toggle="tab">发货标签</a>
                        </li>
                    </ul>
                    <div class="tab-content" >
                        <div role="tabpanel" class="tab-pane active" id="deliveryLabel">
                            <form class="my-label-color-1 my-label form-horizontal" id="deliveryLabelForm" role="form" method="get" action="<?php echo site_url('order/delivery_label/index/print')?>">
                                <input type="hidden" name="order_num" value="" />
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-title form-control-static">发货标签</p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-6">
                                        <div class="radio">
                                            <label class="my-label-enhance">
                                                <input class="" type="radio" value="x" name="type" checked>正常单
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="radio">
                                            <label class="my-label-enhance">
                                                <input class="" type="radio" value="b" name="type">补单
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg" id="wDate">
                                    <div class="col-md-6">
                                        <select class="form-control" name="year" id="wYear"></select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="month" id="wMonth"></select>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" name="prefix" placeholder="大号" />
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4"></div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-enhance form-control-static error"></p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-3">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/delivery_label/index/print');?>" >打印发货标签</a>
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
    <script type="text/javascript" src="<?php echo base_url('js/dateselect.js');?>"></script>
    <script>
    (function($){
    	$("#wDate").DateSelector({
            ctlYearId: 'wYear',
            ctlMonthId: 'wMonth',
            minYear: 2014
    	});
    	var $Form = $('#deliveryLabelForm');
    	$('#deliveryLabelForm').find('input[name = "prefix"]').on('focusout', function(e){
    		var Data = {};
    		Data['prefix'] = $Form.find('input[name = "prefix"]').val();
    		Data['year'] = $Form.find('select[name = "year"]').val(); 
			Data['month'] = $Form.find('select[name="month"]').val();
			Data['type'] = $Form.find('input[name="type"]:checked').val();

			if('' != Data['Prefix']){
				$.ajax({
					async: false,
	                data: Data,
	                type: 'get',
	                url: '<?php echo site_url('order/delivery_label/read');?>',
	                dataType: 'json',
	                success: function(msg){
	                    if(msg.error == 0){
    	                    var Text = msg.data.order_num+'   [   '+msg.data.pack+'   ]件<br />';
	                    	$Form.find('input[name="order_num"]').val(msg.data.order_num);
	                    	
	                    	$Form.find('p.error').html(Text);
	                    }
	                },
	                error: function(x,t,e){
	                	$Form.find('p.error').html(x.responseText);
		            }
	            });
			}else{
				$Form.find('input[name="order_num"]').val('');
            	$Form.find('p.error').text('');
			}
    	}).on('focusin', function(e){
        	$(this).find('input[name="order_num"]').val('');
        	$(this).find('p.error').text('');
    	});

    	$('#deliveryLabelForm').find('a').click(function(e){
			var OrderNum = $Form.find('input[name="order_num"]').val();
			
			if('' != OrderNum){
				$(this).attr('href', function(ii,vv){
					if(vv.lastIndexOf('?') >= 0){
                        return vv.substr(0,vv.lastIndexOf('?'))+'?order_num='+OrderNum;
                    }else{
                        return vv+'?order_num='+OrderNum;
                    }
                });
				return true;
			}else{
				$Form.find('p.error').html('没有找到相应订单, 请重新查找');
				return false;
			}
		});
    })(jQuery);
    </script>
</html>