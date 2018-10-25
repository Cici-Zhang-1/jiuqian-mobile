<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/20
 * Time: 15:45
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
    .radio label.my-label-enhance input[type="radio"], .checkbox label.my-label-enhance input[type="checkbox"]{
        margin-top: 20px;
    }
    .my-label-color-1{
        color: red;
    }
    .print-area {
        width: 6cm;
    }
    .print-label {
        position: relative;
        width: 100%;
        height: 3.9cm;
        page-break-after: always;
    }
    .print-label > div {
        display: table;
        margin-top: auto;
        margin-bottom: auto;
    }
    .print-label > div > .middle{
        display: table-cell;
        vertical-align: middle;
        text-align: center;
    }
    .print-label .print-sn {
        position: relative;
        top: 1mm;
        left: 1mm;
        width: 15mm;
        height: 15mm;
        border: 1px solid #000;
        border-radius: 50%;
        font-weight: bold;
        font-size: 10mm;
    }
    .print-label .print-board {
        position: absolute;
        top: 1mm;
        left: 16mm;
        width: 43mm;
        height: 5mm;
        font-size: 3mm;
    }
    .print-label .print-size {
        position: absolute;
        top: 6mm;
        left: 16mm;
        width: 43mm;
        height: 5mm;
        font-size: 3mm;
    }
    .print-label .print-cubicle {
        position: absolute;
        top: 11mm;
        left: 16mm;
        width: 22mm;
        height: 5mm;
        font-size: 3mm;
    }
    .print-label .print-plate {
        position: absolute;
        top: 11mm;
        left: 38mm;
        width: 20mm;
        height: 5mm;
        font-size: 3mm;
    }
    .print-label .print-qrcode {
        position: absolute;
        top: 16mm;
        left: 1mm;
        width: 40mm;
        height: 5mm;
        font-size: 3mm;
    }
    .print-label .print-edge {
        position: absolute;
        top: 21mm;
        left: 1mm;
        width: 10mm;
        height: 5mm;
        font-size: 3mm;
    }
    .print-label .print-slot {
        position: absolute;
        top: 26mm;
        left: 1mm;
        width: 25mm;
        height: 5mm;
        font-size: 3mm;
    }
    .print-label .print-remark {
        position: absolute;
        top: 31mm;
        left: 1mm;
        width: 30mm;
        height: 5mm;
        font-size: 3mm;
    }
    .print-label .qrcode {
        position: absolute;
        top: 17mm;
        left: 38mm;
        width: 20mm;
        height: 20mm;
    }
</style>
<div class="container-fluid hidden-print">
    <div class="row" id="labelSelect">
        <div class="col-md-offset-2 col-md-8">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#cabinet" aria-controls="classify" role="tab" data-toggle="tab">板块标签</a>
                </li>
            </ul>
            <div class="tab-content" id="classifyLabel">
                <div role="tabpanel" class="tab-pane active" id="classify">
                    <form class="my-label-color-1 my-label form-horizontal" id="labelForm" role="form" method="get" action="<?php echo site_url('order/order_product_board_plate/label')?>">
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
    <div class="row hide" id="labelInfo">
        <div class="col-md-offset-2 col-md-8" style="border: 3px solid red; border-radius: 10px;font-size: 32px;">
            <dl class="dl-horizontal">
                <dt>订单编号:</dt>
                <dd id="labelInfoNum"></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>客户:</dt>
                <dd id="labelInfoDealer"></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>业主:</dt>
                <dd id="labelInfoOwner"></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>板块件数:</dt>
                <dd id="labelInfoAmount"></dd>
            </dl>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-offset-4 col-md-8">
            <button class="btn btn-primary btn-lg" value="打印" id="print" type="button">打印</button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-default btn-lg" value="预览" id="preview" type="button">预览</button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-default btn-lg" value="返回" id="back" type="button">返回</button>
        </div>
    </div>
