<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of company_m
 *
 * @author pxthanh
 */
class Company_m extends MY_Model{
    //put your code here
        protected $_table = "company";

    public function __construct() {
        parent::__construct();
    }
}
