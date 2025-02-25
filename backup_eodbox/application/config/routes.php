<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['([a-z]+)/sitemap\.xml'] = "sitemap/index";
$route['([a-z]+)/testimonials/index'] = 'testimonial/home/index';

$route['([a-z]+)/activation/email/(:num)/(:any)'] = 'users/activation/email_activation/$2/$3';

$route['([a-z]+)/category/(:any)-(:num)'] = 'auctions/details/category_lists/$2/$3';
$route['([a-z]+)/category/(:any)-(:num)/(:num)'] = 'auctions/details/category_lists/$2/$3/$4';

$route['([a-z]+)/auctions/upcomming'] = 'auctions/details/upcomming';
$route['([a-z]+)/auctions/upcomming/(:num)'] = 'auctions/details/upcomming/$2';
$route['([a-z]+)/upcomming/(:any)-(:num)'] = 'auctions/details/upcomming_details/$3';

$route['([a-z]+)/auctions/live'] = 'auctions/details/live_all';
$route['([a-z]+)/auctions/live/(:num)'] = 'auctions/details/live_all/$2';
$route['([a-z]+)/auctions/vote'] = 'auctions/votes/index';
$route['([a-z]+)/vote/details/(:any)-(:num)'] = 'auctions/votes/details/$3';

$route['([a-z]+)/auctions/winners/(:num)'] = 'auctions/winners/index/$2';
$route['([a-z]+)/feed'] = 'feed/index/index';
$route['([a-z]+)/feed/winners'] = 'feed/index/winners';
$route['([a-z]+)/feed/winners/(:num)'] = 'feed/index/winners/$2';
$route['([a-z]+)/auctions/closed/(:any)-(:num)'] = 'auctions/winners/details/$3';
$route['([a-z]+)/auctions/(:any)-(:num)'] = 'auctions/details/index/$3';
$route['([a-z]+)/referer/(:any)'] = 'referer/index/$1';


$route['([a-z]+)/page/([a-zA-Z_-]+)'] = 'page/page/index/$2';
$route['([a-z]+)/contact-us'] = 'contact_us';

$route['cron/auto_close/index'] = 'cron/auto_close/index';


$route['servertimer'] = 'servertimer/index';



$route[ADMIN_DASHBOARD_PATH.'/auction/vote'] = 'auction/vote/index';
$route[ADMIN_DASHBOARD_PATH.'/auction/vote/add_auction'] = 'auction/vote/add_auction';
$route[ADMIN_DASHBOARD_PATH.'/auction/vote/edit_auction/(:num)'] = 'auction/vote/edit_auction/$1';
$route[ADMIN_DASHBOARD_PATH.'/auction/vote/copy_auction/(:num)'] = 'auction/vote/copy_auction/$1';
$route[ADMIN_DASHBOARD_PATH.'/auction/vote/move_auction/(:num)'] = 'auction/vote/move_auction/$1';
$route[ADMIN_DASHBOARD_PATH.'/auction/vote/delete_auction/(:num)'] = 'auction/vote/delete_auction/$1';



//added by sujit
$route[ADMIN_LOGIN_PATH] = 'login/admin';
$route[ADMIN_DASHBOARD_PATH] = 'dashboard/admin';
$route[ADMIN_DASHBOARD_PATH.'/logout'] = 'login/admin/logout';
$route[ADMIN_DASHBOARD_PATH.'/([a-zA-Z_-]+)/(:any)'] = '$1/admin/$2';
$route[ADMIN_DASHBOARD_PATH.'/([a-zA-Z_-]+)/(:any)/(:any)'] = '$1/admin/$2/$3';
$route[ADMIN_DASHBOARD_PATH.'/([a-zA-Z_-]+)/(:any)/(:any)/(:num)'] = '$1/admin/$2/$3/$4';
$route[ADMIN_DASHBOARD_PATH.'/([a-zA-Z_-]+)/(:any)/(:any)/(:num)/(:num)'] = '$1/admin/$2/$3/$4/$5';
$route[ADMIN_DASHBOARD_PATH.'/([a-zA-Z_-]+)/(:any)/(:any)/(:num)/(:num)/(:num)'] = '$1/admin/$2/$3/$4/$5/$6';


//Routes for multiple language with dyanamic
$route['^([a-z]+)/(.+)$'] = "$2";
$route['^([a-z]+)$'] = $route['default_controller'];
