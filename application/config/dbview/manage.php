<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年9月23日
 * @author Zhangcc
 * @version
 * @des
 */

$config['manage/menu_model/select_menu'] = array(
        'm_id' => 'mid',
        'm_name' => 'name',
        'm_class' => 'class',
        'm_parent' => 'parent',
        'm_url' => 'url',
        'm_displayorder' => 'displayorder',
        'm_img' => 'img'
);

$config['manage/operation_model/select'] = array(
        'o_id' => 'oid',
        'o_name' => 'name',
        'o_class' => 'class',
        'o_parent' => 'parent',
        'o_url' => 'url'
);
$config['manage/role_menu/_read_menu'] = array(
        'rm_id' => 'rmid',
        'rm_role_id' => 'role',
        'm_id' => 'mid',
        'm_des' => 'des', 
        'm_class' => 'class', 
        'm_parent' => 'parent', 
        'm_displayorder' => 'displayorder'
);

$config['manage/signin_model/select_self'] = array(
        's_id' => 'sid',
        's_user_id' => 'uid',
        's_host'    => 'host',
        's_ip'  => 'ip',
        's_create_datetime' => 'create_datetime'
);

$config['manage/user/read'] = array(
        'u_id'=>'uid',
        'u_name'=>'name',
        'u_truename'=>'truename',
        'u_mobilephone'=>'mobilephone',
        'creator'=>'creator',
        'u_create_datetime'=>'create_datetime',
        'r_id'=>'rid',
        'r_des'=>'role',
		'r_class'=>'class',
		'r_parent'=>'parent'
);

$config['manage/user_model/select_self'] = array(
    'u_id'  => 'uid',
    'u_mobilephone' => 'mobilephone',
    'u_name' => 'username',
    'u_truename' => 'truename',
    'u_password' => 'password'
);

$config['manage/user_model/check_username'] = array(
    'U.u_id' => 'uid',
    'U.u_name' => 'username',
    'U.u_password' => 'password',
    'U.u_salt' => 'salt',
    'U.u_truename' => 'truename',
    'U.u_mobilephone' => 'mobilephone',
    'U.u_usergroup_id' => 'ugid',
    'UG.u_name' => 'usergroup'
);

$config['manage/user_model/signed_in'] = array(
	'u_id' => 'uid'
);

$config['manage/user_model/is_user'] = array(
        'U.u_id' => 'uid',
        'U.u_name' => 'username',
        'U.u_truename' => 'truename',
        'U.u_mobilephone' => 'mobilephone',
        'U.u_usergroup_id' => 'ugid',
        'UG.u_name' => 'usergroup'
);

$config['manage/user_model/select'] = array(
    'u_id' => 'uid',
    'u_name' => 'name',
    'u_truename' => 'truename'
);

$config['manage/user_model/select_by_usergroup'] = array(
    'U.u_id' => 'uid',
    'U.u_name' => 'username',
    'U.u_truename' => 'truename',
    'U.u_mobilephone' => 'mobilephone',
    'U.u_usergroup_id' => 'ugid',
    'U.u_create_datetime' => 'create_datetime',
    'UG.u_name' => 'usergroup',
    'UG.u_class' => 'class',
    'UG.u_parent' => 'parent',
    'C.u_truename' => 'creator'
);
$config['manage/usergroup_model/select'] = array(
        'u_id' => 'uid',
        'u_name' => 'name',
        'u_class' => 'class',
        'u_parent' => 'parent'
);

$config['manage/usergroup_priviledge_model/select_operation'] = array(
        'o_url' => 'url'
);

$config['manage/usergroup_priviledge_model/select_apps'] = array(
        'm_id' => 'id',
        'm_name' => 'name',
        'm_class' => 'class',
        'm_parent' => 'parent',
        'm_url' => 'url',
        'm_displayorder' => 'displayorder',
        'm_img' => 'img',
        'p_id' => 'pid'
);

$config['manage/workflow/read'] = array(
        'w_id' => 'wid',
        'w_name' => 'name',
        'w_code' => 'code',
        'w_parent' => 'parent',
        'w_class' => 'class',
        'w_remark' => 'remark'
);
