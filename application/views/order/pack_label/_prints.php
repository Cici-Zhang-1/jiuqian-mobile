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
            .j-list-group-item {
                padding: 2px 15px;
                font-size: 1.5em;
            }
        </style>
        <div class="container-fluid hidden-print">
            <div class="row" id="packLabelSelect">
                <div class="col-md-3">
                    <div class="form-group form-group-lg">
                        <select class="form-control" name="packer" id="packer"></select>
                    </div>
                    <h2>今日打包记录：</h2>
                    <ul class="list-group" id="todayPacked"></ul>
                </div>
                <div class="col-md-8">
                    <ul class="nav nav-tabs" role="tablist" id="packLabelNavs">
                        <li role="presentation" class="active">
                            <a href="#wardrobeThick" aria-controls="wardrobeThick" role="tab" data-toggle="tab">衣柜-厚板</a>
                        </li>
                        <li role="presentation" >
                            <a href="#wardrobeThin" aria-controls="wardrobeThin" role="tab" data-toggle="tab">衣柜-薄板</a>
                        </li>
                        <li role="presentation" >
                            <a href="#wardrobeAll" aria-controls="wardrobeAll" role="tab" data-toggle="tab">衣柜-所有</a>
                        </li>
                        <li role="presentation">
                            <a href="#cabinetThick" aria-controls="cabinetThick" role="tab" data-toggle="tab">橱柜-厚板</a>
                        </li>
                        <li role="presentation" >
                            <a href="#cabinetThin" aria-controls="cabinetThin" role="tab" data-toggle="tab">橱柜-薄板</a>
                        </li>
                        <li role="presentation" >
                            <a href="#cabinetAll" aria-controls="cabinetAll" role="tab" data-toggle="tab">橱柜-所有</a>
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
                        <?php
                            require_once '_cabinet_thick.php';
                            require_once '_cabinet_thin.php';
                            require_once '_cabinet_all.php';
                            require_once '_wardrobe_thick.php';
                            require_once '_wardrobe_thin.php';
                            require_once '_wardrobe_all.php';
                            require_once '_door.php';
                            require_once '_wood.php';
                            require_once '_fitting.php';
                            require_once '_other.php';
                        ?>
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
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
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
            let joinTodayPacked = function (TodayPacked, Packer) {
                let lists = []
                if (TodayPacked[Packer] !== undefined) {
                    TodayPacked[Packer].map(__ => {
                        lists.push('<li class="j-list-group-item list-group-item">' + __.num + '</li>')
                    })
                    $('#todayPacked').html(lists.join(''))
                } else {
                    $('#todayPacked').html('')
                }
            }
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

            $.ajax({
                async: true,
                type: 'get',
                url: '<?php echo site_url('manage/user/ppacker');?>',
                dataType: 'json',
                success: function(res){
                    if(res.code > 0) {
                        alert('没有获取到打包人员信息, 请刷新!');
                        return false
                    }else{
                        let lists = ['<option value="0">---</option>']
                        res.contents.content.map(__ => {
                            lists.push('<option value="' + __.v + '">' + __.truename + '</option>')
                        });
                        $('#packer').html(lists.join(''));
                        return true;
                    }
                },
                error: function(x,t,e){
                    alert('没有获取到打包人员信息, 请刷新!');
                }
            });
            let TodayPacked = {};
            let today = new Date().Format("yyyy-MM-dd");
            if(!(TodayPacked = $.localStorage('today_packed'))){
                TodayPacked = {}
                TodayPacked[today] = {}
            } else {
                TodayPacked = JSON.parse(TodayPacked);
                if (TodayPacked[today] === undefined) {
                    TodayPacked = {}
                    TodayPacked[today] = {}
                }
            }
            let Packer = 0;
            joinTodayPacked(TodayPacked[today], Packer)
            $('#packer').on('change', function (e) {
                Packer = e.target.value;
                joinTodayPacked(TodayPacked[today], Packer)
            });
            let OrderProduct = {};
            let Pack = 0;
            let Classify = '';
            let Brothers;
            let IsPacked = false;
            let $$Form;
            $('#packLabel').find('form').each(function(i, v){
                let $Form = $(this);
                $Form.find('input[name = "prefix"], input[name="middle"]').on('focusout', function(e){
                    let Data = {};
                    Data['prefix'] = $Form.find('input[name = "prefix"]').val();
                    Data['middle'] = $Form.find('input[name = "middle"]').val();
                    Data['year'] = $Form.find('select[name = "year"]').val();
                    Data['month'] = $Form.find('select[name="month"]').val();
                    Data['code'] = $Form.find('input[name="code"]').val();
                    Data['type'] = $Form.find('input[name="type"]').val();
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
                                    if ($.inArray(Classify, res.contents['pack_type']) < 0) { // 当前分类不在打包类型中
                                        if ($.inArray('both', res.contents['pack_type']) >= 0) { // 如果是合包类型
                                            Text += '建议所有一起打包...<br />'
                                            /*$Form.find('p.error').html('该订单不包含对应打包类型');
                                            return false*/
                                        } else {
                                            if (Classify === 'both') {
                                                Text += '建议厚薄各自打包...<br />'
                                            } else {
                                                $Form.find('p.error').html('该订单不包含对应打包类型');
                                                return false
                                            }
                                        }
                                    } else {
                                        if (res.contents['packer'] !== undefined && res.contents['packer'][Classify] !== undefined) {
                                            $Form.find('p.warning').html(res.contents['packer'][Classify] + '已经打包该订单');
                                            IsPacked = true
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
                                        let tmp = []
                                        const THICK = 13 // 厚板和薄板厚度分界线
                                        let Thick = []
                                        let Thin = []
                                        let msg = ''
                                        res.contents.un_scanned.map(__ => {
                                            msg = '<br />' + __['board'] + '[   ' + __['amount'] + '   ]块未扫描';
                                            tmp.push(msg)
                                            if (__.thick > THICK) {
                                                Thick.push(msg)
                                            } else {
                                                Thin.push(msg)
                                            }
                                            return __
                                        })
                                        if (Classify === 'both') { // 如果是合包存在未扫描的，则不能进行合包
                                            Text = tmp.join('') + '<br />还有板块没有扫描不能打包'
                                            $Form.find('p.error').html(Text);
                                            return false;
                                        } else if (Classify === 'thick' && Thick.length > 0) { // 有厚板未扫描的不能打包
                                            Text = Thick.join('') + '<br />还有板块没有扫描不能打包'
                                            $Form.find('p.error').html(Text);
                                            return false;
                                        } else if (Classify === 'thin' && Thin.length > 0) { // 有薄板未扫描的不能打包
                                            Text = Thin.join('') + '<br />还有板块没有扫描不能打包'
                                            $Form.find('p.error').html(Text);
                                            return false;
                                        } else {
                                            Text += tmp.join('')
                                        }
                                    }
                                    if(undefined !== res.contents.brothers && false !==  res.contents.brothers){
                                        for(var i in res.contents.brothers){
                                            if($.inArray(Classify, res.contents.brothers[i]['pack_type']) >= 0){
                                                Brothers += '<div class="checkbox"><label class="my-label-enhance"><input type="checkbox" value="'+res.contents.brothers[i]['v']+'" name="brothers" />'+ res.contents.brothers[i]['order_product_num']+'[ '+ (res.contents.brothers[i][Classify] || 0) + ' ]件</label></div>';
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
                        $Form.find('p.warning').text('');
                        IsPacked = false
                    }
                }).on('focusin', function(e){
                    OrderProduct = {};
                    $Form.find('input[name="num"]').val('');
                    $Form.find('div.brothers').text('');
                    $Form.find('p.error').text('');
                    $Form.find('p.warning').text('');
                    IsPacked = false
                });
                $Form.find('a').click(function(e){
                    e.preventDefault();
                    if (JSON.stringify(OrderProduct) === '{}' || undefined === OrderProduct.num || '' === OrderProduct.num) {
                        $Form.find('p.error').html('没有找到相应订单, 请重新查找');
                        return false;
                    } else {
                        $$Form = $Form
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

                        let PackType = ''
                        if (Classify == 'thick') {
                            PackType = '--柜体'
                        } else if (Classify == 'thin') {
                            PackType = '--背板'
                        }
                        for (let I = 1; I <= Pack; I++) {
                            Url = PubUrl + '/' + OrderProduct['num'] + '-' + Pack + '-' + I + '-' + Classify;
                            PackLabel = PackLabel + '<div class="print-label">' +
                                '<div class="delivery-address"><div class="middle">' + OrderProduct['delivery_area'] + '</div></div>' +
                                '<div class="order-product-num"><div class="middle">' + OrderProduct['num'] + '</div></div>' +
                                '<div class="package-type"><div class="middle">产品: ' + OrderProduct['product'] + PackType + '</div></div>' +
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
                OrderProduct = {};
                $$Form.find('input[name="num"]').val('');
                $$Form.find('div.brothers').text('');
                $$Form.find('input[name="type"]').val('');
                $$Form.find('input[name="prefix"]').val('');
                $$Form.find('input[name="middle"]').val('');
                $$Form.find('input[name="pack"]').val('');
                $$Form.find('p.error').text('');
                $$Form.find('p.warning').text('');
                Pack = 0;
                IsPacked = false;
            });
            $('#print').on('click', function(e){
                $Btn = $(this);
                $.ajax({
                    async: false,
                    data: {order_product_id: OrderProduct['v'], pack: Pack, classify: Classify, brothers: Brothers, packer: Packer},
                    type: 'post',
                    url: SiteUrl + 'order/pack_label/prints',
                    dataType: 'json',
                    beforeSend: function () {
                        $Btn.prop('disabled', true);
                    },
                    complete: function () {
                        $Btn.prop('disabled', false);
                    },
                    success: function(res){
                        if (res.code > 0) {
                            window.alert(res.message);
                        } else {
                            if (!IsPacked) {
                                if (TodayPacked[today][Packer] === undefined) {
                                    TodayPacked[today][Packer] = []
                                }
                                TodayPacked[today][Packer].unshift(OrderProduct)
                                joinTodayPacked(TodayPacked[today], Packer)
                                $.localStorage('today_packed', JSON.stringify(TodayPacked));
                            }
                            window.print();
                            return true;
                        }
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