<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月18日
 * @author Zhangcc
 * @version
 * @des
 * 打包标签打印
 */
?>
        <style>
            .my-label{
	            font-size: 36px;
                font-weight: bold;
            }
            .my-label .my-label-title{
            	text-align:center; 
            }
            .radio label.my-label-enhance, .checkbox label.my-label-enhance{
	            font-weight: bold;
            }
            .radio label.my-label-enhance input[type="radio"], .checkbox label.my-label-enhance input[type="checkbox"]{
	            margin-top: 20px;
            }
            .radio label.my-label-enhance input:checked+span, .checkbox label.my-label-enhance input:checked+span{
	            font-size: 48px;
            	color: lightseagreen;
            }
            .my-label-color-1{
	            color: red;
            }
            .my-label-color-2{
	            color: green;    
            }
            .my-label-color-3{
	            color: fuchsia;
            }
            .my-label-color-4{
	            color: olive;
            }
            .my-label-color-5{
	            color: blue;
            }
            .my-label-color-6{
	            color: black;
            }
            .my-label-color-7{
                color: navy;
            }
            .my-label-color-8{
                color: teal;
            }
            .my-label-color-9{
                color: chocolate;
            }
            .my-label-color-10{
                color: mediumvioletred;
            }
            .print-area {
                width: 10cm;
            }
            .print-label {
                position: relative;
                width: 100%;
                height: 7.9cm;
                page-break-after: always;
            }
            .print-label > div {
                display: table;
                margin-left: 2mm;
                margin-top: auto;
                margin-bottom: auto;
                width: 66mm;
            }
            .print-label > div > .middle{
                display:table-cell;
                vertical-align:middle;
                width:100%;
            }
            .print-label .delivery-address {
                height: 23mm;
                font-size: 8mm;
                font-weight: bold;
                text-align: center;
            }
            .print-label .order-product-num {
                height: 7.8mm;
                font-size: 5mm;
                font-weight: bold;
                text-align: center;
            }
            .print-label .package-type, .print-label .dealer, .print-label .delivery-linker, .print-label .owner, .print-label .product, .print-label .date {
                height: 7.8mm;
                font-size: 4mm;
                font-weight: bolder;
                text-align: right;
            }
            .print-label > img {
                position: absolute;
                width: 100%;
                height: 79.5mm;
                top: 0;
                left: 0;
                z-index: -1;
            }
            .print-label .package-total {
                position: absolute;
                top: 37mm;
                left: 72mm;
                width: 21mm;
                height: 15mm;
                font-weight: bolder;
                text-align: center;
                border-bottom: 2px solid #000000;
            }
            .print-label .package-total .package-num {
                font-size: 7mm;
            }
            .print-label > .qrcode {
                width: 28mm;
                height: 28mm;
                position: absolute;
                top: 4mm;
                left: 69mm;
            }
        </style>
        <div class="container-fluid hidden-print">
            <div class="row" id="packLabelSelect">
                <div class="col-md-offset-2 col-md-8">
                    <ul class="nav nav-tabs" role="tablist" id="packLabelNavs">
                        <li role="presentation" class="active">
                            <a href="#cabinetThick" aria-controls="cabinetThick" role="tab" data-toggle="tab">橱柜-厚板</a>
                        </li>
                        <li role="presentation" >
                            <a href="#cabinetThin" aria-controls="cabinetThin" role="tab" data-toggle="tab">橱柜-薄板</a>
                        </li>
                        <li role="presentation" >
                            <a href="#cabinetAll" aria-controls="cabinetAll" role="tab" data-toggle="tab">橱柜-所有</a>
                        </li>
                        <li role="presentation" >
                            <a href="#wardrobeThick" aria-controls="wardrobeThick" role="tab" data-toggle="tab">衣柜-厚板</a>
                        </li>
                        <li role="presentation" >
                            <a href="#wardrobeThin" aria-controls="wardrobeThin" role="tab" data-toggle="tab">衣柜-薄板</a>
                        </li>
                        <li role="presentation" >
                            <a href="#wardrobeAll" aria-controls="wardrobeAll" role="tab" data-toggle="tab">衣柜-所有</a>
                        </li>
                        <li role="presentation" >
                            <a href="#door" aria-controls="door" role="tab" data-toggle="tab">门板</a>
                        </li>
                        <li role="presentation" >
                            <a href="#wood" aria-controls="wood" role="tab" data-toggle="tab">拼框门</a>
                        </li>
                        <li role="presentation" >
                            <a href="#fitting" aria-controls="fitting" role="tab" data-toggle="tab">配件</a>
                        </li>
                        <li role="presentation" >
                            <a href="#other" aria-controls="other" role="tab" data-toggle="tab">外购</a>
                        </li>
                        <li role="presentation" >
                            <a href="#setting" aria-controls="setting" role="tab" data-toggle="tab">设置</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="packLabel">
                        <div role="tabpanel" class="tab-pane active" id="cabinetThick">
                            <form class="my-label-color-1 my-label form-horizontal" role="form" method="get" action="<?php echo site_url('order/pack_label/prints')?>">
                                <input type="hidden" name="code" value="w" />
                                <input type="hidden" name="num" value="" />
                                <input type="hidden" name="classify" value="thick" />
                                <input type="hidden" name="order_product_id" value="" />
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-title form-control-static">橱柜-厚板</p>
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
                                <div class="form-group form-group-lg" id="wThickDate">
                                    <div class="col-md-6">
                                        <select class="form-control" name="year" id="wThickYear"></select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="month" id="wThickMonth"></select>
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
                                        <p class="my-label-enhance form-control-static error"></p>
                                    </div>
                                </div>
                                <div class="hide form-group form-group-lg">
                                    <div class="col-md-3">
                                        <div class="radio">
                                            <label class="my-label-enhance">
                                                <input type="radio" value="thick" name="classify" checked="checked" ><span>厚板</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/prints');?>" >橱柜厚板打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
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
                        <div role="tabpanel" class="tab-pane" id="cabinetAll">
                            <form class="my-label-color-8 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="w" />
                                <input type="hidden" name="num" value="" />
                                <input type="hidden" name="classify" value="both" />
                                <input type="hidden" name="order_product_id" value="" />
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-title form-control-static" >橱柜-所有</p>
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
                                <div class="form-group form-group-lg" id="wAllDate">
                                    <div class="col-md-6">
                                        <select class="form-control" name="year" id="wAllYear"></select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="month" id="wAllMonth"></select>
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
                                        <p class="my-label-enhance form-control-static error" ></p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/prints');?>" >橱柜所有打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="wardrobeThick">
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
                        <div role="tabpanel" class="tab-pane" id="wardrobeThin">
                            <form class="my-label-color-9 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="y" />
                                <input type="hidden" name="num" value="" />
                                <input type="hidden" value="thin" name="classify" />
                                <input type="hidden" name="order_product_id" value="" />
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-title form-control-static" >衣柜-薄板</p>
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
                                <div class="form-group form-group-lg" id="yThinDate">
                                    <div class="col-md-6">
                                        <select class="form-control" name="year" id="yThinYear"></select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="month" id="yThinMonth"></select>
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
                                        <p class="my-label-enhance form-control-static error" ></p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/prints');?>" >衣柜薄板打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="wardrobeAll">
                            <form class="my-label-color-10 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="y" />
                                <input type="hidden" name="num" value="" />
                                <input type="hidden" value="both" name="classify" />
                                <input type="hidden" name="order_product_id" value="" />
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-title form-control-static" >衣柜-所有</p>
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
                                <div class="form-group form-group-lg" id="yAllDate">
                                    <div class="col-md-6">
                                        <select class="form-control" name="year" id="yAllYear"></select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="month" id="yAllMonth"></select>
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
                                        <p class="my-label-enhance form-control-static error" ></p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/prints');?>" >衣柜所有打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="door">
                            <form class="my-label-color-3 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="m" />
                                <input type="hidden" name="num" value="" />
                                <input type="hidden" value="both" name="classify" />
                                <input type="hidden" name="order_product_id" value="" />
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-title form-control-static" >门板</p>
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
                                <div class="form-group form-group-lg" id="mDate">
                                    <div class="col-md-6">
                                        <select class="form-control" name="year" id="mYear"></select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="month" id="mMonth"></select>
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
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-enhance form-control-static error"></p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-9">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/prints');?>" >门板打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="wood">
                            <form class="my-label-color-4 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="k" />
                                <input type="hidden" name="num" value="" />
                                <input type="hidden" value="both" name="classify" />
                                <input type="hidden" name="order_product_id" value="" />
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-title form-control-static" >拼框门</p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-6">
                                        <div class="radio">
                                            <label class="my-label-enhance ">
                                                <input class="" type="radio" value="x" name="type" checked><span>正常单</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="radio">
                                            <label class="my-label-enhance ">
                                                <input class="" type="radio" value="b" name="type"><span>补单</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg" id="kDate">
                                    <div class="col-md-6">
                                        <select class="form-control" name="year" id="kYear"></select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="month" id="kMonth"></select>
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
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-enhance form-control-static error"></p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-9">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/prints');?>" >拼框门打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="fitting">
                            <form class="my-label-color-5 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="p" />
                                <input type="hidden" name="num" value="" />
                                <input type="hidden" value="both" name="classify" />
                                <input type="hidden" name="order_product_id" value="" />
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-title form-control-static" >配件</p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-6">
                                        <div class="radio">
                                            <label class="my-label-enhance ">
                                                <input class="" type="radio" value="x" name="type" checked><span>正常单</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="radio">
                                            <label class="my-label-enhance ">
                                                <input class="" type="radio" value="b" name="type"><span>补单</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg" id="pDate">
                                    <div class="col-md-6">
                                        <select class="form-control" name="year" id="pYear"></select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="month" id="pMonth"></select>
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
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-enhance form-control-static error" ></p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-9">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/prints');?>" >配件打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="other">
                            <form class="my-label-color-6 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="g" />
                                <input type="hidden" name="num" value="" />
                                <input type="hidden" value="both" name="classify" />
                                <input type="hidden" name="order_product_id" value="" />
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-title form-control-static">外购</p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-6">
                                        <div class="radio">
                                            <label  class="my-label-enhance ">
                                                <input class="" type="radio" value="x" name="type" checked><span>正常单</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="radio">
                                            <label  class="my-label-enhance ">
                                                <input class="" type="radio" value="b" name="type"><span>补单</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg" id="gDate">
                                    <div class="col-md-6">
                                        <select class="form-control" name="year" id="gYear"></select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="month" id="gMonth"></select>
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
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <p class="my-label-enhance form-control-static error"></p>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-9">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/prints');?>" >外购打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="setting">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="cabinetThick">橱柜-厚板
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="cabinetThin">橱柜-薄板
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="cabinetAll">橱柜-所有
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="wardrobeThick">衣柜-厚板
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="wardrobeThin">衣柜-薄板
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="wardrobeAll">衣柜-所有
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="door">门板
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="wood">拼框门
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="fitting">配件
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="other">外购
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row hide" id="packLabelInfo">
                <div class="col-md-offset-2 col-md-8" style="border: 3px solid red; border-radius: 10px;font-size: 32px;">
                    <dl class="dl-horizontal">
                        <dt>订单编号:</dt>
                        <dd id="packLabelInfoNum"></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>客户:</dt>
                        <dd id="packLabelInfoDealer"></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>业主:</dt>
                        <dd id="packLabelInfoOwner"></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>货到地址:</dt>
                        <dd id="packLabelInfoAddress"></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>联系人:</dt>
                        <dd id="packLabelInfoLinker"></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>联系方式:</dt>
                        <dd id="packLabelInfoPhone"></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>打包件数:</dt>
                        <dd id="packLabelInfoPack"></dd>
                    </dl>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-offset-4 col-md-8">
                    <button class="btn btn-primary btn-lg" value="打印" id="print" type="button">打印</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-default btn-lg" value="预览" id="preview" type="button">预览</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-default btn-lg" value="返回" id="back" type="button">返回</button>&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </div>
        <div class="print-area visible-print-block" id="packLabelLabel">
        </div>
    </body>
    <script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('js/jquery-qrcode/jquery.qrcode.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/jquery.storage.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('js/dateselect.js');?>"></script>
    <script>
        Date.prototype.Format = function (fmt) {
            var o = {
                'M+': this.getMonth() + 1,
                'd+': this.getDate(),
                'H+': this.getHours(),
                'm+': this.getMinutes(),
                's+': this.getSeconds(),
                'S+': this.getMilliseconds()
            }

            if (/(y+)/.test(fmt)) {
                fmt = fmt.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length))
            }
            for (var k in o) {
                if (new RegExp('(' + k + ')').test(fmt)) {
                    fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? (o[k]) : (('00' + o[k]).substr(String(o[k]).length)))
                }
            }
            return fmt
        }
        let BaseUrl = '<?php echo base_url(); ?>';
        let PubUrl = '<?php echo pub_url(); ?>';
        let SiteUrl = '<?php echo site_url(); ?>';

        (function($){
            $("#wThickDate").DateSelector({
                ctlYearId: 'wThickYear',
                ctlMonthId: 'wThickMonth',
                minYear: 2014
            });
            $("#wThinDate").DateSelector({
                ctlYearId: 'wThinYear',
                ctlMonthId: 'wThinMonth',
                minYear: 2014
            });
            $("#wAllDate").DateSelector({
                ctlYearId: 'wAllYear',
                ctlMonthId: 'wAllMonth',
                minYear: 2014
            });
            $("#yThickDate").DateSelector({
                ctlYearId: 'yThickYear',
                ctlMonthId: 'yThickMonth',
                minYear: 2014
            });
            $("#yThinDate").DateSelector({
                ctlYearId: 'yThinYear',
                ctlMonthId: 'yThinMonth',
                minYear: 2014
            });
            $("#yAllDate").DateSelector({
                ctlYearId: 'yAllYear',
                ctlMonthId: 'yAllMonth',
                minYear: 2014
            });
            $("#mDate").DateSelector({
                  ctlYearId: 'mYear',
                  ctlMonthId: 'mMonth',
                  minYear: 2014
            });
            $("#kDate").DateSelector({
                  ctlYearId: 'kYear',
                  ctlMonthId: 'kMonth',
                  minYear: 2014
            });
            $("#pDate").DateSelector({
                  ctlYearId: 'pYear',
                  ctlMonthId: 'pMonth',
                  minYear: 2014
            });
            $("#gDate").DateSelector({
                  ctlYearId: 'gYear',
                  ctlMonthId: 'gMonth',
                  minYear: 2014
            });

            let OrderProduct = {};
            let Pack = 0;
            let Classify = '';
            let Brothers;
            $('#packLabel').find('form').each(function(i, v){
                var $Form = $(this);
                $Form.find('input[name = "prefix"], input[name="middle"]').on('focusout', function(e){
                    let Data = {};
                    Data['prefix'] = $Form.find('input[name = "prefix"]').val();
                    Data['middle'] = $Form.find('input[name = "middle"]').val();
                    Data['year'] = $Form.find('select[name = "year"]').val();
                    Data['month'] = $Form.find('select[name="month"]').val();
                    Data['code'] = $Form.find('input[name="code"]').val();
                    Data['type'] = $Form.find('input[name="type"]:checked').val();
                    if('' != Data['Prefix'] && '' != Data['middle']){
                        $.ajax({
                            async: false,
                            data: Data,
                            type: 'post',
                            url: '<?php echo site_url('order/pack_label/read');?>',
                            dataType: 'json',
                            success: function(res){
                                if(res.code == 0) {
                                    let Text = res.contents.num+'   [   '+ res.contents.pack+'   ]件<br />';
                                    let Brothers='';
                                    let Classify = $Form.find('input[name="classify"]').val();
                                    if ($.inArray(Classify, res.contents['pack_type']) < 0) {
                                        if ($.inArray('both', res.contents['pack_type']) >= 0) {
                                            $Form.find('p.error').html('该订单不包含对应打包类型');
                                            return false
                                        } else {
                                            Text += '推荐使用分包'
                                        }
                                    }
                                    $Form.find('input[name="num"]').val(res.contents.num);
                                    if(undefined !== res.contents.thin){
                                        Text += '薄板[   '+ res.contents.thin +'   ]包, ';
                                    }
                                    if(undefined !== res.contents.thick){
                                        Text += '厚板[   '+ res.contents.thick +'   ]包';
                                    }
                                    if(undefined !== res.contents.un_scanned && false !==  res.contents.un_scanned){
                                        for(var i in res.contents.un_scanned){
                                            Text += '<br />' + res.contents.un_scanned[i]['board']+'[   '+res.contents.un_scanned[i]['amount']+'   ]块未扫描';
                                        }
                                    }
                                    if(undefined !== res.contents.brothers && false !==  res.contents.brothers){
                                        for(var i in res.contents.brothers){
                                            if($.inArray(Classify, res.contents.brothers[i]['pack_type']) >= 0){
                                                Brothers += '<div class="checkbox"><label class="my-label-enhance"><input type="checkbox" value="'+res.contents.brothers[i]['v']+'" name="brothers" />'+ res.contents.brothers[i]['num']+'[ '+ res.contents.brothers[i][Classify]+' ]件</label></div>';
                                            }
                                        }
                                    }
                                    $Form.find('div.brothers').html(Brothers);
                                    $Form.find('p.error').html(Text);
                                    OrderProduct = res.contents
                                }else{
                                    $Form.find('p.error').html(res.message);
                                    return false;
                                }
                            },
                            error: function(x,t,e){
                                $Form.find('p.error').html(x.responseText);
                            }
                        });
                    }else{
                        $Form.find('input[name="num"]').val('');
                        $Form.find('p.error').text('');
                    }
                }).on('focusin', function(e){
                    OrderProduct = {};
                    $Form.find('input[name="num"]').val('');
                    $Form.find('div.brothers').text('');
                    $Form.find('p.error').text('');
                });
                $Form.find('a').click(function(e){
                    e.preventDefault();
                    if (JSON.stringify(OrderProduct) === '{}' || undefined === OrderProduct.num || '' === OrderProduct.num) {
                        $Form.find('p.error').html('没有找到相应订单, 请重新查找');
                        return false;
                    } else {
                        Pack = $Form.find('input[name="pack"]').val();
                        if('' === Pack || parseInt(Pack) <= 0){
                            $Form.find('input[name="pack"]').focus();
                            $Form.find('p.error').html('请填写包装件数');
                            return false;
                        }
                        Classify = $Form.find('input[name="classify"]').val();
                        Brothers = $.map($Form.find('input[name="brothers"]:checked'), function(n){return $(n).val();}).join(',');
                        if(undefined === Brothers){
                            Brothers = '';
                        }
                        $('#packLabelSelect').addClass('hide');
                        $('#packLabelInfo').removeClass('hide');
                        $('#packLabelInfoDealer').text(OrderProduct.dealer);
                        $('#packLabelInfoOwner').text(OrderProduct.owner);
                        $('#packLabelInfoAddress').text(OrderProduct.delivery_area + OrderProduct.delivery_address);
                        $('#packLabelInfoNum').text(OrderProduct.num);
                        $('#packLabelInfoLinker').text(OrderProduct.delivery_linker);
                        $('#packLabelInfoPhone').text(OrderProduct.delivery_phone);
                        $('#packLabelInfoPack').text(Pack);
                        let PritnDate = new Date().Format("yyyy-MM-dd");
                        let Image = BaseUrl + 'style/image/truck-7.png';
                        if (OrderProduct['delivery_area'] == '') {
                            OrderProduct['delivery_area'] = '--';
                        }
                        if (OrderProduct['delivery_linker'] == '') {
                            OrderProduct['delivery_linker'] = '--';
                        }
                        if (OrderProduct['owner'] == '') {
                            OrderProduct['owner'] = '--';
                        }
                        let Dealer = OrderProduct['dealer'].split('_');
                        let DealerName;
                        if (Dealer.length > 4) {
                            let DealerNum = Dealer.shift()
                            DealerName = Dealer.shift()
                            let ShopName = Dealer.shift()
                            DealerName = DealerName === ShopName ? DealerNum + DealerName : DealerNum + DealerName + ShopName
                        } else {
                            DealerName = Dealer.join('_')
                        }
                        let PackLabel = '';
                        let Url = '';
                        for (let I = 1; I <= Pack; I++) {
                            Url = PubUrl + '/' + OrderProduct['num'] + '-' + Pack + '-' + I + '-' + Classify;
                            PackLabel = PackLabel + '<div class="print-label">' +
                                '<div class="delivery-address"><div class="middle">' + OrderProduct['delivery_area'] + '</div></div>' +
                                '<div class="order-product-num"><div class="middle">' + OrderProduct['num'] + '</div></div>' +
                                '<div class="package-type"><div class="middle">产品: ' + OrderProduct['product'] + '</div></div>' +
                                '<div class="dealer"><div class="middle">' + DealerName + '</div></div>' +
                                '<div class="delivery-linker"><div class="middle">收货: ' + OrderProduct['delivery_linker'] + '</div></div>' +
                                '<div class="owner"><div class="middle">业主: ' + OrderProduct['owner'] + '</div></div>' +
                                '<div class="date"><div class="middle">日期: ' + PritnDate + '</div></div>' +
                                '<img  src="' + Image + '" />' +
                                '<div class="package-total"><div class="middle">共<span class="package-num">' + Pack + '</span>件<br />第<span class="package-num">' + I + '</span>件</div></div>' +
                                '<div class="qrcode" data-url="' + Url + '"></div>' +
                                '</div>'
                        }
                        $('#packLabelLabel').html(PackLabel);
                        $('.qrcode').each(function (i, v) {
                            $(this).qrcode({
                                width: 93,
                                height: 93,
                                text: $(this).data('url')
                            });
                        });
                    }
                    /*var Pack = $Form.find('input[name="pack"]').val();
                    if('' == Pack || parseInt(Pack) <= 0){
                        $Form.find('input[name="pack"]').focus();
                        $Form.find('p.error').html('请填写包装件数');
                        return false;
                    }
                    var OrderProductNum = $Form.find('input[name="num"]').val();
                    let OrderProductId = Form.find('input[name="order_product_id"]').val();
                    var Classify = $Form.find('input[name="classify"]').val();
                    if(undefined == Classify){
                        Classify = 'other';
                    }
                    var Brothers = $.map($Form.find('input[name="brothers"]:checked'), function(n){return $(n).val();}).join(',');
                    if(undefined == Brothers){
                        Brothers = '';
                    }
                    if('' != OrderProductNum){
                        $(this).attr('href', function(ii,vv){
                            if(vv.lastIndexOf('?') >= 0){
                                return vv.substr(0,vv.lastIndexOf('?'))+'?order_product_id='+OrderProductId+'&&pack='+Pack+'&&classify='+Classify+'&&brothers='+Brothers;
                            }else{
                                return vv+'?order_product_id='+OrderProductId+'&&pack='+Pack+'&&classify='+Classify+'&&brothers='+Brothers;
                            }
                        });
                        return true;
                    }else{
                        $Form.find('p.error').html('没有找到相应订单, 请重新查找');
                        return false;
                    }*/
                });
            });
            $('#back').click(function(e){
                $('#packLabelSelect').removeClass('hide');
                $('#packLabelInfo').addClass('hide');
                $('.print-area').addClass('visible-print-block');
                $('#packLabelInfoDealer').text('');
                $('#packLabelInfoOwner').text('');
                $('#packLabelInfoAddress').text('');
                $('#packLabelInfoNum').text('');
                $('#packLabelInfoLinker').text('');
                $('#packLabelInfoPhone').text('');
                $('#packLabelLabel').html('');
                Pack = 0;
            });
            $('#print').on('click', function(e){
                $Btn = $(this);
                $.ajax({
                    async: false,
                    data: {order_product_id: OrderProduct['v'], pack: Pack, classify: Classify, brothers: Brothers},
                    type: 'post',
                    url: SiteUrl + 'order/pack_label/prints',
                    dataType: 'json',
                    beforeSend: function () {
                        $Btn.prop('disabled', true);
                    },
                    complete: function () {
                        $Btn.prop('disabled', false);
                    },
                    success: function(msg){
                        window.print();
                        return true;
                    },
                    error: function(x,t,e){
                        alert(x.responseText);
                    }
                });
            });
            $('#preview').on('click', function(e){
                $('.print-area').removeClass('visible-print-block');
            });
            $('#setting input').each(function(i, v) {
                $(this).on('click', function(e){
                    if ($(this).prop('checked')) {
                        $('#packLabelNavs').find('a[href="#' +$(this).val()+ '"]').parent().removeClass('hide');
                    }else {
                        $('#packLabelNavs').find('a[href="#' +$(this).val()+ '"]').parent().addClass('hide');
                    }
                    resetting();
                });
            });
            let SessionData;
            if(!(SessionData = $.sessionStorage('pack_label_setting'))){
                $('#setting input').prop('checked', true);
                resetting();
            }else{
                let index
                for(index in SessionData){
                    if (SessionData[index]) {
                        $('#setting input[value="' + index + '"]').prop('checked', true);
                    }else {
                        $('#packLabelNavs').find('a[href="#' + index + '"]').parent().addClass('hide');
                    }
                }
            }
            function resetting() {
                let Content = {};
                $('#setting input').each(function(i, v) {
                    Content[$(this).val()] = $(this).prop('checked');
                });
                $.sessionStorage('pack_label_setting', Content);
            }

        })(jQuery);
    </script>
</html>