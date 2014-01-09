<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Feedback_manager_question_m Class
 * @Description: This is a model class process crud for feedback_manager_question table
 * @Author: Hoang Thi Tuan Dung
 * @Date: 11/20/13
 * @Update: 11/20/2013
 */
class Feedback_manager_question_m extends MY_Model {

    protected $_table = "feedback_manager_question";

    public function __construct() {
        parent::__construct();
    }
    
    public function get_all_question_in_feedback($feedback_id){
        return $this->db->select('question.id,question.title,question.description')
                        ->where('feedback_manager_id',$feedback_id)
                        ->join('question', 'question.id = feedback_manager_question.question_id')
                        ->get($this->_table)
                        ->result();
    }

}
