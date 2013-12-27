<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Admin
 * @Description: This is class admin
 * @Author: HoangThiTuanDung
 * @Company: framgia
 * @Date: 12/25/13
 * @Update:
 */

class Feedback_manager extends Public_Controller
{
    private $_data = Array();
    protected $section = 'posts';

    protected $validation_rules = array(
        'title'         => array(
            'field'     => 'title',
            'label'     => 'lang:feedback_manager:title',
            'rules'     => 'trim|htmlspecialchars|required|max_length[200]'
        ),
        'description'   => array(
            'field'     => 'description',
            'label'     => 'lang:feedback_manager:description',
            'rules'     => 'trim|htmlspecialchars|'
        ),
        'start_date'    => array(
            'field'     => 'start_date',
            'label'     => 'lang:feedback_manager:start_date',
            'rules'     => ''
        ),
        'end_date'    => array(
            'field'     => 'end_date',
            'label'     => 'lang:feedback_manager:end_date',
            'rules'     => ''
        ),
        'type_id'    => array(
            'field'     => 'type_id',
            'label'     => 'lang:feedback_manager:type_id',
            'rules'     => ''
        ),
        'require'       => array(
            'field'     => 'require',
            'label'     => 'lang:feedback_manager:require',
            'rules'     => ''
        ),
        'status'    => array(
            'field'     => 'status',
            'label'     => 'lang:feedback_manager:status',
            'rules'     => ''
        ),
    );

    public function __construct() {
        parent::__construct();
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->load->model('feedback_manager_m');
        $this->load->model('feedbacktype/feedbacktype_m');
        $this->load->model('users/user_m');
        $this->lang->load('feedback_manager');
        $this->lang->load('buttons');
    }

    /**
     * The index function
     * @Description: This is index function
     * @Parameter:
     * @Return: null
     * @Date: 11/20/13
     * @Update:
     */
    public function index()
    {
        $base_where = array();
        if ($this->input->post('f_keywords'))
        {
            $base_where['title'] = $this->input->post('f_keywords');
        }

        if ($this->uri->segment(3) === null)
        {
            $current_page = 1;
        }
        else
        {
            $current_page = $this->uri->segment(3);
        }
        $total_rows = $this->feedback_manager_m->count_by($base_where);
        $this->load->library('pagination');

        $config['base_url'] = base_url().'feedback_manager/page/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = true;

        $offset = ($current_page - 1) * $config['per_page'];

        $this->pagination->initialize($config);

        $post = $this->feedback_manager_m->get_feedback_manager_list($config['per_page'], $offset, $base_where);

        $this->template
            ->title($this->module_details['name'])
            ->set('post', $post);
            $this->template->build('index');
    }

    /**
     * The create function
     * @Description: This is create function
     * @Parameter:
     * @Return: null
     * @Date: 12/25/13
     * @Update: 12/25/13
     */
    public function create(){
        $this->_data['test'] = $this->feedbacktype_m->get_all();
        $post = new stdClass();

        // Get the blog stream.
        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('feedback_manager', 'feedback_managers');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        // Get the validation for our custom blog fields.
        $feedback_manager_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $feedback_manager_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $extra = array(
                'title'             => $this->input->post('title'),
                'description'       => $this->input->post('description'),
                'start_date'        => $this->input->post('start_date'),
                'end_date'          => $this->input->post('end_date'),
                'type_id'           => $this->input->post('type_id'),
                'require'           => $this->input->post('require'),
                'status'            => $this->input->post('status'),
                'created'		    => date('Y-m-d H:i:s', now()),
                'created_by'        => $this->current_user->id
            );

            if ($id = $this->streams->entries->insert_entry($_POST, 'feedback_manager', 'feedback_managers', array('created'), $extra)) {
                $this->pyrocache->delete_all('feedback_manager_m');
                $this->session->set_flashdata('success', sprintf($this->lang->line('feedback_manager:post_add_success'), $this->input->post('title')));
                Events::trigger('post_created', $id);
            } else {
                $this->session->set_flashdata('error', lang('feedback_manager:post_add_error'));
            }

            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('feedback_manager')
                : redirect('feedback_manager/edit/'.$id);

        } else {
            $post = new stdClass;
            foreach ($this->validation_rules as $key => $field)
            {
                $post->$field['field'] = set_value($field['field']);
            }
            $post->created_on = now();
        }

        // Set Values
        $values = $this->fields->set_values($stream_fields, null, 'new');

        // Run stream field events
        $this->fields->run_field_events($stream_fields, array(), $values);

        $this->template
            ->title($this->module_details['name'], lang('feedback_manager:create_title'))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values))
            ->set('post', $post)
            /*->append_js('module::datapicker.js')*/
            ->build('form',$this->_data);
