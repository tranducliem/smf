<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Feedback_manager_m Class
 * @Description: This is a model class process crud for test table
 * @Author: Hoang Thi Tuan Dung
 * @Date: 12/25/13
 * @Update: 12/25/2013
 */
class Feedback_employee_m extends MY_Model {

    protected $_table = "feedback_employee";

    public function __construct() {
        parent::__construct();
    }

    public function get_team_list($limit, $offset, $base_where = array()) {
        $this->db
            ->select('feedback_employee.*,department.title as department, user.username as apply')
            ->join('department', 'feedback_employee.department_id = department.id')
            ->join('user', 'feedback_employee.apply_id = user.id');
        if (!empty($base_where)) {
            if ($base_where['title'] != '') {
                $this->db->like('feedback_employee.title', $base_where['title']);
            }
        }
        parent::limit($limit, $offset);
        return parent::get_all();
    }
}