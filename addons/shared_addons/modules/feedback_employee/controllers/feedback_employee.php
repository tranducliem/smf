<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Admin
 * @Description: This is class admin
 * @Author: HoangThiTuanDung
 * @Company: framgia
 * @Date: 12/25/13
 * @Update:
 */

class Feedback_employee extends Public_Controller {

    protected $validation_rules = array(
        'title'         => array(
            'field'     => 'title',
            'label'     => 'lang:feedback_employee:title',
            'rules'     => 'trim|htmlspecialchars|required|max_length[200]'
        ),
        'description'   => array(
            'field'     => 'description',
            'label'     => 'lang:feedback_employee:description',
            'rules'     => 'trim|htmlspecialchars|'
        ),
        'date'    => array(
            'field'     => 'date',
            'label'     => 'lang:feedback_employee:date',
            'rules'     => ''
        ),
        'apply_id'    => array(
            'field'     => 'apply_id',
            'label'     => 'lang:feedback_employee:apply_id',
            'rules'     => 'required'
        ),
        'department_id'    => array(
            'field'     => 'department_id',
            'label'     => 'lang:feedback_employee:department_id',
            'rules'     => 'required'
        ),
        'status'    => array(
            'field'     => 'status',
            'label'     => 'lang:feedback_employee:status',
            'rules'     => ''
        ),
    );

