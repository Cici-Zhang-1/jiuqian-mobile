<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_form_page_model/select'] = array(
                                'rfp_id' => 'v',                                        'rfp_role_id' => 'role_id',                                        'rfp_form_page_id' => 'form_page_id'                    );


$config['permission/role_form_page_model/select_by_usergroup_v'] = array(
    'fp_id' => 'v',
    'fp_label' => 'label',
    'm_label' => 'menu_label'
);

$config['permission/role_form_page_model/select_by_role_v'] = array(
    'fp_id' => 'v',
    'fp_label' => 'label',
    'm_label' => 'menu_label',
    'if(A.rfp_id is not null && A.rfp_id > 0, 1, 0)' => 'checked'
);
