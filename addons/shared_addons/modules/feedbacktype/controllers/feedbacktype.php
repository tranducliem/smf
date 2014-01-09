<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Class Feedbacktype
 * @Description: This is a controller class for feedback type manager
 * @Author: DaoLM
 * @Company: Framgia
 * @Date: 11/30/13
 * @Update: 11/30/13
 */
class Feedbacktype extends Public_Controller {
    
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
    
    public function __construct()
    {
        parent::__construct();
        if(!check_user_permission($this->current_user, $this->module, $this->permissions)) redirect();
        $this->template->set_layout('feedback_layout.html');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        $this->load->driver('Streams');
        $this->load->library(array('keywords/keywords', 'form_validation'));
        $this->stream = $this->streams_m->get_stream('feedback_manager_type', true, 'feedback_manager_types');
        $this->load->model('feedbacktype_m');
        $this->load->model('feedback_manager_m');
        $this->lang->load('feedbacktype');
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
        if ($this->input->post('f_keywords')) {
            $where .= "`title` LIKE '%".$this->input->post('f_keywords')."%' ";
        }
        
        $posts = $this->streams->entries->get_entries(array(
            'stream'		=> 'feedback_manager_type',
            'namespace'		=> 'feedback_manager_types',
            'limit'             => Settings::get('records_per_page'),
            'where'             => $where,
            'paginate'		=> 'yes',
            'pag_base'		=> site_url('feedbacktype/page'),
            'pag_segment'       => 3
        ));
        
        foreach ($posts['entries'] as &$post) {
            $this->_process_post($post);
        }
        $meta = $this->_posts_metadata($posts['entries']);
        $this->input->is_ajax_request() and $this->template->set_layout(false);
        
        $this->template
            ->title($this->module_details['name'])
            ->set_breadcrumb(lang('feedbacktype:title'))
            ->set('breadcrumb_title', $this->module_details['name'])
            ->set_metadata('og:title', $this->module_details['name'], 'og')
            ->set_metadata('og:type', 'feedbacktype', 'og')
            ->set_metadata('og:url', current_url(), 'og')
            ->set_metadata('og:description', $meta['description'], 'og')
            ->set_metadata('description', $meta['description'])
            ->set_metadata('keywords', $meta['keywords'])
            ->append_js('module::fbtype_form.js')
            ->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
            ->set('posts', $posts['entries'])
            ->set('pagination', $posts['pagination']);
        
        $this->input->is_ajax_request()
            ? $this->template->build('tables/posts')
            : $this->template->build('index');
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
            $post_names = array();
            $deleted_ids = array();
            foreach ($ids as $id){
                if ($post = $this->feedbacktype_m->get($id)){
                    // Check references data  
                    $check = $this->check_references_by_typeid($id);
                    if($check != true)
                    {
                        if ($this->feedbacktype_m->delete($id)){
                            $this->pyrocache->delete('feedbacktype_m');
                            $post_names[] = $post->title;
                            $deleted_ids[] = $id;
                        }
                    }
                }
            }
            Events::trigger('feedback_type_deleted', $deleted_ids);
        }
        $message = array();
        if (!empty($post_names)){
            if (count($post_names) == 1) {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $post_names[0], lang('feedbacktype:delete_success'));
            } else {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", implode('", "', $post_names), lang('feedbacktype:mass_delete_success'));
            }
        } else {
            $message['status']  = 'warning';
            $message['message']  = lang('feedbacktype:delete_error');
        }
        echo json_encode($message);
    }
    
    /**
     * The check_references_by_typeid function
     * @Description: Check references data 
     * @Parameter:
     *      1. $var int The ID of the feedbackType to check
     * @Return: null
     * @Date: 1/2/14
     * @Update: 1/2/14
     */
    private function check_references_by_typeid($var) {
        $base_where['type_id'] = $var;
        $references = $this->feedback_manager_m->get_many_by($base_where);
        if(count($references) > 0) {
            return true;
        }else { 
            return false;
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
    public function process(){
        if(!$this->input->is_ajax_request()) redirect('feedbacktype');
        if($this->input->post('action') == 'create'){
            $this->create();
        }else if($this->input->post('action') == 'edit'){
            $this->edit();
        }
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
     * The get_fbtype_by_id function
     * @Description: This is edit function
     * @Parameter:
     *      1. $id int The ID of the fbtype to get
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function get_fbtype_by_id($id){
        if(!$this->input->is_ajax_request()) redirect('team');
        if($id != null && $id != ""){
            $item = $this->feedbacktype_m->get($id);
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
        $stream = $this->streams->streams->get_stream('feedback_manager_type', 'feedback_manager_types');
        // Get the validation for our custom blog fields.
        $team_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $team_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run()){
            $extra = array(
                'title'            => $this->input->post('title'),
                'description'      => $this->input->post('description'),
                'created'          => date('Y-m-d H:i:s', now()),
                'created_by'       => $this->current_user->id
            );

            if ($id = $this->streams->entries->insert_entry($_POST, 'feedback_manager_type', 'feedback_manager_types', array('created'), $extra)) {
                $this->pyrocache->delete_all('feedbacktype_m');
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $this->input->post('title'), lang('feedbacktype:add_success'));
                Events::trigger('feedback_type_created', $id);
            } else {
                $message['status']  = 'error';
                $message['message']  = lang('feedbacktype:feedbacktype_add_error');
            }
        } else {
            $message['status']  = 'error';
            $message['message']  = lang('feedbacktype:validate_error');
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
        $post = $this->feedbacktype_m->get($id);
        $message = array();
        // Get all company
        $stream = $this->streams->streams->get_stream('feedback_manager_type', 'feedback_manager_types');
        $team_validation = $this->streams->streams->validation_array($stream->stream_slug, $stream->stream_namespace, 'new');
        $rules = array_merge($this->validation_rules, $team_validation);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run()){
            $author_id = empty($post->created_by) ? $this->current_user->id : $post->created_by;
            $extra = array(
                'title'            => $this->input->post('title'),
                'description'      => $this->input->post('description'),
                'updated'          => date('Y-m-d H:i:s', now()),
                'created_by'       => $author_id
            );

            if ($this->streams->entries->update_entry($id, $_POST, 'feedback_manager_type', 'feedback_manager_types', array('updated'), $extra)) {
                $message['status']  = 'success';
                $message['message']  = str_replace("%s", $this->input->post('title'), lang('feedbacktype:edit_success'));
                Events::trigger('feedback_type_updated', $id);
            } else {
                $message['status']  = 'error';
                $message['message']  = lang('feedbacktype:edit_error');
            }
        } else {
            $message['status']  = 'error';
            $message['message']  = lang('feedbacktype:validate_error');
        }
        echo json_encode($message);
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
        $post['url_edit'] = site_url('feedbacktype/edit/'.$post['id']);
        $post['url_delete'] = site_url('feedbacktype/delete/'.$post['id']);
    }
    
     /**
     * The posts_metadata function
     * @Description: This is posts_metadata function
     * @Parameter:
     * @Return: null
     * @Date: 11/30/13
     * @Update: 11/30/13
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
