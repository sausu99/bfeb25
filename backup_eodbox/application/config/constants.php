<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Custon define variable
  | Define by sujit shah @ July 10 2012
  |--------------------------------------------------------------------------
 */

define('WEBSITE_NAME', '');


define('ADMIN_CSS_DIR_FULL_PATH', 'assets/admin_css');
define('ADMIN_IMG_DIR_FULL_PATH', 'assets/admin_images');
define('ADMIN_JS_DIR_FULL_PATH', 'assets/js/admin');
define('MAIN_CSS_DIR_FULL_PATH', 'assets/css/');
define('MAIN_IMG_DIR_FULL_PATH', 'assets/images/');
define('MAIN_JS_DIR_FULL_PATH', 'assets/js/');
define('ASSETS_PATH', 'assets/');
define('ASSETS_CALENDER', 'assets/calender/');
define('MAIN_CAPTCHA_DIR_FULL_PATH', 'captcha/');
define('DROPZONE_PATH', 'assets/dropzone/'); //path for dropzone multiple image uploader
//admin login session
define('ADMIN_LOGIN_ID', 'admin_user_id');

//admin & dashboard path
define('ADMIN_LOGIN_PATH', 'admin/index');
define('ADMIN_DASHBOARD_PATH', 'dashboard');

//upload file location
define('PAYMENT_API_LOGO_PATH', 'upload_files/payment/');
define('FLAG_PATH', 'upload_files/flag/');
define('BANNER_PATH', 'upload_files/banner/');
define('AUCTION_IMG_PATH', 'upload_files/auction/');
define('AUCTION_TEMP_PATH', 'upload_files/auction_temp/');
define('PRODUCT_CATEGORY_PATH', 'upload_files/category/');
define('TESTIMONIAL_PATH', 'upload_files/testimonial/');
define('SITE_LOGO_PATH', 'upload_files/logo/');
define('USER_PROFILE_PATH', 'upload_files/user_profile/');
define('MAXIMUM_NUMBERS_OF_PRODUCT_IMAGES', '6');



define('SESSION', 'ASDFGHJ');

define('MY_ACCOUNT', 'my-account');

/* End of file constants.php */
/* Location: ./application/config/constants.php */