<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(feedbackuser)/(:num)/(:num)/(:any)']   = 'feedbackuser/view/$4';
$route['(feedbackuser)/page(/:num)?']           = 'feedbackuser/index$2';