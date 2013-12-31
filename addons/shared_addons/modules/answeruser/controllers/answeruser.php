<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Question
 * @Description: This is a controller class for Question table
 * @Author: Pxthanh
 * @Company: Framgia
 * @Date: 11/21/13
 * @Update: 11/21/13
 */

class Answeruser extends Public_Controller {
    protected $section = 'answerusers';
    
    protected $validation_rules = array(
        'user_id' => array(
            'field' => 'user_id',
            'label' => 'lang:answeruser:user_id',
            'rules' => 'trim|htmlspecialchars|required|numeric'
        ),
        'answer_id' => array(
            'field' => 'answer_id',
            'label' => 'lang:answeruser:answer_id',
            'rules' => 'trim|htmlspecialchars|required|numeric'
        )
    );

    public function __construct(){
        parent::__construct();
        $this->load->driver('Streams');
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->stream = $this->streams_m->get_stream('answer_user', true, 'answer_users');
        $this->load->model('answeruser_m');
        $this->load->model('answer_m');
        $this->lang->load('answeruser');
    }

    /**
     * The index function
     * @Description: This is index function
     * @Parameter:
     * @Return: null
     * @Date: 12/27/13
     * @Update: 12/27/13
     */
    public function index(){
        $base_where = array();
        if ($this->input->post('f_keywords')) {
            $base_where['name'] = $this->input->post('f_keywords');
        }

        // Create pagination links
        $total_rows = $this->answeruser_m->count_by($base_where);
        $pagination = create_pagination('team/index', $total_rows);
        $posts = $this->answeruser_m->get_answerusers_list($pagination['limit'], $pagination['offset'], $base_where);
        $this->input->is_ajax_request() and $this->template->set_layout(false);
        
        if($posts) {
            $meta = $this->_posts_metadata($posts);
            $this->template
                ->set_metadata('og:title', $this->module_details['name'], 'og')
                ->set_metadata('og:type', 'team', 'og')
                ->set_metadata('og:url', current_url(), 'og')
                ->set_metadata('og:username', $meta['username'], 'og')
                ->set_metadata('username', $meta['username'])
                ->set_metadata('keywords', $meta['keywords']);
        }

        $this->template
            ->title($this->module_details['name'])
            ->set_breadcrumb(lang('answeruser:title'))
            ->set_partial('filters', 'partials/filters')
            ->set('posts', $posts)
            ->set('pagination', $pagination);

        $this->input->is_ajax_request()
            ? $this->template->build('tables/posts')
            : $this->template->build('index');
    }

    /**
     * The posts_metadata function
     * @Description: This is posts_metadata function
     * @Parameter:
     * @Return: null
     * @Date: 12/27/13
     * @Update: 12/27/13
     */
    private function _posts_metadata(&$posts = array()) {
        $keywords = array();
        $description = array();

        if (!empty($posts)) {
            foreach ($posts as &$post){
                $keywords[] = $post->title;
                $username[] = $post->username;
            }
        }

        return array(
            'keywords' => implode(', ', $keywords),
            'username' => implode(', ', $username)
        );
    }
    
    public function create() {
        $answer_user = new stdClass();

        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('answer_user', 'answer_users');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        $answeruser_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $answeruser_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $extra = array(
                'answer_id'             => $this->input->post('answer_id'),
                'user_id'               => $this->input->post('user_id'),
                'created'               => date('Y-m-d H:i:s', now()),
                'created_by'            => $this->current_user->id
            );

            if ($id = $this->streams->entries->insert_entry($_POST, 'answer_user', 'answer_users', array('created'), $extra)) {
                $this->pyrocache->delete_all('answeruser_m');
                $this->session->set_flashdata('success', sprintf($this->lang->line('answeruser:answeruser_add_success')));
                Events::trigger('answer_user_created', $id);
            } else {
                $this->session->set_flashdata('error', lang('answeruser:answeruser_add_error'));
            }
            
            redirect('answeruser');

        } else {
            $answer_user = new stdClass;
            foreach ($this->validation_rules as $key => $field)
            {
                $answer_user->$field['field'] = set_value($field['field']);
            }
            $answer_user->created_on = now();
        }

