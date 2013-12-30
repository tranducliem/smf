<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Admin
 * @Description: This is class admin
 * @Author: Pxthanh     
 * @Company: framgia
 * @Date: 11/21/13
 * @Update: 11/21/13
 */

class Admin extends Admin_Controller
{
    protected $section = 'posts';

    protected $validation_rules = array(
        'title' => array(
            'field' => 'title',
            'label' => 'lang:department:title',
            'rules' => 'trim|htmlspecialchars|required|max_length[150]'
        ),
        'description' => array(
            'field' => 'description',
            'label' => 'lang:department:description',
            'rules' => 'trim|htmlspecialchars|max_length[255]'
        ),
        'company_id' => array(
            'field' => 'company_id',
            'label' => 'lang:department:company_id',
            'rules' => 'integer|less_than[1000000000]'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->load->model(array('department_m','company_m'));
        $this->lang->load('department');
    }

    /**
     * The index function
     * @Description: This is index function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function index()
    {
        $base_where = array();
        if ($this->input->post('f_keywords'))
        {
            $base_where['title'] = $this->input->post('f_keywords');
        }

        // Create pagination links
        $total_rows = $this->department_m->count_by($base_where);
        $pagination = create_pagination('admin/department/index', $total_rows);

        $post = $this->department_m->get_department_list($pagination['limit'], $pagination['offset'], $base_where);
        $this->input->is_ajax_request() and $this->template->set_layout(false);

        $this->template
            ->title($this->module_details['name'])
            ->append_js('admin/filter.js')
            ->set_partial('filters', 'admin/partials/filters')
            ->set('pagination', $pagination)
            ->set('post', $post);
        $this->input->is_ajax_request()
            ? $this->template->build('admin/tables/posts')
            : $this->template->build('admin/index');
    }
        
    /**
     * The create function
     * @Description: This is create function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function create(){
        $post = new stdClass();
        // Get all company
        $company = $this->company_m->get_all();
        // Get the department stream.
        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('department', 'departments');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        // Get the validation for our custom department fields.
        $department_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $department_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $extra = array(
                'title'            => $this->input->post('title'),
                'description'           => $this->input->post('description'),
                'company_id'           => $this->input->post('company_id'),
                'created'		   => date('Y-m-d H:i:s', now()),
                'created_by'        => $this->current_user->id
            );

            if ($id = $this->streams->entries->insert_entry($_POST, 'department', 'departments', array('created'), $extra)) {
                $this->pyrocache->delete_all('department_m');
                $this->session->set_flashdata('success', sprintf($this->lang->line('department:post_add_success'), $this->input->post('title')));
                Events::trigger('post_created', $id);
            } else {
                $this->session->set_flashdata('error', lang('department:post_add_error'));
            }
          
            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/department')
                : redirect('admin/department/edit/'.$id);
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
            ->title($this->module_details['name'], lang('department:create_title'))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values))
            ->set('post', $post)
            ->set('company',$company)
            ->build('admin/form');
    }


    /**
     * The edit function
     * @Description: This is edit function
     * @Parameter:
     *      1. $id int The ID of the department post to edit
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function edit($id = 0){
        $id or redirect('admin/department');
        $post = $this->department_m->get($id);
        // Get all company
        $company = $this->company_m->get_all();

        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('department', 'departments');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        $department_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $department_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $author_id = empty($post->created_by) ? $this->current_user->id : $post->created_by;

            $extra = array(
               'title'            => $this->input->post('title'),
                'description'           => $this->input->post('description'),
                'company_id'           => $this->input->post('company_id'),
                'updated'		   => date('Y-m-d H:i:s', now()),
                'created_by'       => $author_id
            );

            if ($this->streams->entries->update_entry($id, $_POST, 'department', 'departments', array('updated'), $extra)) {
                $this->session->set_flashdata(array('success' => sprintf(lang('department:edit_success'), $this->input->post('title'))));
                Events::trigger('post_updated', $id);
            } else {
                $this->session->set_flashdata('error', lang('department:edit_error'));
            }

            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/department')
                : redirect('admin/department/edit/'.$id);
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
            ->title($this->module_details['name'], sprintf(lang('department:edit_name_label'), $post->title))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values, $post->id))
            ->set('post', $post)
            ->set('company',$company)
            ->build('admin/form');
    }

    /**
     * The delete function
     * @Description: This is delete function
     * @Parameter:
     *      1. $id int The ID of the department post to delete
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function delete($id = 0) {
        $ids = ($id) ? array($id) : $this->input->post('action_to');
        if (!empty($ids)){
            $post_names = array();
            $deleted_ids = array();
            foreach ($ids as $id){
                if ($post = $this->department_m->get($id)){
                    if ($this->department_m->delete($id)){
                        $this->pyrocache->delete('department_m');
                        $post_names[] = $post->title;
                        $deleted_ids[] = $id;
                    }
                }
            }
            Events::trigger('post_deleted', $deleted_ids);
        }

        if (!empty($post_names)){
            if (count($post_names) == 1) {
                $this->session->set_flashdata('success', sprintf($this->lang->line('department:delete_success'), $post_names[0]));
            } else {
                $this->session->set_flashdata('success', sprintf($this->lang->line('department:mass_delete_success'), implode('", "', $post_names)));
            }
        } else {
            $this->session->set_flashdata('notice', lang('blog:delete_error'));
        }
        redirect('admin/department');
    }

    /**
     * The action function
     * @Description: This is action function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function action()
    {
        switch ($this->input->post('btnAction'))
        {
            case 'delete':
                $this->delete();
                break;

            default:
                redirect('admin/department');
                break;
        }
    }
} 