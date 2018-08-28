<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_procedure_model/select'] = array(
                                'wp_id' => array(
                                                                    'id',                                                        'v'                                                ),                                        'wp_name' => 'name',                                        'wp_label' => 'label',                                        'wp_previous' => 'previous',                                        'wp_next' => 'next',                                        'wp_file' => 'file'                    );

$config['workflow/workflow_procedure_model/select_by_name'] = array(
    'wp_id' => array(
        'id',
        'v'
    ),
    'wp_name' => 'name',
    'wp_label' => 'label',
    'wp_previous' => 'previous',
    'wp_next' => 'next',
    'wp_file' => 'file'
);