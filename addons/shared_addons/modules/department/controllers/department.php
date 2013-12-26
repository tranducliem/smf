<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Question
 * @Description: This is a controller class for Question table
 * @Author: Pxthanh
 * @Company: Framgia
 * @Date: 11/21/13
 * @Update: 11/21/13
 */

class Question extends Public_Controller {

    private $_data = array();

    public function __construct(){
        parent::__construct();
        $this->load->driver('Streams');
        $this->stream = $this->streams_m->get_stream('question', true, 'questions');
        $this->load->model('question_m');
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
        // Get the latest question posts
        $posts = $this->streams->entries->get_entries(array(
            'stream'		=> 'question',
            'namespace'		=> 'questions',
            'limit'             => Settings::get('records_per_page'),
            'where'		=> "",
            'paginate'		=> 'yes',
            'pag_base'		=> site_url('question/page'),
            'pag_segment'       => 3
        ));

        // Process posts
        foreach ($posts['entries'] as &$post) {
            $this->_process_post($post);
        }
        $meta = $this->_posts_metadata($posts['entries']);

        $this->template
            ->title($this->module_details['name'])
            ->set_breadcrumb(lang('question:question_title'))
            ->set_metadata('og:title', $this->module_details['name'], 'og')
            ->set_metadata('og:type', 'question', 'og')
            ->set_metadata('og:url', current_url(), 'og')
            ->set_metadata('og:description', $meta['description'], 'og')
            ->set_metadata('description', $meta['description'])
            ->set_metadata('keywords', $meta['keywords'])
            ->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
            ->set('posts', $posts['entries'])
            ->set('pagination', $posts['pagination'])
            ->build('index');
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
        $post['url'] = site_url('question/'.date('Y/m', $post['created']).'/'.$post['name']);
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
                $keywords[] = $post['name'];
                $description[] = $post['desc'];
            }
        }

        return array(
            'keywords' => implode(', ', $keywords),
            'description' => implode(', ', $description)
        );
    }


    /**
     * The view function
     * @Description: This is view function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    public function view($slug = ''){
        if (!$slug){
            redirect('question');
        }
        $params = array(
            'stream'		=> 'question',
            'namespace'		=> 'questions',
            'limit'			=> 1,
            'where'			=> "`name` = '{$slug}'"
        );
        $data = $this->streams->entries->get_entries($params);
        $post = (isset($data['entries'][0])) ? $data['entries'][0] : null;
        $this->_single_view($post);
    }

    /**
     * The single_view function
     * @Description: This is single_view function
     * @Parameter:
     * @Return: null
     * @Date: 11/21/13
     * @Update: 11/21/13
     */
    private function _single_view($post){
        $this->session->set_flashdata(array('referrer' => $this->uri->uri_string()));
        $this->template->set_breadcrumb(lang('question:question_title'), 'question');
        $this->_process_post($post);

        $this->template
            ->title($post['name'], lang('question:question_title'))
            ->set_metadata('og:type', 'article', 'og')
            ->set_metadata('og:url', current_url(), 'og')
            ->set_metadata('og:title', $post['name'], 'og')
            ->set_metadata('og:site_name', Settings::get('site_name'), 'og')
            ->set_metadata('og:description', $post['desc'], 'og')
            ->set_metadata('article:published_time', date(DATE_ISO8601, $post['created']), 'og')
            ->set_metadata('article:modified_time', date(DATE_ISO8601, $post['updated']), 'og')
            ->set_metadata('description', $post['desc'])
            ->set_metadata('keywords', $post['name'])
            ->set_breadcrumb($post['name'])
            ->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
            ->set('post', array($post))
            ->build('view');
    }
    public function view_file(){
        $this->load->model('files/file_m');
        $arr['images_1_6']=$this->file_m->order_by('name')
                ->get_many_by_folder_id_limits('1','6','0');
        $arr['images_7_12']=$this->file_m->order_by('name')
                ->get_many_by_folder_id_limits('1','6','6');
        foreach ($arr['images_1_6'] as $item) {
            $path=$this->file_m->get_by_folder_id_and_name('5',$item->name)->path;
            $item->thumb_path= $path;
        }
        foreach ($arr['images_7_12'] as $item) {
            $path=$this->file_m->get_by_folder_id_and_name('5',$item->name)->path;
            $item->thumb_path= $path;
        }
        $this->load->view('show_img',$arr);
        
    }

} 