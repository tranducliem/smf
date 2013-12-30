<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(answer)/(:num)/(:num)/(:any)']   = 'answer/view/$4';
$route['(answer)/page(/:num)?']           = 'answer/index$2';