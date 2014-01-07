<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of feedback_not_answer
 *
 * @author pxthanh
 */
class Feedback_not_answer extends Public_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        if (!$this->current_user)
            redirect();
        $this->load->driver('Streams');
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->load->model(array('question_m', 'feedbackuser_m', 'feedbackmanager_m', 'user_m', 'feedback_manager_question_m'));
        $this->lang->load('feedbackuser');
    }

    public function index() {
        $posts = array();
        $posts = $this->feedbackuser_m->get_feedback_not_answer($this->current_user->id);
        $this->input->is_ajax_request() and $this->template->set_layout(false);
        $this->template
                ->title($this->module_details['name'])
                ->set_breadcrumb(lang('feedbackuser:types_title'))
                ->set('breadcrumb_title', $this->module_details['name'])
                ->append_js('module::feedback_details.js')
                ->append_css('module::feedback.css')
                ->set('posts', $posts);
        $this->input->is_ajax_request() ? $this->template->build('tables/not_answers') : $this->template->build('fbnot_answer');
    }

    public function details_feedback($feedback_id) {
        $feedback_details = array();
        $this->load->model(array('answer_m'));
        $feedback_details['feedback'] = $this->feedbackmanager_m->get($feedback_id);
        $questions = $this->feedback_manager_question_m->get_all_question_in_feedback($feedback_id);
        foreach ($questions as $question) {
            $question->answers = $this->answer_m->get_many_by('question_id', $question->id);
        }
        $feedback_details['questions'] = $questions;
        echo json_encode($feedback_details);
    }

    public function answer_feedback() {
        $this->load->model('answeruser_m');
        $message = array();
        $feedback_id = $this->input->post('feedback_id');
        $feedback_user = $this->feedbackuser_m->get_by_feedback_id_and_user_id($feedback_id, $this->current_user->id);
        $questions = $this->feedback_manager_question_m->get_many_by('feedback_manager_id', $feedback_id);
        $success = 1;
        foreach ($questions as $question) {
            $extra = array(
                'answer_id' => $this->input->post($question->question_id),
                'user_id' => $this->current_user->id,
                'created' => date('Y-m-d H:i:s', now()),
                'created_by' => $this->current_user->id
            );
            if (!$this->answeruser_m->insert($extra)) {
                $success = 0;
                $message['status'] = 'error';
                $message['message'] = lang('feedbackuser:feedbackuser_add_error');
            }
        }
        if ($success == 1) {
            $feedback_extra = array(
                'status' => 2,
                'updated'=> date('Y-m-d H:i:s', now()),
                'date' => date('Y-m-d H:i:s', now()),
            );
            if ($this->feedbackuser_m->update($feedback_user->id, $feedback_extra)) {
                $message['status'] = 'success';
                $message['message'] = str_replace("%s", 'aa', lang('feedbackuser:feedbackuser_add_success'));
            } else {
                $message['status'] = 'error';
                $message['message'] = lang('feedbackuser:feedbackuser_add_error');
            }
        }
        echo json_encode($message);
    }

}
