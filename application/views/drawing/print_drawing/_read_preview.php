<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月15日
 * @author Zhangcc
 * @version
 * @des
 */
?>
        <div class="my-print-data container-fluid">
    		<div class="row">
    			<div class="col-md-12">
    			    <?php
    			        if(isset($Drawing) && is_array($Drawing) && count($Drawing) > 0){
    			            foreach ($Drawing as $key => $value){
    			                echo '<img class="img-responsive" alt="图纸" src="'.drawing_url($value['path']).'" />';
    			            }
    			        }else{
    			            echo "没有图纸";
    			        }
    			    ?>
    			</div>
    		</div>
    	</div>
	</body>
</html>