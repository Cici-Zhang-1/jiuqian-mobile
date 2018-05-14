<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/19
 * Time: 11:09
 *
 * Desc: 功能权限公知
 */
$GroupFuncTemplate = array(
    '编辑' => '<li><a href="javascript:void(0);" data-toggle="modal" data-target="#orderModal" data-action="' . site_url('order/order/edit') . '" data-multiple=false><i class="fa fa-pencil"></i>&nbsp;&nbsp;编辑</a></li>',
    '进程图' => '<li><a href="javascript:void(0);" data-toggle="child" data-target="#orderTable" data-action="' . site_url('data/workflow_msg/index/read') . '" data-multiple=false><i class="fa fa-hourglass-1"></i>&nbsp;&nbsp;进程图</a></li>'
);
$FuncTemplate = array(
    '刷新' => '<button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>',
    '作废' => '<a class="btn btn-default" data-toggle="backstage" href="' . site_url('order/order/remove') . '" data-target="#orderTable" data-multiple=true><i class="fa fa-trash-o"></i>&nbsp;&nbsp;作废</a>'
);
if (isset($Func) && is_array($Func) && count($Func) > 0) {
    foreach ($GroupFuncTemplate as $Key => $Value) {
        if (!in_array($Key, $Func)) {
            unset($GroupFuncTemplate[$Key]);
        }
    }
    foreach ($FuncTemplate as $Key => $Value) {
        if (!in_array($Key, $Func)) {
            unset($FuncTemplate[$Key]);
        }
    }
    if (count($GroupFuncTemplate) > 0) {
        $GroupFuncHtml = <<<END
    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        共选中<span id="orderTableSelected" data-num="">0</span>项
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" data-table="#orderTable">
END;
        $GroupFuncHtml .= implode('<li role="separator" class="divider"></li>', $GroupFuncTemplate);
        $GroupFuncHtml .= <<<END
    </ul>
END;
    }else {
        $GroupFuncHtml = '';
    }
    if (count($FuncTemplate) > 0) {
        $FuncHtml = implode('', $FuncTemplate);
    }else {
        $FuncHtml = '';
    }
    if (!empty($FuncHtml) || !empty($GroupFuncHtml)) {
        echo <<<END
<div class="btn-group" role="group">
    $GroupFuncHtml
    $FuncHtml
</div>
END;
    }
}
