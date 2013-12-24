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
            'label' => 'lang:book:title',
            'rules' => 'trim|htmlspecialchars|required|max_length[200]'
        ),
        'author' => array(
            'field' => 'author',
            'label' => 'lang:book:author',
            'rules' => 'trim|htmlspecialchars|required|max_length[200]'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->load->model('book_m');
        $this->lang->load('book');
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
        $total_rows = $this->book_m->count_by($base_where);
        $pagination = create_pagination('admin/book/index', $total_rows);

        $post = $this->book_m->get_book_list($pagination['limit'], $pagination['offset'], $base_where);
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
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    public function create(){
        $post = new stdClass();

        // Get the blog stream.
        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('book', 'books');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        // Get the validation for our custom blog fields.
        $book_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $book_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $extra = array(
                'title'            => $this->input->post('title'),
                'author'           => $this->input->post('author'),
                'created'		   => date('Y-m-d H:i:s', now()),
                'created_by'        => $this->current_user->id
            );

            if ($id = $this->streams->entries->insert_entry($_POST, 'book', 'books', array('created'), $extra)) {
                $this->pyrocache->delete_all('book_m');
                $this->session->set_flashdata('success', sprintf($this->lang->line('book:post_add_success'), $this->input->post('title')));
                Events::trigger('post_created', $id);
            } else {
                $this->session->set_flashdata('error', lang('book:post_add_error'));
            }

            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/book')
                : redirect('admin/book/edit/'.$id);

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
            ->title($this->module_details['name'], lang('book:create_title'))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values))
            ->set('post', $post)
            ->build('admin/form');
    }


    /**
     * The edit function
     * @Description: This is edit function
     * @Parameter:
     *      1. $id int The ID of the book post to edit
     * @Return: null
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    public function edit($id = 0){
        $id or redirect('admin/book');
        $post = $this->book_m->get($id);

        $this->load->driver('Streams');
        $stream = $this->streams->streams->get_stream('book', 'books');
        $stream_fields = $this->streams_m->get_stream_fields($stream->id, $stream->stream_namespace);

        $book_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $book_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run())
        {
            $author_id = empty($post->created_by) ? $this->current_user->id : $post->created_by;

            $extra = array(
                'title'            => $this->input->post('title'),
                'author'           => $this->input->post('author'),
                'updated'		   => date('Y-m-d H:i:s', now()),
                'created_by'       => $author_id
            );

            if ($this->streams->entries->update_entry($id, $_POST, 'book', 'books', array('updated'), $extra)) {
                $this->session->set_flashdata(array('success' => sprintf(lang('book:edit_success'), $this->input->post('title'))));
                Events::trigger('post_updated', $id);
            } else {
                $this->session->set_flashdata('error', lang('book:edit_error'));
            }

            ($this->input->post('btnAction') == 'save_exit')
                ? redirect('admin/book')
                : redirect('admin/book/edit/'.$id);
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
            ->title($this->module_details['name'], sprintf(lang('book:edit_title_label'), $post->title))
            ->set('stream_fields', $this->streams->fields->get_stream_fields($stream->stream_slug, $stream->stream_namespace, $values, $post->id))
            ->set('post', $post)
            ->build('admin/form');
    }

    /**
     * The delete function
     * @Description: This is delete function
     * @Parameter:
     *      1. $id int The ID of the book post to delete
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
                if ($post = $this->book_m->get($id)){
                    if ($this->book_m->delete($id)){
                        $this->pyrocache->delete('book_m');
                        $post_titles[] = $post->title;
                        $deleted_ids[] = $id;
                    }
                }
            }
            Events::trigger('post_deleted', $deleted_ids);
        }

        if (!empty($post_titles)){
            if (count($post_titles) == 1) {
                $this->session->set_flashdata('success', sprintf($this->lang->line('book:delete_success'), $post_titles[0]));
            } else {
                $this->session->set_flashdata('success', sprintf($this->lang->line('book:mass_delete_success'), implode('", "', $post_titles)));
            }
        } else {
            $this->session->set_flashdata('notice', lang('book:delete_error'));
        }
        redirect('admin/book');
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
                redirect('admin/book');
                break;
        }
    }



} 