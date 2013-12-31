<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Question
 * @Description: This is a controller class for Question table
 * @Author: Pxthanh
 * @Company: Framgia
 * @Date: 11/21/13
 * @Update: 11/21/13
 */

class Department extends Public_Controller {

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
            'rules' => 'integer|required|less_than[1000000000]'
        )
    );

    public function __construct(){
        parent::__construct();
        if(!check_user_permission($this->current_user, $this->module, $this->permissions)) redirect();
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        $this->load->driver('Streams');
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->stream = $this->streams_m->get_stream('department', true, 'departments');
        $this->load->model(array('department_m', 'company_m'));
        $this->lang->load('department');

        if ( ! $companies = $this->cache->get('companies')){
            $companies = array(
                ''  => lang('department:select_company')
            );
            $rows = $this->company_m->get_all();
            foreach($rows as $row){
                $companies[$row->id] = $row->title;
            }
            $this->cache->save('companies', $companies, 300);
        }
    }

    /**
     * The index function
     * @Description: This is index function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function index(){
        $where = "";
        if ($this->input->post('f_keywords')) {
            $where .= "`title` LIKE '%".$this->input->post('f_keywords')."%' ";
        }

        // Get the latest department posts
        $posts = $this->streams->entries->get_entries(array(
            'stream'		=> 'department',
            'namespace'		=> 'departments',
            'limit'         => Settings::get('records_per_page'),
            'where'		    => $where,
            'paginate'		=> 'yes',
            'pag_base'		=> site_url('department/page'),
            'pag_segment'   => 3
        ));

        // Process posts
        foreach ($posts['entries'] as &$post) {
            $this->_process_post($post);
        }
        $meta = $this->_posts_metadata($posts['entries']);
        $this->input->is_ajax_request() and $this->template->set_layout(false);

        $this->template
            ->title($this->module_details['name'])
            ->set_breadcrumb(lang('department:department_title'))
            ->set('breadcrumb_title', $this->module_details['name'])
            ->set_metadata('og:title', $this->module_details['name'], 'og')
            ->set_metadata('og:type', 'department', 'og')
            ->set_metadata('og:url', current_url(), 'og')
            ->set_metadata('og:description', $meta['description'], 'og')
            ->set_metadata('description', $meta['description'])
            ->set_metadata('keywords', $meta['keywords'])
            ->append_js('module::department_form.js')
            ->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
            ->set('posts', $posts['entries'])
            ->set('pagination', $posts['pagination'])
            ->set('companies', $this->cache->get('companies'));

        $this->input->is_ajax_request()
            ? $this->template->build('tables/posts')
            : $this->template->build('index');
    }

    /**
     * The process function
     * @Description: This is process function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function process(){
        if(!$this->input->is_ajax_request()) redirect('department');
        if($this->input->post('action') == 'create'){
            $this->create();
        }else if($this->input->post('action') == 'edit'){
            $this->edit();
        }
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
            Events::trigger('department_deleted', $deleted_ids);
        }
        $message = array();
        if (!empty($post_names)){
            if (count($post_names) == 1) {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $post_names[0], lang('department:delete_success'));
            } else {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", implode('", "', $post_names), lang('department:mass_delete_success'));
            }
        } else {
            $message['status']  = 'warning';
            $message['message']  = lang('department:delete_error');
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
    public function action()
    {
        switch ($this->input->post('btnAction'))
        {
            case 'delete':
                $this->delete();
                break;
            default:
                echo '';
                break;
        }
    }

    /**
     * The get_department_by_id function
     * @Description: This is edit function
     * @Parameter:
     *      1. $id int The ID of the department post to get
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function get_department_by_id($id){
        if(!$this->input->is_ajax_request()) redirect('department');
        if($id != null && $id != ""){
            $item = $this->department_m->get($id);
            echo json_encode($item);
        }else{
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
    private function create(){
        $message = array();
        $stream = $this->streams->streams->get_stream('department', 'departments');
        // Get the validation for our custom blog fields.
        $department_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $department_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run()){
            $extra = array(
                'title'            => $this->input->post('title'),
                'description'      => $this->input->post('description'),
                'company_id'       => $this->input->post('company_id'),
                'created'		   => date('Y-m-d H:i:s', now()),
                'created_by'       => $this->current_user->id
            );

            if ($id = $this->streams->entries->insert_entry($_POST, 'department', 'departments', array('created'), $extra)) {
                $this->pyrocache->delete_all('department_m');
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $this->input->post('title'), lang('department:post_add_success'));
                Events::trigger('department_created', $id);
            } else {
                $message['status']  = 'error';
                $message['message']  = lang('department:post_add_error');
            }
        } else {
            $message['status']  = 'error';
            $message['message']  = lang('department:validate_error');
        }
        echo json_encode($message);
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
    private function edit(){
        $id = $this->input->post('row_edit_id');
        $post = $this->department_m->get($id);
        $message = array();
        // Get all company
        $stream = $this->streams->streams->get_stream('department', 'departments');
        $department_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $department_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run()){
            $author_id = empty($post->created_by) ? $this->current_user->id : $post->created_by;
            $extra = array(
                'title'            => $this->input->post('title'),
                'description'      => $this->input->post('description'),
                'company_id'       => $this->input->post('company_id'),
                'updated'		   => date('Y-m-d H:i:s', now()),
                'created_by'       => $author_id
            );

            if ($this->streams->entries->update_entry($id, $_POST, 'department', 'departments', array('updated'), $extra)) {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $this->input->post('title'), lang('department:edit_success'));
                Events::trigger('department_updated', $id);
            } else {
                $message['status']  = 'error';
                $message['message']  = lang('department:edit_error');
            }
        } else {
            $message['status']  = 'error';
            $message['message']  = lang('department:validate_error');
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
        $post['company'] = $this->company_m->get($post['company_id'])->title;
        $post['url_edit'] = site_url('department/edit/'.$post['id']);
        $post['url_delete'] = site_url('department/delete/'.$post['id']);
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
            foreach ($posts as &$post){
                $keywords[] = $post['title'];
                $description[] = $post['description'];
            }
        }

        return array(
            'keywords' => implode(', ', $keywords),
            'description' => implode(', ', $description)
        );
    }
} 