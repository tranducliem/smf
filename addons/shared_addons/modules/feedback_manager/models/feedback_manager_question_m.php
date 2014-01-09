<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Feedback_manager_m Class
 * @Description: This is a model class process crud for test table
 * @Author: Hoang Thi Tuan Dung
 * @Date: 12/25/13
 * @Update: 12/25/2013
 */
class Feedback_manager_question_m extends MY_Model {

    protected $_table = "feedback_manager_question";

    public function __construct() {
        parent::__construct();
    }

    public function get_team_list($limit, $offset, $base_where = array()) {
        $this->db
            ->select('feedback_manager.*,type.title as type')
            ->join('feedbacktype', 'feedback_manager.type_id = feedbacktype.id');
        if (!empty($base_where)) {
            if ($base_where['title'] != '') {
                $this->db->like('feedback_manager.title', $base_where['title']);
            }
        }
        parent::limit($limit, $offset);
        return parent::get_all();
    }

    public function get_question_list_by_fid($id)
    {
        return $this->db
                    ->select('feedback_manager_question.feedback_manager_id, question.id as question_id, question.title as question_title, question.description as question_description')
                    ->join('question','feedback_manager_question.question_id = question.id')
                    ->where('feedback_manager_question.feedback_manager_id', $id)
                    ->get($this->_table)
                    ->result();
    }

}

