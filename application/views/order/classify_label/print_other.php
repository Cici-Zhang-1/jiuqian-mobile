<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月31日
 * @author Zhangcc
 * @version
 * @des
 * 打印其它标签
 */
?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-offset-2 col-md-8" style="border: 3px solid red; border-radius: 10px;font-size: 32px;">
                    <dl class="dl-horizontal">
                        <dt>订单编号:</dt>
                        <dd><input type="text" id="order_num" /></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>客户:</dt>
                        <dd><input type="text" id="dealer" /></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>板材:</dt>
                        <dd><input type="text" id="board" /></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>板块名称:</dt>
                        <dd><input type="text" id="plate_name" /></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>长:</dt>
                        <dd><input type="text" id="length" /></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>宽:</dt>
                        <dd><input type="text" id="width" /></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>厚度:</dt>
                        <dd><input type="text" id="thick" /></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>批次号:</dt>
                        <dd><input type="text" id="batch" /></dd>
                    </dl>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-offset-4 col-md-8">
                    <button class="btn btn-primary btn-lg" value="打印" id="print" type="button">打印</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-default btn-lg" value="打印" id="preview" type="button">预览</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-default btn-lg" value="打印" id="modify" type="button">微调</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-default btn-lg" value="返回" id="back" type="button">返回</button>
                </div>
            </div>
        </div>
        <script language="javascript" src="<?php echo base_url('js/LodopFuncs.js');?>"></script>
        <object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
        	<embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
        </object>
    </body>
    <script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    
    <script>
    var LODOP; //声明为全局变量
    var OrderNum = '20506850901AZ01',Dealer = '湖北大冶', Board= '18MM暖白吸塑板';
    var Plate = new Array(
    	    '1019x409x18', '1019x409x18', 
    	    '400x409x18', '400x409x18',
    	    '198x818x18', '198x818x18', 
    	    '616x409x18', '616x409x18', '616x409x18', '616x409x18', 
    	    '616x407x18', '616x407x18', 
    	    '1836x630x18', 
    	    '672x630x18', 
    	    '710x344x18', '710x344x18',
    	    '772x298x18', '772x298x18', 
    	    '1044x386x18', '1044x386x18', 
    	    '1044x386x18', 
    	    '592x386x18', 
    	    '592x386x18', '592x386x18',
    	    '1398x476x18', '1398x476x18', 
    	    '606x476x18', '606x476x18', '606x476x18', '606x476x18', 
    	    '1982x614x18', 
    	    '720x614x18');
    var Plate1 = new Array(
    	    '2400x78x18', '2400x78x18', '1128x112x18');
    var Plate2 = new Array(
    	    '1827x50x18', '1827x50x18', '676x50x18', '676x50x18', '1982x50x18','1982x50x18','719x50x18','719x50x18',
    	    '2365x50x18','2365x50x18','2000x50x18','2000x50x18','1860x50x18','1860x50x18',
    	    '1768x50x18','1768x50x18','775x50x18', '775x50x18');
    var Plate3 = new Array(
    	    '2400x70x18','2400x70x18');
    var Plate4 = new Array(
    	    '2365x610x18');
    
	function MyPreview() {	
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));  
		LODOP.PRINT_INIT("条码打印");
		LODOP.SET_PRINT_PAGESIZE(0,600,400,"9000");
   
		CreateAllPages();
		if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
		//if (LODOP.SET_PRINTER_INDEXA("Doro PDF Writer"));
		LODOP.SET_PREVIEW_WINDOW(0,0,0,760,540,"");
		LODOP.PREVIEW();
	};
	
	
	function MyPrint() {	
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));  
		LODOP.PRINT_INIT("条码打印");
		LODOP.SET_PRINT_PAGESIZE(0,600,400,"9000");
   
		CreateAllPages();
		if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
		//if (LODOP.SET_PRINTER_INDEXA("Doro PDF Writer"));
		LODOP.SET_PREVIEW_WINDOW(0,0,0,760,540,"");
		LODOP.PRINT();
	};	
	
	function Setup() {	
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));  
		LODOP.PRINT_INIT("条码打印");
		LODOP.SET_PRINT_PAGESIZE(0,600,400,"9000");
		if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
		//if (LODOP.SET_PRINTER_INDEXA("Doro PDF Writer"));
		CreateAllPages();
		LODOP.PRINT_DESIGN();		
	};
 
 
 	function CreateAllPages(){	
 	 	/* var OrderNum = document.getElementById('order_num').value,
 	 	Dealer = document.getElementById('dealer').value,
 	 	Board = document.getElementById('board').value,
 	 	PlateName = document.getElementById('plate_name').value,
 	 	Length = document.getElementById('length').value,
 	 	Width = document.getElementById('width').value,
 	 	Thick = document.getElementById('thick').value,
 	 	Batch = document.getElementById('batch').value; */
 		for (i = 0; i < Plate3.length; i++) {
			if(i > 0)
			{
				LODOP.NewPage();
				}
			LODOP.ADD_PRINT_TEXT('2mm',0,'20mm','10mm',Dealer);
			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-1);
			LODOP.ADD_PRINT_TEXT('2mm','15mm','40mm','10mm',OrderNum);
			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",0);
			
			LODOP.ADD_PRINT_TEXT('13mm','0mm','35mm','10mm',Board);
			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-1);
			
			LODOP.ADD_PRINT_TEXT('18mm','0mm','35mm','10mm','顶线'+Plate3[i]);
			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.SET_PRINT_STYLEA(0,"LineSpacing",-5);
			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-1);

			LODOP.ADD_PRINT_HTM('12mm','35mm', '10mm','10mm',"<div style='background-color:#000;font-size: 30px;color: #fff; text-align:center;'>R2</div>");

			LODOP.ADD_PRINT_IMAGE('22mm','35mm','15mm','15mm',"<img src='<?php echo base_url('style/image/qrcode2.png');?>'/>");
			//LODOP.ADD_PRINT_BARCODE('22mm','35mm','15mm','15mm',"PDF417",OrderNum);	
			//LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
		}	
 	 	
	};
    (function($){
		$('#back').click(function(e){
			window.history.back();
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
    })(jQuery);
    </script>
</html>