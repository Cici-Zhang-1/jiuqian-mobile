<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/procedure_workflow_model'] = array(
                                'procedure' => 'pw_procedure',
                                'workflow_procedure_id' => 'pw_workflow_procedure_id',
                        'init' => 'pw_init'
                    );
$config['data/procedure_workflow_model/insert'] = array(
                                'procedure' => 'pw_procedure',
                                'workflow_procedure_id' => 'pw_workflow_procedure_id',
                        'init' => 'pw_init'
                    );
$config['data/procedure_workflow_model/insert_batch'] = array(
                                'procedure' => 'pw_procedure',
                                'workflow_procedure_id' => 'pw_workflow_procedure_id',
                        'init' => 'pw_init'
                    );
$config['data/procedure_workflow_model/update'] = array(
                                'procedure' => 'pw_procedure',
                                'workflow_procedure_id' => 'pw_workflow_procedure_id',
                        'init' => 'pw_init'
                    );
$config['data/procedure_workflow_model/update_batch'] = array(
                                'procedure' => 'pw_procedure',
                                'workflow_procedure_id' => 'pw_workflow_procedure_id',
                                'init' => 'pw_init',
                        'v' => 'pw_id'
                    );
