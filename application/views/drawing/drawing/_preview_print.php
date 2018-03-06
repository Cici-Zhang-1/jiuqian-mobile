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
    		    <?php
			        if(isset($Drawing) && is_array($Drawing) && count($Drawing) > 0){
			            $Html = '';
			            foreach ($Drawing as $key => $value){
			                $Url = drawing_url($value['path']);
			                $Html .= <<<END
<div class="row">
    <div class="col-md-12">
        <img class="page-line" alt="图纸" src="$Url" />
    </div>
</div>
END;
			                echo $Html;
			            }
			        }else{
			            echo "没有图纸";
			        }
			    ?>
    	</div>
	</body>
</html>