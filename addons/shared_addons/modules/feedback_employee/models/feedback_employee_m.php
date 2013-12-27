<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Feedback_manager_m Class
 * @Description: This is a model class process crud for test table
 * @Author: Hoang Thi Tuan Dung
 * @Date: 12/25/13
 * @Update: 12/25/2013
 */

class Feedback_employee_m extends MY_Model {

    protected $_table = "feedback_employee";

    public function __construct(){
        parent::__construct();
    }

    public function get_feedback_employee_list($limit, $offset, $base_where = array()){
        if(!empty($base_where)){
            if($base_where['title'] != ''){
                $this->db->like('feedback_employee.title', $base_where['title']);
            }
        }
        $this->db->select('feedback_employee.*,users.username, department.title as d_title');
        $this->db->join('users', 'users.id = feedback_employee.apply_id');
        $this->db->join('department', 'department.id = feedback_employee.department_id');
        $this->db->order_by('created','DESC');
        parent::limit($limit, $offset);
        return parent::get_all();
    }
} 