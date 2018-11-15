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
defined('EXIT_PERMISSION')     OR define('EXIT_PERMISSION', 11);

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

define('MICRO', 100000000);

define('CLIENT_MULTI_RESULTS', 131072);


// 定义当前请求的系统常量
define('NOW_TIME',      $_SERVER['REQUEST_TIME']);
define('REQUEST_METHOD',isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET');
define('IS_GET',        REQUEST_METHOD =='GET' ? true : false);
define('IS_POST',       REQUEST_METHOD =='POST' ? true : false);
define('IS_PUT',        REQUEST_METHOD =='PUT' ? true : false);
define('IS_DELETE',     REQUEST_METHOD =='DELETE' ? true : false);


// 定义订单编号的长度的常量
define('ORDER_PREFIX', 6);
define('ORDER_SUFFIX', 4);
define('QRCODE_PREFIX', 4);
define('QRCODE_SUFFIX', 3);
defined('ORDER_MODE') OR define('ORDER_MODE', 0); // 模式1统一，模式0分开计数
defined('REGULAR_ORDER') OR define('REGULAR_ORDER', 'X');
defined('MEND_ORDER') OR define('MEND_ORDER', 'B');

/**
 * WORKFLOW
 */
defined('O_RECORD') OR define('O_RECORD', -2); // 简单信息记录
defined('O_HOOK') OR define('O_HOOK', -1);   // 挂起
defined('O_REMOVE') OR define('O_REMOVE', 0);
defined('O_CREATE') OR define('O_CREATE', 1);
defined('O_DISMANTLING') OR define('O_DISMANTLING', 2);
defined('O_DISMANTLED') OR define('O_DISMANTLED', 3);
defined('O_VALUATE') OR define('O_VALUATE', 4);
defined('O_VALUATING') OR define('O_VALUATING', 5);
defined('O_VALUATED') OR define('O_VALUATED', 6);
defined('O_CHECK') OR define('O_CHECK', 7);
defined('O_CHECKED') OR define('O_CHECKED', 8);
defined('O_WAIT_SURE') OR define('O_WAIT_SURE', 9);
defined('O_PRODUCE') OR define('O_PRODUCE', 10);
defined('O_PRE_PRODUCE') OR define('O_PRE_PRODUCE', 11);
defined('O_PRODUCING') OR define('O_PRODUCING', 12);
defined('O_INNING') OR define('O_INNING', 13);
defined('O_INNED') OR define('O_INNED', 14);
defined('O_WAIT_DELIVERY') OR define('O_WAIT_DELIVERY', 15);
defined('O_DELIVERING') OR define('O_DELIVERING', 16);
defined('O_DELIVERED') OR define('O_DELIVERED', 17);
defined('O_BLANK_NOTE') OR define('O_BLANK_NOTE', 18);
defined('O_MONEY_LOGISTICS') OR define('O_MONEY_LOGISTICS', 19);
defined('O_OUTED') OR define('O_OUTED', 20);
defined('O_MINUS') OR define('O_MINUS', -9999);

defined('OP_CREATE') OR define('OP_CREATE', 1);
defined('OP_DISMANTLING') OR define('OP_DISMANTLING', 2);
defined('OP_DISMANTLED') OR define('OP_DISMANTLED', 3);
defined('OP_PRE_PRODUCING') OR define('OP_PRE_PRODUCING', 4);
defined('OP_PRODUCING') OR define('OP_PRODUCING', 5);
defined('OP_PACKING') OR define('OP_PACKING', 6);   // 正在打包
defined('OP_PACKED') OR define('OP_PACKED', 7);     // 已包装
defined('OP_INED') OR define('OP_INED', 8);         // 已入库
defined('OP_REMOVE') OR define('OP_REMOVE', 0);     // 删除
defined('OP_HOOK') OR define('OP_HOOK', -1);        // 挂起

defined('WP_UN_WORK') OR define('WP_UN_WORK', 0);
defined('WP_CREATE') OR define('WP_CREATE', 1);
defined('WP_OPTIMIZE') OR define('WP_OPTIMIZE', 2);
defined('WP_PRINT_LIST') OR define('WP_PRINT_LIST', 3);
defined('WP_SHEAR') OR define('WP_SHEAR', 4);
defined('WP_SHEARING') OR define('WP_SHEARING', 5);
defined('WP_SHEARED') OR define('WP_SHEARED', 6);
defined('WP_ELECTRONIC_SAW') OR define('WP_ELECTRONIC_SAW', 7);
defined('WP_ELECTRONIC_SAWING') OR define('WP_ELECTRONIC_SAWING', 8);
defined('WP_ELECTRONIC_SAWED') OR define('WP_ELECTRONIC_SAWED', 9);
defined('WP_EDGE') OR define('WP_EDGE', 10);
defined('WP_EDGING') OR define('WP_EDGING', 11);
defined('WP_EDGED') OR define('WP_EDGED', 12);
defined('WP_PUNCH') OR define('WP_PUNCH', 13);
defined('WP_PUNCHING') OR define('WP_PUNCHING', 14);
defined('WP_PUNCHED') OR define('WP_PUNCHED', 15);
defined('WP_SCAN') OR define('WP_SCAN', 16);
defined('WP_SCANNING') OR define('WP_SCANNING', 17);
defined('WP_SCANNED') OR define('WP_SCANNED', 18);
defined('WP_PACK') OR define('WP_PACK', 19);
defined('WP_PACKING') OR define('WP_PACKING', 20);
defined('WP_PACKED') OR define('WP_PACKED', 21);

defined('M_REMOVE') OR define('M_REMOVE', 0); // 待排产
defined('M_SHEAR') OR define('M_SHEAR', 1); // 待排产
defined('M_SHEARED') OR define('M_SHEARED', 2); // 已排产
defined('M_ELECTRONIC_SAW') OR define('M_ELECTRONIC_SAW', 3);  // 待下料
defined('M_ELECTRONIC_SAWED') OR define('M_ELECTRONIC_SAWED', 4);   // 已下料

defined('OPBP_CREATE') OR define('OPBP_CREATE', 1);     // 创建
defined('OPBP_PRODUCE') OR define('OPBP_PRODUCE', 2);   // 等待生产
defined('OPBP_OPTIMIZE') OR define('OPBP_OPTIMIZE', 3); // 优化
defined('OPBP_PRODUCE_ELECTRONIC_SAW') OR define('OPBP_PRODUCE_ELECTRONIC_SAW', 4); // 电子锯
defined('OPBP_PRODUCE_ELECTRONIC_SAWED') OR define('OPBP_PRODUCE_ELECTRONIC_SAWED', 6); // 电子锯
defined('OPBP_PRODUCE_TABLE_SAW') OR define('OPBP_PRODUCE_TABLE_SAW', 4); // 电子锯
defined('OPBP_PRODUCE_TABLE_SAWED') OR define('OPBP_PRODUCE_TABLE_SAWED', 6); // 电子锯
defined('OPBP_PRODUCE_SCAN') OR define('OPBP_PRODUCE_SCAN', 7); //
defined('OPBP_PRODUCE_SCANED') OR define('OPBP_PRODUCE_SCANED', 9); //

defined('P_OPTIMIZE') OR define('P_OPTIMIZE', 1);
defined('P_PRINT_LIST') OR define('P_PRINT_LIST', 2);
defined('P_SHEAR') OR define('P_SHEAR', 3);
defined('P_ELECTRONIC_SAW') OR define('P_ELECTRONIC_SAW', 4);
defined('P_TABLE_SAW') OR define('P_TABLE_SAW', 5);
defined('P_ENGRAVE') OR define('P_ENGRAVE', 6);
defined('P_EDGE') OR define('P_EDGE', 7);
defined('P_PUNCH') OR define('P_PUNCH', 8);
defined('P_PUNCH_ONE') OR define('P_PUNCH_ONE', 9); // 160
defined('P_PUNCH_TWO') OR define('P_PUNCH_TWO', 10);    // 220
defined('P_PUNCH_THREE') OR define('P_PUNCH_THREE', 11);  // 230
defined('P_PUNCH_HAND') OR define('P_PUNCH_HAND', 12);  // 手工
defined('P_SCAN') OR define('P_SCAN', 13);
defined('P_SCAN_THICK') OR define('P_SCAN_THICK', 14);
defined('P_SCAN_THIN') OR define('P_SCAN_THIN', 15);
defined('P_PACK') OR define('P_PACK', 16);
defined('P_PACK_THIN') OR define('P_PACK_THIN', 17);
defined('P_PACK_THICK') OR define('P_PACK_THICK', 18);
/*
defined('P_CREATE') OR define('P_CREATE', 1);
defined('P_OPTIMIZE') OR define('P_OPTIMIZE', 2);
defined('P_PRINT_LIST') OR define('P_PRINT_LIST', 3);
defined('P_SHEAR') OR define('P_SHEAR', 4);
defined('P_SHEARING') OR define('P_SHEARING', 5);
defined('P_SHEARED') OR define('P_SHEARED', 6);
defined('P_ELECTRONIC_SAW') OR define('P_ELECTRONIC_SAW', 7);
defined('P_ELECTRONIC_SAWING') OR define('P_ELECTRONIC_SAWING', 8);
defined('P_ELECTRONIC_SAWED') OR define('P_ELECTRONIC_SAWED', 9);
defined('P_EDGE') OR define('P_EDGE', 10);
defined('P_EDGING') OR define('P_EDGING', 11);
defined('P_EDGED') OR define('P_EDGED', 12);
defined('P_PUNCH') OR define('P_PUNCH', 13);
defined('P_PUNCHING') OR define('P_PUNCHING', 14);
defined('P_PUNCHED') OR define('P_PUNCHED', 15);
defined('P_SCAN') OR define('P_SCAN', 16);
defined('P_SCANNING') OR define('P_SCANNING', 17);
defined('P_SCANNED') OR define('P_SCANNED', 18);
defined('P_PACK') OR define('P_PACK', 19);
defined('P_PACKING') OR define('P_PACKING', 20);
defined('P_PACKED') OR define('P_PACKED', 21);*/

defined('UNSCAN') OR define('UNSCAN', 0);
defined('SCANNING') OR define('SCANNING', 1);
defined('SCANNED') OR define('SCANNED', 2);

defined('START_WORK') OR define('START_WORK', 1); // 员工工作状态
defined('STOP_WORK') OR define('STOP_WORK', 0);
defined('OFFTIME') OR define('OFFTIME', 2);
/**
 * 封边信息
 */
define('O_EDGE', 0);

define('A_ALL', '__'); // 所有占位符

defined('MAX_SET') OR define('MAX_SET', 30); // 拆单时可以提交的最大套数
define('M_REGULAR', 1000);
define('M_ONE', 10000);
define('M_TWO', 100);
define('MIN_DOWN_PAYMENT', 0.3);
define('MIN_AREA', 0.066);
define('MIN_M_AREA', 0.1);
define('MIN_K_AREA', 0.3);
define('MIN_B_AREA', 0.01);

define('MIN_PAGESIZE', 100);
define('MOBILE_MIN_PAGESIZE', 15);
define('MAX_PAGESIZE', 1000);
define('ALL_PAGESIZE', 9999999999);

defined('YES')                              OR define('YES', 1);
defined('NO')                               OR define('NO', 0);
defined('PASSED')                           OR define('PASSED', 2); // 通过审核
defined('UNPASS')                           OR define('UNPASS',0);  // 未通过审核
defined('PASS')                             OR define('PASS', 1); // 正在审核

defined('VIEW_EXPIRED')         OR define('VIEW_EXPIRED', MINUTES); // View file store time
defined('FILE_FORCE_EXPIRED')   OR define('FILE_FORCE_EXPIRED', false);  // View file force expired

defined('ONE')  OR define('ONE', 1);
defined('TWO')  OR define('TWO', 2);
defined('THREE')  OR define('THREE', 3);
defined('FOUR')  OR define('FOUR', 4);
defined('FIVE')  OR define('FIVE', 5);
defined('TEN') OR define('TEN', 10);
defined('ZERO') OR define('ZERO', 0);
defined('EMPTY') OR define('EMPTY', 0);
defined('NEGATIVE') OR define('NEGATIVE', -1);

defined('SUPER_NO') OR define('SUPER_NO', 1); //超级管理员ID

defined('THICK') OR define('THICK', 13);    // 厚薄板分界线

defined('UNPRINT') OR define('UNPRINT', 0); // 未打印
defined('PRINTED') OR define('PRINTED',1);    // 已打印

define('MAX_LENGTH', 2432); //板块最大长度
define('MAX_WIDTH', 1220);  // 板块最大宽度

defined('UNPAY') OR define('UNPAY', 0); // 未支付
defined('PAYED') OR define('PAYED', 1); // 已支付
defined('PAY')  OR define('PAY', 2);    // 部分支付
defined('EASY_DELIVERY') OR define('EASY_DELIVERY', '宽松发货'); // 客户支付条款
defined('EASY_PRODUCE') OR define('EASY_PRODUCE', '宽松生产');
defined('NORMAL_PAY') OR define('NORMAL_PAY', '常规付款');
defined('PAY_FULL') OR define('PAY_FULL', '全款'); // 支付流水类型
defined('PAY_FIRST') OR define('PAY_FIRST', '首付');
defined('PAY_LAST') OR define('PAY_LAST', '尾款');
defined('PAY_EASY_PRODUCE') OR define('PAY_EASY_PRODUCE', '宽松生产');
defined('PAY_EASY_DELIVERY') OR define('PAY_EASY_DELIVERY', '宽松发货');
defined('PAY_PART') OR define('PAY_PART', '补款');

/**
 * 产品定义
 */
defined('CABINET') OR define('CABINET', 1);
defined('WARDROBE') OR define('WARDROBE', 2);
defined('DOOR') OR define('DOOR', 3);
defined('WOOD') OR define('WOOD', 4);
defined('FITTING') OR define('FITTING', 5);
defined('OTHER') OR define('OTHER',6);
defined('SERVER') OR define('SERVER',7);

defined('CABINET_NUM') OR define('CABINET_NUM', 'W');
defined('WARDROBE_NUM') OR define('WARDROBE_NUM', 'Y');
defined('DOOR_NUM') OR define('DOOR_NUM', 'M');
defined('WOOD_NUM') OR define('WOOD_NUM', 'K');
defined('FITTING_NUM') OR define('FITTING_NUM', 'P');
defined('OTHER_NUM') OR define('OTHER_NUM','G');
defined('SERVER_NUM') OR define('SERVER_NUM','F');

defined('REG_ORDER') OR define('REG_ORDER', '/[xXbB][\d]{10}/');
defined('REG_ORDER_PRODUCT') OR define('REG_ORDER_PRODUCT', '/[xXbB][\d]{10}-[wWyYmMpPgGkKaA][\d]{1,}/');
defined('REG_ORDER_PRODUCT_STRICT') OR define('REG_ORDER_PRODUCT_STRICT', '/^[xXbB][\d]{10}-[wWyYmMpPgGkKfFaA][\d]{1,}$/');
defined('REG_ORDER_QRCODE') OR define('REG_ORDER_QRCODE', '/([xXbB][\d]{10})-([wWyYmMpPgGkKaA][\d]{1,})-([\d]{1,})/');
defined('REG_PACK_LABEL') OR define('REG_PACK_LABEL', '/(([xXbB][\d]{10})-[wWyYmMpPgGkKaA][\d]{1,})-([\d]{1,4})-([\d]{1,4})-(.*)$/');   // 标签
defined('REG_RECOMMEND') OR define('REG_RECOMMEND', '/(([xXbB][\d]{10})-[wWyYmMpPgGkKaA][\d]{1,})(-([\d]{1,4})-[\d]{1,4}-(.*))?/'); // 推荐货位
defined('REG_PACK_LABEL_UNSTRICT') OR define('REG_PACK_LABEL_UNSTRICT', '/(([xXbB][\d]{10})-[wWyYmMpPgGkKaA][\d]{1,})(-([\d]{1,4})-[\d]{1,4}(-(.*))?)?/');
