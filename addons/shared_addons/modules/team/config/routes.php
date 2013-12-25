<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(team)/(:num)/(:num)/(:any)']   = 'team/view/$4';
$route['(team)/page(/:num)?']           = 'team/index$2';