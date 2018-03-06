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
                $Tab .= '<li role="presentation" class="active"><a href="#dismantle'.$Code.'" aria-controls="dismantle'.$Code.'" role="tab" data-toggle="tab">'.$value['Name'].'</a></li>';
            }else{
                $Tab .= '<li role="presentation" class="active"><a class="diff-red" href="#dismantle'.$Code.'" aria-controls="dismantle'.$Code.'" role="tab" data-toggle="tab">'.$value['Name'].'</a></li>';
            }
            $TabContent .= '<div role="tabpanel" class="tab-pane active" id="dismantle'.$Code.'" >'.$value['content'].'</div>';
        }else{
            if(0 == $value['Dismantle']){
                $Tab .= '<li role="presentation"><a href="#dismantle'.$Code.'" aria-controls="dismantle'.$Code.'" role="tab" data-toggle="tab">'.$value['Name'].'</a></li>';
            }else{
                $Tab .= '<li role="presentation"><a class="diff-red" href="#dismantle'.$Code.'" aria-controls="dismantle'.$Code.'" role="tab" data-toggle="tab">'.$value['Name'].'</a></li>';
            }
            $TabContent .= '<div role="tabpanel" class="tab-pane" id="dismantle'.$Code.'" >'.$value['content'].'</div>';
        }
    }
}
?>
    <script src="<?php echo base_url('js/dismantle.js?v=0.1');?>"></script>
    <div class="page-line" id="dismantle" >
        <div class="my-tools col-md-12">
            <div class="col-md-6">
                <div class="input-group" id="dismantleSearch">
                    <input type="hidden" name="id" value="<?php echo $Id;?>"/>
                    <p class="form-control-static">订单编号:<?php echo $Info['order_num'];?>&nbsp;&nbsp;&nbsp;客户:<?php echo $Info['dealer'];?>&nbsp;&nbsp;&nbsp;业主:<?php echo $Info['owner'];?></p>
		    	</div>
			</div>
	  		<div class="col-md-6 text-right" id="dismantleFunction">
	  		    <button class="btn btn-primary" data-action="<?php echo site_url('order/dismantle/edit/dismantle');?>" type="button" id="dismantleButton1" value="暂存"><i class="fa fa-save"></i>&nbsp;&nbsp;暂存</button>
	  		    <button class="btn btn-primary" data-action="<?php echo site_url('order/dismantle/edit/dismantled');?>" type="button" id="dismantleButton2" value="确认"><i class="fa fa-save"></i>&nbsp;&nbsp;确认</button>
	  		    <button class="btn btn-default" data-action="<?php echo site_url('order/dismantle/remove');?>" type="button" id="dismantleButton3" value="清除"><i class="fa fa-remove"></i>&nbsp;&nbsp;清除</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
        </div>
        <div class="my-table col-md-12">
            <div role="tabpanel" id="dismantleTab">
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
        	$('#dismantleButton1, #dismantleButton2').each(function(i, v){
				$(this).on('click', function(e){
					var $Button = $(this), Action = $(this).data('action'), $Target, Func,
						Type = $('#dismantleTab > div.tab-content > div.active').find('input:hidden[name="code"]').val();
					switch(Type){
						case 'w':
							$Target = $('#dismantleWForm');
							break;
						case 'y':
							$Target = $('#dismantleYForm');
							break;
						case 'm':
							$Target = $('#dismantleMForm');
							break;
						case 'k':
							$Target = $('#dismantleKForm');
							break;
						case 'p':
							$Target = $('#dismantlePForm');
							break;
						case 'g':
							$Target = $('#dismantleGForm');
							break;
						case 'f':
							$Target = $('#dismantleFForm');
							break;
					}
					$Target.save_dismantle({Button:$Button, Action: Action, 
						Type: Type, $Page: $('#dismantleSearch')});
				});
            });
            $('#dismantleButton3').on('click', function(e){
            	var $Button = $(this), Action = $(this).data('action'), 
            		$Active = $('#dismantleTab > div.tab-content > div.active'),
            		Code = $Active.find('input:hidden[name="code"]').val(),
					Selected = $Active.find('select[name="opid"]').val();
				if(undefined != Selected && Selected > 0){
					$.ajax({
						async: true,
						dataType: 'json',
						type: 'post',
						url: Action,
						data: {id:Selected, code: Code},
						beforeSend: function(){
								$Button.prop('disabled', true);
							},
						success: function(msg){
								if(msg.error == 0){
									alert('清除成功!');
									$.tabRefresh();
								}else{
									alert(msg.message);
								}
								return ;
							},
						error: function(x, t, e){
								alert(x.responseText);
							},
						complete: function(){
								$Button.prop('disabled', false);
							}
					});
				}else{
					return false;
				}
			});
    		$('div#dismantle').handle_form();
			$('div#dismantle').find('table').each(function(i, v) {
				$(this).find('tbody tr').each(function(i, v){
					$(this).click(function(e){
						$(this).addClass('success').siblings().removeClass('success');
					}).dblclick(function(e){
						$(this).find('input:checkbox').trigger('click');
						if($(this).hasClass('active')){
							$(this).removeClass('active');
						}else{
							$(this).addClass('active');
						}
					});
				});
			});
        })(jQuery);
    </script>