<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月19日
 * @author Zhangcc
 * @version
 * @des
 * 等待发货
 */
$Tab = '';
$TabContent = '';
$Content = '';
$Active = true;
if(isset($Outting) && is_array($Outting) && count($Outting) > 0){
    foreach ($Outting as $key=>$value){
        if($Active){
            $Tab .= '<li role="presentation" class="active"><a href="#outting'.$key.'" aria-controls="outting'.$key.'" role="tab" data-toggle="tab">'.$value['name'].'</a></li>';
            $TabContent .= '<div role="tabpanel" class="tab-pane active" id="outting'.$key.'" >'.$value['content'].'</div>';
            $Active = false;
        }else{
            $Tab .= '<li role="presentation"><a href="#outting'.$key.'" aria-controls="outting'.$key.'" role="tab" data-toggle="tab">'.$value['name'].'</a></li>';
            $TabContent .= '<div role="tabpanel" class="tab-pane" id="outting'.$key.'" >'.$value['content'].'</div>';
        }
    }
}
?>
    <div class="page-line" id="waitDelivery" >
		<div class="my-tools col-md-12">
			<div class="col-md-8">
			    <form class="form-inline" id="waitDeliveryForm">
                    <div class="form-group">
                        <label class="sr-only" for="waitDeliveryAmount">总件数</label>
                        <input type="text" class="form-control" id="waitDeliveryAmount" name="amount" placeholder="总件数" readonly>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="waitDeliveryTruck">货车</label>
                        <select class="form-control" id="waitDeliveryTruck" name="truck"><option value="">--选择货车--</option></select>
                    </div>
                    <div class="checkbox">
                        <label class="sr-only" for="waitDeliveryTrain">车次</label>
                        <select class="form-control" id="waitDeliveryTrain" name="train"><option value="">--选择车次--</option></select>
                    </div>
                    <div class="checkbox">
                        <label class="sr-only" for="waitDeliveryEndDatetime">出厂日期</label>
                        <input type="email" class="form-control" id="waitDeliveryEndDatetime" name="end_datetime" placeholder="发货日期" >
                    </div>
                </form>
			</div>
	  		<div class="col-md-4 text-right" id="waitDeliveryFunction">
	  		    <a class="btn btn-primary" href="<?php echo site_url('order/wait_delivery/index/protocol');?>" target="_blank" id="waitDeliveryProtocolButton"><i class="fa fa-save"></i>&nbsp;&nbsp;拟定发货</a>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
            <div role="tabpanel" id="waitDeliveryTab">
		  	   <!-- Nav tabs -->
		  		<ul class="nav nav-tabs" role="tablist">
		  	        <?php echo $Tab;?>
		  		</ul>

	  			<!-- Tab panes -->
			  	<div class="tab-content">
			  	    <?php echo $TabContent;?>
			  	</div>
			</div>
        </div>
	</div>
	<script>
    	(function($){
        	var Data = {}, SessionData = undefined;
        	$('#waitDeliveryEndDatetime').datepicker({
				todayBtn: "linked",
                language: "zh-CN",
                orientation: "top auto",
                autoclose: true,
                todayHighlight: true
			});
        	if(!(SessionData = $.sessionStorage('truck'))){
        		$.ajax({
        			async: true,
        			type: 'get',
        			dataType: 'json',
        			url: '<?php echo site_url('data/truck/read');?>',
        			success: function(msg){
        					if(msg.error == 0){
        						var Item = '', Content = msg.data.content;
        						for(var i in Content){
        							Item += '<option value="'+Content[i]['name']+'" >'+Content[i]['name']+'</option>';
        			            }
        						$.sessionStorage('truck', Item);
        			            $('#waitDeliveryTruck').append(Item);
        			        }
        				}
        		});
    		}else{
    			$('#waitDeliveryTruck').append(SessionData);
    		}
        	if(!(SessionData = $.sessionStorage('train'))){
        		$.ajax({
        			async: true,
        			type: 'get',
        			dataType: 'json',
        			url: '<?php echo site_url('data/train/read');?>',
        			success: function(msg){
        					if(msg.error == 0){
        						var Item = '', Content = msg.data.content;
        						for(var i in Content){
        							Item += '<option value="'+Content[i]['name']+'" >'+Content[i]['name']+'</option>';
        			            }
        						$.sessionStorage('train', Item);
        			            $('#waitDeliveryTrain').append(Item);
        			        }
        				}
        		});
    		}else{
    			$('#waitDeliveryTrain').append(SessionData);
    		}
        	$('#waitDelivery').handle_page();
        	$('#waitDeliveryProtocolButton').on('click', function(e){
        		var $Button = $(this), Action = $(this).attr('href'), 
            		$Active = $('#waitDeliveryTab > div.tab-content > div.active'),
            		Data = {};
            	Data['amount'] = $('#waitDeliveryAmount').val(),
            	Data['truck'] = $('#waitDeliveryTruck').val(),
            	Data['train'] = $('#waitDeliveryTrain').val(),
            	Data['end_datetime'] = $('#waitDeliveryEndDatetime').val(),
            		$Selected = $Active.find('table tbody tr:not(.model) input:checkbox:checked');
        		if($Selected.length <= 0){
        			alert('请先选择要发货的订单');
        			return false;
        		}else{
        			Data['selected'] = $.map($Selected, function(n){return n.value;});
        		}
        		if('' == Data['end_datetime']){
        			alert('请选择发货日期');
        			$('#waitDeliveryEndDatetime').focus();
        			return false;
        		}
        		if(Action.lastIndexOf('?') >= 0){
                    Action = Action.substr(0,Action.lastIndexOf('?'))+'?'+$.param(Data);
                }else{
                	Action = Action+'?'+$.param(Data);
                }
                $Button.attr('href', Action);
                return true;
        	});
        })(jQuery);
    </script>