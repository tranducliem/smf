<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Admin
 * @Description: This is class admin
 * @Author: tranducliem
 * @Company: framgia
 * @Date: 11/20/13
 * @Update: 11/20/13
 */

class Admin extends Admin_Controller
{
    protected $section = 'posts';

    protected $validation_rules = array(
        'title' => array(
            'field' => 'title',
            'label' => 'lang:definition:title',
            'rules' => 'trim|htmlspecialchars|required|max_length[200]'
        ),
        'slug' => array(
            'field' => 'slug',
            'label' => 'lang:definition:slug',
            'rules' => 'trim|htmlspecialchars|required|max_length[200]'
        ),
        'description' => array(
            'field' => 'description',
            'label' => 'lang:definition:description',
            'rules' => 'trim|htmlspecialchars'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->load->model('definition_m');
        $this->lang->load('definition');
    }

    /**
     * The index function
     * @Description: This is index function
     * @Parameter:
     * @Return: null
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    public function index()
    {
        $base_where = array();
        if ($this->input->post('f_keywords'))
        {
            $base_where['title'] = $this->input->post('f_keywords');
        }

        // Create pagination links
        $total_rows = $this->definition_m->count_by($base_where);
        $pagination = create_pagination('admin/definition/index', $total_rows);

        $post = $this->definition_m->get_definition_list($pagination['limit'], $pagination['offset'], $base_where);
        $this->input->is_ajax_request() and $this->template->set_layout(false);

        //print_r($post);
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
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    public function create(){
        $post = new stdClass();

        // Get the blog stream.
        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('definition', 'definitions');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        // Get the validation for our custom blog fields.
        $definition_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $definition_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $extra = array(
                'title'            => $this->input->post('title'),
                'slug'             => $this->input->post('slug'),
                'description'      => $this->input->post('description'),
                'system'           => 0,
                'created'		   => date('Y-m-d H:i:s', now()),
                'created_by'       => $this->current_user->id
            );

            if ($id = $this->streams->entries->insert_entry($_POST, 'definition', 'definitions', array('created'), $extra)) {
                $this->pyrocache->delete_all('definition_m');
                $this->session->set_flashdata('success', sprintf($this->lang->line('definition:post_add_success'), $this->input->post('title')));
                Events::trigger('post_created', $id);
            } else {
                $this->session->set_flashdata('error', lang('definition:post_add_error'));
            }

            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/definition')
                : redirect('admin/definition/edit/'.$id);

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
            ->title($this->module_details['name'], lang('definition:create_title'))
            ->append_metadata($this->load->view('fragments/wysiwyg', array(), true))
            ->append_js('jquery/jquery.tagsinput.js')
            ->append_js('module::definition_form.js')
            ->append_css('jquery/jquery.tagsinput.css')
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values))
            ->set('post', $post)
            ->build('admin/form');
    }


    /**
     * The edit function
     * @Description: This is edit function
     * @Parameter:
     *      1. $id int The ID of the definition post to edit
     * @Return: null
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    public function edit($id = 0){
        $id or redirect('admin/definition');
        $post = $this->definition_m->get($id);

        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('definition', 'definitions');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        $definition_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $definition_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $author_id = empty($post->created_by) ? $this->current_user->id : $post->created_by;

            $extra = array(
                'title'            => $this->input->post('title'),
                'description'      => $this->input->post('description'),
                'updated'		   => date('Y-m-d H:i:s', now()),
                'created_by'       => $author_id
            );

            if ($this->streams->entries->update_entry($id, $_POST, 'definition', 'definitions', array('updated'), $extra)) {
                $this->session->set_flashdata(array('success' => sprintf(lang('definition:edit_success'), $this->input->post('title'))));
                Events::trigger('post_updated', $id);
            } else {
                $this->session->set_flashdata('error', lang('definition:edit_error'));
            }

            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/definition')
                : redirect('admin/definition/edit/'.$id);
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
            ->title($this->module_details['name'], sprintf(lang('definition:edit_title_label'), $post->title))
            ->append_metadata($this->load->view('fragments/wysiwyg', array(), true))
            ->append_js('jquery/jquery.tagsinput.js')
            ->append_css('jquery/jquery.tagsinput.css')
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values, $post->id))
            ->set('post', $post)
            ->build('admin/form');
    }

    /**
     * The delete function
     * @Description: This is delete function
     * @Parameter:
     *      1. $id int The ID of the definition post to delete
     * @Return: null
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    public function delete($id = 0) {
        $ids = ($id) ? array($id) : $this->input->post('action_to');
        if (!empty($ids)){
            $post_titles = array();
            $deleted_ids = array();
            foreach ($ids as $id){
                if ($post = $this->definition_m->get($id)){
                    if($post->system == 0){
                        if ($this->definition_m->delete($id)){
                            $this->pyrocache->delete('definition_m');
                            $post_titles[] = $post->title;
                            $deleted_ids[] = $id;
                        }
                    }
                }
            }
            Events::trigger('post_deleted', $deleted_ids);
        }

        if (!empty($post_titles)){
            if (count($post_titles) == 1) {
                $this->session->set_flashdata('success', sprintf($this->lang->line('definition:delete_success'), $post_titles[0]));
            } else {
                $this->session->set_flashdata('success', sprintf($this->lang->line('definition:mass_delete_success'), implode('", "', $post_titles)));
            }
        } else {
            $this->session->set_flashdata('notice', lang('definition:delete_error'));
        }
        redirect('admin/definition');
    }

    /**
     * The action function
     * @Description: This is action function
     * @Parameter:
     * @Return: null
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    public function action()
    {
        switch ($this->input->post('btnAction'))
        {
            case 'delete':
                $this->delete();
                break;

            default:
                redirect('admin/definition');
                break;
        }
    }



} 