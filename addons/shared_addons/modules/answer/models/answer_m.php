<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Answer_m Class
 * @Description: This is a model class process crud for answer table
 * @Author: HoangThiTuanDung
 * @Date: 11/20/13
 * @Update: 11/20/2013
 */
class Answer_m extends MY_Model {

    protected $_table = "answer";

    public function __construct() {
        parent::__construct();
    }

    /**
    * join table answer and question 
    */
    public function get_answer_list($limit, $offset, $base_where = array()) {
        $this->db
            ->select('answer.*,question.title as question')
            ->join('question', 'answer.question_id = question.id');
        if (!empty($base_where)) {
            if ($base_where['title'] != '') {
                $this->db->like('answer.title', $base_where['title']);
            }
        }
        parent::limit($limit, $offset);
        return parent::get_all();
    }

}
