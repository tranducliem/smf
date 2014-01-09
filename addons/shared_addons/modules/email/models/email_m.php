<?php defined('BASEPATH') or exit('No direct script access allowed');

class Email_m extends MY_Model 
{
    protected $_table = "email";

    public function __construct(){
        parent::__construct();
    }
}