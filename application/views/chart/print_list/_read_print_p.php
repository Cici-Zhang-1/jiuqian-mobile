<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月16日
 * @author Zhangcc
 * @version
 * @des
 */
?>
        <div class="my-print-data container-fluid">
            <!-- <div class="row hidden-print">
                <div class="col-md-12">
                    <div class="col-md-6"></div>
        	  		<div class="col-md-6">
        	  		    <button class="btn btn-primary" data-action="<?php echo site_url('order/print_list/edit');?>" type="button" id="printButton" value="打印"><i class="fa fa-save"></i>&nbsp;&nbsp;打印</button>
        	  		</div>
                </div>
            </div> -->
    		<div class="row">
    			<div class="col-md-8">
    			    <p class='my-enhance-1'>订单来源：<?php echo $Info['dealer'];?></p>
    			    <p class='my-enhance-1'>业主: <?php echo $Info['owner'];?></p>
    			    <p><?php echo $Info['remark'];?></p>
    			    <p><?php echo $Info['order_product_remark'];?></p>
    			</div>
    			<div class="my-box col-md-4">
    			    <p>订单编号: <span class="my-enhance-2"><?php echo $Info['order_product_num'];?></span></p>
    			    <p>要求出厂: <span class="my-enhance-2"><?php echo $Info['request_outdate'];?></span></p>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-md-12">
    			    <table class="table table-condensed" >
    			        <thead>
    			            <tr>
    			                <th>#</th>
    			                <th>类型</th>
    			                <th>名称</th>
    			                <th>数量</th>
    			                <th>单位</th>
    			                <th>备注</th>
    			            </tr>
    			        </thead>
    			        <tbody>
			                <?php
			                    if(isset($List) && is_array($List) && count($List) > 0){
			                        $Html = '';
			                        $K = 1;
			                        foreach($List as $key => $value){
			                            $Html .= <<<END
<tr>
    <td>$K</td>
    <td>$value[type]</td>
    <td>$value[name]</td>
    <td>$value[amount]</td>
    <td>$value[unit]</td>
    <td>$value[remark]</td>
</tr>
END;
			                            $K++;
			                        }
			                        echo $Html;
			                    }
			                ?>
    			        </tbody>
    			    </table>
    			</div>
    		</div>
    	</div>
    	<script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
  	    <script>
  	    (function($){
  	  	    $('#printButton').on('click', function(e){
  	  	  	    window.print();
  	  	  	  	$.ajax({
  	  	  	  	  async: false,
  	  	  	  	  data:{id:<?php echo $Info['opid'];?>},
  	              type: 'post',
  	              url: $(this).data('action'),
  	              beforeSend: function(ie){},
  	              dataType: 'json',
  	              success: function(msg){
  	                  if(msg.error == 0){
  	                      return true;
  	                  }else if(msg.error == 1){
  	  	                  alert(msg.message);
  	                  }
  	              },
  	              error: function(x,t,e){
  	              	if(x.responseText.length > 0){
  	  	              	alert(x.responseText);
  	              	}else{
  	              		alert('服务器执行错误, 提交失败!');
  	              	}
  	              }
  	           });
   	  	    });
  	    })(jQuery);
	    </script>
	</body>
</html>