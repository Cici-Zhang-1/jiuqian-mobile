<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-27
 * @author ZhangCC
 * @version
 * @description  
 */
class Order extends MY_Controller {
	private $Count;
	private $Insert;
	private $Search = array(
			'status' => '',
	        'start_date' => '',
	        'end_date' => '',
			'keyword' => ''
	);
	public function __construct(){
		parent::__construct();
        log_message('debug', 'Controller Order/Order __construct Start!');
		$this->load->model('order/order_model');
	}
	
	public function index(){
		$View = $this->uri->segment(4, 'read');
		if(method_exists(__CLASS__, '_'.$View)){
			$View = '_'.$View;
			$this->$View();
		}else{
			$this->_index($View);
		}
	}
	
	public function read(){
		$Cookie = $this->_Cookie.__FUNCTION__;
		$this->_Search = array_merge($this->_Search, $this->Search);
        $this->get_page_search();
        // $this->Search = $this->get_page_conditions($Cookie, $this->Search);
        $this->Search = $this->_Search;
		$Data = array();
		if(!empty($this->Search)){
		    $this->load->library('permission');
		    if (!!($E = $this->permission->get_element_by_operation())) {
                $this->order_model->set_element($E);
            }

		    if(!!($Data = $this->order_model->select_order($this->Search))){
		        $this->Search['pn'] = $Data['pn'];
		        $this->Search['num'] = $Data['num'];
		        $this->Search['p'] = $Data['p'];
		        $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
		    }else{
		        $this->Code = EXIT_ERROR;
		        $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的订单';;
		    }
		}else{
		    $this->Message = '对不起, 没有符合条件的内容!';
		}
		$this->_ajax_return($Data);
	}
	
	/**
	 * 根据经销商信息获取订单编号(登帐时使用)
	 */
	public function read_order_num(){
	    $Did = $this->input->get('did', true);
	    $Days = $this->input->get('days', true);
	    $Did = intval(trim($Did));
	    $Days = intval(trim($Days));
	    $StartDatetime = $Days <= 0?date('Y-m-d H:i:s', strtotime('-30 days')):date('Y-m-d H:i:s', strtotime('-'.$Days.' days'));
	    $Data = array();
	    if(!($Data = $this->order_model->select_order_num($Did, $StartDatetime))){
	        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'获取订单编号失败!';
	    }
	    $this->_return($Data);
	}

	public function read_wait_position() {
		$this->_Item = $this->_Item.__FUNCTION__;

		$Data = array();
		if(!($Query = $this->position_model->select_wait_position())){
			$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有de';
		}else{
			$Data['content'] = $Query;
		}
		$this->_return($Data);
	}
	
	/**
	 * 新建订单
	 */
	public function add($Dismantle=''){
	    $Dismantle = trim($Dismantle);
		if ($this->_do_form_validation()) {
            $Order = array(
                'otid' => $this->input->post('otid', true),
                'dealer_id' => $this->input->post('did', true),
                'dealer' => $this->input->post('dealer', true),
                'checker' => $this->input->post('checker', true),
                'checker_phone' => $this->input->post('checker_phone', true),
                'payterms' => $this->input->post('payterms', true),
                'payer' => $this->input->post('payer', true),
                'payer_phone' => $this->input->post('payer_phone', true),
                'logistics' => $this->input->post('logistics', true),
                'out_method' => $this->input->post('out_method', true),
                'delivery_area' => $this->input->post('delivery_area', true),
                'delivery_address' => $this->input->post('delivery_address', true),
                'delivery_linker' => $this->input->post('delivery_linker', true),
                'delivery_phone' => $this->input->post('delivery_phone', true),
                'owner' => $this->input->post('owner', true),
                'remark' => $this->input->post('remark', true),
                'dealer_remark' => $this->input->post('dealer_remark', true),
                'request_outdate' => $this->input->post('request_outdate', true),
                'flag' => $this->input->post('flag', true)
            );
            $Order = gh_escape($Order);
            if(!!($this->Insert = $this->order_model->insert_order($Order))){
                $this->load->library('workflow/workflow');
                if(!!($this->workflow->initialize('order',$this->Insert['oid']))){
                    $this->workflow->create();
                    $this->_add_order_product();
                    if('dismantle' == $Dismantle){
                        $this->Location = array(
                            'type' => 'tab',
                            'title' => '拆单',
                            'url' => site_url('order/dismantle/index/read?id='.$this->Insert['oid'])
                        );
                    }
                    $this->Message = $this->Insert['order_num'].'订单新增成功, 刷新后生效!';
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message = $this->workflow->get_failue();
                }
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单新增失败!';
            }
        }
		$this->_return($this->Insert);
	}
	
