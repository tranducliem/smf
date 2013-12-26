<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Category module
 *
 * @author  laiminhdao
 * @company Framgia
 * @package Addons\Share\Modules\feedbackuser
 */
class Module_Feedbackuser extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'FeedbackManagerUser',
            ),
            'description' => array(
                'en' => 'Feedback manager user.',
            ),
            'frontend'  => false,
            'backend'   => true,
            'skip_xss'  => true,
            'menu'      => 'content',
            'roles'     => array(),

            'sections' => array(
                'feedbackuers' => array(
                    'name'      => 'feedbackuser:types_title',
                    'uri'       => 'admin/feedbackuser',
                    'shortcuts' => array(
                        array(
                            'name'  => 'feedbackuser:create_title',
                            'uri'   => 'admin/feedbackuser/create',
                            'class' => 'add',
                        ),
                    ),
                )
            ),
        );

        return $info;
    }


    public function install()
    {
        $this->load->driver('Streams');
        $this->streams->utilities->remove_namespace('feedback_manager_users');

        // Just in case.
        $this->dbforge->drop_table('feedback_manager_user');

        if ($this->db->table_exists('data_streams')) {
            $this->db->where('stream_namespace', 'feedback_manager_users')->delete('data_streams');
        }

        $this->streams->streams->add_stream(
            'feedbackuser:types_title',
            'feedback_manager_user',
            'feedback_manager_users',
            null,
            null
        );

        // Ad the rest of the blog fields the normal way.
        $feedback_manager_user_fields = array(
            'feedback_manager_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
            'user_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
            'status' => array('type' => 'SMALLINT', 'constraint' => 1, 'null' => true),
            'date' => array('type' => 'DATETIME', 'null' => false),
        );
        if ($this->dbforge->add_column('feedback_manager_user', $feedback_manager_user_fields) AND
            is_dir($this->upload_path . 'feedback_manager_user') OR @mkdir($this->upload_path . 'feedback_manager_user', 0777, TRUE)
        ) {
            return true;
        }
    }


    public function uninstall()
    {
        //if ($this->dbforge->drop_table('category')) {
        //    return TRUE;
        //}
        return true;
    }

    public function upgrade($old_version)
    {
        return true;
    }

    public function help()
    {
        $help = "<h3>Feedback Manager User Module v1.0.0</h3>";
        $help .= "Feedback Manager User is a back-end module of PyroCMS and it supports the latest version 2.1.x.<br />";
        return $help;
    }
}
