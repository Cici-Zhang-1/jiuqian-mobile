<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月15日
 * @author Administrator
 * @version
 * @des
 */
?>
        <div class="my-print-data container-fluid">
    		<div class="row">
    			<div class="col-md-12">
    			    <?php
    			        if(isset($PrintDrawing) && is_array($PrintDrawing) && count($PrintDrawing) > 0){
    			            foreach ($PrintDrawing as $key => $value){
    			                echo '<img alt="图纸" src="'.drawing_url($value['path']).'" />';
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