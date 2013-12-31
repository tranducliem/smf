<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Test Class
 * @Description: This is a model class process crud for test table
 * @Author: tranducliem
 * @Date: 11/20/13
 * @Update: 11/20/2013
 */
class Feedback_manager_question_m extends MY_Model {

    protected $_table = "feedback_manager_question";

    public function __construct() {
        parent::__construct();
    }

    public function get_feedback_manager_question_list($limit, $offset, $base_where = array()) {
        $this->db
            ->select('feedback_manager_question.*,feedback_manager.title as feedback_manager, question.title as question')
            ->join('feedback_manager', 'feedback_manager_question.feedback_manager_id = feedback_manager.id')
            ->join('question', 'feedback_manager_question.question_id = question.id');
        if (!empty($base_where)) {
            if ($base_where['title'] != '') {
                $this->db->like('feedback_manager.title', $base_where['title']);
            }
        }
        parent::limit($limit, $offset);
        return parent::get_all();
    }

}
