<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2014-9-24
 * @author ZhangCC
 * @version
 * @description
 * 生成Html
 */
 
function board_table($Info, $Product, $Url){
    $Info['name'] = $Info['name'].'清单';
    $Table = <<<END
<table class="table table-bordered table-striped table-hover table-responsive table-condensed">
     <thead>
         <tr>
             <th class="td-md"><a href="$Url" data-title="$Info[name]" title="$Product" data-toggle="tooltip" data-placement="right">$Info[order_product_num]</a>$Product</th>
             <th class="td-md">板材</th>
             <th class="td-sm">数量</th>
             <th class="td-sm">面积</th>
             <th>单价</th>
             <th>金额</th>
         </tr>
     </thead>
     <tbody>
END;
    $I = 1;
    foreach($Info['detail'] as $key=>$value){
        $Table .= <<<END
<tr>
    <td>$I</td>
    <td >$value[board]</td>
    <td>$value[amount]</td>
    <td>$value[area]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    $Table .= <<<END
     </tbody>
    <tfoot>
        <tr><td colspan="15">$Info[remark]</td></tr>
        <tr><td colspan="15">入库件数: $Info[pack], 当前状态: $Info[status], 库位: $Info[position]</td></tr>
    </tfoot>
</table>
END;
    return $Table;
}
/**
 * 板块列表
 * @param unknown $Board
 * @param unknown $OrderProductNum
 * @param unknown $Product
 * @param unknown $Url
 * @param string $Name
 * @return string
 */
/* function board_table($Board,$OrderProductNum, $Product, $Url, $Name=''){
    $Name = $Name.'清单';
    $Table = <<<END
<table class="table table-bordered table-striped table-hover table-responsive table-condensed">
     <thead>
         <tr>
             <th class="td-md"><a href="$Url" data-title="$Name" title="$Product" data-toggle="tooltip" data-placement="right">$OrderProductNum</a>$Name</th>
             <th class="td-md">板材</th>
             <th class="td-sm">数量</th>
             <th class="td-sm">面积</th>
             <th>单价</th>
             <th>金额</th>
         </tr>
     </thead>
     <tbody>
END;
    $I = 1;
    foreach($Board as $key=>$value){
        $Table .= <<<END
<tr>
    <td>$I</td>
    <td >$value[board]</td>
    <td>$value[amount]</td>
    <td>$value[area]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    $Table .= <<<END
     </tbody>
</table>
END;
    return $Table;
} */

function board_table_tr($Board){
    $Tr = <<<END
    <tr>
        <td>#</td>
        <td>板材</td>
        <td>数量</td>
        <td>面积</td>
        <td>单价</td>
        <td>金额</td>
    </tr>
END;
    $I = 1;
    foreach($Board as $key=>$value){
        $Tr .= <<<END
<tr>
    <td>$I</td>
    <td >$value[board]</td>
    <td>$value[amount]</td>
    <td>$value[area]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    return $Tr;
}

function fitting_table($Info, $Product, $Url){
    $Table = <<<END
<table class="table table-bordered table-striped table-hover table-responsive table-condensed">
     <thead>
         <tr>
         <th class="td-md"><a href="$Url" data-title="配件清单" title="$Product" data-toggle="tooltip" data-placement="right">$Info[order_product_num]</a></th>
         <th class="td-md">分类</th>
         <th class="td-md">名称</th>
         <th class="td-sm">单位</th>
         <th class="td-sm">数量</th>
         <th>单价</th>
         <th>金额</th>
         </tr>
     </thead>
     <tbody>
END;
    $I = 1;
    foreach($Info['detail'] as $key=>$value){
        $Table .= <<<END
<tr>
    <td>$I</td>
    <td >$value[type]</td>
    <td >$value[name]</td>
    <td>$value[unit]</td>
    <td>$value[amount]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    $Table .= <<<END
     </tbody>
    <tfoot>
        <tr><td colspan="15">$Info[remark]</td></tr>
        <tr><td colspan="15">入库件数: $Info[pack], 当前状态: $Info[status]</td></tr>
    </tfoot>
</table>
END;
    return $Table;
}
/* function fitting_table($Fitting,$OrderProductNum, $Product, $Url){
    $Table = <<<END
<table class="table table-bordered table-striped table-hover table-responsive table-condensed">
     <thead>
         <tr>
         <th class="td-md"><a href="$Url" data-title="配件清单" title="$Product" data-toggle="tooltip" data-placement="right">$OrderProductNum</a></th>
         <th class="td-md">分类</th>
         <th class="td-md">名称</th>
         <th class="td-sm">单位</th>
         <th class="td-sm">数量</th>
         <th>单价</th>
         <th>金额</th>
         </tr>
     </thead>
     <tbody>
END;
    $I = 1;
    foreach($Fitting as $key=>$value){
        $Table .= <<<END
<tr>
    <td>$I</td>
    <td >$value[type]</td>
    <td >$value[name]</td>
    <td>$value[unit]</td>
    <td>$value[amount]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    $Table .= <<<END
     </tbody>
</table>
END;
    return $Table;
} */

function fitting_table_tr($Fitting){
    $Tr = <<<END
    <tr>
        <td>#</td>
        <td>分类</td>
        <td>名称</td>
        <td>单位</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
    </tr>
END;
    $I = 1;
    foreach($Fitting as $key=>$value){
        $Tr .= <<<END
<tr>
    <td>$I</td>
    <td >$value[type]</td>
    <td >$value[name]</td>
    <td>$value[unit]</td>
    <td>$value[amount]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    return $Tr;
}

function other_table($Info, $Product, $Url){
    $Table = <<<END
<table class="table table-bordered table-striped table-hover table-responsive table-condensed">
     <thead>
         <tr>
         <th class="td-md"><a href="$Url" data-title="外购清单" title="$Product" data-toggle="tooltip" data-placement="right">$Info[order_product_num]</a></th>
         <th class="td-md">分类</th>
         <th class="td-md">名称</th>
         <th class="td-md">规格</th>
         <th class="td-sm">单位</th>
         <th class="td-sm">数量</th>
         <th>单价</th>
         <th>金额</th>
             </tr>
     </thead>
     <tbody>
END;
    $I = 1;
    foreach($Info['detail'] as $key=>$value){
        $Table .= <<<END
<tr>
    <td>$I</td>
    <td >$value[type]</td>
    <td >$value[name]</td>
    <td >$value[spec]</td>
    <td>$value[unit]</td>
    <td>$value[amount]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    $Table .= <<<END
     </tbody>
    <tfoot>
        <tr><td colspan="15">$Info[remark]</td></tr>
        <tr><td colspan="15">入库件数: $Info[pack], 当前状态: $Info[status]</td></tr>
    </tfoot>
</table>
END;
    return $Table;
}
/* function other_table($Other,$OrderProductNum, $Product, $Url){
    $Table = <<<END
<table class="table table-bordered table-striped table-hover table-responsive table-condensed">
     <thead>
         <tr>
         <th class="td-md"><a href="$Url" data-title="外购清单" title="$Product" data-toggle="tooltip" data-placement="right">$OrderProductNum</a></th>
         <th class="td-md">分类</th>
         <th class="td-md">名称</th>
         <th class="td-md">规格</th>
         <th class="td-sm">单位</th>
         <th class="td-sm">数量</th>
         <th>单价</th>
         <th>金额</th>
             </tr>
     </thead>
     <tbody>
END;
    $I = 1;
    foreach($Other as $key=>$value){
        $Table .= <<<END
<tr>
    <td>$I</td>
    <td >$value[type]</td>
    <td >$value[name]</td>
    <td >$value[spec]</td>
    <td>$value[unit]</td>
    <td>$value[amount]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    $Table .= <<<END
     </tbody>
</table>
END;
    return $Table;
} */

function other_table_tr($Other){
    $Tr = <<<END
    <tr>
        <td>#</td>
        <td>分类</td>
        <td>名称</td>
        <th>规格</th>
        <td>单位</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
    </tr>
END;
    $I = 1;
    foreach($Other as $key=>$value){
        $Tr .= <<<END
<tr>
    <td>$I</td>
    <td >$value[type]</td>
    <td >$value[name]</td>
    <td >$value[spec]</td>
    <td>$value[unit]</td>
    <td>$value[amount]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    return $Tr;
}

function server_table($Info, $Product, $Url){
    $Table = <<<END
<table class="table table-bordered table-striped table-hover table-responsive table-condensed">
     <thead>
        <tr>
         <th class="td-md"><a href="$Url" data-title="服务清单" title="$Product" data-toggle="tooltip" data-placement="right">$Info[order_product_num]</a></th>
         <th class="td-md">分类</th>
         <th class="td-md">名称</th>
         <th class="td-sm">单位</th>
         <th class="td-sm">数量</th>
         <th>单价</th>
         <th>金额</th>
             </tr>
     </thead>
     <tbody>
END;
    $I = 1;
    foreach($Info['detail'] as $key=>$value){
        $Table .= <<<END
<tr>
    <td>$I</td>
    <td >$value[type]</td>
    <td >$value[name]</td>
    <td>$value[unit]</td>
    <td>$value[amount]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    $Table .= <<<END
     </tbody>
     <tfoot>
        <tr><td colspan="15">$Info[remark]</td></tr>
     </tfoot>
</table>
END;
    return $Table;
}
/* function server_table($Server,$OrderProductNum, $Product, $Url){
    $Table = <<<END
<table class="table table-bordered table-striped table-hover table-responsive table-condensed">
     <thead>
        <tr>
         <th class="td-md"><a href="$Url" data-title="服务清单" title="$Product" data-toggle="tooltip" data-placement="right">$OrderProductNum</a></th>
         <th class="td-md">分类</th>
         <th class="td-md">名称</th>
         <th class="td-sm">单位</th>
         <th class="td-sm">数量</th>
         <th>单价</th>
         <th>金额</th>
             </tr>
     </thead>
     <tbody>
END;
    $I = 1;
    foreach($Server as $key=>$value){
        $Table .= <<<END
<tr>
    <td>$I</td>
    <td >$value[type]</td>
    <td >$value[name]</td>
    <td>$value[unit]</td>
    <td>$value[amount]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    $Table .= <<<END
     </tbody>
</table>
END;
    return $Table;
} */

function server_table_tr($Server){
    $Tr = <<<END
    <tr>
        <td>#</td>
        <td>分类</td>
        <td>名称</td>
        <td>单位</td>
        <td>数量</td>
        <td>单价</td>
        <td>金额</td>
    </tr>
END;
    $I = 1;
    foreach($Server as $key=>$value){
        $Tr .= <<<END
<tr>
    <td>$I</td>
    <td >$value[type]</td>
    <td >$value[name]</td>
    <td>$value[unit]</td>
    <td>$value[amount]</td>
    <td>$value[unit_price]</td>
    <td>$value[sum]</td></tr>
END;
        $I++;
    }
    return $Tr;
}