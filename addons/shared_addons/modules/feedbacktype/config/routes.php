<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(feedbacktype)/(:num)/(:num)/(:any)']   = 'feedbacktype/view/$4';
$route['(feedbacktype)/page(/:num)?']           = 'feedbacktype/index$2';