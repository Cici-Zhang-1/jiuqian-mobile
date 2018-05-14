<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'][] = array(
    'class'    => 'Mobile',
    'function' => 'is_mobile',
    'filename' => 'Mobile.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'Auth',
    'function' => 'is_signed_in',
    'filename' => 'Auth.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'Permit',
    'function' => 'is_permit',
    'filename' => 'Permit.php',
    'filepath' => 'hooks'
);

