<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(feedback_employee)/(:num)/(:num)/(:any)']   = 'feedback_employee/view/$4';
$route['(feedback_employee)/page(/:num)?']           = 'feedback_employee/index$2';