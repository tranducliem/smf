<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(question)/(:num)/(:num)/(:any)']   = 'question/view/$4';
$route['(question)/page(/:num)?']           = 'question/index$2';