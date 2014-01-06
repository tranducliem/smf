<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Feedback_employee_m Class
 * @Description: This is a model class process crud for feedback_employee table
 * @Author: Hoang Thi Tuan Dung
 * @Date: 12/25/13
 * @Update: 12/25/2013
 */
class Feedback_employee_m extends MY_Model {

    protected $_table = "feedback_employee";

    public function __construct() {
        parent::__construct();
    }
}