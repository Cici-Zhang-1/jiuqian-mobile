<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/production_line_procedure_model/select'] = array(
                                'plp_id' => 'v',
    'plp_production_line' => 'production_line',
    'pl_label' => 'production_line_label',
    'plp_procedure' => 'procedure',
    'p_label' => 'procedure_label',
    'plp_displayorder' => 'displayorder'
);

$config['data/production_line_procedure_model/_select'] = array(
    'plp_production_line' => 'production_line',
    'plp_procedure' => 'procedure',
    'pw_workflow_procedure_id' => 'status'
);
