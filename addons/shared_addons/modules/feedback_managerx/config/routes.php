<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(feedback_manager)/(:num)/(:num)/(:any)']   = 'feedback_manager/view/$4';
$route['(feedback_manager)/page(/:num)?']           = 'feedback_manager/index$2';