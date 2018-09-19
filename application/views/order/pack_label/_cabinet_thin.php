<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 15:25
 */
?>
<div role="tabpanel" class="tab-pane" id="cabinetThin">
    <form class="my-label-color-7 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
        <input type="hidden" name="code" value="w" />
        <input type="hidden" name="num" value="" />
        <input type="hidden" name="classify" value="thin" />
        <input type="hidden" name="order_product_id" value="" />
        <div class="form-group form-group-lg">
            <div class="col-md-12">
                <p class="my-label-title form-control-static" >橱柜-薄板</p>
            </div>
        </div>
        <div class="form-group form-group-lg">
            <div class="col-md-6">
                <div class="radio">
                    <label class="my-label-enhance">
                        <input class="" type="radio" value="x" name="type" checked><span>正常单</span>
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="radio">
                    <label class="my-label-enhance">
                        <input class="" type="radio" value="b" name="type"><span>补单</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group form-group-lg" id="wThinDate">
            <div class="col-md-6">
                <select class="form-control" name="year" id="wThinYear"></select>
            </div>
            <div class="col-md-6">
                <select class="form-control" name="month" id="wThinMonth"></select>
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
        <div class="brothers"></div>
        <hr />
        <div class="form-group form-group-lg">
            <div class="col-md-12">
                <p class="my-label-enhance form-control-static warning" ></p>
                <p class="my-label-enhance form-control-static error" ></p>
            </div>
        </div>
        <div class="hide form-group form-group-lg">
            <div class="col-md-3">
                <div class="radio">
                    <label class="my-label-enhance">
                        <input type="radio" value="thin" name="classify" checked="checked"><span>薄板</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group form-group-lg">
            <div class="col-md-12">
                <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/prints');?>" >橱柜薄板打印</a>
            </div>
        </div>
    </form>
</div>
