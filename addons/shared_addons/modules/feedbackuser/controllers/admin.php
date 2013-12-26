<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Admin
 * @Description: Feedback type manager controller (edit, add, delete, view)
 * @Author: laiminhdao
 * @Company: framgia
 * @Date: 12/26/13
 * @Update: 12/26/13
 */

class Admin extends Admin_Controller
{
    protected $section = 'feedbackuers';

    protected $validation_rules = array(
        'feedback_manager_id' => array(
            'field' => 'feedback_manager_id',
            'label' => 'lang:feedbacktype:manager_id',
            'rules' => 'trim|htmlspecialchars|required|numeric'
        ),
        'user_id' => array(
            'field' => 'user_id',
            'label' => 'lang:feedbacktype:user_id',
            'rules' => 'trim|htmlspecialchars|required|numeric'
        ),
        'status' => array(
            'field' => 'status',
            'label' => 'lang:feedbacktype:status',
            'rules' => 'trim|htmlspecialchars|required|numeric'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->load->model('feedbackuser_m');
        $this->load->model('feedbackmanager_m');
        $this->lang->load('feedbackuser');
    }

    /**
     * The index function
     * @Description: Feedback user manager page index
     * @Parameter:
     * @Return: null
     * @Date: 12/26/13
     * @Update: 12/26/13
     */
    public function index() {
        $base_where = array();
        // ley key tu filter neu nguoi dung search
        if ($this->input->post('f_keywords'))
        {
            $base_where['name'] = $this->input->post('f_keywords');
        }
        // create pagination
        $total_rows = $this->feedbackuser_m->count_by($base_where);
        $pagination = create_pagination('admin/feedbackuser/index', $total_rows, 10);

        $feedbackusers = $this->feedbackuser_m->get_feedbackusers_list($pagination['limit'], $pagination['offset'], $base_where);
        $this->input->is_ajax_request() and $this->template->set_layout(false);
        // set tempalte content
        $this->template
            ->title($this->module_details['name'])
            ->append_js('admin/filter.js')
            ->set_partial('filters', 'admin/partials/filters')
            ->set('pagination', $pagination)
            ->set('feedbackusers', $feedbackusers);

        // check if ajax request
        $this->input->is_ajax_request()
            ? $this->template->build('admin/tables/feedbackusers')
            : $this->template->build('admin/index');
    }
    
    /**
     * The create function
     * @Description: Create new feedback manager user
     * @Parameter:
     * @Return: null
     * @Date: 12/26/13
     * @Update: 12/26/13
     */
     public function create(){
        $feedback_manager_user = new stdClass();

        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('feedback_manager_user', 'feedback_manager_users');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        $feedbackuser_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $feedbackuser_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $extra = array(
                'feedback_manager_id'   => $this->input->post('feedback_manager_id'),
                'user_id'               => $this->input->post('user_id'),
                'status'                => $this->input->post('status'),
                'date'                  => date('Y-m-d H:i:s', now()),
                'created'               => date('Y-m-d H:i:s', now()),
                'created_by'            => $this->current_user->id
            );

            if ($id = $this->streams->entries->insert_entry($_POST, 'feedback_manager_user', 'feedback_manager_users', array('created'), $extra)) {
                $this->pyrocache->delete_all('feedbackuser_m');
                $this->session->set_flashdata('success', sprintf($this->lang->line('feedbackuser:feedbackuser_add_success')));
                Events::trigger('feedback_manager_user_created', $id);
            } else {
                $this->session->set_flashdata('error', lang('feedbackuser:feedbacktype_add_error'));
            }
            
            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/feedbackuser')
                : redirect('admin/feedbackuser/edit/'.$id);

        } else {
            $feedback_manager_user = new stdClass;
            foreach ($this->validation_rules as $key => $field)
            {
                $feedback_manager_user->$field['field'] = set_value($field['field']);
            }
            $feedback_manager_user->created_on = now();
        }

        // Set Values
        $values = $this->fields->set_values($stream_fields, null, 'new');
        $feedbackmanagers = $this->feedbackmanager_m->get_all();
        $CI = & get_instance();
        $CI->load->model('users/user_m');
        $users = $CI->user_m->get_all();
        // End set values

        // Run stream field events
        $this->fields->run_field_events($stream_fields, array(), $values);

        $this->template
            ->title($this->module_details['name'], lang('description:create_title'))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values))
            ->set('feedback_manager_user', $feedback_manager_user)
            ->set('feedbackmanagers', $feedbackmanagers)
            ->set('users', $users)
            ->build('admin/form');
    }
    
    /**
     * The edit function
     * @Description: Edit feedback manager user by $id
     * @Parameter: int $id
     * @Return: null
     * @Date: 12/26/13
     * @Update: 12/26/13
     */
    public function edit($id = 0){
        // Neu request khong truyen id thi redirect sang trang index
        $id or redirect('admin/gift');
        // Lay du lieu tu database
        $feedback_manager_user = $this->feedbackuser_m->get($id);

        // Load data cache
        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('feedback_manager_user', 'feedback_manager_users');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        // Get the validation for our custom blog fields.
        $feedbackuser_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $feedbackuser_validation);
        $this->form_validation->set_rules($rules);

        // Neu thoa man validation thi luu vao stream
        if ($this->form_validation->run())
        {
            $author_id = empty($feedback_manager_user->created_by) ? $this->current_user->id : $feedback_manager_user->created_by;

            $extra = array(
                'feedback_manager_id'   => $this->input->post('feedback_manager_id'),
                'user_id'               => $this->input->post('user_id'),
                'status'                => $this->input->post('status'),
                'date'                  => date('Y-m-d H:i:s', now()),
                'created'               => date('Y-m-d H:i:s', now()),
                'created_by'            => $this->current_user->id
            );

            if ($this->streams->entries->update_entry($id, $_POST, 'feedback_manager_user', 'feedback_manager_users', array('updated'), $extra)) {
                $this->session->set_flashdata(array('success' => sprintf(lang('feedbackuser:edit_success'))));
                Events::trigger('feedback_manager_user_updated', $id);
            } else {
                $this->session->set_flashdata('error', lang('feedbackuser:edit_error'));
            }

            // Neu nguoi dung nhan save exit => chuyen sang sang index
            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/feedbackuser')
                : redirect('admin/feedbackuser/edit/'.$id);
        }

        foreach ($this->validation_rules as $key => $field)
        {
            if (isset($_POST[$field['field']]))
            {
                $feedback_manager_user->$field['field'] = set_value($field['field']);
            }
        }

        $values = $this->fields->set_values($stream_fields, $feedback_manager_user, 'edit');
        $this->fields->run_field_events($stream_fields, array(), $values);
        
        $feedbackmanagers = $this->feedbackmanager_m->get_all();
        $CI = & get_instance();
        $CI->load->model('users/user_m');
        $users = $CI->user_m->get_all();

        // Set content cho template
        $this->template
            ->title($this->module_details['name'], lang('description:create_title'))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values, $feedback_manager_user->id))
            ->set('feedback_manager_user', $feedback_manager_user)
            ->set('feedbackmanagers', $feedbackmanagers)
            ->set('users', $users)
            ->build('admin/form');
    }
    
    /**
     * The delete function
     * @Description: Delete feedback manager user by $id
     * @Parameter: int $id
     * @Return: null
     * @Date: 12/26/13
     * @Update: 12/26/13
     */
    public function delete($id = 0){
        $ids = ($id) ? array($id) : $this->input->post('action_to');
        if (!empty($ids)){
            $deleted_ids = array();
            foreach ($ids as $id){
                if ($feedback_manager_type = $this->feedbackuser_m->get($id)){
                    if ($this->feedbackuser_m->delete($id)){
                        $this->pyrocache->delete('feedbackuser_m');
                        $deleted_ids[] = $id;
                    }
                }
            }
            Events::trigger('feedback_manager_user_deleted', $deleted_ids);
        }

        if (!empty($deleted_ids)){
            if (count($deleted_ids) == 1) {
                $this->session->set_flashdata('success', 
                    sprintf($this->lang->line('feedbackuser:delete_success'), $deleted_ids[0]));
            } else {
                $this->session->set_flashdata('success', sprintf($this->lang->line('feedbackuser:mass_delete_success'), implode('", "', $deleted_ids)));
            }
        } else {
            $this->session->set_flashdata('notice', lang('blog:delete_error'));
        }
        redirect('admin/feedbackuser');
    }
    
    /**
     * The action function
     * @Description: This is action function
     * @Parameter:
     * @Return: null
     * @Date: 12/26/13
     * @Update: 12/26/13
     */
    public function action()
    {
        switch ($this->input->post('btnAction'))
        {
            case 'delete':
                $this->delete();
                break;

            default:
                redirect('admin/feedbackuser');
                break;
        }
    }
} 