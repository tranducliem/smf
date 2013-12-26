<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(feedback_manager_question)/(:num)/(:num)/(:any)']   = 'feedback_manager_question/view/$4';
$route['(feedback_manager_question)/page(/:num)?']           = 'feedback_manager_question/index$2';