	private function _add_order_product(){
	    $Pid = $this->input->post('pid', TRUE);
	    if(!is_array($Pid)){
	        $Pid = array($Pid);
	    }
	    $this->load->model('product/product_model');
	    if(!!($Pid = $this->product_model->select_product_code_by_id($Pid))){
	        $this->load->model('order/order_product_model');
	        if(!!($Query = $this->order_product_model->insert($Pid, $this->Insert))){
	            foreach ($Query as $key => $value){
	                if(!!($this->workflow->initialize('order_product', $value['opid']))){
	                    $this->workflow->create();
	                }else{
	                    $this->Failue = $this->workflow->get_failue();
	                    break;
	                }
	            }
	        }else{
	            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单产品新增失败!';
	        }
	    }else{
	        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'获取产品类型失败!';
	    }
	}
	
	private function _add_order_products(){
	    $Pid = $this->input->post('pid', true);
	    if($Pid){
	        if(!is_array($Pid)){
	            $Pid = array($Pid);
	        }
	        $this->load->model('product/product_model');
	        if(!!($Pid = $this->product_model->select_product_code_by_id($Pid))){
	            foreach ($Pid as $key => $value){
	                $OrderProduct[$key] = array(
	                    'order_id' => $this->Insert['oid'],
	                    'pid' => $value['pid'],
	                    'num' => $this->Insert['order_num'].'-'.$value['code'].'1'
	                );
	            }
	            $this->load->model('order/order_product_model');
	            if(!!($Return = $this->order_product_model->insert_batch($OrderProduct))){
	                foreach ($Return as $key => $value){
	                    $this->workflow->initialize('order_product', $value['opid']);
	                    $this->workflow->create();
	                }
	            }
	        }
	    }
	    return true;
	}
	
	/**
	 * 插入订单详情
	 * @param unknown $Id
	 * @return boolean
	 */
	private function _add_order_detail(){
		$OdproductId = $this->input->post('odproduct_id');
		if(is_array($OdproductId)){
			$this->Count = count($OdproductId);
		}elseif($OdproductId !== false){
			$this->Count = 1;
		}
		if($this->Count > 0){
			$Item = $this->_Module.'/order_detail';
			$Run = $Item.'/add';
			if($this->Count > 1){
				$Run .= '/array';
			}
			if($this->form_validation->run($Run)){
				$this->config->load('formview', TRUE);
				$FormView = $this->config->item($Item, 'formview');
				foreach ($FormView as $key=>$value){
					$tmp = $this->input->post($key)?$this->input->post($key):$this->_default($key);
					if($tmp !== false){
						if(is_array($tmp)){
							foreach ($tmp as $ikey => $ivalue){
								$Set[$ikey][$value] = $ivalue;
							}
						}else{
							$Set[$value] = $tmp;
						}
						unset($tmp);
					}
				}
				if(isset($Set)){
					if($this->Count > 1){
						if(!!($this->order_detail_model->insert_batch_order_detail(gh_mysql_string($Set)))){
							return true;
						}else{
							$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户新增失败&nbsp;&nbsp;';
						}
					}else{
						if(!!($this->order_detail_model->insert_order_detail(gh_mysql_string($Set)))){
							return true;
						}else{
							$this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户新增失败&nbsp;&nbsp;';
						}
					}
				}else{
					return true;
				}
			}else{
				$this->Failue .= validation_errors();
			}
		}else{
			return true;
		}
	}
	
