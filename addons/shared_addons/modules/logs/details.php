<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Module_Logs extends Module {

    public $version = '1.1.0';

    public function info() {
         $info = array(
            'name' => array(
                'en' => 'Logs'
            ),
            'description' => array(
                'en' => 'This module lets you to browse log files.'
            ),
            'frontend' => FALSE,
            'backend' => TRUE,
            'menu' => 'utilities', // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
        );
         
         if (version_compare(CMS_VERSION, '2.2.0-beta') >= 0) {
             $info['menu'] = 'data';
         } 
         
         return $info;
    }

    public function install() {
        return TRUE;
    }

    public function uninstall() {
        return TRUE;
    }

    public function upgrade($old_version) {
        // Your Upgrade Logic
        return TRUE;
    }

    public function help() {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }

}

/* End of file details.php */
