<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月22日
 * @author Administrator
 * @version
 * @des
 */
?>
    <div class="page-line" id="dealerTrace" >
        <div class="my-tools col-md-12">
            <div class="col-md-3">
                <div class="input-group" id="dealerTraceSearch">
		      		<input type="text" class="form-control" name="keyword" placeholder="搜索">
		      		<span class="input-group-btn">
		        		<button class="btn btn-default" id="dealerTraceSearchBtn" type="button">Go!</button>
		      		</span>
		    	</div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="dealerTraceFunction">
	  		    <button class="btn btn-primary" type="button" value="新增" data-toggle="modal" data-target="#dealerTraceModal" data-action="<?php echo site_url('dealer/dealer_trace/add');?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;新增</button>
	  			<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  			<button class="btn btn-default" data-toggle="reply" type="button" value="返回"><i class="fa fa-reply"></i>&nbsp;&nbsp;返回</button>
	  		</div>
        </div>
		<div class="my-table col-md-12" id="dealerTraceDl">
		        <?php 
		            if(isset($Trace) && is_array($Trace) && count($Trace) > 0){
		                foreach ($Trace as $key => $value){
		                    echo <<<END
<dl class="dl-horizontal">
<dt>$value[create_datetime]</dt>
<dd>$value[creator]-$value[trace]</dd>
</dl>
END;
		                }
		            }
		        ?>
		</div>
		<div class="modal fade" id="dealerTraceModal" tabindex="-1" role="dialog" aria-labelledby="dealerTraceModalLabel" aria-hidden="true" >
    	   <div class="modal-dialog">
    	       <div class="modal-content">
    	           <form class="form-horizontal" id="dealerTraceForm" action="" method="post" role="form">
    					<div class="modal-header">
    	        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    	        			<h4 class="modal-title" id="dealerTraceModalLabel">经销商跟踪</h4>
    	      			</div>
    			      	<div class="modal-body">
    			      		<input type="hidden" name="selected" value="" />
    			      		<input type="hidden" name="dealer_id" value="<?php echo $Id;?>" />
    			      		<div class="form-group">
    			      			<label class="control-label col-md-2" >跟踪:</label>
    			      			<div class="col-md-8">
    			      			    <textarea class="form-control" placeholder='在此处填写跟踪内容' name="trace"></textarea>
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
    </div>
	<script type="text/javascript">
		(function($, window, undefined){
			$('#dealerTrace').handle_page();
			$('div#dealerTraceModal').handle_modal_000();
            $('#dealerTraceSearch input[name="keyword"]').keyup(function(){
                $('#dealerTraceDl').find('dl').hide().filter(":contains('"+( $(this).val() )+"')").show();
            });
		})(jQuery);
	</script>