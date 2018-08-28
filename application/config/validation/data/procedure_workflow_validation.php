<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/procedure_workflow/add'] = array(
                        array (
            'field' => 'procedure',
            'label' => '工序',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'workflow_procedure_id',
            'label' => '工序工作流',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'init',
            'label' => '初始状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['data/procedure_workflow/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'procedure',
            'label' => '工序',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'workflow_procedure_id',
            'label' => '工序工作流',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'init',
            'label' => '初始状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['data/procedure_workflow/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                );
