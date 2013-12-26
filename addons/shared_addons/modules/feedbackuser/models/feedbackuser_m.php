<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Test Class
 * @Description: This is a model class process crud for feedback type manager
 * @Author: laiminhdao
 * @Date: 12/25/2013
 * @Update: 12/25/2013
 */

class Feedbackuser_m extends MY_Model {

    protected $_table = "feedback_manager_user";

    public function __construct(){
        parent::__construct();
    }

    public function get_feedbackusers_list($limit, $offset, $base_where = array()){
        if (!empty($base_where)) {
            if ($base_where['name'] != '') {
                echo $base_where['name'];
                $CI = & get_instance();
                $CI->load->model('users/user_m');
                $users = $CI->user_m->get_many_by($base_where);
                if(count($users) > 0) {
                    $ids = array();
                    foreach ($users as $user) {
                        array_push($ids, $user->id);
                    }
                    $this->db->where_in('user_id', $ids);
                } else {
                    $this->db->where_in('user_id', -1);
                }
            }
        }
        $this->db->join('users', 'users.id = feedback_manager_user.user_id');
        $this->db->join('feedback_manager', 'feedback_manager.id = feedback_manager_user.feedback_manager_id');
        $this->db->select('feedback_manager_user.*, users.username, feedback_manager.title');
        parent::limit($limit, $offset);
        return parent::get_all();
    }
    
    public function count_by($base_where = array())
    {
        $CI = & get_instance();
        $CI->load->model('users/user_m');
        $users = $CI->user_m->get_many_by($base_where);
        if(count($users) > 0) {
            $ids = array();
            foreach ($users as $user) {
                array_push($ids, $user->id);
            }
            $this->db->where_in('user_id', $ids);
        } else {
            $this->db->where_in('user_id', -1);
        }

        return $this->db->count_all_results($this->_table);
    }
} 