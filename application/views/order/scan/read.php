<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月2日
 * @author Zhangcc
 * @version
 * @des
 * 扫描
 */
?>
    <div class="page-line" id="scan" data-load="<?php echo site_url('order/scan/read');?>">
		<div class="my-tools col-md-12">
			<div class="col-md-3">
    			<div class="input-group">
    		      	<input name="qrcode" class="form-control input-lg" type="text" placeholder="二维码/条形码" >
    		      	<span class="input-group-btn">
    		        	<button class="btn btn-default btn-lg" id="scanSearchBtn" type="button" value="搜索" >搜索</button>
    		      	</span>
    		    </div>
			</div>
	  		<div class="col-md-offset-3 col-md-6 text-right" id="scanFunction">
	  		    <div class="btn-group" role="group">
		    		<button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		      			显示<span class="caret"></span>
		    		</button>
		    		<ul class="dropdown-menu" role="menu" id="scanShowMode" data-mode="1">
		    		    <li><a href="javascript:void(0);">全部</a></li>
		    		    <li><a href="javascript:void(0);">未扫描</a></li>
		    		</ul>
		  		</div>
	  		    <button type="button" class="btn btn-primary btn-lg" data-toggle="save"  value="确认" data-action="<?php echo site_url('order/scan/edit');?>"><i class="fa fa-save"></i>&nbsp;&nbsp;确认</button>
        		<button type="button" class="btn btn-default btn-lg" data-toggle="refresh"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
	  		</div>
		</div>
		<div class="my-table col-md-12">
		    <table class="table table-hover table-responsive table-condensed" id="scanBaseInfoTable">
		        <tbody>
		            <tr><td>订单编号:</td><td name="order_num"></td><td>客户:</td><td name="dealer"></td></tr>
		            <tr><td>联系人:</td><td name="dealer_linker"></td><td>联系方式:</td><td name="dealer_phone"></td></tr>
		            <tr><td>联系地址:</td><td name="dealer_address"></td><td>业主:</td><td name="owner"></td></tr>
		        </tbody>
		    </table>
		</div>
		<div class="col-md-12">
			<table class="table table-bordered table-hover table-responsive table-condensed" id="scanBoardTable">
				<thead>
					<tr>
						<th class="td-xs" data-name="selected">#</th>
						<th >二维码<i class="fa fa-sort"></i></th>
						<th >板材<i class="fa fa-sort"></i></th>
						<th >柜体位置<i class="fa fa-sort"></i></th>
						<th >名称<i class="fa fa-sort"></i></th>
						<th >宽度<i class="fa fa-sort"></i></th>
						<th >长度<i class="fa fa-sort"></i></th>
						<th >厚度<i class="fa fa-sort"></i></th>
						<th >封边<i class="fa fa-sort"></i></th>
						<th >打孔<i class="fa fa-sort"></i></th>
						<th >开槽<i class="fa fa-sort"></i></th>
						<th >封边<i class="fa fa-sort"></i></th>
						<th >备注<i class="fa fa-sort"></i></th>
						<th >扫描人<i class="fa fa-sort"></i></th>
						<th >扫描时间<i class="fa fa-sort"></i></th>
					</tr>
				</thead>
				<tbody>
				    <tr class="loading"><td colspan="15">加载中...</td></tr>
					<tr class="no-data"><td colspan="15">没有数据</td></tr>
			      	<tr class="model">
			      		<td ><input name="opbpid"  type="checkbox" value=""/></td>
						<td name="qrcode"></td>
						<td name="board"></td>
						<td name="cubicle_name"></td>
						<td name="plate_name"></td>
						<td name="width"></td>
						<td name="length"></td>
						<td name="thick"></td>
						<td name="fengbian"></td>
						<td name="punch"></td>
						<td name="slot"></td>
						<td name="edge"></td>
						<td name="remark"></td>
						<td name="scanner"></td>
						<td name="scan_datetime"></td>
			      	</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="scanned-nums">
	    <p><span>0</span><span>0</span></p>
	</div>
	<div class="scanned-info">
	    <ul>
	        <li>
	            <ul>
	                <li ></li>
	                <li ></li>
	            </ul>
	        </li>
	        <li >
	            <ul>
	                <li ></li>
	                <li ></li>
	            </ul>
	        </li>
	    </ul>
	</div>
	<script type="text/javascript">
		var time1 = undefined;
		var Options = {};
		(function($){
			var Abnormity = {};
			function get_width(){
				return (document.body.clientWidth+document.body.scrollLeft);
			};
			function get_height(){
				return (document.body.clientHeight+document.body.scrollTop);
			};
			function get_left(w){
				var bw=document.body.clientWidth;
				var bh=document.body.clientHeight;
				w=parseFloat(w);
				return (bw/2-w/2+document.body.scrollLeft);
			};
			function get_top(h){
				var bw=document.body.clientWidth;
				var bh=document.body.clientHeight;
				h=parseFloat(h);
				return (bh/2-h/2+document.body.scrollTop);
			};
			function re_mask(){
				/*
				更改遮罩层的大小,确保在滚动以及窗口大小改变时还可以覆盖所有的内容
				*/
				var mask=document.getElementById("mask")	;
				if(null==mask)return;
				mask.style.width=get_width()+"px";
				mask.style.height=get_height()+"px";
			};
			function re_pos(){
				/*
				更改弹出对话框层的位置,确保在滚动以及窗口大小改变时一直保持在网页的最中间
				*/
				var box=document.getElementById("msgbox");
				if(null!=box){
					var w=box.style.width;
					var h=box.style.height;
					box.style.left=get_left(w)+"px";
					box.style.top=get_top(h)+"px";
				}
			};
			
			function re_show(){
				/*
				重新显示遮罩层以及弹出窗口元素
				*/
				re_pos();
				re_mask();	
			};
			function remove(){
				/*
				清除遮罩层以及弹出的对话框
				*/
				var mask=document.getElementById("mask");
				var msgbox=document.getElementById("msgbox");
				if(null==mask&&null==msgbox)return;
				document.body.removeChild(mask);
				document.body.removeChild(msgbox);
			};
			function load_func(){
				/*
				加载函数,覆盖window的onresize和onscroll函数
				*/
				window.onresize=re_show;
				window.onscroll=re_show;	
			};
			function create_mask(){
				var mask=document.createElement("div");
				mask.id="mask";
				mask.style.position="absolute";
				mask.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=4,opacity=25)";//IE的不透明设置
				mask.style.opacity=0.4;//Mozilla的不透明设置
				mask.style.background="black";
				mask.style.top="0px";
				mask.style.left="0px";
				mask.style.width=get_width()+'px';
				mask.style.height=get_height()+'px';
				mask.style.zIndex=20000;
				document.body.appendChild(mask);
			};
			function create_msgbox(w,h,t){//创建弹出对话框的函数
				var box=document.createElement("div")	;
				box.id="msgbox";
				box.style.position="absolute";
				box.style.width=w+'px';
				box.style.height=h+'px';
				box.style.overflow="visible";
				box.innerHTML=t;
				box.style.zIndex=20001;
				document.body.appendChild(box);
				re_pos();
			};
			function focus_asure(){
				setTimeout(function(){$('#scanAbnormityAsure').focus()}, 2000);
			};
			/** 获取异形数据*/
			$.ajax({
				async: true,
				type: 'get',
				dataType: 'json',
				data: {keyword: ''},
				url: '<?php echo site_url('data/abnormity/read?scan=1');?>',
				success: function(msg){
						if(msg.error == 0){
							var Item = '', Content = msg.data.content;
							for(var i in Content){
								Abnormity[i] = Content[i]['name'];
							}
				        }
					}
			});
			$.extend({
				msgbox: function(content, type){
					var temp='<div class="dialog" ><div class="dialog-content">'+content+'</div>'
    					+'<div class="dialog-footer"><button class="btn btn-primary" type="button" id="scanAbnormityAsure" value="确认"><i class="fa fa-check"></i>确认</button>';
    				if(type == 0){
    					
    				}else if(type == 1){
    					temp += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-default" type="button" value="取消"><i class="fa fa-times"></i>取消</button>';
    				}
    				temp += '</div></div>';
    				create_mask();
    				create_msgbox(400,200,temp);
    				focus_asure();
				},
				disposeAbnormity: function(Options, $$, Qrcode){/** 处理异形*/
					var $Prev = Options.$Prev, $Current = Options.$Current,
						Remark = undefined, AbnormityReg, AbnormityFlag = false
					Remark = $$.find('td[name="remark"]').text();
					if(getJsonLength(Abnormity) > 0){
						for(var ii in Abnormity){
							AbnormityReg = new RegExp(Abnormity[ii]);
							if(AbnormityReg.test(Remark)){
								AbnormityFlag = true;
								break;
							}
						}
					}
					$Prev.find('ul li:first').text($Current.find('ul li:first').text());
					$Prev.find('ul li:last').text($Current.find('ul li:last').text());
					if($Current.hasClass('abnormity')){
						$Prev.addClass('abnormity');
					}else if($Prev.hasClass('abnormity')){
						$Prev.removeClass('abnormity');
					}
					$Current.find('ul li:first').text(Qrcode);
					$Current.find('ul li:last').text(Remark);
					if(AbnormityFlag){
						$Current.addClass('abnormity');
						Options.$Qrcode.blur();
						$.msgbox('当前异形是否已经做好?', 1);
						$('#msgbox button').on('click',function(e){
							remove();
        					$.findScan(Options, $$, Qrcode, $(this).val());
	        			});
						return false;
					}else if($Current.hasClass('abnormity')){
						$Current.removeClass('abnormity');
					}
					return true;
				},
				findScan: function(Options, $$, Qrcode, Type){
					if('确认' == Type){
						$$.find('input:checkbox').prop('checked',true);
					}else{
						$$.find('input:checkbox').prop('checked',false);
					}
					Options.$Qrcode.focus();
				},
				disposeScan: function(Options){ /**处理扫描*/
					var Qrcode = $.trim(Options.$Qrcode.val()), Find = false,
						$Table1 = Options.$Table1, $Table2 = Options.$Table2, DisposeAbnormityReturn;
					if(undefined != Qrcode && '' != Qrcode){
						Options.$Qrcode.val('');
						var OrderNum = $Table1.find('tbody td[name="order_num"]').text();
						if('' != OrderNum && undefined != OrderNum){
							$Table2.find('tbody tr').each(function(i, v){
								if(Qrcode == $(this).find('td[name="qrcode"]').text()){
									Find = true;
									if($.disposeAbnormity(Options, $(this), Qrcode)){
										if(!$(this).hasClass('danger')){
											Options.$Show.text(function(){return parseInt($(this).text())-1;});
											switch(Options.ScanShowMode){
					    						case 0:
					    							$(this).addClass('danger').find('input:checkbox').prop('checked',true);
					    							break;
					    						case 1:
					    							$(this).addClass('hide danger').find('input:checkbox').prop('checked',true);
					    							break;
											}
										}
									}else{
										if(!$(this).hasClass('danger')){
											Options.$Show.text(function(){return parseInt($(this).text())-1;});
											switch(Options.ScanShowMode){
					    						case 0:
					    							$(this).addClass('danger');
					    							break;
					    						case 1:
					    							$(this).addClass('hide danger');
					    							break;
											}
										}
									}
								}
							});
							if(!Find){
								Options.$Qrcode.blur();
								$.msgbox(Qrcode+'不是该订单内部件', 0);
								$('#msgbox button').on('click',function(e){
									remove();
									Options.$Qrcode.focus();
			        			});
							}
						}else{
							$.ajax({
								type: 'get',
								data: {qrcode:Qrcode},
								url: Options.Load,
								dataType: 'json',
								beforeSend: function(){
	    								$Table2.find('tr.loading').show();
	    		                        $Table2.find('tr.no-data').hide();
	    		                        $Table2.find('tr.model').prevUntil('.no-data').remove();
									},
								success: function(Msg){
									if(0 == Msg.error){
							            var $Model = $Table2.find('tbody tr.model').eq(0);
							            var $ItemClone;
							            var BaseInfo = Msg.data.order, Sum = Msg.data.content.length, Total = Sum, 
							            	AbnormityDisposeFlag=true;
							            for(var i in Msg.data.content){
							            	$ItemClone = $Model.clone(true);
							                $Model.before($ItemClone);
							                $ItemClone.children().each(function(ii, iv){
							                    if($(this).find('input:checkbox').length > 0){
							                        $(this).find('input:checkbox').val(function(){return Msg.data.content[i][this.name];});
							                    }else if($(this).find('input:hidden').length > 0){
							                        $(this).find('input:hidden').val(function(){return Msg.data.content[i][this.name];});
							                    }
							                    if($(this).attr('name') != undefined){
							                    	$(this).append(Msg.data.content[i][$(this).attr('name')]);
							                    }
							                });
							                if(Qrcode == $ItemClone.find('td[name="qrcode"]').text()){
							                	AbnormityDisposeFlag = $.disposeAbnormity(Options, $ItemClone, Qrcode);
							                }else{
												AbnormityDisposeFlag = true;
											}
							                if(Qrcode == $ItemClone.find('td[name="qrcode"]').text() || '' != $ItemClone.find('td[name="scan_datetime"]').text()){
							                	Sum = Sum - 1;
							                	if(AbnormityDisposeFlag){
								                	switch(Options.ScanShowMode){
        		    				    				case 0:
        		    				    					$ItemClone.addClass('danger').find('input:checkbox').prop('checked',true);
        		    				    					break;
        		    				    				case 1:
        		    				    					$ItemClone.addClass('hide danger').find('input:checkbox').prop('checked',true);
        		    				    					break;
        		    								}
												}else{
								                	switch(Options.ScanShowMode){
        		    				    				case 0:
        		    				    					$ItemClone.addClass('danger');
        		    				    					break;
        		    				    				case 1:
        		    				    					$ItemClone.addClass('hide danger');
        		    				    					break;
        		    								}
        		    							}
											}
								        }
							            Options.$Total.text(function(){return Total;});
						            	Options.$Show.text(function(){return Sum;});
						            	$Table1.find('td').each(function(ii, iv){
											if($(this).attr('name') != undefined && BaseInfo[$(this).attr('name')] != undefined){
												$(this).text(BaseInfo[$(this).attr('name')]);
											}
								        });
							            $Model.prevUntil('.no-data').removeClass('model');
							            Options.$Table2.tablesorter(Options.$Table2.find('thead tr').getHeaders());
							        }else{
							        	alert(Msg.message);
							            $Table.find('.no-data').show();
							        }
								},
								complete: function(msg){
				                       $Table2.find('tr.loading').hide();
				                    },
								error: function(x,t,e){alert(e+'Error on 扫描二维码!');}
							});
						}
					}
					return ;
				}
			});
			Options = {
					Load: $('#scan').data('load'),          /**  数据加载*/
					$Qrcode: $('input[name="qrcode"]'),		/** 二维码、条形码*/
					$Table1: $('#scanBaseInfoTable'),  /** 基本信息表*/
					$Table2: $('#scanBoardTable'),	/** 板块信息表*/
					$Show:$('.scanned-nums p span:first'),    /** 显示当前剩余的数目*/
					$Total:$('.scanned-nums p span:last'),    /** 显示全部板块数目*/
					$Prev: $('.scanned-info > ul > li:first'),   /** 显示扫描前一块信息*/
					$Current: $('.scanned-info > ul > li:last'),   /** 显示扫描后一块信息*/
					ScanShowMode: $('#scanShowMode').data('mode')  /** 板块信息的显示方式, 全显示, 还是只显示未扫描的*/
				};
			Options.$Qrcode.focus().on('keydown', function(e){
        		var ev= window.event||e;
	  			if (ev.keyCode == 13) {
					$.disposeScan(Options);
	  			}
			});
			
			$('#scanSearchBtn').click(function(e){
				$.disposeScan(Options);
			});

			Options.$Table2.find('input:checkbox').each(function(i, v){
				$(this).on('change', function(e){
					if('' == $(this).parents('tr').eq(0).find('td[name="scan_datetime"]').text()){
						if($(this).prop('checked')){
							Options.$Show.text(function(){return parseInt($(this).text())-1;});
							$(this).parents('tr').eq(0).addClass('danger');
						}else{
							Options.$Show.text(function(){return parseInt($(this).text())+1;});
							$(this).parents('tr').eq(0).removeClass('danger');
						}
					}
				});
			});
			
			$('ul#scanShowMode a').each(function(i, v){
				$(this).on('click', function(e){
					Options.ScanShowMode = $(this).parents('li').eq(0).prevAll().length;
					$('ul#scanShowMode').data('mode', Options.ScanShowMode);
					switch(Options.ScanShowMode){
	    				case 0:
	        				Options.$Table2.find('tr.hide').removeClass('hide');
	    					break;
	    				case 1:
	    					Options.$Table2.find('tr.danger').addClass('hide');
	    					break;
					}
				});
			});
			$('button[data-toggle="refresh"]').off('click.refresh').on('click.refresh', function(e){
				if(Options.$Table2.find('input:checked').length > 0 && confirm('您有未确认的扫描，是否确认?')){
					$('button[data-toggle="save"]').trigger('click');
				}else{
					$.tabRefresh();
				}
            });
            $('button[data-toggle="save"]').on('click', function(e){
            	e.preventDefault();
            	var Action = $(this).data('action'), Data = $.map(Options.$Table2.find('input:checked'), function(n){ return $(n).val();});
                $.ajax({
                    async: false,
                    data: {selected: Data},
                    type: 'post',
                    url: Action,
                    beforeSend: function(ie){},
                    dataType: 'json',
                    success: function(msg){
                        if(msg.error == 0){
                            $.tabRefresh();
                        }else if(msg.error == 1){
                        	alert(msg.message);
                        	return false;
                        }
                    },
                    error: function(x,t,e){alert('服务器执行错误, 提交失败!');}
                });
            });
		})(jQuery);
	</script>