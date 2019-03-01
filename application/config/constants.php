<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    define('FILE_READ_MODE', 0644);
    define('FILE_WRITE_MODE', 0666);
    define('DIR_READ_MODE', 0755);
    define('DIR_WRITE_MODE', 0777);

    /*
     |--------------------------------------------------------------------------
     | File Stream Modes
     |--------------------------------------------------------------------------
     |
     | These modes are used when working with fopen()/popen()
     |
     */

    define('FOPEN_READ',							'rb');
    define('FOPEN_READ_WRITE',						'r+b');
    define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
    define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
    define('FOPEN_WRITE_CREATE',					'ab');
    define('FOPEN_READ_WRITE_CREATE',				'a+b');
    define('FOPEN_WRITE_CREATE_STRICT',				'xb');
    define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');
/*
 |--------------------------------------------------------------------------
 | ADD SITE DESCRIPTION AND SITE MODELS
 |--------------------------------------------------------------------------
 |
 | These modes are used when working when call site name @author prasanjit

 |
 */
/*
 |--------------------------------------------------------------------------
 | Table Stream Modes
 |--------------------------------------------------------------------------
 |
 | These modes are used when working with database tables. Added by Prasannjit Prabhat
 |
 */

define('GMT_DATE_TIME', gmdate("Y-m-d H:i:s"));

//Other customizable settings
include_once 'other_system_config.php';
define('SITE_SESSION_NAME','Logistic_management_'); // It's being used to create session variable
define('PREFIX', 'ld_');

define('TBL_ADMINLOGIN', PREFIX.'adminlogin');
define('TBL_MENU', PREFIX.'menu');
define('TBL_PERMISSION', PREFIX.'permissions');
define('TBL_ROLE', PREFIX.'roles');
define('TBL_ROLE_PERMISSION', PREFIX.'permission_role');
define('TBL_SESSIONDETAIL', PREFIX.'sessiondetail');
define('TBL_ADMINPERMISSION', PREFIX.'adminpermission');
define('TBL_ADMINLEVEL', PREFIX.'adminlevel');
define('TBL_SYSTEMCONFIG', PREFIX.'system_config');
define('TBL_MENUPOSITION', PREFIX.'menuposition');
define('TBL_USERS', PREFIX.'users');
define('TBL_DEPARTMENT', PREFIX.'department');
define('TBL_VEHICLE', PREFIX.'vehicles');
define('TBL_PROJECT', PREFIX.'projects');
define('TBL_EMPLOYEE', PREFIX.'employees');
define('TBL_SERVICE', PREFIX.'services');
define('TBL_SERVICE_DETAILS', PREFIX.'services_detail');
define('TBL_TIMEZONE', PREFIX.'timezones');
define('TBL_TIMESHEET', PREFIX.'timesheet');
define('TBL_HOLIDAY', PREFIX.'holidays');
define('TBL_LEAVE', PREFIX.'leaves');
define('TBL_CHECKUP', PREFIX.'checkups');

define('TBL_BOOKSERVICE', PREFIX.'bookservice');
define('TBL_BOOKSERVICE_INFO', PREFIX.'bookservice_info');

/*
 |--------------------------------------------------------------------------
 | END Table Stream Modes
 |--------------------------------------------------------------------------
 |
 | These modes are used when working with File system. Added by Prasannjit Prabhat
 |
 */

//define('BASE_URL',base_url());
//$basedir='';
//$domain = '10.0.4.4';
//define('OAUTHDOMAIN', $domain);
//define('SITE_DIR',realpath(''));


//user image related constant
define('DIR_IMAGE_USER_ORIGINAL',$site_dir.'files/user/original/');
define('DIR_IMAGE_USER_THUMB',$site_dir.'files/user/thumb/');
define('USER_IMAGE_MAX_SIZE','10000'); //10 MB
define('ABSOLUTEPATH', $site_dir.'files/');
define('SHOWPATH', $site_url.'files/admin/');
define('DEFAULT_IMAGE_MAX_HEIGHT','5000'); 
define('DEFAULT_IMAGE_MAX_WIDTH','5000'); 
define('USER_CV_MAX_SIZE','2048');
define('BRAND_IMAGE_MAX_SIZE','5000'); //2 MB
define('BRAND_IMAGE_THUMB_SIZE','50X27');
define('USER_IMAGE_THUMB_SIZE','160X160');
define('DATE_FORMAT_IN_ADMIN','d-M-Y h:i:s');
define('DATE_FORMAT_IN_FRONT','d/m/Y');
define('DEFAULT_PER_PAGE',10);//Default value for limit in listing
define('DEFAULT_TIME_ZONE', 'UM11');
define('MAX_ALLOWED_CHARACTERS_IN_USERNAME',20);
define('SITE_DEFAULT_TIME_ZONE','America/New_York');
define('MAX_ALLOWED_CHARACTERS_IN_TITLE',20);

define('COOKIES_INTERVAL',3000);
define('NETWORK_COUNT',4);
//define('URL_PARAMETER','?keyword={keyword}&matchtype={matchtype}&adposition={adposition}&device={device}&devicemodel={devicemodel}');



    
