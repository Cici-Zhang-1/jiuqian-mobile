<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2014-11-19
 * @author ZhangCC
 * @version
 * @description  
 */
class Order_model extends MY_Model{
    private $_Num;
	public function __construct(){
		parent::__construct(__DIR__, __CLASS__);
		log_message('debug', 'Model Order/Order_model start!');
	}

	public function select ($Search, $Type = '') {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search) . $Type;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item . $Type);
                $this->HostDb->select($Sql)->from('order')
                    ->join('order_datetime', 'od_order_id = o_id', 'left')
                    ->join('task_level', 'tl_id = o_task_level', 'left')
                    ->join('pay_status', 'ps_name = o_pay_status', 'left')
                    ->join('workflow_order', 'wo_id = o_status', 'left')
                    ->join('user AS A', 'A.u_id = od_creator', 'left');
                if ($Type == '_check') {
                    $this->HostDb->join('user AS V', 'V.u_id = od_valuate', 'left');
                } elseif ($Type == '_produce') {
                    $this->HostDb->join('user AS S', 'S.u_id = od_sure', 'left');
                }
                if (!empty($Search['order_id'])) {
                    $this->HostDb->where('o_id', $Search['order_id']);
                }
                if (empty($Search['all'])) {
                    $this->HostDb->where('od_creator', $this->session->userdata('uid'));
                }
                if (!empty($Search['status'])) {
                    if (is_array($Search['status'])) {
                        $this->HostDb->where_in('o_status', $Search['status']);
                    } else {
                        $this->HostDb->where_in('o_status', explode(',', $Search['status']));
                    }
                } else {
                    $this->HostDb->where('o_status != ', O_REMOVE);
                }
                if (!empty($Search['dealer_id'])) {
                    $this->HostDb->where('o_dealer_id', $Search['dealer_id']);
                }
                if (!empty($Search['start_create_date'])) {
                    $this->HostDb->where('od_create_datetime >= ', $Search['start_create_date']);
                }
                if (!empty($Search['end_create_date'])) {
                    $this->HostDb->where('od_create_datetime < ', $Search['end_create_date']);
                }

                if (isset($Search['keyword'])) {
                    $this->HostDb->group_start()
                            ->like('o_dealer', $Search['keyword'])
                            ->or_like('o_owner', $Search['keyword'])
                            ->or_like('o_num', $Search['keyword'])
                        ->group_end();
                }

                $Query = $this->HostDb->order_by('o_id', 'desc')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单';
            }
        }
        return $Return;
    }

    private function _page_num ($Search) {
        $this->HostDb->select('o_id', FALSE)
            ->join('order_datetime', 'od_order_id = o_id', 'left')
            ->join('workflow_order', 'wo_id = o_status', 'left')
            ->join('user', 'u_id = od_creator', 'left');
        if (!empty($Search['order_id'])) {
            $this->HostDb->where('o_id', $Search['order_id']);
        }
        if (empty($Search['all'])) {
            $this->HostDb->where('od_creator', $this->session->userdata('uid'));
        }
        if (!empty($Search['status'])) {
            if (is_array($Search['status'])) {
                $this->HostDb->where_in('o_status', $Search['status']);
            } else {
                $this->HostDb->where_in('o_status', explode(',', $Search['status']));
            }
        } else {
            $this->HostDb->where('o_status != ', O_REMOVE);
        }
        if (!empty($Search['dealer_id'])) {
            $this->HostDb->where('o_dealer_id', $Search['dealer_id']);
        }
        if (!empty($Search['start_create_date'])) {
            $this->HostDb->where('od_create_datetime >= ', $Search['start_create_date']);
        }
        if (!empty($Search['end_create_date'])) {
            $this->HostDb->where('od_create_datetime < ', $Search['end_create_date']);
        }
        if (isset($Search['keyword'])) {
            $this->HostDb->group_start()
                ->like('o_dealer', $Search['keyword'])
                ->or_like('o_owner', $Search['keyword'])
                ->or_like('o_num', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('order');

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $this->_Num = $Query->num_rows();
            $Query->free_result();
            if(intval($this->_Num%$Search['pagesize']) == 0){
                $Pn = intval($this->_Num/$Search['pagesize']);
            }else{
                $Pn = intval($this->_Num/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * 获取订单详情
     * @param $Search
     */
    public function select_detail ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql, false)->from('order')
                ->join('task_level', 'tl_id = o_task_level', 'left')
                ->join('pay_status', 'ps_name = o_pay_status', 'left')
                ->join('dealer', 'd_id = o_dealer_id', 'left')
                ->join('shop', 's_id = o_shop_id', 'left')
                ->join('workflow_order', 'wo_id = o_status', 'left')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->join('user AS C', 'C.u_id = od_creator', 'left')
                ->where('o_id', $Search['order_id'])
                ->get();

            $Return = array(
                'content' => $Query->row_array(),
                'num' => ONE,
                'p' => ONE,
                'pn' => ONE,
                'pagesize' => ALL_PAGESIZE
            );
            $this->cache->save($Cache, $Return, MONTHS);
        }
        return $Return;
    }

    /**
     * 获取多个订单的订单详情
     * @param $Search
     * @return array|bool
     */
    public function select_details ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . array_to_string($Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('order')
                ->where_in('o_id', is_array($Search['order_id']) ? $Search['order_id'] : array($Search['order_id']))
                ->get();

            $Return = array(
                'content' => $Query->result_array(),
                'num' => $Query->num_rows(),
                'p' => ONE,
                'pn' => ONE,
                'pagesize' => ALL_PAGESIZE
            );
            $this->cache->save($Cache, $Return, MONTHS);
        }
        return $Return;
    }

    /**
     * 获取等待确认的订单
     * @param $Search
     * @return array|bool
     */
    public function select_wait_sure ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_wait_sure_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('order')
                    ->join('order_datetime', 'od_order_id = o_id', 'left')
                    ->join('dealer', 'd_id = o_dealer_id', 'left')
                    ->join('task_level', 'tl_id = o_task_level', 'left')
                    ->join('pay_status', 'ps_name = o_pay_status', 'left')
                    ->join('application', 'a_source_id = o_id', 'left')
                    ->join('application_status', 'as_name = a_status', 'left');

                $this->HostDb->where('o_status', O_WAIT_SURE);
                if (empty($Search['all'])) {
                    $this->HostDb->where('od_creator', $this->session->userdata('uid'));
                }

                if (isset($Search['keyword'])) {
                    $this->HostDb->group_start()
                        ->like('o_dealer', $Search['keyword'])
                        ->or_like('o_num', $Search['keyword'])
                        ->group_end();
                }

                $Query = $this->HostDb->order_by('o_id', 'desc')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单';
            }
        }
        return $Return;
    }
    private function _page_wait_sure_num ($Search) {
        $this->HostDb->select('o_id', FALSE)
            ->join('order_datetime', 'od_order_id = o_id', 'left');
        $this->HostDb->where('o_status', O_WAIT_SURE);
        if (empty($Search['all'])) {
            $this->HostDb->where('od_creator', $this->session->userdata('uid'));
        }
        if (isset($Search['keyword'])) {
            $this->HostDb->group_start()
                ->like('o_dealer', $Search['keyword'])
                ->or_like('o_num', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('order');

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $this->_Num = $Query->num_rows();
            $Query->free_result();
            if(intval($this->_Num%$Search['pagesize']) == 0){
                $Pn = intval($this->_Num/$Search['pagesize']);
            }else{
                $Pn = intval($this->_Num/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * 获取等待发货订单
     * @param $Search
     * @return array|bool
     */
    public function select_wait_delivery ($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_wait_delivery_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('order')
                    ->join('dealer', 'd_id = o_dealer_id', 'left')
                    ->join('pay_status', 'ps_name = o_pay_status', 'left')
                    ->join('j_application', 'a_source_id = o_id && a_des = o_payterms', 'left', false)
                    ->where_in('o_status', array(O_WAIT_DELIVERY, O_DELIVERING));
                if (!empty($Search['out_method'])) {
                    $this->HostDb->where('o_out_method', $Search['out_method']);
                }
                if (empty($Search['all'])) {
                    $this->HostDb->where('od_creator', $this->session->userdata('uid'));
                }

                if (isset($Search['keyword'])) {
                    $this->HostDb->group_start()
                        ->like('o_dealer', $Search['keyword'])
                        ->or_like('o_num', $Search['keyword'])
                        ->group_end();
                }

                $Query = $this->HostDb->order_by('o_id', 'desc')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的订单';
            }
        }
        return $Return;
    }
    private function _page_wait_delivery_num ($Search) {
        $this->HostDb->select('o_id', FALSE)
            ->join('order_datetime', 'od_order_id = o_id', 'left')
            ->where_in('o_status', array(O_WAIT_DELIVERY, O_DELIVERING));
        if (!empty($Search['out_method'])) {
            $this->HostDb->where('o_out_method', $Search['out_method']);
        }
        if (empty($Search['all'])) {
            $this->HostDb->where('od_creator', $this->session->userdata('uid'));
        }
        if (isset($Search['keyword'])) {
            $this->HostDb->group_start()
                ->like('o_dealer', $Search['keyword'])
                ->or_like('o_num', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('order');

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $this->_Num = $Query->num_rows();
            $Query->free_result();
            if(intval($this->_Num%$Search['pagesize']) == 0){
                $Pn = intval($this->_Num/$Search['pagesize']);
            }else{
                $Pn = intval($this->_Num/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * 获取已经发货的件数
     * @param $Vs
     * @return bool
     */
    public function select_delivered ($Vs) {
        $Item = $this->_Item . __FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order')
            ->where_in('o_id', $Vs)
            ->get();
        if ($Query->num_rows() > 0) {
            return $Query->result_array();
        }
        return false;
    }
    /**
     * 获取当前订单的工作流
     */
    public function select_current_workflow($V){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order')
            ->join('workflow_order', 'wo_id = o_status', 'left')
            ->where('o_id', $V)->limit(1)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->row_array();
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 获取同一经销商的某个时间段的订单编号
     * @param $Did
     * @param $Startdate
     * @return bool\
     */
    public function select_order_num($Did, $Startdate){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.'_'.$Did.'_'.$Startdate;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql,  FALSE);
            $this->HostDb->from('order')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->where('o_sum > ', ZERO)
                ->where('o_dealer_id', $Did)
                ->where('o_status > ', O_CHECKED)
                ->where('od_check_datetime > ', $Startdate)
                ->order_by('o_num', 'desc');

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $Query->num_rows(),
                    'p' => ONE,
                    'pn' => ONE,
                    'pagesize' => ALL_PAGESIZE
                );
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合条件的订单编号';
            }
        }
        return $Return;
    }
    /**
     * 获取对账的订单
     * @param $Did
     * @param $StartDatetime
     * @param $EndDatetime
     * @return bool
     */
    public function select_for_debt($Did, $StartDatetime, $EndDatetime){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.'_'.$Did.$StartDatetime.$EndDatetime;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql,  FALSE);
            $this->HostDb->from('order')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->where('o_sum > ', ZERO);

            $this->HostDb->where('od_check_datetime >', $StartDatetime);
            $this->HostDb->where('od_check_datetime <', $EndDatetime);
            $this->HostDb->where('o_dealer_id', $Did);

            $this->HostDb->where('o_status > ', O_REMOVE);

            $this->HostDb->order_by('o_num', 'desc');

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有符合条件的对账订单';
            }
        }
        return $Return;
    }
    /**
     * 判断是否是存在的订单编号
     * @param $OrderNum
     * @return bool
     */
    public function is_exist_order_num ($OrderNum){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order')
            ->where('o_num', $OrderNum)->where('o_status > ', O_REMOVE)->limit(1)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->row_array();
            $Query->free_result();
            return $Return;
        }else{
            return false;
        }
    }

    /**
     * 判断订单是否在某个状态
     * @param $V
     * @param $Status
     */
    public function is_status ($V, $Status) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order')
            ->where('o_id', $V)
            ->where_in('o_status', is_array($Status) ? $Status : array($Status))
            ->get();
        if($Query->num_rows() > 0){
            return $Query->row_array();
        }else{
            return false;
        }
    }

    /**
     * 判断订单状态
     * @param $V
     * @param $Status
     * @return bool
     */
    public function are_status ($V, $Status) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order')
            ->where_in('o_id', $V)
            ->where_in('o_status', is_array($Status) ? $Status : array($Status))
            ->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }
    /**
     * 是否可以拆单
     * @param $V
     * @return bool
     */
    public function is_dismantlable ($V) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order')
            ->where('o_id', $V)
            ->where('o_status > ', O_REMOVE) /*订单没有删除*/
            ->where('o_status < ', O_DISMANTLED)  /*订单没有确认拆单*/
            ->get();
        if($Query->num_rows() > 0){
            return $Query->row_array();
        }else{
            return false;
        }
    }

    /**
     * 是否可以重新拆单
     * @param $V
     */
    public function is_re_dismantlable ($V) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order')
            ->where('o_id', $V)
            ->where('o_status > ', O_REMOVE) /*订单没有删除*/
            ->where('o_status < ', O_PRODUCE)  /*订单没有确认拆单*/
            ->get();
        if($Query->num_rows() > 0){
            return $Query->row_array();
        }else{
            return false;
        }
    }
    /**
     * 判断是否可以修改
     * @param $Ids
     * @return bool
     */
    public function is_editable($Ids){
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order')
            ->where_in('o_id', is_array($Ids) ? $Ids : array($Ids))
            ->where('o_status > ', O_REMOVE) /*订单没有删除*/
            ->where('o_status < ', O_DELIVERED)  /*订单没有发货*/
            ->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            $GLOBALS['error'] = '订单已经删除或者已经发货，不可编辑信息!';
            return false;
        }
    }

    /**
     * 是否可以申请。。。
     * @param $Vs
     * @param $Status
     * @return bool
     */
    public function are_applicable ($Vs, $Status, $Payterms) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order')
            ->join('dealer', 'd_id = o_dealer_id', 'left')
            ->where_in('o_id', $Vs)
            ->where_in('o_status', is_array($Status) ? $Status : array($Status))
            ->where('o_payterms != ', $Payterms)
            ->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }

    /**
     * 判断订单是否可以删除
     * @param $Vs
     * @return bool
     */
    public function are_removable ($Vs) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order')
            ->join('dealer', 'd_id = o_dealer_id', 'left')
            ->where_in('o_id', $Vs)
            ->where('o_status > ', O_REMOVE)
            ->where('o_status <= ', O_WAIT_DELIVERY)
            ->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }

    /**
     * 订单是否可以直接出厂, 只有已经确认生产的订单可以确认出厂
     * @param $Vs
     * @return bool
     */
    public function are_directable ($Vs) {
        $Item = $this->_Item.__FUNCTION__;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('order')
            ->join('dealer', 'd_id = o_dealer_id', 'left')
            ->where_in('o_id', $Vs)
            ->where('o_status > ', O_WAIT_SURE)
            ->where('o_status <= ', O_WAIT_DELIVERY)
            ->get();
        if($Query->num_rows() > 0){
            return $Query->result_array();
        }else{
            return false;
        }
    }
    /**
     * 获取时间段内的下单数
     * @param $Con
     * @return bool
     */
    public function select_order_sorter($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql, FALSE);
            $this->HostDb->from('order')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->where('o_sum > ', ZERO);

            if (!empty($Con['dealer_id'])) {
                $this->HostDb->where('o_dealer_id', $Con['dealer_id']);
            }
            if(!empty($Con['start_date'])){
                $this->HostDb->where('od_sure_datetime > ', $Con['start_date']);
            }

            if(!empty($Con['end_date'])){
                $this->HostDb->where('od_sure_datetime < ', $Con['end_date']);
            }

            if(!empty($Con['keyword'])){
                $this->HostDb->group_start()
                    ->like('o_dealer', $Con['keyword'])
                    ->group_end();
            }

            $Query = $this->HostDb->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '没有对应下单排行榜订单';
                $Return = false;
            }
        }
        return $Return;
    }

    /**
     * 等待确认
     * @param $Con
     * @return bool
     */
    public function select_after_wait_sure ($Con) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);

            $Query = $this->HostDb->select($Sql, FALSE)
                ->from('order')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->where('od_check_datetime > ', $Con['start_date'])
                ->where('od_check_datetime < ', $Con['end_date'])
                ->where('o_status > ', O_CHECKED)
                ->where('o_sum > ', ZERO)
                ->get();

            if($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '本月还没有开始销售!';
                $Return = false;
            }
        }
        return $Return;
    }

    /**
     * 确认生产后的销售额
     * @param $Con
     * @return bool
     */
    public function select_after_produce($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);

            $Query = $this->HostDb->select($Sql, FALSE)
                ->from('order')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->where('od_sure_datetime > ', $Con['start_date'])
                ->where('od_sure_datetime < ', $Con['end_date'])
                ->where('o_status > ', O_WAIT_SURE)
                ->where('o_sum > ', ZERO)
                ->get();

            if($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '本月还没有开始销售!';
                $Return = false;
            }
        }
        return $Return;
    }

    public function select_everyday_sured ($Con) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.implode('_', $Con);
        if(!($Return = $this->cache->get($Cache))){
            $Thick = $this->HostDb->select('o_id as order_id, sum(opb_area) as thick', FALSE)
                ->from('order_product_board')
                ->join('board', 'b_name = opb_board', 'left')
                ->join('order_product', 'op_id = opb_order_product_id', 'left')
                ->join('order', 'o_id = op_order_id', 'left')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->where('od_sure_datetime > ', $Con['start_date'])
                ->where('od_sure_datetime < ', $Con['end_date'])
                ->where('op_status > ', OP_DISMANTLING)
                ->where('o_status > ', O_WAIT_SURE)
                ->where('opb_area > ', ZERO)
                ->where('b_thick > ', THICK)
                ->group_by('o_id')
                ->get_compiled_select();

            $Sql = $this->_unformat_as($Item);

            $Query = $this->HostDb->select($Sql)
                ->from('order')
                ->join('order_datetime', 'od_order_id = o_id', 'left')
                ->join('user', 'u_id = od_sure', 'left')
                ->join('(' . $Thick . ') as A', 'A.order_id = o_id', 'left')
                ->where('od_sure_datetime > ', $Con['start_date'])
                ->where('od_sure_datetime < ', $Con['end_date'])
                ->where('o_status > ', O_WAIT_SURE)
                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, HOURS);
            }else{
                $GLOBALS['error'] = '本日还没有确认订单!';
                $Return = false;
            }
        }
        return $Return;
    }

    public function insert ($Set) {
        $Item = $this->_Item.__FUNCTION__;
        if(!!($Order = $this->_generate_order_num($Set))){
            $this->update($Set, $Order['v']);
            $this->_insert_datetime(array('order_id' => $Order['v']));
            $this->remove_cache($this->_Module);
            log_message('debug', "Model Order_model/insert_order Success");
            return $Order;
        }else{
            log_message('debug', "Model Order_model/insert_order Error");
            return false;
        }
    }

    /**
     * 调用存储过程，在保存订单后生成订单编号
     * @param unknown $Oid
     * @return boolean
     * generate_order_num3 返回生成的订单编号;
     * YYYYMM     YYYYMMDD
     * @type 订单类型
     * @num 订单前缀位数201512
     * @suffix 订单后缀位数0001
     * @newOrderNum 新订单编号
     * @oid 订单id号
     * create PROCEDURE `generate_order_num3`(in type varchar(2), in num int(10), in suffix int(10), out newOrderNum varchar(16), out oid int(10))
    begin
    declare currentDate varchar(8);
    declare maxNum int default 0;
    declare oldOrderNum varchar(16) default '';
    if(num = 6) then
    select date_format(now(), '%Y%m') into currentDate;
    else
    select date_format(now(), '%Y%m%d') into currentDate;
    end if;
    select concat(type, currentDate) into currentDate;
    select IFNULL(o_num, '') into oldOrderNum
    from n9_order
    where o_num REGEXP concat('^',currentDate)
    order by o_num desc limit 1;
    if oldOrderNum != '' then
    set maxNum = convert(substring(oldOrderNum, -1 * suffix), decimal);
    end if;
    select concat(currentDate, LPAD((maxNum+1),suffix,'0')) into newOrderNum;
    insert into n9_order(o_num) values (newOrderNum);
    select LAST_INSERT_ID() into oid;
    END
     * begin
    declare currentDate varchar(8);
    declare maxNum int default 0;
    declare oldOrderNum varchar(16) default '';
    if(num = 6) then
    select date_format(now(), '%Y%m') into currentDate;
    else
    select date_format(now(), '%Y%m%d') into currentDate;
    end if;
    select IFNULL(o_num, '') into oldOrderNum
    from n9_order
    where o_num REGEXP concat('[XB]',currentDate)
    order by o_id desc limit 1;
    if oldOrderNum != '' then
    set maxNum = convert(substring(oldOrderNum, -1 * suffix), decimal);
    end if;
    select concat(type, currentDate, LPAD((maxNum+1),suffix,'0')) into newOrderNum;
    insert into n9_order(o_num) values (newOrderNum);
    select LAST_INSERT_ID() into oid;
    END
     */
    private function _generate_order_num($Set){
        if($this->HostDb->query("call generate_order_num('" . $Set['order_type'] . "',".ORDER_PREFIX.", ".ORDER_SUFFIX.", ". ORDER_MODE .", @pp, @v)", false)){
            if(!!($Query = $this->HostDb->query('select @pp as order_num, @v as v', false))){
                $Row = $Query->row_array();
                log_message('debug', 'Model order/order_model _generate_order_num on Num '.$Row['order_num']);
                return $Row;
            }
        }
        return false;
    }

    private function _insert_datetime ($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('order_datetime', $Data)){
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入订单日期失败!';
            return false;
        }
    }
	public function update($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('o_id', $Where);
        } else {
            $this->HostDb->where('o_id', $Where);
        }
        $this->HostDb->update('order', $Data);
        $this->remove_cache($this->_Module);
        return true;
	}
    /**
     * 更新工作流
     * @param unknown $Set
     * @param unknown $Where
     */
    public function update_workflow($Set, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Set = $this->_format_re($Set, $Item);
        if(is_array($Where)){
            $this->HostDb->where_in('o_id',$Where);
        }else{
            $this->HostDb->where('o_id',$Where);
        }
        $this->HostDb->update('order', $Set);
        log_message('debug', "Model order_product_model/update_workflow");
        $this->remove_cache($this->_Module);
        return true;
    }

	public function update_batch($Data){
	    $Item = $this->_Item.__FUNCTION__;
	    foreach ($Data as $key => $value){
	        $Data[$key] = $this->_format_re($value, $Item);
	    }
	    $this->remove_cache($this->_Module);
	    return $this->HostDb->update_batch('order', $Data, 'o_id');
	}

	public function update_datetime ($Data, $V) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);

        $this->HostDb->where_in('od_order_id', is_array($V) ? $V : array($V));
        $this->remove_cache($this->_Module);
        return $this->HostDb->update('order_datetime', $Data);
    }
}
