<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月16日
 * @author Zhangcc
 * @version
 * @des
 * 拆单页面
 */
$Tab = '';
$TabContent = '';
$Content = '';
if(isset($Product) && is_array($Product) && count($Product) > 0){
    foreach ($Product as $key=>$value){
        $Code = strtoupper($value['Code']);
        if($Select == $value['Code']){
            if(0 == $value['Dismantle']){
                $Tab .= '<li role="presentation" class="active"><a href="#postSale'.$Code.'" aria-controls="postSale'.$Code.'" role="tab" data-toggle="tab">'.$value['Name'].'</a></li>';
            }else{
                $Tab .= '<li role="presentation" class="active"><a class="diff-red" href="#postSale'.$Code.'" aria-controls="postSale'.$Code.'" role="tab" data-toggle="tab">'.$value['Name'].'</a></li>';
            }
            $TabContent .= '<div role="tabpanel" class="tab-pane active" id="postSale'.$Code.'" >'.$value['content'].'</div>';
        }else{
            if(0 == $value['Dismantle']){
                $Tab .= '<li role="presentation"><a href="#postSale'.$Code.'" aria-controls="postSale'.$Code.'" role="tab" data-toggle="tab">'.$value['Name'].'</a></li>';
            }else{
                $Tab .= '<li role="presentation"><a class="diff-red" href="#postSale'.$Code.'" aria-controls="postSale'.$Code.'" role="tab" data-toggle="tab">'.$value['Name'].'</a></li>';
            }
            $TabContent .= '<div role="tabpanel" class="tab-pane" id="postSale'.$Code.'" >'.$value['content'].'</div>';
        }
    }
}
?>
    <script src="<?php echo base_url('js/post_sale.js');?>"></script>
    <div class="page-line" id="postSale" >
        <div class="my-tools col-md-12">
            <div class="col-md-6">
                <div class="input-group" id="postSaleSearch">
                    <input type="hidden" name="id" value="<?php echo $Id;?>"/>
                    <p class="form-control-static">订单编号:<?php echo $Info['order_num'];?>&nbsp;&nbsp;&nbsp;客户:<?php echo $Info['dealer'];?>&nbsp;&nbsp;&nbsp;业主:<?php echo $Info['owner'];?></p>
		    	</div>
			</div>
	  		<div class="col-md-6 text-right" id="postSaleFunction">
	  		    <button class="btn btn-primary" data-action="<?php echo site_url('order/post_sale/edit');?>" type="button" id="postSaleButton1" value="确认"><i class="fa fa-save"></i>&nbsp;&nbsp;确认</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
        </div>
        <div class="my-table col-md-12">
            <div role="tabpanel" id="postSaleTab">
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
        	var Data = {};
        	$('#postSaleButton1').each(function(i, v){
				$(this).on('click', function(e){
					var $Button = $(this), Action = $(this).data('action'), $Target, Func,
						Type = $('#postSaleTab > div.tab-content > div.active').find('input:hidden[name="code"]').val();
					switch(Type){
						case 'p':
							$Target = $('#postSalePForm');
							break;
						case 'g':
							$Target = $('#postSaleGForm');
							break;
						case 'f':
							$Target = $('#postSaleFForm');
							break;
					}
					$Target.save_post_sale({Button:$Button, Action: Action, 
						Type: Type, $Page: $('#postSaleSearch')});
				});
            });
    		$('div#postSale').handle_form();
        })(jQuery);
    </script>