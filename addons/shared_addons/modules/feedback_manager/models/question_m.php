<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Test Class
 * @Description: This is a model class process crud for test table
 * @Author: tranducliem
 * @Date: 11/20/13
 * @Update: 11/20/2013
 */

class Question_m extends MY_Model {

    protected $_table = "question";

    public function __construct(){
        parent::__construct();
    }
} 