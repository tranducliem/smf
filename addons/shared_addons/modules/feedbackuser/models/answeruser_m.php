<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of anwseruser_m
 *
 * @author pxthanh
 */
class Answeruser_m extends MY_Model {
    //put your code here
    protected $_table = "answer_user";

    public function __construct(){
        parent::__construct();
    }
}
