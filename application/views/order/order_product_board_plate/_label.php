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
    .radio label.my-label-enhance input[type="radio"], .checkbox label.my-label-enhance input[type="checkbox"]{
        margin-top: 20px;
    }
    .my-label-color-1{
        color: red;
    }
</style>
<div class="container-fluid">
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
            <button class="btn btn-default btn-lg" value="微调" id="modify" type="button">微调</button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-default btn-lg" value="返回" id="back" type="button">返回</button>
        </div>
    </div>
    <script language="javascript" src="<?php echo base_url('js/LodopFuncs.js');?>"></script>
    <object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
        <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
    </object>
</div>
</body>
<script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script>
    var LODOP; //声明为全局变量
    let Data = {};
    let OrderProduct = {};
    function MyPreview() {
        LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
        LODOP.PRINT_INIT("条码打印");
        //LODOP.SET_PRINT_PAGESIZE(0,600,400,"9000");
        LODOP.SET_PRINT_PAGESIZE(0,600,370,"9000");

        CreateAllPages();
        if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
        //if (LODOP.SET_PRINTER_INDEXA("Doro PDF Writer"));
        LODOP.SET_PREVIEW_WINDOW(0,0,0,760,540,"");
        LODOP.PREVIEW();
    };


    function MyPrint() {
        LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
        LODOP.PRINT_INIT("条码打印");
        //LODOP.SET_PRINT_PAGESIZE(0,600,400,"9000");
        LODOP.SET_PRINT_PAGESIZE(0,600,370,"9000");

        CreateAllPages();
        if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
        //if (LODOP.SET_PRINTER_INDEXA("Doro PDF Writer"));
        LODOP.SET_PREVIEW_WINDOW(0,0,0,760,540,"");
        LODOP.PRINT();
    };

    function Setup() {
        LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
        LODOP.PRINT_INIT("条码打印");
        LODOP.SET_PRINT_PAGESIZE(0,600,370,"9000");
        if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
        CreateAllPages();
        LODOP.PRINT_DESIGN();
    };


    function CreateAllPages(){
        var J = 1;
        for(var i in Data){
            if(J > 1){
                LODOP.NewPage();
            }else{
                J++;
            }
            if (null == Data[i]['sn']) {
                Data[i]['sn'] = 0;
            }
            LODOP.ADD_PRINT_HTML('1mm','1mm','16mm','16mm','<span style="width: 100%; height: 100%; text-align: center; font-family: SimHei; font-weight: bold"><font size=10>'+Data[i]['sn']+'</font></span>');
            LODOP.ADD_PRINT_ELLIPSE('1mm','1mm','16mm','16mm',0,2);

            LODOP.ADD_PRINT_TEXT('1mm','17mm','43mm','5mm',Data[i]['board']);
            LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);

            LODOP.ADD_PRINT_TEXT('4mm','18mm','42mm','6mm',Data[i]['length']+'X'+Data[i]['width']);
            LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);

            LODOP.ADD_PRINT_TEXT('8mm','18mm','22mm','6mm',Data[i]['cubicle_name']);
            LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
            LODOP.ADD_PRINT_TEXT('8mm','40mm','20mm','6mm',Data[i]['plate_name']);
            LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);

            LODOP.ADD_PRINT_TEXT('13mm','17mm','43mm','6mm',Data[i]['qrcode']);
            LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);

            LODOP.ADD_PRINT_TEXT('18mm','17mm','10mm','6mm',Data[i]['edge']);
            LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);

            LODOP.ADD_PRINT_TEXT('23mm','5mm','25mm','6mm',Data[i]['slot']);
            LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);

            LODOP.ADD_PRINT_TEXT('28mm','1mm','30mm','6mm',Data[i]['remark']);
            LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);

            LODOP.ADD_PRINT_BARCODE('17mm','38mm','20mm','20mm',"QRCode",Data[i]['qrcode']);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
        }
    };
    (function($){
        $('#back').click(function(e){
            Data = {}
            OrderProduct = {}
            $('#labelSelect').removeClass('hide');
            $('#labelInfo').addClass('hide');
        });
        $('#print').on('click', function(e){
            MyPrint();
        });
        $('#preview').on('click', function(e){
            MyPreview();
        });
        $('#modify').on('click', function(e){
            Setup();
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