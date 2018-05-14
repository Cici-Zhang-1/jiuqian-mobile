<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/19
 * Time: 11:09
 *
 * Desc: 搜索权限控制
 */
$StartDate = date('Y-m-d', time()-MONTHS);
$PSTemplate = array(
    'status' => '<input type="hidden" name="status" value=""/>',
    'start_date' => '<input type="hidden" name="start_date" value="' . $StartDate . '"/>',
    'end_date' => '<input type="hidden" name="end_date" value=""/>',
    'keyword' => '<input type="text" class="form-control" name="keyword" placeholder="订单编号/经销商/业主/备注">'
);
if (isset($PageSearch) && is_array($PageSearch) && count($PageSearch) > 0) {
    foreach ($PSTemplate as $Key => $Value) {
        if (!in_array($Key, $PageSearch)) {
            unset($PSTemplate[$Key]);
        }
    }
    if (count($PSTemplate) > 0) {
        $PSHtml = implode('', $PSTemplate);
        echo <<<END
<div class="input-group" id="orderSearch" data-toggle="filter" data-target="#orderTable">
    <span class="input-group-btn">
        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#orderFilterModal"><i class="fa fa-search"></i></button>
    </span>
    $PSHtml
    <span class="input-group-btn">
        <button class="btn btn-default" type="submit">Go!</button>
    </span>
</div>
END;
    }
}
