<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

defined('EXIT_SIGNIN')         OR define('EXIT_SIGNIN', 10); // signin error


/**
 * DAY
 */
define('DAY', 1);
define('WEEK', 7);
define('MONTH', 30);
define('YEAR', 365);
/*
 * Seconds
 */
define('MINUTES', 60);
define('QUAETER', 900);
define('HOURS', 3600);
define('DAYS', 86400);
define('WEEKS', 604800);
define('MONTHS', 2592000);
define('YEARS', 31536000);

define('CLIENT_MULTI_RESULTS', 131072);


// 定义当前请求的系统常量
define('NOW_TIME',      $_SERVER['REQUEST_TIME']);
define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);
define('IS_GET',        REQUEST_METHOD =='GET' ? true : false);
define('IS_POST',       REQUEST_METHOD =='POST' ? true : false);
define('IS_PUT',        REQUEST_METHOD =='PUT' ? true : false);
define('IS_DELETE',     REQUEST_METHOD =='DELETE' ? true : false);


// 定义订单编号的长度的常量
define('ORDER_PREFIX', 6);
define('ORDER_SUFFIX', 4);
define('QRCODE_PREFIX', 4);
define('QRCODE_SUFFIX', 3);

/**
 * WORKFLOW
 */
define('O_REMOVE', 0);
define('O_CREATE', 1);
define('O_DISMANTLING', 2);
define('O_DISMANTLED', 3);
define('O_CHECK', 4);
define('O_CHECKING', 5);
define('O_CHECKED', 6);
define('O_QUOTE', 7);
define('O_QUOTED', 8);
define('O_MONEY_PRODUCE', 9);
define('O_WAIT_ASURE', 10);
define('O_PRODUCE', 11);
define('O_PRODUCING', 12);
define('O_INING', 13);
define('O_INED', 14);
define('O_MONEY_DELIVERY', 15);
define('O_DELIVERY', 16);
define('O_DELIVERIED', 17);
define('O_MONEY_LOGISTICS', 18);
define('O_MONEY_MONTH', 19);
define('O_MONEY_FACTORY', 20);
define('O_OUTTED', 21);

define('T_EDGE', 2);
define('H_EDGE', 1.5);
define('I_EDGE', 1.0);
define('B_EDGE', 0.7);
define('O_EDGE', 0);


define('A_ALL', '__');

define('M_REGULAR', 1000);
define('M_ONE', 10000);
define('M_TWO', 100);
define('MIN_AREA', 0.01);
define('MIN_M_AREA', 0.1);
define('MIN_K_AREA', 0.4);
define('MIN_B_AREA', 0.01);

define('MIN_PAGESIZE', 100);
define('MAX_PAGESIZE', 1000);
