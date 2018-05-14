<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-5-5
 * @author ZhangCC
 * @version
 * @description  
 */
	if(isset($Process) && is_array($Process) && count($Process) > 0){
		foreach ($Process as $key=>$value){
			$value['remark']= htmlspecialchars_decode($value['remark']);
			echo <<<END
<dl>
<dt><i class="fa fa-user"></i>&nbsp;&nbsp;$value[truename]&nbsp;&nbsp;$value[create_datetime]</dt>
<dd>$value[name]&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;$value[status_text]</dd>
<dd>$value[remark]</dd>
</dl>
END;
		}
	}else{
		echo <<<END
		<h3>没有找到对应订单的工作流!</h3>
END;
	}
?>
