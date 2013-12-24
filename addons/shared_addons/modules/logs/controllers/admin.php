<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This is a logs module for PyroCMS
 *
 * @author 		Blazej Adamczyk
 * @website		http://sein.com.pl
 */
class Admin extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('logs');
        $this->load->helper('directory');
        $this->load->helper('file');
        $this->_folder = APPPATH . 'logs/';

        $this->template->append_css('module::admin.css');
    }

    /**
     * List all items
     */
    public function index() {

        $items = directory_map($this->_folder, 1);
        $items = array_diff($items, array('.', '..', 'index.php', 'index.html'));
        sort($items);
        $items = array_reverse($items);

        $this->data = new stdClass();
        $this->data->items = & $items;
        $this->template->title($this->module_details['name'])
                ->set('folder', $this->_folder)
                ->build('admin/items', $this->data);
    }

    public function view($item = '') {
        $data = new stdClass();

        $data->content = read_file($this->_folder . $item);
        $data->content OR redirect('admin/logs');
        $data->filename = $item;

        $this->template->title(lang('global:view') . ': ' . $item)
                ->append_js('module::rainbow.min.js')
                ->build('admin/view', $data);
    }

    public function delete($item = '') {
        if ($this->input->post('action_to') && is_array($this->input->post('action_to'))) {
            $array = $this->input->post('action_to');
            foreach ($array as $value) {
                if (file_exists($this->_folder . $value)) {
                    unlink($this->_folder . $value);
                }
            }
            $this->session->set_flashdata('success', sprintf(lang('logs:array_success'), count($array)));
        } else if (file_exists($this->_folder . $item)) {

            if (unlink($this->_folder . $item)) {
                $this->session->set_flashdata('success', sprintf(lang('logs:success'), $item));
            } else {
                $this->session->set_flashdata('error', lang('logs:error'));
            }
        } else {
            $this->session->set_flashdata('error', lang('logs:error'));
        }
        redirect('admin/logs');
    }

}
