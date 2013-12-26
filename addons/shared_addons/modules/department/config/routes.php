<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(department)/(:num)/(:num)/(:any)']   = 'department/view/$4';
$route['(department)/page(/:num)?']           = 'department/index$2';