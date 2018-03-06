<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月4日
 * @author Zhangcc
 * @version
 * @des
 * 拆单详情的浮动窗口
 */
 if(isset($Board) && is_array($Board) && count($Board) > 0){
     $Id = 0;
     echo <<<END
<table class="table table-bordered table-condensed">
    <tbody>
      	<tr><td>#</td><td>板材</td><td>数量</td><td>面积</td></tr>
END;
     foreach ($Board as $key => $value){
         $Id++;
         echo <<<END
<tr><td>$Id</td><td>$value[board]</td><td>$value[amount]</td><td>$value[area]</td></tr>
END;
     }
     echo <<<END
     </tbody>
</table>
END;
 }
 
 if(isset($Fitting) && is_array($Fitting) && count($Fitting) > 0){
     $Id = 0;
     echo <<<END
<table class="table table-bordered table-condensed">
    <tbody>
      	<tr><td>#</td><td>分类</td><td>名称</td><td>单位</td><td>数量</td></tr>
END;
     foreach ($Fitting as $key => $value){
         $Id++;
         echo <<<END
<tr><td>$Id</td><td>$value[type]</td><td>$value[name]</td><td>$value[unit]</td><td>$value[amount]</td></tr>
END;
     }
     echo <<<END
     </tbody>
</table>
END;
 }
 
 if(isset($Other) && is_array($Other) && count($Other) > 0){
     $Id = 0;
     echo <<<END
<table class="table table-bordered table-condensed">
    <tbody>
      	<tr><td>#</td><td>类型</td><td>名称</td><td>规格</td><td>单位</td><td>数量</td></tr>
END;
     foreach ($Other as $key => $value){
         $Id++;
         echo <<<END
<tr><td>$Id</td><td>$value[type]</td><td>$value[name]</td><td>$value[spec]</td><td>$value[unit]</td><td>$value[amount]</td></tr>
END;
     }
     echo <<<END
     </tbody>
</table>
END;
 }
 
 if(isset($Server) && is_array($Server) && count($Server) > 0){
     $Id = 0;
     echo <<<END
<table class="table table-bordered table-condensed">
    <tbody>
      	<tr><td>#</td><td>类型</td><td>名称</td><td>单位</td><td>数量</td></tr>
END;
     foreach ($Server as $key => $value){
         $Id++;
         echo <<<END
<tr><td>$Id</td><td>$value[type]</td><td>$value[name]</td><td>$value[unit]</td><td>$value[amount]</td></tr>
END;
     }
     echo <<<END
     </tbody>
</table>
END;
 }
