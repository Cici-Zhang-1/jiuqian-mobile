<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月25日
 * @author Zhangcc
 * @version
 * @des
 */
?>
    <div class="page-line" id="workflowMsg" >
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="input-group" id="workflowMsgSearch">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="workflowMsgSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="workflowMsgFunction">
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回</button>
	  		</div>
        </div>
		<div class="my-table col-md-12" id="workflowMsgDl">
		        <?php 
		            if(isset($Msg) && is_array($Msg) && count($Msg) > 0){
		                foreach ($Msg as $key => $value){
		                    echo <<<END
<dl class="dl-horizontal">
<dt>$value[create_datetime]</dt>
<dd>$value[creator]-$value[target]-$value[msg]</dd>
</dl>
END;
		                }
		            }
		        ?>
		</div>
    </div>
	<script type="text/javascript">
		(function($, window, undefined){
			$('#workflowMsg').handle_page();
            $('#workflowMsgSearch input[name="keyword"]').keyup(function(){
                $('#workflowMsgDl').find('dl').hide().filter(":contains('"+( $(this).val() )+"')").show();
            });
		})(jQuery);
	</script>