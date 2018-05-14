<?php
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 2016/11/14
 * Time: 10:28
 *
 * Desc:
 * 打印板块标签
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
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#cabinet" aria-controls="classify" role="tab" data-toggle="tab">板块标签</a>
                </li>
            </ul>
            <div class="tab-content" id="classifyLabel">
                <div role="tabpanel" class="tab-pane active" id="classify">
                    <form class="my-label-color-1 my-label form-horizontal" role="form" method="get" action="<?php echo site_url('order/order_product_board_plate/label')?>">
                        <div class="form-group form-group-lg">
                            <div class="col-md-12">
                                <p class="my-label-title form-control-static">板块标签</p>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <div class="col-md-12">
                                <input class="form-control" type="text" name="order_product_num" placeholder="订单产品编号(X20160101001-Y1)" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <div class="col-md-12">
                                <p class="my-label-enhance form-control-static error"></p>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-lg" type="submit">打印</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</html>