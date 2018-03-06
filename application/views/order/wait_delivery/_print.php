<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月21日
 * @author Zhangcc
 * @version
 * @des
 * 打印
 */
?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-offset-2 col-md-8" style="border: 3px solid red; border-radius: 10px;font-size: 32px;">
                    <dl class="dl-horizontal">
                        <dt>发货车辆:</dt>
                        <dd><?php echo $Truck;?></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>发货车次:</dt>
                        <dd><?php echo $Train;?></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>发货日期:</dt>
                        <dd><?php echo $EndDatetime;?></dd>
                    </dl>
                    <dl class="dl-horizontal">
                        <dt>发货总数:</dt>
                        <dd><?php echo $Amount;?></dd>
                    </dl>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-offset-4 col-md-8">
                    <button class="btn btn-primary btn-lg" value="打印" id="print" type="button">打印</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-default btn-lg" value="预览" id="preview" type="button">预览</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-default btn-lg" value="微调" id="modify" type="button">微调</button>&nbsp;&nbsp;&nbsp;
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
    var Order = <?php echo json_encode($Order);?>;
    var LODOP; //声明为全局变量
	function MyPreview() {	
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));  
		LODOP.PRINT_INIT("条码打印");
		LODOP.SET_PRINT_PAGESIZE(0,1000,800,"9000");
   
		CreateAllPages();
		if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
		//if (LODOP.SET_PRINTER_INDEXA("Doro PDF Writer"));
		LODOP.SET_PREVIEW_WINDOW(0,0,0,760,540,"");
		LODOP.PREVIEW();
	};
	
	
	function MyPrint() {	
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));  
		LODOP.PRINT_INIT("条码打印");
		LODOP.SET_PRINT_PAGESIZE(0,1000,800,"9000");
   
		CreateAllPages();
		if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
		//if (LODOP.SET_PRINTER_INDEXA("Doro PDF Writer"));
		LODOP.SET_PREVIEW_WINDOW(0,0,0,760,540,"");
		LODOP.PRINT();
	};	
	
	function Setup() {	
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));  
		LODOP.PRINT_INIT("条码打印");
		LODOP.SET_PRINT_PAGESIZE(0,1000,800,"9000");
		if (LODOP.SET_PRINTER_INDEXA("Godex EZ-1105"));
		//if (LODOP.SET_PRINTER_INDEXA("Doro PDF Writer"));
		CreateAllPages();
		LODOP.PRINT_DESIGN();		
	};
 
 
 	function CreateAllPages(){	
 	 	var New = false, Pack = 0, All;
 	 	for(var i in Order){
 	 	 	All = Order[i]['pack'];
 	 		Pack = Order[i]['order_product_pack'];
 	 	 	for(j=1; j <= Pack; j++){
    			if(New){
    				LODOP.NewPage();
    			}else{
    				New = true;
    			}
    			LODOP.ADD_PRINT_TEXT('1mm',0,'14mm','12mm','编号');
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    			LODOP.ADD_PRINT_TEXT('1mm','14mm','46mm','12mm',Order[i]['order_product_num']);
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",18);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    			
    			LODOP.ADD_PRINT_TEXT('12mm',0,'14mm','12mm','客户');
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    			LODOP.ADD_PRINT_TEXT('12mm','14mm','46mm','12mm',Order[i]['dealer']);
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",18);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LineSpacing",-5);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    
    			LODOP.ADD_PRINT_TEXT('24mm',0,'14mm','12mm','业主');
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    			LODOP.ADD_PRINT_TEXT('24mm','14mm','46mm','12mm',Order[i]['owner']);
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",16);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LineSpacing",-5);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);

    			LODOP.ADD_PRINT_TEXT('36mm',0,'14mm','12mm','收货');
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    			LODOP.ADD_PRINT_TEXT('36mm','14mm','46mm','12mm',Order[i]['delivery_linker']);
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",16);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LineSpacing",-5);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    
    			LODOP.ADD_PRINT_TEXT('48mm',0,'14mm','12mm','日期');
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    			LODOP.ADD_PRINT_TEXT('48mm','14mm','46mm','12mm','<?php echo $EndDatetime;?>');
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",16);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    
    			LODOP.ADD_PRINT_TEXT('60mm',0,'14mm','12mm','包装');
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    			LODOP.ADD_PRINT_TEXT('60mm','14mm','46mm','12mm',All+'--'+Pack+'--'+j);
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",18);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    			
    			LODOP.ADD_PRINT_BARCODE('1mm','60mm','39mm','39mm',"QRCode",Order[i]['order_num']);	
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
    			
    			LODOP.ADD_PRINT_TEXT('41mm','60mm','40mm','10mm','货到');
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",18);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
    			LODOP.ADD_PRINT_TEXT('50mm','60mm','40mm','24mm',Order[i]['delivery_address']);
    			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
    			LODOP.SET_PRINT_STYLEA(0,"FontSize",20);
    			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    			LODOP.SET_PRINT_STYLEA(0,"LineSpacing",-8);
    			LODOP.SET_PRINT_STYLEA(0,"LetterSpacing",-2);
 	 	 	}
		}	
	};
    (function($){
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