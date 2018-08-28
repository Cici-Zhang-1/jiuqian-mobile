<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/form_page/add'] = array(
                        array (
            'field' => 'menu_v',
            'label' => '菜单V',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'url',
            'label' => 'URL',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'source_url',
            'label' => '源URL',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'source_query',
            'label' => '源Query',
            'rules' => 'trim|max_length[128]'
        )
            );

$config['permission/form_page/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'menu_v',
            'label' => '菜单V',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'url',
            'label' => 'URL',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'source_url',
            'label' => '源URL',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'source_query',
            'label' => '源Query',
            'rules' => 'trim|max_length[128]'
        )
            );

$config['permission/form_page/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        )
                            );
