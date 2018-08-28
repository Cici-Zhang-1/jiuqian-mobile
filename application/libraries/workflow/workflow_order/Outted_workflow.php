<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author zhangcc
 * @version
 * @des
 */
class Outted_workflow extends Workflow_abstract {
    private $_Source_id;
    
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }
    
    public function __call($name, $arguments){
        ;
    }
}