//            ->build('admin/form');
    }


    /**
     * The edit function
     * @Description: This is edit function
     * @Parameter:
     *      1. $id int The ID of the feedback_manager post to edit
     * @Return: null
     * @Date: 12/25/13
     * @Update:
     */
    public function edit($id = 0){
        $this->_data['test'] = $this->feedbacktype_m->get_all();
        $id or redirect('feedback_manager');
        $post = $this->feedback_manager_m->get($id);

        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('feedback_manager', 'feedback_managers');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        $feedback_manager_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $feedback_manager_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $author_id = empty($post->created_by) ? $this->current_user->id : $post->created_by;

            $extra = array(
                'title'             => $this->input->post('title'),
                'description'       => $this->input->post('description'),
                'start_date'        => $this->input->post('start_date'),
                'end_date'          => $this->input->post('end_date'),
                'type_id'           => $this->input->post('type_id'),
                'require'           => $this->input->post('require'),
                'status'            => $this->input->post('status'),
                'updated'		    => date('Y-m-d H:i:s', now()),
                'created_by'        => $author_id
            );

            if ($this->streams->entries->update_entry($id, $_POST, 'feedback_manager', 'feedback_managers', array('updated'), $extra)) {
                $this->session->set_flashdata(array('success' => sprintf(lang('feedback_manager:edit_success'), $this->input->post('title'))));
                Events::trigger('post_updated', $id);
            } else {
                $this->session->set_flashdata('error', lang('feedback_manager:edit_error'));
            }

            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('feedback_manager')
                : redirect('feedback_manager/edit/'.$id);
        }

        foreach ($this->validation_rules as $key => $field)
        {
            if (isset($_POST[$field['field']]))
            {
                $post->$field['field'] = set_value($field['field']);
            }
        }

        $values = $this->fields->set_values($stream_fields, $post, 'edit');
        $this->fields->run_field_events($stream_fields, array(), $values);

        $this->template
            ->title($this->module_details['name'], sprintf(lang('feedback_manager:edit_title_label'), $post->title))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values, $post->id))
            ->set('post', $post)
            ->build('form',$this->_data);
    }

    /**
     * The delete function
     * @Description: This is delete function
     * @Parameter:
     *      1. $id int The ID of the feedback_manager post to delete
     * @Return: null
     * @Date: 12/25/13
     * @Update:
     */
    public function delete($id = 0)
    {
        $ids = ($id) ? array($id) : $this->input->post('action_to');
        if (!empty($ids)){
            $post_titles = array();
            $deleted_ids = array();
            foreach ($ids as $id){
                if ($post = $this->feedback_manager_m->get($id)){
                    if ($this->feedback_manager_m->delete($id)){
                        $this->pyrocache->delete('feedback_manager_m');
                        $post_titles[] = $post->title;
                        $deleted_ids[] = $id;
                    }
                }
            }
            Events::trigger('post_deleted', $deleted_ids);
        }

        if (!empty($post_titles)){
            if (count($post_titles) == 1) {
                $this->session->set_flashdata('success', sprintf($this->lang->line('feedback_manager:delete_success'), $post_titles[0]));
            } else {
                $this->session->set_flashdata('success', sprintf($this->lang->line('feedback_manager:mass_delete_success'), implode('", "', $post_titles)));
            }
        } else {
            $this->session->set_flashdata('notice', lang('feedback_manager:delete_error'));
        }
        redirect('feedback_manager');
    }

    /**
     * The action function
     * @Description: This is action function
     * @Parameter:
     * @Return: null
     * @Date: 12/5/13
     * @Update:
     */
    public function action()
    {
        switch ($this->input->post('btnAction'))
        {
            case 'delete':
                $this->delete();
                break;

            default:
                redirect('feedback_manager');
                break;
        }
    }

    public function manage()
    {
        $base_where = array();
        if ($this->input->post('f_keywords'))
        {
            $base_where['title'] = $this->input->post('f_keywords');
        }

        // Create pagination links
        $total_rows = $this->feedback_manager_m->count_by($base_where);
        $pagination = create_pagination('feedback_manager/manage/index', $total_rows);

        $post = $this->feedback_manager_m->get_feedback_manager_list($pagination['limit'], $pagination['offset'], $base_where);
        $this->input->is_ajax_request() and $this->template->set_layout(false);

        $this->template
            ->title($this->module_details['name'])
            ->append_js('module::filter.js')
            ->set_partial('filters', 'partials/filters')
            ->set('pagination', $pagination)
            ->set('post', $post);

        $this->input->is_ajax_request()
            ? $this->template->build('tables/posts')
            : $this->template->build('manage');
    }

    public function view($id)
    {
        $this->_data['post'] = $this->feedback_manager_m->get($id);
        $user_id = $this->_data['post']->created_by;
        $type_id = $this->_data['post']->type_id;
        $this->_data['user'] = $this->user_m->get_by(array('id'=>$user_id));
        $this->_data['type'] = $this->feedbacktype_m->get_by(array('id'=>$type_id));
        $this->template->build('view',$this->_data);
    }
}