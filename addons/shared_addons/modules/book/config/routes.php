<?php  defined('BASEPATH') or exit('No direct script access allowed');

// public
$route['(book)/(:num)/(:num)/(:any)']   = 'book/view/$4';
$route['(book)/page(/:num)?']           = 'book/index$2';