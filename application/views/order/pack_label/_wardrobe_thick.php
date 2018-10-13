<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 15:26
 */
?>
<div role="tabpanel" class="tab-pane active" id="wardrobeThick">
    <form class="my-label-color-2 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
        <input type="hidden" name="code" value="y" />
        <input type="hidden" name="num" value="" />
        <input type="hidden" value="thick" name="classify" />
        <input type="hidden" name="order_product_id" value="" />
        <div class="form-group form-group-lg">
            <div class="col-md-12">
                <p class="my-label-title form-control-static" >衣柜-厚板</p>
            </div>
        </div>
        <div class="form-group form-group-lg">
            <div class="col-md-12">
                <input class="form-control" type="text" name="type" autocomplete="off" placeholder="订单类型X/B" />
            </div>
        </div>
        <div class="form-group form-group-lg" id="yThickDate">
            <div class="col-md-6">
                <select class="form-control" name="year" id="yThickYear"></select>
            </div>
            <div class="col-md-6">
                <select class="form-control" name="month" id="yThickMonth"></select>
            </div>
        </div>
        <div class="form-group form-group-lg">
            <div class="col-md-4">
                <input class="form-control" type="text" name="prefix" placeholder="大号" />
            </div>
            <div class="col-md-4">
                <input class="form-control" type="text" name="middle" placeholder="小号" />
            </div>
            <div class="col-md-4">
                <input class="form-control" type="text" name="pack" placeholder="包装件数" />
            </div>
        </div>
        <div class="hide brothers"></div>
        <div class="form-group form-group-lg">
            <div class="col-md-12">
                <p class="my-label-enhance form-control-static warning" ></p>
                <p class="my-label-enhance form-control-static error" ></p>
            </div>
        </div>
        <div class="form-group form-group-lg">
            <div class="col-md-12">
                <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/prints');?>" >衣柜厚板打印</a>
            </div>
        </div>
    </form>
</div>
