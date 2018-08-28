<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Form_page_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Form_page_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Form_page_model Start!');
    }

    /**
     * Select from table form_page
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('form_page')
                    ->join('menu', 'm_id = fp_menu_id', 'left');
                if (isset($Search['v']) && $Search['v'] != '') {
                    $this->HostDb->where('fp_menu_id', $Search['v']);
                }
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                        ->like('fp_name', $Search['keyword'])
                        ->group_end();
                }
                $this->HostDb->order_by('m_displayorder');
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的表单页面';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(fp_id) as num', FALSE);
        if (isset($Search['v']) && $Search['v'] != '') {
            $this->HostDb->where('fp_menu_id', $Search['v']);
        }

        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                ->like('fp_name', $Search['keyword'])
                ->group_end();
        }
        $this->HostDb->from('form_page');

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Search['pagesize']) == 0){
                $Pn = intval($Row['num']/$Search['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }
    /**
     * 通过用户组id获取数据
     * @param $Uid
     * @return bool
     */
    public function select_by_mid($Mid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('form_page')
                ->where('fp_menu_id', $Mid)
                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    public function select_allowed($Ugid, $Mid = 0) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Ugid . $Mid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('role_form_page')
                ->join('form_page', 'fp_id = rfp_form_page_id', 'left');
            if ($Mid) {
                $this->HostDb->where('fp_menu_id', $Mid);
            }
            $Query = $this->HostDb->where("rfp_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Ugid)")->group_by('fp_id')->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    /**
     * Insert data to table form_page
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('form_page', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入表单页面数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table form_page
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('form_page', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入表单页面数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table form_page
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('fp_id', $Where);
        } else {
            $this->HostDb->where('fp_id', $Where);
        }
        $this->HostDb->update('form_page', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table form_page
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('form_page', $Data, 'fp_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table form_page
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('fp_id', $Where);
        } else {
            $this->HostDb->where('fp_id', $Where);
        }

        $this->HostDb->delete('form_page');
        $this->remove_cache($this->_Module);
        return true;
    }
}
