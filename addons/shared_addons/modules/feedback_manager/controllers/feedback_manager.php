<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Feedback_manager
 * @Description: This is class Feedback_manager
 * @Author: HoangThiTuanDung
 * @Date: 12/25/13
 * @Update: 12/25/13
 */
class Feedback_manager extends Public_Controller {

    // Validate
    protected $validation_rules = array(
        'title' => array(
            'field' => 'title',
            'label' => 'lang:feedback_manager:title',
            'rules' => 'trim|htmlspecialchars|required|max_length[200]'
        ),
        'description' => array(
            'field' => 'description',
            'label' => 'lang:feedback_manager:description',
            'rules' => 'trim|htmlspecialchars|'
        ),
        'start_date' => array(
            'field' => 'start_date',
            'label' => 'lang:feedback_manager:start_date',
            'rules' => ''
        ),
        'end_date' => array(
            'field' => 'end_date',
            'label' => 'lang:feedback_manager:end_date',
            'rules' => ''
        ),
        'type_id' => array(
            'field' => 'type_id',
            'label' => 'lang:feedback_manager:type_id',
            'rules' => ''
        ),
        'require' => array(
            'field' => 'require',
            'label' => 'lang:feedback_manager:require',
            'rules' => ''
        ),
        'status' => array(
            'field' => 'status',
            'label' => 'lang:feedback_manager:status',
            'rules' => ''
        ),
    );
    
    /**
    * construct function
    * load model and library
    * check login
    */
    public function __construct() {
        
        parent::__construct();
        
        if (!check_user_permission($this->current_user, $this->module, $this->permissions)) redirect();
        
        $this->template->set_layout('feedback_layout.html');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        $this->load->driver('Streams');
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->stream = $this->streams_m->get_stream('feedback_manager', true, 'feedback_managers');
        $this->load->model(array('feedback_manager_m', 'feedbacktype/feedbacktype_m', 'feedbackquestion_m', 'feedback_manager_question/feedback_manager_question_m', 'feedbackuser/feedbackuser_m'));
        $this->lang->load('feedback_manager');

        // Get feedback_manager_type list from cached to bidding select list
        $feedback_manager_type = array(''  => lang('feedback_manager:select_type'));
        $feedback_manager_types = $this->streams->entries->get_entries(array('stream' => 'feedback_manager_type', 'namespace' => 'feedback_manager_types'));
        foreach ($feedback_manager_types['entries'] as $post) {
            $feedback_manager_type[$post['id']] = $post['title'];
        }
        $this->template->set('feedback_manager_types', $feedback_manager_type);


        // Get apply list from cached to bidding select list
        $apply = array(''  => lang('feedback_manager:select_apply'));
        $users = $this->streams->entries->get_entries(array('stream' => 'profiles', 'namespace' => 'users'));
        foreach ($users['entries'] as $post) {
            $apply[$post['user_id']] = get_username_by_id($post['user_id']);
        }
        $this->template->set('users', $apply);  
    }

