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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'login';
$route['register'] = 'login/register';
$route['register-user'] = 'login/register_user';
$route['register-verify-otp'] = 'login/register_verify_otp';
$route['send-otp'] = 'login/send_otp';
$route['verify-otp'] = 'login/verify_otp';
$route['dashboard'] = 'dashboard';
$route['profile'] = 'profile';
$route['profile/update'] = 'profile/update';
$route['logout'] = 'login/logout';

$route['admin'] = 'admin/login';
$route['admin/send-otp'] = 'admin/login/send_otp';
$route['admin/verify-otp'] = 'admin/login/verify_otp';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/users'] = 'admin/users';
$route['admin/profile'] = 'admin/profile';
$route['admin/profile/update'] = 'admin/profile/update';
$route['admin/logout'] = 'admin/login/logout';

$route['investor'] = 'investor/login';
$route['investor/register'] = 'investor/login/register';
$route['investor/register-user'] = 'investor/login/register_user';
$route['investor/register-verify-otp'] = 'investor/login/register_verify_otp';
$route['investor/send-otp'] = 'investor/login/send_otp';
$route['investor/verify-otp'] = 'investor/login/verify_otp';
$route['investor/dashboard'] = 'investor/dashboard';
$route['investor/profile'] = 'investor/profile';
$route['investor/profile/update'] = 'investor/profile/update';
$route['investor/logout'] = 'investor/login/logout';

// User Loans
$route['loans'] = 'loans';
$route['loans/apply'] = 'loans/apply';

// Admin Investors & Loans
$route['admin/investors'] = 'admin/investors';
$route['admin/loans'] = 'admin/loans';
$route['admin/loans/assign/(:num)'] = 'admin/loans/assign/$1';
$route['admin/loans/reject/(:num)'] = 'admin/loans/reject/$1';
$route['admin/loans/responses/(:num)'] = 'admin/loans/responses/$1';
$route['admin/loans/fund/(:num)'] = 'admin/loans/fund/$1';

// Investor Pages
$route['investor/funds'] = 'investor/funds';
$route['investor/funds/add'] = 'investor/funds/add_money';
$route['investor/investments'] = 'investor/investments';
$route['investor/returns'] = 'investor/returns';
$route['investor/notifications'] = 'investor/notifications';
$route['investor/notifications/invest/(:num)'] = 'investor/notifications/invest/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
