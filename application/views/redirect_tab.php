<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月12日
 * @author Zhangcc
 * @version
 * @des
 * 重新载入标签页
 */
?>
<script>
	(function($, window, undefined){
		<?php
		/* if(!empty($msg)){
		    echo <<<END
$.tabRefresh({type:'new',url: '$url'});
END;
		}else{
		    echo <<<END
if(confirm('确认$msg?')){
     $.tabRefresh({type:'new',url: '$url'});
}else{
     $.tabDelete();
}
END;
		} */
		?>
		$.tabRefresh({type:'new',url: '<?php echo $url;?>'});
	})(jQuery, window, undefined);
</script>