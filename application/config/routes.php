<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'FrontController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;

// Admin : -
// Auth Routes
$route['admin/auth/login'] = 'admin/AuthController';
$route['admin/auth/register'] = 'admin/AuthController/register';
$route['admin/auth/login/process'] = 'admin/AuthController/login';
$route['admin/auth/register/process'] = 'admin/AuthController/registerProcess';

// Home routes
$route['admin/dashboard'] = 'admin/HomeController';
$route['admin/home/profile'] = 'admin/HomeController/profile';
$route['admin/home/updateUser'] = 'admin/HomeController/updateUser';
$route['admin/home/logout'] = 'admin/AuthController/logout';
