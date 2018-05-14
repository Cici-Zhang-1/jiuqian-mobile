<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月6日
 * @author Administrator
 * @version
 * @des
 */
?>
        <div class="my-print-data container-fluid">
    		<div class="row">
    			<div class="col-md-offset-2 col-md-8">
    			    <?php
    			        if(isset($Drawing) && is_array($Drawing)){
    			            foreach ($Drawing as $key => $value){
    			                if($value && is_array($value)){
    			                    foreach ($value as $ikey => $ivalue){
    			                        echo '<img alt="图纸" src="'.$ivalue.'" />';
    			                    }
    			                }
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