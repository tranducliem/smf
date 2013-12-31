<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(company)/(:num)/(:num)/(:any)']   = 'company/view/$4';
$route['(company)/page(/:num)?']           = 'company/index$2';