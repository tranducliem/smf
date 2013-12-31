<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Feedbackuser
 * @Description: This is a controller class for Feedback user manager table
 * @Author: DaoLM
 * @Company: Framgia
 * @Date: 11/30/13
 * @Update: 11/30/13
 */

class Feedbackuser extends Public_Controller {
    
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
        )
    );
    
    public function __construct()
    {
        parent::__construct();
        if(!check_user_permission($this->current_user, $this->module, $this->permissions)) redirect();
        $this->template->set_layout('feedback_layout.html');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        $this->load->driver('Streams');
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->stream = $this->streams_m->get_stream('feedback_manager_user', true, 'feedback_manager_users');
        $this->load->model(array('feedbackuser_m', 'feedbackmanager_m'));
        $this->load->model('user_m');
        $this->lang->load('feedbackuser');
        
        if (!$feedbackmanagers = $this->cache->get('feedbackmanagers')){
            $feedbackmanagers = array(
                ''  => lang('feedbackuser:select_feedback')
            );
            $rows = $this->feedbackmanager_m->get_all();
            foreach($rows as $row){
                $feedbackmanagers[$row->id] = $row->title;
            }
            $this->cache->save('feedbackmanagers', $feedbackmanagers, 300);
        }
        
         if (!$users = $this->cache->get('users')){
            $users = array(
                ''  => lang('feedbackuser:select_user')
            );
            $rows = $this->user_m->get_all();
            foreach($rows as $row){
                $users[$row->id] = $row->email;
            }
            $this->cache->save('users', $users, 300);
        }
    }
    
     /**
     * The index function
     * @Description: This is index function
     * @Parameter:
     * @Return: null
     * @Date: 11/30/13
     * @Update: 11/30/13
     */
    public function index(){
        $where = "";
        $base_where = array();
        if ($this->input->post('f_keywords')) {
            $base_where['username'] = $this->input->post('f_keywords');
            $users = $this->user_m->get_users_list('', '', $base_where);
            if(count($users) > 0) {
                $ids = "";
                foreach ($users as $user) {
                    $ids = $ids . $user->id . ",";
                }
                $ids = rtrim($ids, ",");
                $where .= "`user_id` IN (".$ids.")";
            } else {
                $where .= "`user_id` = -1";
            }
        }

        // Get the latest team posts
        $posts = $this->streams->entries->get_entries(array(
            'stream'		=> 'feedback_manager_user',
            'namespace'		=> 'feedback_manager_users',
            'limit'             => Settings::get('records_per_page'),
            'where'		=> $where,
            'paginate'		=> 'yes',
            'pag_base'		=> site_url('feedbackuser/page'),
            'pag_segment'       => 3
        ));

        // Process posts
        foreach ($posts['entries'] as &$post) {
            $this->_process_post($post);
        }
        $this->input->is_ajax_request() and $this->template->set_layout(false);
        $this->template
            ->title($this->module_details['name'])
            ->set_breadcrumb(lang('feedbackuser:types_title'))
            ->append_js('module::fbuser_form.js')
            ->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
            ->set('posts', $posts['entries'])
            ->set('pagination', $posts['pagination'])
            ->set('feedbackmanagers', $this->cache->get('feedbackmanagers'))
            ->set('users', $this->cache->get('users'));

        $this->input->is_ajax_request()
            ? $this->template->build('tables/posts')
            : $this->template->build('index');
    }
    
    /**
     * The create function
     * @Description: This is create function
     * @Parameter:
     * @Return: null
     * @Date: 11/30/13
     * @Update: 11/30/13
     */
    private function create(){
        $message = array();
        $stream = $this->streams->streams->get_stream('feedback_manager_user', 'feedback_manager_users');
        // Get the validation for our custom blog fields.
        $team_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $team_validation);
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()){
            $extra = array(
                'user_id'               => $this->input->post('user_id'),
                'feedback_manager_id '  => $this->input->post('feedback_manager_id'),
                'status'                => $this->input->post('status'),
                'date'                  => date('Y-m-d H:i:s', now()),
                'created'		=> date('Y-m-d H:i:s', now()),
                'created_by'            => $this->current_user->id
            );

            if ($id = $this->streams->entries->insert_entry($_POST, 'feedback_manager_user', 'feedback_manager_users', array('created'), $extra)) {
                $this->pyrocache->delete_all('feedbackuser_m');
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $this->input->post('title'), lang('feedbackuser:feedbackuser_add_success'));
                Events::trigger('feedbackuser_add_created', $id);
            } else {
                $message['status']  = 'error';
                $message['message']  = lang('feedbackuser:feedbackuser_add_error');
            }
        } else {
            $message['status']  = 'error';
            $message['message']  = lang('feedbackuser:validate_error');
        }
        echo json_encode($message);
    }
    
    /**
     * The edit function
     * @Description: This is edit function
     * @Parameter:
     *      1. $id int The ID of the feedbacktype to edit
     * @Return: null
     * @Date: 11/30/13
     * @Update: 11/30/13
     */
    private function edit(){
        $id = $this->input->post('row_edit_id');
        $post = $this->feedbackuser_m->get($id);
        $message = array();
        // Get all company
        $stream = $this->streams->streams->get_stream('feedback_manager_user', 'feedback_manager_users');
        $team_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $team_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run()){
            $author_id = empty($post->created_by) ? $this->current_user->id : $post->created_by;
            $extra = array(
                'user_id'               => $this->input->post('user_id'),
                'feedback_manager_id '  => $this->input->post('feedback_manager_id'),
                'status'                => $this->input->post('status'),
                'date'                  => date('Y-m-d H:i:s', now()),
                'created'		=> date('Y-m-d H:i:s', now()),
                'created_by'            => $this->current_user->id
            );

            if ($this->streams->entries->update_entry($id, $_POST, 'feedback_manager_user', 'feedback_manager_users', array('updated'), $extra)) {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $this->input->post('title'), lang('feedbackuser:edit_success'));
                Events::trigger('feedback_type_updated', $id);
            } else {
                $message['status']  = 'error';
                $message['message']  = lang('feedbackuser:edit_error');
            }
        } else {
            $message['status']  = 'error';
            $message['message']  = lang('feedbackuser:validate_error');
        }
        echo json_encode($message);
    }
    
    /**
     * The get_fbuser_by_id function
     * @Description: This is edit function
     * @Parameter:
     *      1. $id int The ID of the fbuser to get
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function get_fbuser_by_id($id){
        if(!$this->input->is_ajax_request()) redirect('feedbackuser');
        if($id != null && $id != ""){
            $item = $this->feedbackuser_m->get($id);
            echo json_encode($item);
        }else{
            echo "";
        }
    }
    
    /**
     * The delete function
     * @Description: This is delete function
     * @Parameter:
     *      1. $id int The ID of the feedbackType to delete
     * @Return: null
     * @Date: 11/30/13
     * @Update: 11/30/13
     */
    public function delete($id = 0) {
        $ids = ($id) ? array($id) : $this->input->post('action_to');
        if (!empty($ids)){
            $deleted_ids = array();
            foreach ($ids as $id){
                if ($post = $this->feedbackuser_m->get($id)){
                    if ($this->feedbackuser_m->delete($id)){
                        $this->pyrocache->delete('feedbacktype_m');
                        $deleted_ids[] = $id;
                    }
                }
            }
            Events::trigger('feedback_type_deleted', $deleted_ids);
        }
        $message = array();
        if (!empty($deleted_ids)){
            if (count($deleted_ids) == 1) {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $deleted_ids[0], lang('feedbackuser:delete_success'));
            } else {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", implode('", "', $deleted_ids), lang('feedbackuser:mass_delete_success'));
            }
        } else {
            $message['status']  = 'warning';
            $message['message']  = lang('feedbackuser:delete_error');
        }
        echo json_encode($message);
    }
    
    /**
     * The action function
     * @Description: This is action function
     * @Parameter:
     * @Return: null
     * @Date: 11/30/13
     * @Update: 11/30/13
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
     * The process function
     * @Description: This is process function
     * @Parameter:
     * @Return: null
     * @Date: 11/30/13
     * @Update: 11/30/13
     */
    public function process() {
        if(!$this->input->is_ajax_request()) redirect('feedbackuser');
        if($this->input->post('action') == 'create'){
            $this->create();
        }else if($this->input->post('action') == 'edit'){
            $this->edit();
        }
    }
    
    /**
     * The process_post function
     * @Description: This is process_post function
     * @Parameter:
     * @Return: null
     * @Date: 11/30/13
     * @Update: 11/30/13
     */
    private function _process_post(&$post) {
        $CI = & get_instance();
        $CI->load->model('users/user_m');
        $post['fbmanager'] = $this->feedbackmanager_m->get($post['feedback_manager_id'])->title;
        $post['username'] = $CI->user_m->get($post['user_id'])->username;
        $post['url_edit'] = site_url('feedbackuser/edit/'.$post['id']);
        $post['url_delete'] = site_url('feedbackuser/delete/'.$post['id']);
    }
}


