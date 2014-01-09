<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Test Class
 * @Description: This is a model class process crud for feedback type manager
 * @Author: laiminhdao
 * @Date: 12/25/2013
 * @Update: 12/25/2013
 */

class Answeruser_m extends MY_Model {

    protected $_table = "answer_user";

    public function __construct(){
        parent::__construct();
    }

    public function get_answerusers_list($limit, $offset, $base_where = array()){
        if (!empty($base_where)) {
            if ($base_where['name'] != '') {
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
        $this->db->join('users', 'users.id = answer_user.user_id');
        $this->db->join('answer', 'answer.id = answer_user.answer_id');
        $this->db->select('answer_user.*, users.username, answer.title');
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