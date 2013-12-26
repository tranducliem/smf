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
            'label' => 'lang:team:title',
            'rules' => 'trim|htmlspecialchars|required|max_length[150]'
        ),
        'description' => array(
            'field' => 'description',
            'label' => 'lang:team:description',
            'rules' => 'trim|htmlspecialchars|max_length[255]'
        ),
        'company_id' => array(
            'field' => 'company_id',
            'label' => 'lang:team:company_id',
            'rules' => 'integer|less_than[1000000000]'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->load->model('team_m');
        $this->lang->load('team');
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
        $total_rows = $this->team_m->count_by($base_where);
        $pagination = create_pagination('admin/team/index', $total_rows);

        $post = $this->team_m->get_team_list($pagination['limit'], $pagination['offset'], $base_where);
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
        $company = $this->team_m->get_company_list();
        // Get the team stream.
        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('team', 'teams');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        // Get the validation for our custom blog fields.
        $team_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $team_validation);
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

            if ($id = $this->streams->entries->insert_entry($_POST, 'team', 'teams', array('created'), $extra)) {
                $this->pyrocache->delete_all('team_m');
                $this->session->set_flashdata('success', sprintf($this->lang->line('team:post_add_success'), $this->input->post('title')));
                Events::trigger('post_created', $id);
            } else {
                $this->session->set_flashdata('error', lang('team:post_add_error'));
            }
          
            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/team')
                : redirect('admin/team/edit/'.$id);
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
            ->title($this->module_details['name'], lang('team:create_title'))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values))
            ->set('company',$company)
            ->set('post', $post)
            ->build('admin/form');
    }


    /**
     * The edit function
     * @Description: This is edit function
     * @Parameter:
     *      1. $id int The ID of the team post to edit
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function edit($id = 0){
        $id or redirect('admin/team');
        $post = $this->team_m->get($id);
        // Get all company
        $company = $this->team_m->get_company_list();
        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('team', 'teams');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        $team_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $team_validation);
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

            if ($this->streams->entries->update_entry($id, $_POST, 'team', 'teams', array('updated'), $extra)) {
                $this->session->set_flashdata(array('success' => sprintf(lang('team:edit_success'), $this->input->post('title'))));
                Events::trigger('post_updated', $id);
            } else {
                $this->session->set_flashdata('error', lang('team:edit_error'));
            }

            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/team')
                : redirect('admin/team/edit/'.$id);
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
            ->title($this->module_details['name'], sprintf(lang('team:edit_name_label'), $post->title))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values, $post->id))
            ->set('post', $post)
            ->set('company',$company)
            ->build('admin/form');
    }

    /**
     * The delete function
     * @Description: This is delete function
     * @Parameter:
     *      1. $id int The ID of the team post to delete
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
                if ($post = $this->team_m->get($id)){
                    if ($this->team_m->delete($id)){
                        $this->pyrocache->delete('team_m');
                        $post_names[] = $post->title;
                        $deleted_ids[] = $id;
                    }
                }
            }
            Events::trigger('post_deleted', $deleted_ids);
        }

        if (!empty($post_names)){
            if (count($post_names) == 1) {
                $this->session->set_flashdata('success', sprintf($this->lang->line('team:delete_success'), $post_names[0]));
            } else {
                $this->session->set_flashdata('success', sprintf($this->lang->line('team:mass_delete_success'), implode('", "', $post_names)));
            }
        } else {
            $this->session->set_flashdata('notice', lang('blog:delete_error'));
        }
        redirect('admin/team');
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
                redirect('admin/team');
                break;
        }
    }
} 