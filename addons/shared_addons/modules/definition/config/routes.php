<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(definition)/(:num)/(:num)/(:any)']   = 'definition/view/$4';
$route['(definition)/page(/:num)?']           = 'definition/index$2';