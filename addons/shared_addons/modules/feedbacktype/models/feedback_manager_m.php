<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Feedback_manager_m Class
 * @Description: This is a model class process crud for test table
 * @Author: Hoang Thi Tuan Dung
 * @Date: 12/25/13
 * @Update: 12/25/2013
 */
class Feedback_manager_m extends MY_Model {

    protected $_table = "feedback_manager";

    public function __construct() {
        parent::__construct();
    }
}

