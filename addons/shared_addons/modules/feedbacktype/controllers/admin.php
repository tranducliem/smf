<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Admin
 * @Description: Feedback type manager controller (edit, add, delete, view)
 * @Author: laiminhdao
 * @Company: framgia
 * @Date: 12/25/13
 * @Update: 12/25/13
 */

class Admin extends Admin_Controller
{
    protected $section = 'feedbacktypes';

    protected $validation_rules = array(
        'title' => array(
            'field' => 'title',
            'label' => 'lang:feedbacktype:title',
            'rules' => 'trim|htmlspecialchars|required|max_length[150]'
        ),
        'description' => array(
            'field' => 'description',
            'label' => 'lang:feedbacktype:description',
            'rules' => 'trim|htmlspecialchars|required|max_length[255]'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->load->model('feedbacktype_m');
        $this->lang->load('feedbacktype');
    }

    /**
     * The index function
     * @Description: Feedback type manager page index
     * @Parameter:
     * @Return: null
     * @Date: 12/25/13
     * @Update: 12/25/13
     */
    public function index()
    {
        $base_where = array();
        // ley key tu filter neu nguoi dung search
        if ($this->input->post('f_keywords'))
        {
            $base_where['title'] = $this->input->post('f_keywords');
        }

        // create pagination
        $total_rows = $this->feedbacktype_m->count_by($base_where);
        $pagination = create_pagination('admin/feedbacktype/index', $total_rows, 10);

        $types = $this->feedbacktype_m->get_feedbacktypes_list($pagination['limit'], $pagination['offset'], $base_where);
        $this->input->is_ajax_request() and $this->template->set_layout(false);

        // set tempalte content
        $this->template
            ->title($this->module_details['name'])
            ->append_js('admin/filter.js')
            ->set_partial('filters', 'admin/partials/filters')
            ->set('pagination', $pagination)
            ->set('types', $types);

        // check if ajax request
        $this->input->is_ajax_request()
            ? $this->template->build('admin/tables/feedbacktypes')
            : $this->template->build('admin/index');
    }
    
    /**
     * The create function
     * @Description: Create new type of feedback
     * @Parameter:
     * @Return: null
     * @Date: 12/25/13
     * @Update: 12/25/13
     */
     public function create(){
        $feedback_manager_type = new stdClass();

        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('feedback_manager_type', 'feedback_manager_types');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        $feedbacktype_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $feedbacktype_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $extra = array(
                'title'            => $this->input->post('title'),
                'description'      => $this->input->post('description'),
                'created'          => date('Y-m-d H:i:s', now()),
                'created_by'       => $this->current_user->id
            );

            if ($id = $this->streams->entries->insert_entry($_POST, 'feedback_manager_type', 'feedback_manager_types', array('created'), $extra)) {
                $this->pyrocache->delete_all('feedbacktype_m');
                $this->session->set_flashdata('success', sprintf($this->lang->line('feedbacktype:feedbacktype_add_success'), $this->input->post('title')));
                Events::trigger('feedback_manager_type_created', $id);
            } else {
                $this->session->set_flashdata('error', lang('feedbacktype:feedbacktype_add_error'));
            }
            
            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/feedbacktype')
                : redirect('admin/feedbacktype/edit/'.$id);

        } else {
            $feedback_manager_type = new stdClass;
            foreach ($this->validation_rules as $key => $field)
            {
                $feedback_manager_type->$field['field'] = set_value($field['field']);
            }
            $feedback_manager_type->created_on = now();
        }

        // Set Values
        $values = $this->fields->set_values($stream_fields, null, 'new');

        // Run stream field events
        $this->fields->run_field_events($stream_fields, array(), $values);

        $this->template
            ->title($this->module_details['name'], lang('description:create_title'))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values))
            ->set('feedback_manager_type', $feedback_manager_type)
            ->build('admin/form');
    }
    
    /**
     * The edit function
     * @Description: Edit feedback type by $id
     * @Parameter: int $id
     * @Return: null
     * @Date: 12/25/13
     * @Update: 12/25/13
     */
    public function edit($id = 0){
        $id or redirect('admin/category');
        
        $feedback_manager_type = $this->feedbacktype_m->get($id);

        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('feedback_manager_type', 'feedback_manager_types');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        // Get the validation for our custom blog fields.
        $feedbacktype_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $feedbacktype_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $author_id = empty($feedback_manager_type->created_by) ? $this->current_user->id : $feedback_manager_type->created_by;

            $extra = array(
                'title'            => $this->input->post('title'),
                'description'      => $this->input->post('description'),
                'updated'          => date('Y-m-d H:i:s', now()),
                'created_by'       => $author_id
            );

            if ($this->streams->entries->update_entry($id, $_POST, 'feedback_manager_type', 'feedback_manager_types', array('updated'), $extra)) {
                $this->session->set_flashdata(array('success' => sprintf(lang('feedbacktype:edit_success'), $this->input->post('title'))));
                Events::trigger('feedback_manager_type_updated', $id);
            } else {
                $this->session->set_flashdata('error', lang('feedbacktype:edit_error'));
            }

            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/feedbacktype')
                : redirect('admin/feedbacktype/edit/'.$id);
        }

        foreach ($this->validation_rules as $key => $field)
        {
            if (isset($_POST[$field['field']]))
            {
                $category->$field['field'] = set_value($field['field']);
            }
        }

        $values = $this->fields->set_values($stream_fields, $feedback_manager_type, 'edit');
        $this->fields->run_field_events($stream_fields, array(), $values);

        $this->template
            ->title($this->module_details['name'], sprintf(lang('feedbacktype:edit_title_label'), $feedback_manager_type->title))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values, $feedback_manager_type->id))
            ->set('feedback_manager_type', $feedback_manager_type)
            ->build('admin/form');
    }
    
    /**
     * The delete function
     * @Description: Delete feedback type by $id
     * @Parameter: int $id
     * @Return: null
     * @Date: 12/25/13
     * @Update: 12/25/13
     */
    public function delete($id = 0){
        $ids = ($id) ? array($id) : $this->input->post('action_to');
        if (!empty($ids)){
            $type_titles = array();
            $deleted_ids = array();
            foreach ($ids as $id){
                if ($feedback_manager_type = $this->feedbacktype_m->get($id)){
                    if ($this->feedbacktype_m->delete($id)){
                        $this->pyrocache->delete('feedbacktype_m');
                        $type_titles[] = $feedback_manager_type->title;
                        $deleted_ids[] = $id;
                    }
                }
            }
            Events::trigger('feedback_manager_type_deleted', $deleted_ids);
        }

        if (!empty($type_titles)){
            if (count($type_titles) == 1) {
                $this->session->set_flashdata('success', 
                    sprintf($this->lang->line('feedbacktype:delete_success'), $type_titles[0]));
            } else {
                $this->session->set_flashdata('success', sprintf($this->lang->line('feedbacktype:mass_delete_success'), implode('", "', $type_titles)));
            }
        } else {
            $this->session->set_flashdata('notice', lang('blog:delete_error'));
        }
        redirect('admin/feedbacktype');
    }
    
    /**
     * The action function
     * @Description: This is action function
     * @Parameter:
     * @Return: null
     * @Date: 12/25/13
     * @Update: 12/25/13
     */
    public function action()
    {
        switch ($this->input->post('btnAction'))
        {
            case 'delete':
                $this->delete();
                break;

            default:
                redirect('admin/feedbacktype');
                break;
        }
    }
} 