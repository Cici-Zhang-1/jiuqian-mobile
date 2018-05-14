<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * {{ name | title | replace({'_': ' '}) }} Model
 *
 * @package  CodeIgniter
 * @category Model
{% if type == 'doctrine' %}
 *
 * @Entity
 * @Table(name="{{ name }}")
{% endif %}
 */
class {{ name | capitalize }} extends MY_Model {

    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model {{ name }} Start!');
    }

    /**
     * Select from table {{ table['name'] }}
     */
    public function select() {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('{{ table['name'] }}')->get();
            if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的{{ table['comment'] }}';
            }
        }
        return $Return;
    }

    /**
     * Insert data to table {{ table['name'] }}
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('card', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入{{ table['comment'] }}数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table {{ table['name'] }}
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('table['name']', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入{{ table['comment'] }}数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table {{ table['name'] }}
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('{{ table['v'] }}', $Where);
        } else {
            $this->HostDb->where('{{ table['v'] }}', $Where);
        }
        $this->HostDb->update('{{ table['name'] }}', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table {{ table['name'] }}
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('{{ table['name'] }}', $Data, '{{ table['v'] }}');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table {{ table['name'] }}
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('{{ table['v'] }}', $Where);
        } else {
            $this->HostDb->where('{{ table['v'] }}', $Where);
        }

        $this->HostDb->delete('{{ table['name'] }}');
        $this->remove_cache($this->_Module);
        return true;
    }
}