    public function __construct(){
        parent::__construct();
        if(!check_user_permission($this->current_user, $this->module, $this->permissions)) redirect();
        $this->template->set_layout('feedback_layout.html');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        $this->load->driver('Streams');
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->stream = $this->streams_m->get_stream('feedback_employee', true, 'feedback_employees');
        $this->load->model(array('feedback_employee_m', 'users/user_m', 'department/department_m'));
        $this->lang->load('feedback_employee');


        /*if ( ! $users = $this->cache->get('users')){
            $users = array(
                ''  => lang('feedback_employee:select_apply')
            );
            $rows = $this->user_m->get_all();
            foreach($rows as $row){
                $users[$row->id] = $row->username;
            }
            $this->cache->save('users', $users, 300);
        }*/

         // Get apply list from cached to bidding select list
         $apply = array(''  => lang('feedback_employee:select_apply'));
         $users = $this->streams->entries->get_entries(array('stream' => 'profiles', 'namespace' => 'users'));
         foreach ($users['entries'] as $post) {
             $apply[$post['user_id']] = get_username_by_id($post['user_id']);
         }
         $this->template->set('users', $apply);




        // if ( ! $departments = $this->cache->get('departments')){
        //     $departments = array(
        //         ''  => lang('feedback_employee:select_department')
        //     );
        //     $rows = $this->department_m->get_all();
        //     foreach($rows as $row){
        //         $departments[$row->id] = $row->title;
        //     }
        //     $this->cache->save('departments', $departments, 300);
        // }

        $department = array(''  => lang('feedback_employee:select_department'));
        $departments = $this->streams->entries->get_entries(array('stream' => 'department', 'namespace' => 'departments'));
        foreach ($departments['entries'] as $post) {
            $department[$post['id']] = $post['title'];
        }
        $this->template->set('departments', $department);
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

        // Get the latest team posts
        $posts = $this->streams->entries->get_entries(array(
            'stream'		=> 'feedback_employee',
            'namespace'		=> 'feedback_employees',
            'limit'         => Settings::get('records_per_page'),
            'where'		    => $where,
            'paginate'		=> 'yes',
            'pag_base'		=> site_url('feedback_employee/page'),
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
            ->set_breadcrumb(lang('feedback_employee:feedback_employee_title'))
            ->set('breadcrumb_title', $this->module_details['name'])
            ->set_metadata('og:title', $this->module_details['name'], 'og')
            ->set_metadata('og:type', 'feedback_employee', 'og')
            ->set_metadata('og:url', current_url(), 'og')
            ->set_metadata('og:description', $meta['description'], 'og')
            ->set_metadata('description', $meta['description'])
            ->set_metadata('keywords', $meta['keywords'])
            ->append_js('module::feedback_employee_form.js')
            ->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
            ->set('posts', $posts['entries'])
            ->set('pagination', $posts['pagination']);
            //->set('users', $this->cache->get('users'));
            // ->set('departments', $this->cache->get('departments'));

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
        if(!$this->input->is_ajax_request()) redirect('feedback_employee');
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
                if ($post = $this->feedback_employee_m->get($id)){
                    if ($this->feedback_employee_m->delete($id)){
                        $this->pyrocache->delete('feedback_employee_m');
                        $post_names[] = $post->title;
                        $deleted_ids[] = $id;
                    }
                }
            }
            Events::trigger('feedback_employee_deleted', $deleted_ids);
        }
        $message = array();
        if (!empty($post_names)){
            if (count($post_names) == 1) {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $post_names[0], lang('feedback_employee:delete_success'));
            } else {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", implode('", "', $post_names), lang('feedback_employee:mass_delete_success'));
            }
        } else {
            $message['status']  = 'warning';
            $message['message']  = lang('feedback_employee:delete_error');
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
     * The get_team_by_id function
     * @Description: This is edit function
     * @Parameter:
     *      1. $id int The ID of the team post to get
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function get_feedback_employee_by_id($id){
        if(!$this->input->is_ajax_request()) redirect('feedback_employee');
        if($id != null && $id != ""){
            $item = $this->feedback_employee_m->get($id);
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
        $stream = $this->streams->streams->get_stream('feedback_employee', 'feedback_employees');
        // Get the validation for our custom blog fields.
        $feedback_employee_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $feedback_employee_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run()){
            $extra = array(
                'title'             => $this->input->post('title'),
                'description'       => $this->input->post('description'),
                'date'              => $this->input->post('date'),
                'apply_id'          => $this->input->post('apply_id'),
                'department_id'     => $this->input->post('department_id'),
                'status'            => $this->input->post('status'),
                'created'		    => date('Y-m-d H:i:s', now()),
                'created_by'        => $this->current_user->id
            );


            if ($id = $this->streams->entries->insert_entry($_POST, 'feedback_employee', 'feedback_employees', array('created'), $extra)) {
                $this->pyrocache->delete_all('feedback_employee_m');
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $this->input->post('title'), lang('feedback_employee:post_add_success'));
                Events::trigger('feedback_employee_created', $id);
            } else {
                $message['status']  = 'error';
                $message['message']  = lang('feedback_employee:post_add_error');
            }
        } else {
            $message['status']  = 'error';
            $message['message']  = lang('feedback_employee:validate_error');
        }
        echo json_encode($message);
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
    private function edit(){
        $id = $this->input->post('row_edit_id');
        $post = $this->feedback_employee_m->get($id);
        $message = array();
        // Get all company
        $stream = $this->streams->streams->get_stream('feedback_employee', 'feedback_employees');
        $feedback_employee_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $feedback_employee_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run()){
            $author_id = empty($post->created_by) ? $this->current_user->id : $post->created_by;
            $extra = array(
                'title'             => $this->input->post('title'),
                'description'       => $this->input->post('description'),
                'date'              => $this->input->post('date'),
                'apply_id'          => $this->input->post('apply_id'),
                'department_id'     => $this->input->post('department_id'),
                'status'            => $this->input->post('status'),
                'updated'		    => date('Y-m-d H:i:s', now()),
                'created_by'        => $author_id
            );

            if ($this->streams->entries->update_entry($id, $_POST, 'feedback_employee', 'feedback_employees', array('updated'), $extra)) {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $this->input->post('title'), lang('feedback_employee:edit_success'));
                Events::trigger('feedback_employee_updated', $id);
            } else {
                $message['status']  = 'error';
                $message['message']  = lang('feedback_employee:edit_error');
            }
        } else {
            $message['status']  = 'error';
            $message['message']  = lang('feedback_employee:validate_error');
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
        $post['apply'] = $this->user_m->get_by(array('id'=>$post['apply_id']))->username;
        $post['department'] = $this->department_m->get_by(array('id'=>$post['department_id']))->title;
        $post['url_edit'] = site_url('feedback_employee/edit/'.$post['id']);
        $post['url_delete'] = site_url('feedback_employee/delete/'.$post['id']);
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