        // Set Values
        $values = $this->fields->set_values($stream_fields, null, 'new');
        $answers = $this->answer_m->get_all();
        $CI = & get_instance();
        $CI->load->model('users/user_m');
        $users = $CI->user_m->get_all();
        // End set values

        // Run stream field events
        $this->fields->run_field_events($stream_fields, array(), $values);

        $this->template
            ->title($this->module_details['name'], lang('description:create_title'))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values))
            ->set('answer_user', $answer_user)
            ->set('answers', $answers)
            ->set('users', $users)
            ->append_css('module::au_style.css')
            ->build('form');
    }
    
    public function edit($id = 0){
        // Neu request khong truyen id thi redirect sang trang index
        $id or redirect('answeruser');
        // Lay du lieu tu database
        $answer_user = $this->answeruser_m->get($id);

        // Load data cache
        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('answer_user', 'answer_users');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        // Get the validation for our custom blog fields.
        $answeruser_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $answeruser_validation);
        $this->form_validation->set_rules($rules);

        // Neu thoa man validation thi luu vao stream
        if ($this->form_validation->run())
        {
            $author_id = empty($answer_user->created_by) ? $this->current_user->id : $answer_user->created_by;

             $extra = array(
                'answer_id'             => $this->input->post('answer_id'),
                'user_id'               => $this->input->post('user_id'),
                'created'               => date('Y-m-d H:i:s', now()),
                'created_by'            => $this->current_user->id
            );

            if ($this->streams->entries->update_entry($id, $_POST, 'answer_user', 'answer_users', array('updated'), $extra)) {
                $this->session->set_flashdata(array('success' => sprintf(lang('answeruser:edit_success'))));
                Events::trigger('answer_user_updated', $id);
            } else {
                $this->session->set_flashdata('error', lang('answeruser:edit_error'));
            }

                redirect('answeruser');
        }

        foreach ($this->validation_rules as $key => $field)
        {
            if (isset($_POST[$field['field']]))
            {
                $answer_user->$field['field'] = set_value($field['field']);
            }
        }

        $values = $this->fields->set_values($stream_fields, $answer_user, 'edit');
        $this->fields->run_field_events($stream_fields, array(), $values);
        
        $answers = $this->answer_m->get_all();
        $CI = & get_instance();
        $CI->load->model('users/user_m');
        $users = $CI->user_m->get_all();

        $this->template
            ->title($this->module_details['name'], lang('description:create_title'))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values, $answer_user->id))
            ->set('answer_user', $answer_user)
            ->set('answers', $answers)
            ->set('users', $users)
            ->append_css('module::au_style.css')
            ->build('form');
    }
    
    public function delete($id = 0){
        $ids = ($id) ? array($id) : $this->input->post('action_to');
        if (!empty($ids)){
            $deleted_ids = array();
            foreach ($ids as $id){
                if ($answer_user = $this->answeruser_m->get($id)){
                    if ($this->answeruser_m->delete($id)){
                        $this->pyrocache->delete('answeruser_m');
                        $deleted_ids[] = $id;
                    }
                }
            }
            Events::trigger('answer_user_deleted', $deleted_ids);
        }

        if (!empty($deleted_ids)){
            if (count($deleted_ids) == 1) {
                $this->session->set_flashdata('success', 
                    sprintf($this->lang->line('answeruser:delete_success'), $deleted_ids[0]));
            } else {
                $this->session->set_flashdata('success', sprintf($this->lang->line('answeruser:mass_delete_success'), implode('", "', $deleted_ids)));
            }
        } else {
            $this->session->set_flashdata('notice', lang('blog:delete_error'));
        }
        redirect('answeruser');
    }
} 