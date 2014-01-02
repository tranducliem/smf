<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Book
 * @Description: This is a controller class for Book table
 * @Author: Tranducliem
 * @Company: Framgia
 * @Date: 11/13/13
 * @Update: 11/20/13
 */

class Book extends Public_Controller {

    private $_data = array();

    public function __construct(){
        parent::__construct();
        $this->load->driver('Streams');
        $this->stream = $this->streams_m->get_stream('book', true, 'books');
        $this->load->model('book_m');
    }

    /**
     * The index function
     * @Description: This is index function
     * @Parameter:
     * @Return: null
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    public function index(){
        // Get the latest book posts
        $posts = $this->streams->entries->get_entries(array(
            'stream'		=> 'book',
            'namespace'		=> 'books',
            'limit'			=> Settings::get('records_per_page'),
            'where'			=> "",
            'paginate'		=> 'yes',
            'pag_base'		=> site_url('book/page'),
            'pag_segment'   => 3
        ));

        // Process posts
        foreach ($posts['entries'] as &$post) {
            $this->_process_post($post);
        }
        $meta = $this->_posts_metadata($posts['entries']);

        $this->template
            ->title($this->module_details['name'])
            ->set_breadcrumb(lang('book:book_title'))
            ->set('breadcrumb_title', $this->module_details['name'])
            ->set_metadata('og:title', $this->module_details['name'], 'og')
            ->set_metadata('og:type', 'book', 'og')
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
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    private function _process_post(&$post) {
        $post['url'] = site_url('book/'.date('Y/m', $post['created']).'/'.$post['title']);
    }

    /**
     * The posts_metadata function
     * @Description: This is posts_metadata function
     * @Parameter:
     * @Return: null
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    private function _posts_metadata(&$posts = array()) {
        $keywords = array();
        $description = array();

        if (!empty($posts)) {
            foreach ($posts as &$post){
                $keywords[] = $post['title'];
                $description[] = $post['title'];
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
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    public function view($slug = ''){
        if (!$slug){
            redirect('book');
        }
        $params = array(
            'stream'		=> 'book',
            'namespace'		=> 'books',
            'limit'			=> 1,
            'where'			=> "`title` = '{$slug}'"
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
     * @Date: 11/20/13
     * @Update: 11/20/13
     */
    private function _single_view($post){
        $this->session->set_flashdata(array('referrer' => $this->uri->uri_string()));
        $this->template->set_breadcrumb(lang('book:book_title'), 'book');
        $this->_process_post($post);

        $this->template
            ->title($post['title'], lang('book:book_title'))
            ->set_metadata('og:type', 'article', 'og')
            ->set_metadata('og:url', current_url(), 'og')
            ->set_metadata('og:title', $post['title'], 'og')
            ->set_metadata('og:site_name', Settings::get('site_name'), 'og')
            ->set_metadata('og:description', $post['title'], 'og')
            ->set_metadata('article:published_time', date(DATE_ISO8601, $post['created']), 'og')
            ->set_metadata('article:modified_time', date(DATE_ISO8601, $post['updated']), 'og')
            ->set_metadata('description', $post['title'])
            ->set_metadata('keywords', $post['title'])
            ->set_breadcrumb($post['title'])
            ->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
            ->set('post', array($post))
            ->build('view');
    }

} 