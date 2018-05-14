<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月18日
 * @author Zhangcc
 * @version
 * @des
 * 打印标签
 */
$Num = count($Data);
$Info = $Data[array_rand($Data)];
?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-offset-2 col-md-8" style="border: 3px solid red; border-radius: 10px;font-size: 32px;">
                    <dl class="dl-horizontal">
                        <dt>订单编号:</dt>
                        <dd><?php echo $Info['order_product_num'];?></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>客户:</dt>
                        <dd><?php echo $Info['dealer'];?></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>业主:</dt>
                        <dd><?php echo $Info['owner'];?></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>板块件数:</dt>
                        <dd><?php echo $Num;?></dd>
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
    var Data = <?php echo json_encode($Data);?>;
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
		//LODOP.SET_PRINT_PAGESIZE(0,600,400,"9000");
		LODOP.SET_PRINT_PAGESIZE(0,600,370,"9000");
		if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
		//if (LODOP.SET_PRINTER_INDEXA("Doro PDF Writer"));
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
			LODOP.ADD_PRINT_HTML('1mm','1mm','16mm','16mm','<span style="width: 100%; height: 100%; text-align: center; font-family: SimHei; font-weight: bold"><font size=10>'+<?php echo $Sn;?>+'</font></span>');
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