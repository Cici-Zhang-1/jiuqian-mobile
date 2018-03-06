<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月4日
 * @author Zhangcc
 * @version
 * @des
 * 关闭Tab页面
 */
?>
<script>
	(function($, window, undefined){
		var Msg = '<?php echo $msg;?>';
		alert(Msg);
		$.tabDelete();
	})(jQuery, window, undefined);
</script>