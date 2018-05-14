/**
 * json格式数据长度
 * @param jsonData
 * @returns {Number}
 */
function getJsonLength(jsonData){
		var jsonLength = 0;
		for(var item in jsonData){
			jsonLength++;
		}
		return jsonLength;
};
function isURL(str){
	return !!str.match(/(((^https?:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)$/g);
}

function isUri (str) {
	return !!str.match(/(([A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)$/g);
}
/**
 *数组功能扩展, 根据数组下标删除数组元素
 */
var remove_element = function(Values, E){
	var Return = new Array();
	for(var i in Values){
		if(E != i){
			Return[i] = Values[i];
		}
	}
	return Return;
};
/**
 * jQuery删除Cookie元素
 */
var remove_cookies = function(Name){
	if(jQuery.cookie(Name) != undefined){
		jQuery.removeCookie(Name);
	}
};

var remove_cookie = function(Page, Table){
	if(jQuery.cookie(Page) != undefined){
		var Cookie = JSON.parse(jQuery.cookie(Page));
		if(undefined !== Cookie[Table]){
			Cookie[Table] = remove_element(Cookie, Table);
			jQuery.cookie(Page, JSON.stringify(Cookie));
		}
	}
};

/**
 * jQuery添加Cookie元素
 */
var add_cookie = function(Page, Table, Values){
	var Cookie = undefined;
	if(jQuery.cookie(Page) != undefined){
		Cookie = JSON.parse(jQuery.cookie(Page));
		if(undefined == Cookie[Table]){
			Cookie[Table] = Values;
		}else{
			Cookie[Table] = jQuery.extend(Cookie[Table], Values);
		}
	}else{
		Cookie = new Array;
		Cookie[Values] = Values;
	}
	jQuery.cookie(Page, JSON.stringify(Cookie));
};


var Opened = new Array;/** Tab页面缓存记录*/
/**
 * 退出页面前关闭已打开的Tab页面的缓存
 */
window.onbeforeunload = onbeforeunload_handler;   
function onbeforeunload_handler(){   
    var warning="确认退出?";
    for(var i in Opened){
    	for(var j in Opened[i]){
    		remove_cookies(Opened[i][j]);
    	}
	}
    return warning;   
};
(function($, window, undefined){
	/**
	 *  初始化Tab
	 */
	$('#tabCard').tabs({    
   	 	border: false,
   	 	height: ($(window).height() - 70),
   	 	tools:[{   
	        iconCls:'icon-ok',   
	        handler:function(){
	        	var $Content = $('#jqContent');
	        	$('#jqNav, #side').toggle('hide');
	        	if($Content.hasClass('col-md-11')){
	        		$Content.removeClass('col-md-11 col-lg-11').addClass('col-md-12 col-lg-12');
	        	}else{
	        		$Content.removeClass('col-md-12 col-lg-12').addClass('col-md-11 col-lg-11');
	        	}
	        }   
	    }],
   	 	onBeforeClose: function(title,index){
   	 		/**
   	 		 * 关闭Tab时消除缓存
   	 		 */
			var Id = $('#tabCard').tabs('getTab',index).attr('id');
			for(var i in Opened[Id]){
				remove_cookie(Opened[Id][i]);
			}
			Opened = remove_element(Opened, Id);
	  	}
	});
	/**
	 * 启动首页
	 */
	$('#tabCard').tabs('add',{    
        title: '首页',    
        href: defaultTabCard,
        closable:false
    });
    $('#side li:first').addClass('active');
    /**
     * 从导航中启动Tab
     */
	$('#side a, #waitDispose a').click(function(e){
		$ET = $(this);
		if($ET.attr('href') != 'javascript:void(0);' && $ET.attr('title') != 'admin'){
			e.preventDefault();
			if($('#tabCard').tabs('exists',$ET.find('span').text().replace(/:.*/ig, ''))){
				$('#tabCard').tabs('select', $ET.find('span').text().replace(/:.*/ig, ''));
			}else{
				var Timestamp=new Date().getTime();
				$('#tabCard').tabs('add',{
					id: Timestamp,
				    title: $ET.find('span').text().replace(/:.*/ig, ''),    
				    href: $ET.attr('href'),
			    	closable:true,
			    	tools: $ET.data('tools')
				});
				if(undefined == Opened[Timestamp]){
					Opened[Timestamp] = new Array;
				}
			}
			$('#side').find('li.active').removeClass('active');
			$ET.parents('li').last().addClass('active');
		}
    });
    
    $.extend({
    	tabDelete: function(){
    		var tab = $('#tabCard').tabs('getSelected');
			var index = $('#tabCard').tabs('getTabIndex',tab);
    		$('#tabCard').tabs('close', index);
    	},
		tabRefresh: function(option){
			/**
			 * 面板刷新
			 * 两种方式:1. 载入原url，2. 载入新url 
			 */
			var defaults = {};
			var options = $.extend(defaults, option);
			switch(options.type){
				case 'new':
					var tab = $('#tabCard').tabs('getSelected');
					if(tab.panel('panel').data('history') == undefined){
						var History = [];
					}else{
						var History = JSON.parse(tab.panel('panel').data('history'));
					}
					History.push(tab.panel('options').href);
					tab.panel('panel').data('history', JSON.stringify(History));
					$('#tabCard').tabs('update', {tab: tab, options: {href: options.url}});
					break;
				case 'back':
					var tab = $('#tabCard').tabs('getSelected');
					var History = JSON.parse(tab.panel('panel').data('history'));
					var Pop = History.pop();
					tab.panel('panel').data('history', JSON.stringify(History));
					$('#tabCard').tabs('update', {tab: tab, options: {href: Pop}});
					break;
				case 'tab':
					if($('#tabCard').tabs('exists',options.title)){
						$('#tabCard').tabs('select', options.title);
						$('#tabCard').tabs('update', {tab: $('#tabCard').tabs('getSelected'), options: {href: options.url}});
					}else{
						var Timestamp=new Date().getTime();
						$('#tabCard').tabs('add',{
							id: Timestamp,
						    title: options.title,    
						    href: options.url,
					    	closable:true
						});
						if(undefined == Opened[Timestamp]){
							Opened[Timestamp] = new Array;
						}
					}
					break;
				default:
					$('#tabCard').tabs('getSelected').panel('refresh');
					break;
			}
		},
		storedInputData: function(This, Value){
			/**
			 * 存储输入框值
			 */
			var $This = $(This);
			if(This.tagName == 'INPUT'){
                switch($This.attr('type')){
                    case 'text':
                    case 'hidden':
					case 'tel':
					case 'number':
					case 'email':
					case 'textarea':
                        $This.val(Value);
                        break;
                    case 'radio':
                    case 'checkbox':
                        if($This.val() == Value){
                            $This.prop('checked', true);
                        }else{
                        	$This.prop('checked', false);
                        }
                        break;
                }
            }else if(This.tagName == 'SELECT'){
            	if($This.find('option[value="'+Value+'"]').length > 0){
            		$This.find('option[value="'+Value+'"]').prop('selected', true);
            	}else{
            		$This.append('<option value="'+Value+'" selected>'+Value+'</option>');
            	}
            }else if(This.tagName == 'TEXTAREA'){
				$This.val(Value);
			}
			return ;
		}
	});
	/**
     * 判断表格中哪些列可以排序<i></i>
     */
	$.fn.getHeaders = function(){
		var $Header = {
			headers:{}
		};
		$(this).each(function(j,e){
			var $children = $(this).children();
			for(var i=0; i<$children.length; i++){
	  	      if ($children.eq(i).find('i').length == 0) {
		        $Header.headers[i] = {sorter:false}; 
		      }
	        }
		});
	  return $Header;
    };
    
    /**
     * 模态框处理-针对000格式的
     */
    $.fn.handle_modal_000 = function(Options){
		var Defaults = {
			Data: {}, 
			Form: {}, 
			Type: 'ajax', /**判断模态框中表单类型：搜索型、填写修改型*/
			Trigger: undefined, /** 模态框中的促发器*/
		};
    	var Options = $.extend(Defaults, Options);
    	return this.each(function(i, v){
    		init_modal.call(this, Options);
    		submit_modal_form.call(this, Options);
    	});
	};
	
	/**
	 * 初始化模态框
	 */
	var init_modal = function(Options){
		var $This = $(this);
		Options.Form = $This.find('form').eq(0);
		if($This.hasClass('filter')){
			Options.Type = 'filter'; 
		}
		init_modal_form.call(this, Options);
		$This.on('show.bs.modal', function(e){
			if(undefined != e.relatedTarget){
				Options.Trigger = $(e.relatedTarget);
				if('ajax' == Options.Type){
					return init_modal_ajax.call(this, Options);
				}else if('filter' == Options.Type){
					return init_modal_filter.call(this, Options);
				}else{
					return false;
				}
			}
		}).on('hidden.bs.modal', function(e){
			if('ajax' == Options.Type){
				if(Options.ModalFormSuccess){
					Options.ModalFormSuccess = false;
	                $.tabRefresh();
	            }
				Options.Form[0].reset();
			}else if('filter' == Options.Type){
				
			}else{
				return false;
			}
        });
	};
	
	/**
	 * 初始化数据
	 */
	var init_modal_ajax = function(Options){
		var $This = $(this), $Form = Options.Form, $Button = Options.Trigger, 
			Multiple = $Button.data('multiple'), 
			Names = $Button.data('name')?$Button.data('name').split(','):false,
			Data={},FormPrevValue, AName = undefined;
		if($Button.data('table') != undefined){
			var $TargetTable = $($Button.data('table')); 
		}else if($Button.parents('ul').length > 0 && $Button.parents('ul').eq(0).data('table') != undefined){
			var $TargetTable = $($Button.parents('ul').eq(0).data('table')); 
		}
		
		if($TargetTable != undefined){
			var $FormName = $TargetTable.find('thead tr th');  //模态框中表单名称和对应表格中表头对应
			var $TableSelected = $TargetTable.find('tbody tr td input:checkbox:checked').parents('tr').eq(0).children(),
				$Selected = $TargetTable.find('tbody tr td input:checkbox:checked').parents('tr'); //表格选择项
			if($TableSelected.length == 0 && Multiple !== undefined){
	            alert('请先选择!');
	            return false;
	        }else{
	        	if(Multiple === false){
	        		$FormName.each(function(ii, iv){
	                    if($(this).data('name')){
	                    	AName = $(this).data('name').split('-');
							if($TableSelected.eq(ii).find('input').length > 0){
								FormPrevValue = $TableSelected.eq(ii).find('input').eq(0).val().toString().split('-');
							}else{
								if(AName.length > 1){
									FormPrevValue = $TableSelected.eq(ii).html().toString().replace(/<br\s*\/?>/ig, "\n").split('-');
								}else{
									FormPrevValue = [$TableSelected.eq(ii).html().toString().replace(/<br\s*\/?>/ig, "\n")];
								}
							}
	                        $.each(AName, function(iii, iiv){
	                            $TargetInput = $Form.find('input[name="'+iiv+'"], select[name="'+iiv+'"], textarea[name="'+iiv+'"]');
	                            if($TargetInput.length > 0){
	                                if($TargetInput[0].tagName == 'INPUT'){
	                                    switch($TargetInput.attr('type')){
	                                        case 'text':
	                                        case 'hidden':
											case 'tel':
											case 'number':
											case 'email':
											case 'textarea':
	                                            $TargetInput.val(FormPrevValue[iii]);
	                                            break;
	                                        case 'radio':
	                                            if($TargetInput.next('label').text() == FormPrevValue[iii]){
	                                                $TargetInput.prop('checked', true);
	                                            }
	                                            break;
	                                    }
	                                }else if($TargetInput[0].tagName == 'SELECT'){
										$.each(FormPrevValue[iii].toString().split(','), function(iiii, iiiv){
	                                        $TargetInput.eq(0).find('option[value="'+iiiv+'"]').prop('selected', true);
	                                    });
	                                }else if($TargetInput[0].tagName == 'TEXTAREA'){
										$TargetInput.val(FormPrevValue[iii]);
									}
	                            }
	                        });
	                    }
	                });
	                if(undefined != Names){
	                	Data[0] = {};
	                	for(var jj in Names){
                    		if($Selected.eq(0).find('td[name="'+Names[jj]+'"]').length > 0){
                    			Data[0][Names[jj]] = $Selected.eq(0).find('td[name="'+Names[jj]+'"]').text();
                    		}else if($Selected.eq(0).find('input[name="'+Names[jj]+'"]').length > 0){
                    			Data[0][Names[jj]] = $Selected.eq(0).find('input[name="'+Names[jj]+'"]').val();
                    		}
                    	}
                    	$Form.data('name', JSON.stringify(Data));
                    }
	        	}else if(undefined === Multiple){
	        		$Form.find('input[name="selected"]').val($.map($TargetTable.find('tbody tr td input:checkbox:checked'), function(n){return n.value;}).join(','));
	        	}else{
	        		/**
	        		 * 多行取值
	        		 */
					if(undefined != Names){
						$Selected.each(function(iii, vvv){
							Data[iii] = {};
							for(var jj in Names){
	                    		if($(this).find('td[name="'+Names[jj]+'"]').length > 0){
	                    			Data[iii][Names[jj]] = $(this).find('td[name="'+Names[jj]+'"]').text();
	                    		}else if($(this).find('input[name="'+Names[jj]+'"]').length > 0){
	                    			Data[iii][Names[jj]] = $(this).find('input[name="'+Names[jj]+'"]').val();
	                    		}
	                    	}
						});
                    }
                    $Form.data('name', JSON.stringify(Data));
	        		$Form.find('input[name="selected"]').val($.map($TargetTable.find('tbody tr td input:checkbox:checked'), function(n){return n.value;}).join(','));
	        	}
	        	$Form.find('select').each(function(ii, iv){
					if($(this).data('filter')){
						$(this).children().hide().filter('[data-value="'+$This.find('input[name="'+$(this).data('filter')+'"]').val()+'"]').show();
					}
				});
	        }
		}
		$Form.attr('action', $Button.data('action'));
	};
					
	var init_modal_filter = function(Options){
		var $Button = Options.Trigger, $Wrapper = $Button.parents('div').eq(0), $This = $(this);
		$This.data('wrapper', '#'+$Wrapper.attr('id'));
		$Wrapper.find('input').each(function(i,v){
            if($This.find('input[name="'+this.name+'"]').length > 0){
            	$This.find('input[name="'+this.name+'"]').eq(0).val($(this).val());
            }else if($This.find('select[name="'+this.name+'"]').length > 0){
                var Name = this.name;
                $.each($(this).val().toString().split(','), function(ii, iv){
                    if(iv != ''){
                    	$This.find('select[name="'+Name+'"] option[value="'+iv+'"]').prop('selected', true);
                    }
                });
            }
        });
	};
	
	/**
	 * 模态框中表单的插件初始化
	 */
	var init_modal_form = function(Options){
		//表单的日期插件初始化
		$(this).find('input.datepicker').each(function(i, v){
			$(this).datepicker({
				todayBtn: "linked",
                language: "zh-CN",
                orientation: "top auto",
                autoclose: true,
                todayHighlight: true
			});
		});
    };
    
    var submit_modal_form = function(Options){
    	if('ajax' == Options.Type){
			submit_modal_form_ajax.call(this, Options);
		}else if('filter' == Options.Type){
			submit_modal_form_filter.call(this, Options);
		}else{
			return false;
		}
    };
    
    /**
     * 模态框中表单数据提交
     */
    var submit_modal_form_ajax = function(Options){
    	var $Form = Options.Form, $This = $(this), $Button = Options.Trigger;
    	$Form.find('button:submit').on('click', function(e){
    		e.preventDefault();
    		get_form_data.call($Form[0], Options);
            $.ajax({
                async: false,
                data: Options.SubmitData,
                type: $Form.attr('method'),
                url: $Form.attr('action'),
                beforeSend: function(ie){},
                dataType: 'json',
                success: function(msg){
                    if(msg.error == 0){
                        Options.ModalFormSuccess = true;
                        $This.modal('hide');
                    }else if(msg.error == 1){
                    	$This.find('.serverError').html(msg.message).show();
                    	$This.find('input,select,textarea').on('focus.servererror',function(e){
                    		$This.find('.serverError').html('').hide();
                    		$This.find('input,select,textarea').off('focus.servererror');
                        });
                    }
                },
                error: function(x,t,e){
                	if(x.responseText.length > 0){
                		$This.find('.serverError').html(x.responseText).show();
                    	$This.find('input,select,textarea').on('focus.servererror',function(e){
                    		$This.find('.serverError').html('').hide();
                    		$This.find('input,select,textarea').off('focus.servererror');
                        });
                	}else{
                		alert('服务器执行错误, 提交失败!');
                	}
                }
            });
    	});
    };
    
    /**
     * 模态框中设置搜索条件
     */
    var submit_modal_form_filter = function(Options){
    	var $Form = Options.Form, $This = $(this);
    	$Form.each(function(i, v){
    		$(this).find('button:submit').eq(0).on('click', function(e){
    			e.preventDefault();
    			var $Wrapper = $($This.data('wrapper'));
    			$This.find('input,select').each(function(i,v){
	                if($Wrapper.find('input[name="'+this.name+'"]').length > 0){
	                	$Wrapper.find('input[name="'+this.name+'"]').eq(0).val($(this).val());
	                }
	            });
    		});
    	});
    };
    
    /**
     * 获取表单中数据
     */
    var get_form_data = function(Options){
    	Options.SubmitData = {};
        var Tmp;
        if($(this).data('name')){
        	Options.SubmitData['relate'] = JSON.parse($(this).data('name'));
        }
        $(this).find('input,textarea,select').each(function(i, v){
        	if($(this).parents('tr').length <= 0 || $(this).parents('tr').data('change') || $(this).parents('tr').data('change') === undefined){
                if(this.type == 'radio'){
                    if($(this).prop('checked')){
                    	Options.SubmitData[this.name] = this.value;
                    }
                }else if(this.type == 'checkbox'){
                    if($(this).prop('checked')){
                        if(Options.SubmitData[this.name] == undefined){
                        	Options.SubmitData[this.name] = new Array();
                        	Options.SubmitData[this.name].push(this.value);
                        }else{
                        	Options.SubmitData[this.name].push(this.value);
                        }
                    }
                }else {
                    if($(this).parents('table').length > 0){
                        Tmp = $(this).parents('table').eq(0).attr('id');
                        if(Options.SubmitData[Tmp] == undefined){
                        	Options.SubmitData[Tmp] = {};
                        	Options.SubmitData[Tmp][this.name] = $(this).val();
                        }else{
                            if(Options.SubmitData[Tmp][this.name] != undefined){
                                if(Options.SubmitData[Tmp][this.name] instanceof Array){
                                	Options.SubmitData[Tmp][this.name].push(this.value);
                                }else{
                                	Options.SubmitData[Tmp][this.name] = new Array(Options.SubmitData[Tmp][this.name]);
                                	Options.SubmitData[Tmp][this.name].push(this.value);
                                }
                            }else{
                            	Options.SubmitData[Tmp][this.name] = $(this).val();
                            }
                        }
                    }else{
                        if(Options.SubmitData[this.name] != undefined){
                            if(Options.SubmitData[this.name] instanceof Array){
                            	Options.SubmitData[this.name].push(this.value);
                            }else{
                            	Options.SubmitData[this.name] = new Array(Options.SubmitData[this.name]);
                            	Options.SubmitData[this.name].push(this.value);
                            }
                        }else{
                        	Options.SubmitData[this.name] = $(this).val();
                        }
                    }
                }
            }
        });
    };
    
    $.fn.handle_form = function(Options){
    	var Defaults={
    			Load: '',
    			Form: undefined
    	};
    	var Options = $.extend(Defaults, Options);
    	return this.each(function(i, v){
    		init_form.call(this, Options);
    		init_function.call(this, Options);
    		form_action.call(this, Options);
    	});
    };
    var init_form = function(Options){
    	Options.Form = $(this).find('form[id $= "Form"]').eq(0); /*对应的表单*/
    	Options.Function = $(this).find('div[id $= "Function"]').eq(0);  /*功能区*/
    	Opened[$(this).parent().attr('id')].push(this.id);
    };
    
    var form_action = function(Options){
    	var $Form = Options.Form;
    	$Form.find('button:submit').each(function(i, v){
    		$(this).on('click', function(e){
    			e.preventDefault();
	            get_submit_data.call($Form[0], Options);
	            $.ajax({
	                async: false,
	                data: Options.SubmitData,
	                type: 'post',
	                url: $Form.attr('action'),
	                beforeSend: function(ie){},
	                dataType: 'json',
	                success: function(msg){
	                    if(msg.error == 0){
	                    	if('' != msg.message){
	                    		alert(msg.message);
	                    	}
	                    	if(undefined != Next){
	                    		Next = Next+'?id='+msg.data.oid;
	                    		$.tabRefresh({type:'new',url: Next});
	                    	}else{
	                    		$.tabRefresh();
	                    	}
	                    }else if(msg.error == 1){
	                    	alert(msg.message);
	                    	return false;
	                    }
	                },
	                error: function(x,t,e){
	                	alert('服务器执行错误, 提交失败!');
		            }
	            });
    		});
    	});
    };
    
    /**
     * 主页面处理插件
     */
    $.fn.handle_page = function(Options){
    	var Defaults={
    			Load: '',
    			Table: undefined,
    			Pagination: undefined
    	};
    	var Options = $.extend(Defaults, Options);
    	return this.each(function(i, v){
    		init_page.call(this, Options);
    		init_search.call(this, Options);
    		init_function.call(this, Options);
    		//init_page_linker.call(this, Options);
    		if(Options.Tables.length > 0){
    			init_tables.call(this, Options);
	    		init_pagination.call(this, Options);
	    		load_data.call(this, Options);
    		}
    	});
    };
    
    var init_page = function(Options){
    	Options.This = this;
    	Options.Tables = $(this).find('table');
    	Options.Search = $(this).find('div[id $= "Search"]'); /*搜索区*/
    	Options.Function = $(this).find('div[id $= "Function"]');  /*功能区*/
    	if($(this).parent().attr('id')){
    		Opened[$(this).parent().attr('id')].push(this.id);
    	}
    };
    
    /**
     *搜索框 
     */
    var init_search = function(Options){
    	if(Options.Search.length > 0){
    		Options.Search.each(function(i, v){
	    		var $Search = $(this);
	    		var Data = {
	    			Target: $(this).data('target'),
	    		};
	    		switch($Search.data('toggle')){
		    		case 'search':
		    			search_search.call(this, Options, Data);
		    			break;
		    		case 'filter':
		    			search_filter.call(this, Options, Data);
		    			break;
		    	}
	    	});
    	}
    };
    
    var search_search = function(Options, Param){
    	$(this).find('input[name="keyword"]').each(function(i, v){
            $(this).keyup(function(){
                $(Param.Target).find('tbody tr:not(.model, .no-data, .loading)').hide().filter(":contains('"+( $(this).val() )+"')").show();
            });
        });
    };
    
    var search_filter = function(Options, Param){
    	$(this).find('button:submit').eq(0).on('click', function(e){
    		e.preventDefault();
    		remove_cookie(Options.This.id, $(Param.Target).attr('id'));
    		load_table.call($(Param.Target)[0], Options);
		});
    };
    
    /**
     *功能区 
     */
    var init_function = function(Options){
    	if(Options.Function.length > 0){
    		Options.Function.each(function(i, v){
    			var This = this;
    			$(this).find('button, a').each(function(i, v){
		    		var Toggle = $(this).data('toggle'),
		    		Data = {
		    			Href:$(this).attr('href') != undefined&&!$(this).attr('href').match(/javascript\:void\(0\)/g)?$(this).attr('href'):($(this).data('action')!=undefined?$(this).data('action'):undefined),
		    			Target: $(this).data('target'),
		    			Multiple: $(this).data('multiple'),
		    			Names: $(this).data('name') == undefined?undefined:$(this).data('name').split(','),
		    			Title: $(this).text().replace(/\s/g, '')
		    		};
		    		switch(Toggle){
			    		case 'refresh':
			    			$(this).off('click.refresh').on('click.refresh', function(e){
			                    $.tabRefresh();
			                });
			                break;
			    		case 'backstage':
			    			function_backstage.call(this, Options, Data);
			    			break;
			    		case 'modal':
			    			break;
			    		case 'child':
			    			function_child.call(this, Options, Data);
			    			break;
			    		case 'mtab':
			    			function_mtab.call(this, Options, Data);
			    			break;
			    		case 'blank':
			    			function_blank.call(this, Options, Data);
			    			break;
			    		case 'reply':
			    			function_reply.call(this, Options, This);
			    			break;
			    		case 'save':
			    			function_save.call(this, Options, Data);
			    			break;
		    		}
		    	});
    		});
    	}
    };
    
    /**
     * 提交后台操作
     */
    var function_backstage = function(Options, Param){
    	$(this).on('click', function(e){
    		e.preventDefault();
            var Data={}, $Selected = $(Param.Target).find('tbody tr td input:checkbox:checked');
            if((undefined !== Param.Multiple && 0 == $Selected.length)){
            	alert('请先选择!');
                return false;
            }else{
            	if(confirm('确定执行'+$(this).text()+'操作?')){
            		if(Param.Multiple){
                        Data['selected'] = new Array();
                        Data['selected'] = $.map($Selected, function(n){return n.value;});
                        if(undefined != Param.Names){
                        	Data['relate'] = {};
							$Selected.parents('tr').each(function(iii, vvv){
								Data['relate'][iii] = {};
								for(var jj in Param.Names){
		                    		if($(this).find('td[name="'+Param.Names[jj]+'"]').length > 0){
		                    			Data['relate'][iii][Param.Names[jj]] = $(this).find('td[name="'+Param.Names[jj]+'"]').text();
		                    		}else if($(this).find('input[name="'+Param.Names[jj]+'"]').length > 0){
		                    			Data['relate'][iii][Param.Names[jj]] = $(this).find('input[name="'+Param.Names[jj]+'"]').val();
		                    		}
		                    	}
							});
	                    }
                    }else if(false === Param.Multiple){
                        Data['selected'] = $Selected.eq(0).val();
                        var $SelectedOne = $Selected.parents('tr').eq(0);
                        if(undefined != Param.Names){
                        	Data['relate'] = {};
		                	Data['relate'][0] = {};
		                	for(var jj in Param.Names){
	                    		if($SelectedOne.find('td[name="'+Param.Names[jj]+'"]').length > 0){
	                    			Data[0][Param.Names[jj]] = $SelectedOne.find('td[name="'+Param.Names[jj]+'"]').text();
	                    		}else if($Selected.parents('tr').eq(0).find('input[name="'+Param.Names[jj]+'"]').length > 0){
	                    			Data[0][Param.Names[jj]] = $SelectedOne.find('input[name="'+Param.Names[jj]+'"]').val();
	                    		}
	                    	}
	                    }
                        
                    }
            	}else{
            		return false;
            	}
            }
            $.ajax({
                async: false,
                data:Data,
                url: Param.Href,
                type: 'post',
                dataType: 'json',
                success: function(msg){
                    if(msg.error == 0){
                        $.tabRefresh();
                    }else if(msg.error == 1){
                        alert(msg.message);
                    }
                },
                error: function(x,t,e){
                	alert('服务器执行错误, 提交失败!');
                }
            });
    	});
    };
    /**
     * 载入子页面
     */
    var function_child = function(Options, Param){
    	$(this).on('click', function(e){
    		e.preventDefault();
            var Url, $Selected = $(Param.Target).find('tbody tr td input:checkbox:checked');
            if(true === Param.Multiple){
            	if($Selected.length > 0){
	            	Url = Param.Href+'?id='+$.map($Selected, function(n){return n.value;}).join(',');
            	}else{
            		alert('请先选择!');
                	return false;
            	}
            }else if(false === Param.Multiple){
            	if($Selected.length > 0){
	            	Url = Param.Href+'?id='+$Selected.eq(0).val();
            	}else{
            		alert('请先选择!');
                	return false;
            	}
            }else{
            	Url = Param.Href;
            }
            $.tabRefresh({type:'new',url: Url});
            return true;
    	});
    };
    
    var function_mtab = function(Options, Param){
    	$(this).on('click', function(e){
    		e.preventDefault();
            var Url, $Selected = $(Param.Target).find('tbody tr td input:checkbox:checked');
            if(confirm('确定执行'+$(this).text()+'操作?')){
            	if(true === Param.Multiple){
	            	if($Selected.length > 0){
		            	Url = Param.Href+'?id='+$.map($Selected, function(n){return n.value;}).join(',');
	            	}else{
	            		alert('请先选择!');
	                	return false;
	            	}
	            }else if(false === Param.Multiple){
	            	if($Selected.length > 0){
		            	Url = Param.Href+'?id='+$Selected.eq(0).val();
	            	}else{
	            		alert('请先选择!');
	                	return false;
	            	}
	            }else{
	            	Url = Param.Href;
	            }
	            $.tabRefresh({type:'tab',url: Url, title: Param.Title});
	            return true;
            }else{
            	return false;
            }
            
    	});
    };
    
    /**
     * 打开新页面
     */
    var function_blank = function(Options, Param){
    	$(this).on('click', function(e){
            var $Selected = $(Param.Target).find('tbody tr td input:checkbox:checked');
            var Id = '', Names;
            if(undefined === Param.Multiple){
                return true;
            }else{
                if($Selected.length > 0){
                    if(Param.Multiple){
                        Id = $.map($Selected, function(n){return n.value;}).join(',');
                    }else if(false === Param.Multiple){
                        Id = $Selected.eq(0).val();
                    }
                    $(this).attr('href', function(ii, iv){
                        if(iv.lastIndexOf('?') >= 0){
                            return iv.substr(0,iv.lastIndexOf('?'))+'?id='+Id;
                        }else{
                            return iv+'?id='+Id;
                        }
                    });
                    return true;
                }else{
                    alert('请先选择!');
                    return false;
                }
            }
            
        });
    };
    
    /**
     * 返回前一个界面
     */
    var function_reply = function(Options, This){
    	$(this).on('click', function(e){
        	remove_cookies($(Options.This).attr('id'));
        	$.tabRefresh({type:'back'});
    	});
    };
    
    /**
     *保存表单 
     */
    var function_save = function(Options, Params){
    	$(this).on('click', function(e){
    		e.preventDefault();
            get_submit_data.call($(Params.Target)[0], Options);
            $.ajax({
                async: false,
                data: Options.SubmitData,
                type: 'post',
                url: Params.Href,
                beforeSend: function(ie){},
                dataType: 'json',
                success: function(msg){
                    if(msg.error == 0){
                    	if('' != msg.message){
                    		alert(msg.message);
                    	}
                    	if('' != msg.location){
                    		if(typeof msg.location == 'string'){
                    			if(isURL(msg.location)){
                    				$.tabRefresh({type:'new',url: msg.location});
                    			}else if('close' == msg.location){
                    				$.tabDelete();
                    			}else{
                    				$.tabRefresh();
                    			}
                    		}else if(typeof msg.location == 'object'){
                    			$.tabRefresh();
                    			$.tabRefresh({type:msg.location.type,url: msg.location.url,title: msg.location.title});
                    		}else{
                    			$.tabRefresh();
                    		}
                    	}else{
                    		$.tabRefresh();
                    	}
                    	
                    }else if(msg.error == 1){
                    	alert(msg.message);
                    	return false;
                    }
                },
                error: function(x,t,e){
                	alert('服务器执行错误, 提交失败!');
	            }
            });
    	});
    };
    /*
    
        var init_page_linker = function(Options){
            var $PageLinker = Options.PageLinker;
            var This = this;
            $PageLinker.find('a').each(function(i, v){
                $(this).on('click', function(e){
                    e.preventDefault();
                    if($(this).data('action') != undefined){
                        var Url = $(this).data('action');
                    }else{
                        var Url = $(this).attr('href');
                    }
                    $.tabRefresh({type:'new',url: Url});
                    return true;
                });
            });
        };*/
    
    /**
     * 获取表单中数据
     */
    var get_submit_data = function(Options){
    	Options.SubmitData = {};
        var Tmp;
        $(this).find('input,textarea,select').each(function(i, v){
        	if('radio' == this.type){
        		if( $(this).prop('checked')){
        			Options.SubmitData[this.name] = this.value;
        		}
        	}else if('checkbox' == this.type){
        		if($(this).prop('checked')){
        			if(Options.SubmitData[this.name] == undefined){
	                	Options.SubmitData[this.name] = new Array();
	                	Options.SubmitData[this.name].push(this.value);
	                }else{
	                	Options.SubmitData[this.name].push(this.value);
	                }
        		}
        	}else{
        		if(Options.SubmitData[this.name] != undefined){
                    if(Options.SubmitData[this.name] instanceof Array){
                    	Options.SubmitData[this.name].push(this.value);
                    }else{
                    	Options.SubmitData[this.name] = new Array(Options.SubmitData[this.name]);
                    	Options.SubmitData[this.name].push(this.value);
                    }
                }else{
                	Options.SubmitData[this.name] = $(this).val();
                }
        	}
        });
    };
    

    
    /**
     * 初始化表格
     */
    var init_tables = function(Options){
    	var $Tables = Options.Tables;
    	$Tables.each(function(i, v){
    		/*全选*/
    		var Compute = undefined, $Table = $(this);
    		if($(this).find('thead th.checkall').length > 0){
    			Compute = $(this).find('thead th.checkall').data('compute');
    			$(this).find('thead th.checkall').on('click', function(e){
					var $Statistics = Options.Function.find('span#'+$Table[0].id+'Selected');
					if(false == $(this).data('checkall') || undefined == $(this).data('checkall')){
						$Table.find('tbody tr:not(.model) input:checkbox').prop('checked', true);
						$Statistics.text($Table.find('tbody tr:not(.model) input:checkbox').length);
						$(this).data('checkall', true);
					}else{
						$Table.find('input:checkbox').prop('checked', false);
		                $Statistics.text(0);
						$(this).data('checkall', false);
					}
					if(undefined != Compute){
						var Amount = 0;
						for(var ii in Compute){
							Amount = eval($.map($Table.find('tbody tr:not(.model) input:checkbox:checked'), function(n){
								return parseInt($(n).parents('tr').eq(0).find('td[name="'+ii+'"]').text());
							}).join('+'));
							$(Options.This).find('input[name="'+Compute[ii]+'"]').val(Amount);
						}
					}
				});
    		}
    		/*行高亮*/
    		$(this).find('tbody tr').each(function(i, v){
	            $(this).click(function(e){
	                $(this).addClass('success').siblings().removeClass('success');
	            }).dblclick(function(e){
	                $(this).find('input:checkbox').trigger('click');
	                if($(this).hasClass('active')){
	                	$(this).removeClass('active');
	                }else{
	                	$(this).addClass('active');
	                }
	            });
	        });
	        /*选择行*/
	    	$(this).find('tbody tr td input:checkbox').each(function(ii, iv){
	            $(this).click(function(e){
	                var $Tmp = Options.Function.find('span#'+$(this).parents('table').eq(0)[0].id+'Selected');
	                if($(this).prop('checked')){
	                    $Tmp.data('num', $Tmp.data('num')+$(this).val()+" ")
	                       .text(function(iii, iiv){return parseInt(iiv)+1;});
	                }else{
	                    var selected = $.trim($Tmp.data('num')).toString().split(' '); 
	                    selected.splice($.inArray($(this).val(), selected),1);
	                    $Tmp.data('num', selected.join(' ')+' ')
	                       .text(function(iii, iiv){return parseInt(iiv)-1;});
	                }
	                if(undefined != Compute){
						var Amount = 0;
						for(var ii in Compute){
							Amount = eval($.map($Table.find('tbody tr:not(.model) input:checkbox:checked'), function(n){
								return parseInt($(n).parents('tr').eq(0).find('td[name="'+ii+'"]').text());
							}).join('+'));
							$(Options.This).find('input[name="'+Compute[ii]+'"]').val(Amount);
						}
					}
	            });
	        });
	        /*
	         * 表格中链接处理, 单体中
	         */
	        $(this).find('tbody a').each(function(ii, vv){
	        	if('floatover' == $(this).data('toggle')){
	        		$(this).on('mouseover', function(e){
		        		var Id = $(this).parents('tr').eq(0).children('td:first').find('input:checkbox').val(),
		        			Remote = $(this).data('remote'),
		        			$Target = $(this).data('target')?$($(this).data('target')):false,
		        			LoadId = $Target?$Target.data('load-id'):false;
		        		if(Id && Remote && $Target){
		        			if(Id == LoadId){
		        				$Target.removeClass('hide');
		        			}else{
		        				$.ajax({
				        			async: true,
				                    url: Remote,
				                    type: 'get',
				                    dataType: 'html',
				                    data: {id: Id},
				                    beforeSend: function(){
				                    	$Target.html('').removeClass('hide');
				                    	$('html').on('keydown', function(e){
											key = e.keyCode;
											switch(key){
												case 27:
												$Target.addClass('hide');
												$(this).off('keydown');
												break;
											}
										});
				                    },
				                    success: function(msg){
				                    		$Target.css({left: (e.pageX+50)+'px'}).html(msg).data('load-id', Id);
				                        },
				                    error: function(x,t,e){alert(e+'服务器错误, 执行失败!');}
				        		});
		        			}
		        		}
	        		});
	        		$(this).on('click', function(e){
						e.preventDefault();
			            var Id = $(this).parents('tr').eq(0).children('td:first').find('input:checkbox').val(),
			            	Url = $(this).attr('href'), Title = (undefined == $(this).data('title'))?$(this).attr('title'):$(this).data('title');
			            if(Id && Url && Title){
			            	Url = Url+'?id='+Id;
			            	$.tabRefresh({type:'tab',url: Url, title: Title});
			            }
			            return false;
					});
	        	}else if('blank' == $(this).data('toggle')){
	        		$(this).on('click', function(e){
	        			var Did = $(this).parents('tr').eq(0).find('td[name="did"]').text();
						$(this).attr('href', function(ii, iv){
		                    if(iv.lastIndexOf('?') >= 0){
		                        return iv.substr(0,iv.lastIndexOf('?'))+'?id='+Did;
		                    }else{
		                        return iv+'?id='+Did;
		                    }
		                });
			            return true;
					});
	        	}
	        });
	        $(this).find('thead a').each(function(i, v){
	        	$(this).on('click', function(e){
					e.preventDefault();
		            var Url = $(this).attr('href'), Title = (undefined == $(this).data('title'))?$(this).attr('title'):$(this).data('title');
		            if(Url && Title){
		            	$.tabRefresh({type:'tab',url: Url, title: Title});
		            }
		            return false;
				});
	        });
    	});
    };

    /**
     * 分页插件
     */
    var init_pagination = function(Options){
    	var $This = $(this);
    	Options.Tables.each(function(i, v){
    		var $Table = $(this);
    		var Load = $Table.data('load');
    		var $Paging = $Table.next('div.paging').eq(0);
    		$Paging.find('a').on('click', function(e){
    			e.preventDefault();
	            var Href = $(this).attr('href');
	            if(isNaN(Href)){
	            	return false;
	            }
	            $.ajax({
	                    async: true,
	                    url: Load,
	                    type: 'get',
	                    dataType: 'json',
	                    data: {page: $This.attr('id'), table: $Table.attr('id'), p: Href},
	                    beforeSend: function(){
	                        $Table.find('tr.loading').show();
	                        $Table.find('tr.no-data').hide();
	                        $Paging.addClass('hide');
	                        $Table.find('tr.model').prevUntil('.no-data').remove();
	                    },
	                    success: function(msg){
		                    	if (Options.Success && Options.Success[$Table.attr('id')]) {
		                     	    Options.Success[$Table.attr('id')].call($This[0], $Table[0], msg, Options);
		                        }else{
		                            load_data_success.call($This[0], $Table[0], msg, Options);
		                        }
	                        },
	                    complete: function(msg){
	                        $This.find('tr.loading').hide();
	                    },
	                    error: function(x,t,e){alert(e+'服务器错误, 执行失败!');}
	            });
	            add_cookie($This.attr('id'), $Table.attr('id'), {p: Href});
    		});
    	});
    };
    
    var load_table = function(Options, Cookies){
    	var $Table = $(this), $This = $(Options.This), 
    	Load = $Table.data('load');
    	if(undefined != Cookies && undefined != Cookies[$Table.attr('id')]){
    		var Conditions = Cookies[$Table.attr('id')];
    	}else{
    		var Conditions = conditions(Options, $Table.attr('id'));
    	}
		
		if(undefined != Load){
    		$.ajax({
	            async: true,
	            type: 'get',
	            url: Load,
	            dataType: 'json',
	            data: Conditions,
	            beforeSend: function(){
	            	$Table.find('tr.loading').show();
	            	$Table.find('tr.no-data').hide();
	            	$Table.find('tr.model').prevUntil('.no-data').remove();
	            },
	            success: function(msg){
	                   if (Options.Success && Options.Success[$Table.attr('id')]) {
	                	   Options.Success[$Table.attr('id')].call(Options.This, $Table[0], msg, Options);
	                   }else{
	                       load_data_success.call(Options.This, $Table[0], msg, Options);
	                   }
	                },
	            complete: function(msg){
	            	$Table.find('tr.loading').hide();
	            },
	            error: function(x,t,e){alert(e+'服务器错误, 执行失败!');}
	    	});
	    	var Cookie = {};
	    	Cookie[$Table.attr('id')] = Conditions;
	    	$.cookie(Options.This.id,  JSON.stringify(Cookie));
    	}
    };
    /**
     * 载入数据
     */
    var load_data = function(Options){
    	if(undefined != $.cookie(this.id)){
    		var Cookies = JSON.parse($.cookie(this.id));
    		$.removeCookie(this.id);
    	}else{
    		var Cookies = undefined;
    	}
    	Options.Tables.each(function(i, v){
    		load_table.call(this, Options, Cookies);
    	});
    };
    
    /**
     * 获取赛选条件
     */
    var conditions = function(Options, Table){
    	var Conditions = {};
    	Table = '#'+Table;
    	Options.Search.each(function(i, v){
    		if($(this).data('target') == Table){
    			$(this).find('input,select').each(function(i,v){
		    		Conditions[this.name] = this.value;
		        });
		        return false;
    		}
    	});
    	return Conditions;
    };
    
    var load_data_success = function(Table, Msg, Options){
    	var $This = $(this);
    	var $Table = $(Table);
    	if(0 == Msg.error && Msg.data != undefined && (Msg.data.pn == undefined || Msg.data.pn > 0)){
            var $Model = $Table.find('tbody tr.model').eq(0);
            var $ItemClone;
            Options.Function.find('span#'+$Table[0].id+'Selected').text('0');
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
                    	if($(this).find('a').length > 0){
                    		$(this).find('a').append(Msg.data.content[i][$(this).attr('name')]);
                    	}else{
                    		$(this).append(Msg.data.content[i][$(this).attr('name')]);
                    	}
                    }
                });
            }
            $Model.prevUntil('.no-data').removeClass('model');
            $Table.tablesorter($Table.find('thead tr').getHeaders()); /** 表格排序*/
            var $Paging = $Table.next('div.paging').eq(0);
	    	if($Paging != undefined){
	    		var $Footnote = $Paging.find('p.footnote');
	    		var $Pagination = $Paging.find('ul.pagination');
	    		if(undefined != Msg.data.pn){
	    			$Footnote.html(Msg.data.num+'条&nbsp;&nbsp;共'+Msg.data.pn+'页');
	    		}else{
	    			$Footnote.html('0条&nbsp;&nbsp;共0页');
	    		}
	            if(Msg.data.pn > 1){
	                if($Pagination.children().length > 5){
						/**
						 * 清除原有的分页
						 */
	                    var PLength = $Pagination.children().length;
	                    $Pagination.children().each(function(ii, iv){
	                        if(ii > 2 && ii < PLength-2){
	                            $(this).remove();
	                        }
	                    });
	                }
	                /**
					 * 中间页
					 */
	                var PageClone, ShowPage = 5;
	                var ShowSize = parseInt((Msg.data.pn - 1)/ShowPage);
	                var Show = parseInt((Msg.data.p - 1)/ShowPage);
	                var EndNum = ShowSize*ShowPage + 1;
	                if(0 == ShowSize){
	                	var Start = 1, End = Msg.data.pn;
	                }else{
	                	if(Msg.data.p <= ShowPage){
	                		var Start = 1, End = ShowPage;
	                	}else if(Msg.data.p >= EndNum){
	                		var Start = EndNum, End = Msg.data.pn;
	                	}else{
	                		var Start = Show*ShowPage + 1, End = Start + ShowPage - 1;
	                	}
	                }
	                var $Page = $Pagination.children(':eq(2)');
	                $Page.removeClass('active');
	                
	                for(var jj = End; jj >= Start; jj--){
	                	p = $Page.clone(true);
	                    if(jj == Msg.data.p){
	                    	p.addClass('active').find('a').attr('href', 'javascript:void(0);').html(jj+'<span class="sr-only">(current)</span>');
	                    }else{
	                    	p.find('a').attr('href', jj).html(jj);
	                    }
	                    $Page.after(p);
	                }
	                $Page.remove();
					/**
					 * 第一页
					 */
	                if(1 == Msg.data.p){
	                	$Pagination.children(":first").addClass('hide');
	                	$Pagination.children(':eq(1)').addClass('hide');
	                }else{
	                	$Pagination.children(":first").removeClass('hide').find('a').attr('href', 1);
	                	$Pagination.children(':eq(1)').removeClass('hide').find('a').attr('href', Msg.data.p - 1);
	                }
					/**
					 * 最后一页
					 */
	                if(Msg.data.p == Msg.data.pn){
	                	$Pagination.children(":last").addClass('hide');
	                	$Pagination.children(":last").prev().addClass('hide');
	                }else{
	                	$Pagination.children(":last").removeClass('hide').find('a').attr('href', parseInt(Msg.data.pn));
	                	$Pagination.children(":last").prev().removeClass('hide').find('a').attr('href', Msg.data.p + 1);
	                }
					
	                $Paging.removeClass('hide');
	            }else{
	                if(!$Paging.hasClass('hide')){
	                    $Paging.addClass('hide');
	                }
	            }
	    	}
        }else{
            $Table.find('.no-data').show();
        }
    };
})(jQuery,window, undefined);
