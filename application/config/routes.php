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

// Api for Login and Register
$route['api/register'] = 'auth/register';
$route['api/login'] = 'auth/login';
$route['api/profile'] = 'auth/profile'; // Protected route

// Projects Api
$route['api/projects'] = 'projects/index';
$route['api/projects/create'] = 'projects/create';
$route['api/projects/update/(:num)'] = 'projects/update/$1';
$route['api/projects/delete/(:num)'] = 'projects/delete/$1';


// Tasks Api
$route['api/tasks/(:num)'] = 'tasks/index/$1'; // Get tasks by project
// $route['api/tasks/create/(:num)'] = 'tasks/create/$1'; // Create task
$route['api/tasks/create'] = 'tasks/create';
$route['api/tasks/status/(:num)'] = 'tasks/update_status/$1'; // Update task status
$route['api/tasks/remark/(:num)'] = 'tasks/add_remark/$1'; // Add remark
$route['api/tasks/remarks/(:num)'] = 'tasks/get_remarks/$1'; // Get remarks


$route['api/tasks/report/(:num)'] = 'tasks/report/$1'; // Get task report for a project


$route['default_controller'] = 'welcome'; // Set default controller
