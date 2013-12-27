<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Feedback_manager_m Class
 * @Description: This is a model class process crud for test table
 * @Author: Hoang Thi Tuan Dung
 * @Date: 12/25/13
 * @Update: 12/25/2013
 */

class Answer_m extends MY_Model {

    protected $_table = "answer";

    public function __construct(){
        parent::__construct();
    }

    public function get_answer_list($limit, $offset, $base_where = array()){
        if(!empty($base_where)){
            if($base_where['title'] != ''){
                $this->db->like('answer.title', $base_where['title']);
            }
        }
        $this->db->select('answer.*,question.title as q_title');
        $this->db->join('question', 'question.id = answer.question_id');
        $this->db->order_by('created','DESC');
        parent::limit($limit, $offset);
        return parent::get_all();
    }
} 