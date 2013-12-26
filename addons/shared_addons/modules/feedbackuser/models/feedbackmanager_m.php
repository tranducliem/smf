<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Test Class
 * @Description: This is a model class process crud for feedback type manager
 * @Author: laiminhdao
 * @Date: 12/25/2013
 * @Update: 12/25/2013
 */

class Feedbackmanager_m extends MY_Model {

    protected $_table = "feedback_manager";

    public function __construct(){
        parent::__construct();
    }
} 