</div>
<div class="print-area visible-print-block" id="packLabelLabel"></div>
</body>
<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="<?php echo base_url('js/jquery-qrcode/jquery.qrcode.min.js'); ?>"></script>
<script>
    let LODOP; //声明为全局变量
    let Data = {};
    let OrderProduct = {};
    function MyPreview() {
        /*LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
        LODOP.PRINT_INIT("条码打印");
        //LODOP.SET_PRINT_PAGESIZE(0,600,400,"9000");
        LODOP.SET_PRINT_PAGESIZE(0,600,370,"9000");

        CreateAllPages();
        if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
        //if (LODOP.SET_PRINTER_INDEXA("Doro PDF Writer"));
        LODOP.SET_PREVIEW_WINDOW(0,0,0,760,540,"");
        LODOP.PREVIEW();*/
        // CreateAllPages();
        $('.print-area').removeClass('visible-print-block');
    };
    function MyPrint() {
        /*LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
        LODOP.PRINT_INIT("条码打印");
        //LODOP.SET_PRINT_PAGESIZE(0,600,400,"9000");
        LODOP.SET_PRINT_PAGESIZE(0,600,370,"9000");

        CreateAllPages();
        if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
        //if (LODOP.SET_PRINTER_INDEXA("Doro PDF Writer"));
        LODOP.SET_PREVIEW_WINDOW(0,0,0,760,540,"");
        LODOP.PRINT();*/
        window.print();
    };

    function CreateAllPages(){
        let BoardLabel = ''
        for(let i in Data){
            if (null == Data[i]['sn']) {
                Data[i]['sn'] = 0;
            }
            BoardLabel = BoardLabel + '<div class="print-label">' +
                '<div class="print-sn"><div class="middle">' + Data[i]['sn'] + '</div></div>' +
                '<div class="print-board"><div class="middle">' + Data[i]['board'] + '</div></div>' +
                '<div class="print-size"><div class="middle">' + Data[i]['length'] + 'X' + Data[i]['width'] + '-' + Data[i]['real_length'] + 'X' + Data[i]['real_width'] + '</div></div>' +
                '<div class="print-cubicle"><div class="middle">' + Data[i]['cubicle_name'] + '</div></div>' +
                '<div class="print-plate"><div class="middle">' + Data[i]['plate_name'] + '</div></div>' +
                '<div class="print-qrcode"><div class="middle">' + Data[i]['qrcode'] + '</div></div>' +
                '<div class="print-edge"><div class="middle">' + Data[i]['edge'] + '</div></div>' +
                '<div class="print-slot"><div class="middle">' + Data[i]['slot'] + '</div></div>' +
                '<div class="print-remark"><div class="middle">' + Data[i]['remark'] + '</div></div>' +
                '<div class="qrcode" data-url="' + Data[i]['qrcode'] + '"></div>' +
                '</div>';
        }
        $('#packLabelLabel').html(BoardLabel);
        $('.qrcode').each(function (i, v) {
            $(this).qrcode({
                width: 65,
                height: 65,
                text: $(this).data('url')
            });
        });
    };
    (function($){
        $('#back').click(function(e){
            Data = {}
            OrderProduct = {}
            $('#labelSelect').removeClass('hide');
            $('#labelInfo').addClass('hide');
            $('.print-area').addClass('visible-print-block');
        });
        $('#print').on('click', function(e){
            MyPrint();
        });
        $('#preview').on('click', function(e){
            MyPreview();
        });
        $('#labelForm').on('submit', function (e) {
            e.preventDefault();
            let data = {};
            let $This = $(this);
            data['order_product_num'] = $(this).find('input[name="order_product_num"]').val();
            if('' !== data['order_product_num']) {
                $.ajax({
                    async: false,
                    data: data,
                    type: 'post',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    success: function (res) {
                        if (res.code > 0) {
                            $This.find('p.error').html(res.message);
                            return false;
                        } else {
                            Data = res.contents.content
                            OrderProduct = res.contents['order_product']
                            parseOrderProduct();
                            $('#labelSelect').addClass('hide');
                            $('#labelInfo').removeClass('hide');
                            CreateAllPages();
                        }
                    },
                    error: function (x, t, e) {
                        $This.find('p.error').html(x.responseText);
                    }
                });
            }
        })
        let parseOrderProduct = function () {
            let Amount = Data.length;
            $('#labelInfoDealer').text(OrderProduct['dealer']);
            $('#labelInfoOwner').text(OrderProduct.owner);
            $('#labelInfoNum').text(OrderProduct.num);
            $('#labelInfoAmount').text(Amount);
        }
    })(jQuery);
</script>
</html>
