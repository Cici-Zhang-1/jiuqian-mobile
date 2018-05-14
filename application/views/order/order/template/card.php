<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/19
 * Time: 11:10
 *
 * Desc: 卡片和元素数据控制
 */
$TheadTemplate = array(
    'oid' => '<th class="td-xs checkall" data-name="selected" data-checkall=false>#</th>',
    'icon' => '<th >等级</th>',
    'order_num' => '<th >订单编号</th>',
    'dealer' => '<th >客户</th>',
    'owner' => '<th data-name="owner">业主</th>',
    'remark' => '<th data-name="remark" >备注</th>',
    'dealer_remark' => '<th data-name="dealer_remark" >客户备注</th>',
    'sum' => '<th >金额</th>',
    'balance' => '<th >账户余额</th>',
    'creator' => '<th >创建人</th>',
    'asure_datetime' => '<th >确认时间</th>',
    'request_outdate' => '<th data-name="request_outdate" >要求出厂</th>',
    'end_datetime' => '<th >发货日期</th>',
    'status' => '<th >状态</th>',
    'did' => '<th class="hide" ></th>',
    'delivery_phone' => '<th class="hide" data-name="delivery_phone" ></th>',
    'delivery_linker' => '<th class="hide" data-name="delivery_linker" ></th>',
    'delivery_address' => '<th class="hide" data-name="delivery_address" ></th>',
    'delivery_area' => '<th class="hide" data-name="delivery_area" ></th>',
    'out_method' => '<th class="hide" data-name="out_method" ></th>',
    'logistics' => '<th class="hide" data-name="logistics" ></th>',
    'payer_phone' => '<th class="hide" data-name="payer_phone" ></th>',
    'payer' => '<th class="hide" data-name="payer" ></th>',
    'payterms' => '<th class="hide" data-name="payterms" ></th>',
    'checker_phone' => '<th class="hide" data-name="checker_phone" ></th>',
    'checker' => '<th class="hide" data-name="checker" ></th>',
    'flag' => '<th class="hide" data-name="flag" ></th>'
);
$TbodyTemplate = array(
    'oid' => '<td ><input name="oid"  type="checkbox" value=""/></td>',
    'icon' => '<td name="icon"></td>',
    'order_num' => '<td name="order_num"><a href="' . site_url('order/order_detail/index/read/order') . '" title="订单详情" data-toggle="floatover" data-target="#orderFloatover" data-remote="' . site_url('order/order_detail/index/read_floatover') . '"></a></td>',
    'dealer' => '<td name="dealer"><a href="' . site_url('dealer/dealer_debt/index/read') . '" data-toggle="blank" target="_blank"></a></td>',
    'owner' => '<td name="owner"></td>',
    'remark' => '<td name="remark"></td>',
    'dealer_remark' => '<td name="dealer_remark"></td>',
    'sum' => '<td name="sum"></td>',
    'balance' => '<td name="balance"></td>',
    'creator' => '<td name="creator"></td>',
    'asure_datetime' => '<td name="asure_datetime"></td>',
    'request_outdate' => '<td name="request_outdate"></td>',
    'end_datetime' => '<td name="end_datetime"></td>',
    'status' => '<td name="status"></td>',
    'did' => '<td class="hide" name="did" ></td>',
    'delivery_phone' => '<td class="hide" name="delivery_phone" ></td>',
    'delivery_linker' => '<td class="hide" name="delivery_linker" ></td>',
    'delivery_address' => '<td class="hide" name="delivery_address" ></td>',
    'delivery_area' => '<td class="hide" name="delivery_area" ></td>',
    'out_method' => '<td class="hide" name="out_method" ></td>',
    'logistics' => '<td class="hide" name="logistics" ></td>',
    'payer_phone' => '<td class="hide" name="payer_phone" ></td>',
    'payer' => '<td class="hide" name="payer" ></td>',
    'payterms' => '<td class="hide" name="payterms" ></td>',
    'checker_phone' => '<td class="hide" name="checker_phone" ></td>',
    'checker' => '<td class="hide" name="checker" ></td>',
    'flag' => '<td class="hide" name="flag" ></td>',
);
if (isset($Card) && is_array($Card) && in_array('订单列表', $Card)) {
    if (isset($Element) && is_array($Element) && isset($Element['订单列表'])) {
        $E = $Element['订单列表'];
        foreach ($TheadTemplate as $Key => $Value) {
            if (!in_array($Key, $E)) {
                unset($TheadTemplate[$Key]);
                unset($TbodyTemplate[$Key]);
            }
        }
        if (count($TheadTemplate) > 0) {
            $TheadHtml = implode('', $TheadTemplate);
            $TbodyHtml = implode('', $TbodyTemplate);
            $CardDataFetchUrl = site_url('order/order/read');
            echo <<<END
<table class="table table-bordered table-striped table-hover table-responsive table-condensed" id="orderTable" data-load="$CardDataFetchUrl">
    <thead>
    <tr>
        $TheadHtml
    </tr>
    </thead>
    <tbody>
    <tr class="loading"><td colspan="15">加载中...</td></tr>
    <tr class="no-data"><td colspan="15">没有数据</td></tr>
    <tr class="model">
        $TbodyHtml
    </tr>
    </tbody>
</table>
<div class="hide btn-group pull-right paging">
    <p class="footnote"></p>
    <ul class="pagination">
        <li><a href="1">首页</a></li>
        <li class=""><a href="javascript:void(0);" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
        <li><a href=""></a></li>
        <li class=""><a href="" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
        <li><a href="">尾页</a></li>
    </ul>
</div>
END;
        }
    }
}