    /**
     * The index function
     * @Description: This is index function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function index() {
        //Search
        $where = "";
        if ($this->input->post('f_keywords')) {
            $where .= "`title` LIKE '%" . $this->input->post('f_keywords') . "%' ";
        }

        // Get the latest feedback manager posts
        $posts = $this->streams->entries->get_entries(array(
            'stream' => 'feedback_manager',
            'namespace' => 'feedback_managers',
            'limit' => Settings::get('records_per_page'),
            'where' => $where,
            'paginate' => 'yes',
            'pag_base' => site_url('feedback_manager/page'),
            'pag_segment' => 3
        ));

        // Process posts
        foreach ($posts['entries'] as &$post) {
            $this->_process_post($post);
        }
        $meta = $this->_posts_metadata($posts['entries']);
        $this->input->is_ajax_request() and $this->template->set_layout(false);

        $this->template

            ->title($this->module_details['name'])
            ->set_breadcrumb(lang('feedback_manager:feedback_manager_title'))
            ->set('breadcrumb_title', $this->module_details['name'])
            ->set_metadata('og:title', $this->module_details['name'], 'og')
            ->set_metadata('og:type', 'feedback_manager', 'og')
            ->set_metadata('og:url', current_url(), 'og')
            ->set_metadata('og:description', $meta['description'], 'og')
            ->set_metadata('description', $meta['description'])
            ->set_metadata('keywords', $meta['keywords'])
            ->append_js('module::feedback_manager_form.js')
            ->append_js('module::statistics.js')
            ->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
            ->set('posts', $posts['entries'])
            ->set('pagination', $posts['pagination']);

        $this->input->is_ajax_request() ? $this->template->build('tables/posts') : $this->template->build('index');
    }

    /**
     * The process function
     * @Description: This is process function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function process() {
        if (!$this->input->is_ajax_request())
            redirect('feedback_manager');
        if ($this->input->post('action') == 'create') {
            $this->create();
        } else if ($this->input->post('action') == 'edit') {
            $this->edit();
        }
    }

    /**
     * The delete function
     * @Description: This is delete function
     * @Parameter:
     *      1. $id int The ID of the feedback manager post to delete
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function delete($id = 0) {
        $ids = ($id) ? array($id) : $this->input->post('action_to');
        if (!empty($ids)) {
            $post_names = array();
            $deleted_ids = array();

            foreach ($ids as $id){
                if ($post = $this->feedback_manager_m->get($id)){
                    
                    $postx = $this->feedbackuser_m->get_by(array('feedback_manager_id'=>$id));
                    $posty = $this->feedback_manager_question_m->get_by(array('feedback_manager_id'=>$id));
                    
                    if ($this->feedback_manager_m->delete($id)){
                        if($this->feedbackuser_m->delete($postx->id))
                        {
                            $this->pyrocache->delete('feedbackuser_m');
                        }
                        $this->pyrocache->delete('feedback_manager_m');
                        $post_names[] = $post->title;
                        $deleted_ids[] = $id;
                    }
                }
            }
            Events::trigger('feedback_manager_deleted', $deleted_ids);
        }
        $message = array();
        if (!empty($post_names)) {
            if (count($post_names) == 1) {
                $message['status'] = 'success';
                $message['message'] = str_replace("%s", $post_names[0], lang('feedback_manager:delete_success'));
            } else {
                $message['status'] = 'success';
                $message['message'] = str_replace("%s", implode('", "', $post_names), lang('feedback_manager:mass_delete_success'));
            }
        } else {
            $message['status'] = 'warning';
            $message['message'] = lang('feedback_manager:delete_error');
        }
        echo json_encode($message);
    }

    /**
     * The action function
     * @Description: This is action function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function action() {
        switch ($this->input->post('btnAction')) {
            case 'delete':
                $this->delete();
                break;
            default:
                echo '';
                break;
        }
    }

    /**
     * The get_feedback_manager_by_id function
     * @Parameter: $id
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function get_feedback_manager_by_id($id) {
        if (!$this->input->is_ajax_request())
            redirect('feedback_manager');
        if ($id != null && $id != "") {
            $item = $this->feedback_manager_m->get($id);
            echo json_encode($item);
        } else {
            echo "";
        }
    }

    /**
     * The get_question_manager function
     * @Parameter: $id
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function get_question_manager($id)
    {
        if(!$this->input->is_ajax_request()) redirect('feedback_manager');
        if($id != null && $id != ""){
            $item = $this->feedbackquestion_m->get_question_list_by_fid($id);
            if (count($item) > 0)
                echo json_encode($item);
            else
                echo "";
        }else {
            echo "";
        }
    }

    /**
     * The create function
     * @Description: This is create function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    private function create() {
        $message = array();
        $stream = $this->streams->streams->get_stream('feedback_manager', 'feedback_managers');
        // Get the validation for our custom blog fields.
        $feedback_manager_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $feedback_manager_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run()) {
            if(is_numeric($this->input->post('apply_id')))
            {
                $extra = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'start_date' => $this->input->post('start_date'),
                    'end_date' => $this->input->post('end_date'),
                    'type_id' => $this->input->post('type_id'),
                    'require' => $this->input->post('require'),
                    'status' => $this->input->post('status'),
                    'created' => date('Y-m-d H:i:s', now()),
                    'created_by' => $this->current_user->id
                );

                if ($id = $this->streams->entries->insert_entry($_POST, 'feedback_manager', 'feedback_managers', array('created'), $extra)) {
                    
                    $extra2 = array(
                        'user_id'               =>  $this->input->post('apply_id'),
                        'feedback_manager_id'   =>  $id,
                        'status'                => $this->input->post('status'),
                        'date'                  => date('Y-m-d H:i:s', now()),
                        'created'               => date('Y-m-d H:i:s', now()),
                        'created_by'            => $this->current_user->id
                    );

                    if ($id2 = $this->streams->entries->insert_entry($_POST, 'feedback_manager_user', 'feedback_manager_users', array('created'), $extra2)) {
                        $this->pyrocache->delete_all('feedbackuser_m');
                        Events::trigger('feedbackuser_add_created', $id2);
                    }

                    $this->pyrocache->delete_all('feedback_manager_m');
                    $message['status'] = 'success';
                    $message['message'] = str_replace("%s", $this->input->post('title'), lang('feedback_manager:post_add_success'));
                    Events::trigger('feedback_manager_created', $id);
                } else {
                    $message['status'] = 'error';
                    $message['message'] = lang('feedback_manager:post_add_error');
                }
            }
            else
            {
                $message['status'] = 'error';
                $message['message'] = lang('feedback_manager:validate_user_error');
            }
        } else {
            $message['status'] = 'error';
            $message['message'] = lang('feedback_manager:validate_error');
        }
        echo json_encode($message);
    }

    /**
     * The edit function
     * @Description: This is edit function
     * @Parameter:
     *      1. $id int The ID of the feedback manager post to edit
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    private function edit() {
        $id = $this->input->post('row_edit_id');
        $post = $this->feedback_manager_m->get($id);
        $message = array();
        // Get all company
        $stream = $this->streams->streams->get_stream('feedback_manager', 'feedback_managers');
        $feedback_manager_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $feedback_manager_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run()) {
            if(is_numeric($this->input->post('apply_id')))
            {
                $author_id = empty($post->created_by) ? $this->current_user->id : $post->created_by;
                $extra = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'start_date' => $this->input->post('start_date'),
                    'end_date' => $this->input->post('end_date'),
                    'type_id' => $this->input->post('type_id'),
                    'require' => $this->input->post('require'),
                    'status' => $this->input->post('status'),
                    'updated' => date('Y-m-d H:i:s', now()),
                    'created_by' => $author_id
                );

                if ($this->streams->entries->update_entry($id, $_POST, 'feedback_manager', 'feedback_managers', array('updated'), $extra)) {
                    $extra2 = array(
                        'user_id'               =>  $this->input->post('apply_id'),
                        'feedback_manager_id'   =>  $id,
                        'status'                => $this->input->post('status'),
                        'date'                  => date('Y-m-d H:i:s', now()),
                        'updated'               => date('Y-m-d H:i:s', now()),
                        'created_by'            => $this->current_user->id
                    );
                    $id2 = $this->feedbackuser_m->get_by(array('feedback_manager_id'=>$id))->id;
                    if ($this->streams->entries->update_entry($id2, $_POST, 'feedback_manager_user', 'feedback_manager_users', array('updated'), $extra2)) {
                        Events::trigger('feedback_manager_updated', $id2);
                    }

                    $message['status'] = 'success';
                    $message['message'] = str_replace("%s", $this->input->post('title'), lang('feedback_manager:edit_success'));
                    Events::trigger('feedback_manager_updated', $id);
                } else {
                    $message['status'] = 'error';
                    $message['message'] = lang('feedback_manager:edit_error');
                }
            }
            else
            {
                $message['status'] = 'error';
                $message['message'] = lang('feedback_manager:validate_user_error');
            }
            
        } else {
            $message['status'] = 'error';
            $message['message'] = lang('feedback_manager:validate_error');
        }
        echo json_encode($message);
    }

    /**
     * The process_post function
     * @Description: This is process_post function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    private function _process_post(&$post) {
        $post['type'] = $this->feedbacktype_m->get_by(array('id' => $post['type_id']))->title;
        $post['url_edit'] = site_url('feedback_manager/edit/' . $post['id']);
        $post['url_delete'] = site_url('feedback_manager/delete/' . $post['id']);
    }

    /**
     * The posts_metadata function
     * @Description: This is posts_metadata function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    private function _posts_metadata(&$posts = array()) {
        $keywords = array();
        $description = array();

        if (!empty($posts)) {
            foreach ($posts as &$post) {
                $keywords[] = $post['title'];
                $description[] = $post['description'];
            }
        }

        return array(
            'keywords' => implode(', ', $keywords),
            'description' => implode(', ', $description)
        );
    }

    /**
     * The statistics function
     * @Description: This is statistics function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function statistic($id) {
        $data = array();
        $statistics = array();
        $this->load->model('answer_m');
        $this->load->model('answeruser_m');
        $this->load->model('question_m');
        $this->load->model('feedback_manager_user_m');
        $this->load->model('feedback_manager_question_m');
        $feedback_manager_id = $this->feedback_manager_question_m->get_by('question_id', $id)->feedback_manager_id;
        $count_users = $this->feedback_manager_user_m->count_by('feedback_manager_id', $feedback_manager_id);
        $data['title'] = $this->question_m->get($id)->title;
        $list_answer = $this->answer_m->get_many_by('question_id', $id);
        $i = 1;
        $count_users_answered = 0;
        foreach ($list_answer as $anwser) {
            $statistics[$i]['name'] = $anwser->title;
            $statistics[$i]['percent'] = $this->answeruser_m->count_by('answer_id', $anwser->id) / $count_users;
            $count_users_answered+=$this->answeruser_m->count_by('answer_id', $anwser->id);
            $i++;
        }
        $statistics[0]['name'] = lang('feedback_manager:not_answer');
        $statistics[0]['percent'] = ($count_users - $count_users_answered) / $count_users;
        $data['count_answer'] = $i;
        $data['count_users'] = $count_users;
        $data['statistics'] = $statistics;
        $data['question_id'] = $id;
        echo json_encode($data);
    }

}
