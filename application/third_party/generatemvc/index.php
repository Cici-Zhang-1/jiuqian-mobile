<?php
/**
 * Created for jiuqian-mobile.
 * User: chuangchuangzhang
 * Date: 2018/3/6
 * Time: 10:39
 *
 * Desc:
 */

define('TEMPLATE_PATH', __DIR__ . '/src/Templates');
define('COMMAND_PATH', __DIR__ . '/src/Commands');
define('COMMAND_NAMESPACE', 'Generatemvc\Commands');

$CI = &get_instance();

$config['default'] = [
    'dbdriver' => $CI->db->dbdriver,
    'hostname' => $CI->db->hostname,
    'username' => $CI->db->username,
    'password' => $CI->db->password,
    'database' => $CI->db->database
];

if (empty($config['default']['hostname'])) {
    $config['default']['hostname'] = $CI->db->dsn;
}

$driver = new Generatemvc\Describe\Driver\CodeIgniterDriver($config);

$Describe = new Generatemvc\Describe\Describe($driver);
