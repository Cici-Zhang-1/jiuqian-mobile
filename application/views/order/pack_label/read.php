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
        </style>
        <div class="container-fluid">
            <div class="row">
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
                            <form class="my-label-color-1 my-label form-horizontal" role="form" method="get" action="<?php echo site_url('order/pack_label/index/print')?>">
                                <input type="hidden" name="code" value="w" />
                                <input type="hidden" name="order_product_num" value="" />
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
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/index/print');?>" >橱柜厚板打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="cabinetThin">
                            <form class="my-label-color-7 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="w" />
                                <input type="hidden" name="order_product_num" value="" />
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
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/index/print');?>" >橱柜薄板打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="cabinetAll">
                            <form class="my-label-color-8 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="w" />
                                <input type="hidden" name="order_product_num" value="" />
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
                                <div class="hide form-group form-group-lg">
                                    <div class="col-md-3">
                                        <div class="radio">
                                            <label class="my-label-enhance">
                                                <input type="radio" value="both" name="classify" checked="checked"><span>所有</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/index/print');?>" >橱柜所有打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="wardrobeThick">
                            <form class="my-label-color-2 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="y" />
                                <input type="hidden" name="order_product_num" value="" />
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
                                <div class="hide form-group form-group-lg">
                                    <div class="col-md-3">
                                        <div class="radio">
                                            <label class="my-label-enhance">
                                                <input type="radio" value="thick" name="classify" checked="checked"><span>厚板</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/index/print');?>" >衣柜厚板打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="wardrobeThin">
                            <form class="my-label-color-9 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="y" />
                                <input type="hidden" name="order_product_num" value="" />
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
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/index/print');?>" >衣柜薄板打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="wardrobeAll">
                            <form class="my-label-color-10 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="y" />
                                <input type="hidden" name="order_product_num" value="" />
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
                                <div class="hide form-group form-group-lg">
                                    <div class="col-md-3">
                                        <div class="radio">
                                            <label class="my-label-enhance">
                                                <input type="radio" value="both" name="classify" checked="checked"><span>所有</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/index/print');?>" >衣柜所有打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="door">
                            <form class="my-label-color-3 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="m" />
                                <input type="hidden" name="order_product_num" value="" />
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
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/index/print');?>" >门板打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="wood">
                            <form class="my-label-color-4 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="k" />
                                <input type="hidden" name="order_product_num" value="" />
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
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/index/print');?>" >拼框门打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="fitting">
                            <form class="my-label-color-5 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="p" />
                                <input type="hidden" name="order_product_num" value="" />
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
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/index/print');?>" >配件打印</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="other">
                            <form class="my-label-color-6 my-label form-horizontal" role="form" action="<?php echo site_url('order/pack_label/read')?>">
                                <input type="hidden" name="code" value="g" />
                                <input type="hidden" name="order_product_num" value="" />
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
                                        <a class="btn btn-primary btn-lg" href="<?php echo site_url('order/pack_label/index/print');?>" >外购打印</a>
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
        </div>
    </body>
    <script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('js/jquery.storage.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('js/dateselect.js');?>"></script>
    <script>
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
    	$('#packLabel').find('form').each(function(i, v){
        	var $Form = $(this);
        	$Form.find('input[name = "prefix"], input[name="middle"]').on('focusout', function(e){
        		var Data = {};
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
    	                type: 'get',
    	                url: '<?php echo site_url('order/pack_label/read');?>',
    	                dataType: 'json',
    	                success: function(msg){
    	                    if(msg.error == 0){
        	                    var Text = msg.data.order_product_num+'   [   '+msg.data.pack+'   ]件<br />', Brothers='',
                                    Classify = $Form.find('input[name="classify"]:checked').val(),
                                    ClassifyBrothers = ['thick', 'thin'];

                                if ($.inArray(Classify, ClassifyBrothers) >= 0 && undefined === msg.data[Classify]) {
                                    $Form.find('p.error').html('该订单不包含对应打包类型');
                                    return false;
                                }
    	                    	$Form.find('input[name="order_product_num"]').val(msg.data.order_product_num);
    	                    	if(undefined != msg.data.thin){
        	                    	Text += '薄板[   '+msg.data.thin+'   ]包, ';
    	                    	}
								if(undefined != msg.data.thick){
									Text += '厚板[   '+msg.data.thick+'   ]包';
    	                    	}
    	                    	if(undefined != msg.data.unscaned && false !=  msg.data.unscaned){
        	                    	for(var i in msg.data.unscaned){
										Text += '<br />'+msg.data.unscaned[i]['board']+'[   '+msg.data.unscaned[i]['amount']+'   ]块未扫描';
        	                    	}
    	                    	}
                                if(undefined != msg.data.brothers && false !=  msg.data.brothers){
                                    for(var i in msg.data.brothers){
                                        if($.inArray(Classify, ClassifyBrothers) >= 0 && undefined != msg.data.brothers[i][Classify]){
                                            Brothers += '<div class="checkbox"><label class="my-label-enhance"><input type="checkbox" value="'+msg.data.brothers[i]['opid']+'" name="brothers" />'+msg.data.brothers[i]['order_product_num']+'[ '+msg.data.brothers[i][Classify]+' ]件</label></div>';
                                        }
                                    }
                                }
                                $Form.find('div.brothers').html(Brothers);
    	                    	$Form.find('p.error').html(Text);
    	                    }else{
                                $Form.find('p.error').html(msg.message);
                                return false;
                            }
    	                },
    	                error: function(x,t,e){
    	                	$Form.find('p.error').html(x.responseText);
    		            }
    	            });
    			}else{
    				$Form.find('input[name="order_product_num"]').val('');
                	$Form.find('p.error').text('');
    			}
            }).on('focusin', function(e){
            	$Form.find('input[name="order_product_num"]').val('');
                $Form.find('div.brothers').text('');
            	$Form.find('p.error').text('');
        	});
        	$Form.find('a').click(function(e){
            	var Pack = $Form.find('input[name="pack"]').val();
    			if('' == Pack || parseInt(Pack) <= 0){
        			$Form.find('input[name="pack"]').focus();
    				$Form.find('p.error').html('请填写包装件数');
					return false;
    			}
    			var OrderProductNum = $Form.find('input[name="order_product_num"]').val();
    			var Classify = $Form.find('input[name="classify"]:checked').val();
    			if(undefined == Classify){
        			Classify = 'other';
    			}
                /*var Together = $Form.find('input[name="together"]:checked').val();
                if(undefined == Together){
                    Together = '0';
                }*/
                var Brothers = $.map($Form.find('input[name="brothers"]:checked'), function(n){return $(n).val();}).join(',');
                if(undefined == Brothers){
                    Brothers = '';
                }
				if('' != OrderProductNum){
					$(this).attr('href', function(ii,vv){
						if(vv.lastIndexOf('?') >= 0){
	                        return vv.substr(0,vv.lastIndexOf('?'))+'?order_product_num='+OrderProductNum+'&&pack='+Pack+'&&classify='+Classify+'&&brothers='+Brothers;
	                    }else{
	                        return vv+'?order_product_num='+OrderProductNum+'&&pack='+Pack+'&&classify='+Classify+'&&brothers='+Brothers;
	                    }
                    });
					return true;
				}else{
					$Form.find('p.error').html('没有找到相应订单, 请重新查找');
					return false;
				}
			});
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
        var SessionData = undefined, Item = '', index;
        if(!(SessionData = $.sessionStorage('pack_label_setting'))){
            $('#setting input').prop('checked', true);
            resetting();
        }else{
            for(index in SessionData){
                if (SessionData[index]) {
                    $('#setting input[value="' + index + '"]').prop('checked', true);
                }else {
                    $('#packLabelNavs').find('a[href="#' + index + '"]').parent().addClass('hide');
                }
            }
        }
        function resetting() {
            var Content = {};
            $('#setting input').each(function(i, v) {
                Content[$(this).val()] = $(this).prop('checked');
            });
            $.sessionStorage('pack_label_setting', Content);
        }
    })(jQuery);
    </script>
</html>