<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月14日
 * @author Zhangcc
 * @version
 * @des
 */
?>
        <div class="my-print-data container-fluid">
    		<div class="row">
    			<div class="col-md-12">
    			    <?php
    			        if(isset($Drawing)){
	                        echo '<img class="img-responsive" alt="图纸" src="'.drawing_url($Drawing).'" />';
    			        }else{
    			            echo "没有图纸";
    			        }
    			    ?>
    			</div>
    		</div>
    	</div>
	</body>
</html>