	public function edit(){
        $Run = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $Post = gh_escape($_POST);
            $Selected = intval(trim($this->input->post('selected', true)));
            if(!!($Return = $this->order_model->is_editable($Selected))){
                $Return = array_pop($Return);
                if($Return['status'] > 16){
                    /*等待发货之后的订单支付条款不可以改变*/
                    $Post['payterms'] = $Return['payterms'];
                }elseif($Return['status'] > 15 && ('款到生产' == $Post['payterms'] || '款到发货' == $Post['payterms'])){
                    $Post['payterms'] = $Return['payterms'];
                }elseif (15 == $Return['status'] && ('物流代收' == $Post['payterms'] 
                    || '按月结款' == $Post['payterms'] 
                    || '到厂付款' == $Post['payterms'])){
                    /*款到发货的订单，修改支付方式为物流代收，则自动状态进1*/
                    $Workflow = $Return['oid'];
                }elseif (($Return['status'] > 9 && $Return['status'] <= 15) && '款到生产' == $Post['payterms']){
                    /*款到发货之后修改支付方式，如果修改为款到发货，则保持原支付条款*/
                    $Post['payterms'] = $Return['payterms'];
                }elseif (9 == $Return['status'] && ('物流代收' == $Post['payterms'] 
                    || '款到发货' == $Post['payterms'] 
                    || '按月结款' == $Post['payterms'] 
                    || '到厂付款' == $Post['payterms'])){
                    /*款到发货时修改支付方式，则状态进1*/
                    $Workflow = $Return['oid'];
                }
                /* else{
                    $Post['payterms'] = $Return['payterms'];
                } */
                unset($Return);
                if(!!($this->order_model->update_order($Post, $Selected))){
                    if(isset($Workflow)){
                        $this->load->library('workflow/workflow');
                        if($this->workflow->initialize('order', $Workflow)){
                            $this->workflow->payterms();
                        }
                    }
                    $this->Message .= '订单修改成功, 刷新后生效!';
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单修改失败';
                }
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单修改失败';
            }
        }else{
            $this->Code = EXIT_ERROR;
            $this->Message .= validation_errors();
        }
		$this->_return();
	}
	
	
    /**
     * 发货前的订单【1-16】，都可以作废
     */
	public function remove(){
	    $Item = $this->_Item.__FUNCTION__;
	    if($this->form_validation->run($Item)){
	        $Selected = $this->input->post('selected', true);
	        if(!!($Selected = $this->order_model->is_removable($Selected))){
                $Debt1 = array();
                $Debt2 = array();
                foreach ($Selected as $key => $value){
                    $Selected[$key] = $value['oid'];
                    /*经销商账目*/
                    if ($value['status'] >= 9 && $value['status'] < 12){
                        /**
                         * 等待生产的金额
                         */
                        if(empty($Debt1[$value['did']])){
                            $Debt1[$value['did']] = $value['sum'];
                        }else{
                            $Debt1[$value['did']] += $value['sum'];
                        }
                    }elseif($value['status'] >= 12 && $value['status'] <= 16){
                        /**
                         * 正在生产的金额
                         */
                        if(empty($Debt2[$value['did']])){
                            $Debt2[$value['did']] = $value['sum'];
                        }else{
                            $Debt2[$value['did']] += $value['sum'];
                        }
                    }
                }
                $this->load->model('dealer/dealer_model');
                if(!empty($Debt1)){
                    $this->dealer_model->update_dealer_remove($Debt1, 'debt1');
                }
                if(!empty($Debt2)){
                    $this->dealer_model->update_dealer_remove($Debt2, 'debt2');
                }
                $this->load->library('workflow/workflow');
                if($this->workflow->initialize('order', $Selected)){
                    $this->workflow->remove();
                    $this->load->model('order/order_product_model');
                    $this->order_product_model->delete_by_oid($Selected);/*同时清除订单产品*/
                    $this->Success .= '订单成功作废';
                }else{
                    $this->Failue = $this->workflow->get_failue();
                }
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单作废失败';
            }
	    }else{
	        $this->Failue .= validation_errors();
	    }
	    $this->_return();
	}
}
