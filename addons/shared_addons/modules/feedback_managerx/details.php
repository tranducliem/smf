<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * feedback_manager module
 *
 * @author  HoangThiTuanDung
 * @company Framgia
 * @package Addons\Share\Modules\Feedback_manager
 */
class Module_Feedback_manager extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'Feedback Manager',
            ),
            'description' => array(
                'en' => 'Feedback manager.',
            ),
            //'frontend' => true,
            'backend' => true,
            'skip_xss' => true,
            'menu' => 'content',
            'roles' => array(),

            'sections' => array(
                'posts' => array(
                    'name' => 'feedback_manager:posts_title',
                    'uri' => 'admin/feedback_manager',
                    'shortcuts' => array(
                        array(
                            'name' => 'feedback_manager:create_title',
                            'uri' => 'admin/feedback_manager/create',
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
        $this->streams->utilities->remove_namespace('feedback_managers');
        // Just in case.
        $this->dbforge->drop_table('feedback_manager');

        if ($this->db->table_exists('data_streams')) {
            $this->db->where('stream_namespace', 'feedback_managers')->delete('data_streams');
        }

        $this->streams->streams->add_stream(
            'lang:feedback_manager:feedback_manager_title',
            'feedback_manager',
            'feedback_managers',
            null,
            null
        );

        // Ad the rest of the blog fields the normal way.
        $feedback_manager_fields = array(
            'title'         => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => false),
            'description'   => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true),
            'start_date'    => array('type' => 'DATETIME', 'null'=>false),
            'end_date'      => array('type' => 'DATETIME', 'null'=>false),
            'type_id'       => array('type' => 'INT', 'constraint' => 11, 'null' => true),
            'require'       => array('type' => 'SMALLINT', 'constraint' => 1,'null' => true),
            'status'        => array('type' => 'SMALLINT', 'constraint' => 1, 'null' => true),
        );

        if ($this->dbforge->add_column('feedback_manager', $feedback_manager_fields) AND
            is_dir($this->upload_path . 'feedback_manager') OR @mkdir($this->upload_path . 'feedback_manager', 0777, TRUE)
        )
        {
            return TRUE;
        }
    }


    public function uninstall()
    {
//        if ($this->dbforge->drop_table('feedback_manager')) {
        return TRUE;
//        }
    }

    public function upgrade($old_version)
    {
        return true;
    }

    public function help()
    {
        $help = "<h3>Feedback Manager Module v1.0.2</h3>";
        $help .= "Feedback Manager Module is a back-end, front-end module for PyroCMS and it supports the latest version 2.1.x.<br />";
        $help .= "It helps members to start a discussion internally and collaborate provided the group must be give permissions.<br /><br />";
        $help .= "<strong>Features:</strong><br />";
        $help .= "1. Create / edit / delete test<br />";
        $help .= "2. Add / delete comment<br />";
        $help .= "3. View test<br /><br />";
        $help .= "<strong>Installation:</strong><br />";
        $help .= "1. Download the archive and upload via CP<br />";
        $help .= "2. Install the module<br /><br />";
        $help .= "Reach us for issues / feedback at <a href=\"mailto:hello@netpines.com\"><strong>NetPines Support</strong></a> or tweet us <a href=\"http://twitter.com/netpines\" target=\"_blank\"><strong>@netpines</strong></a><br /><br />";
        $help .= "Note: This is not forum based. A simple discussion panel which is nothing but a single thread in forum.<br />";
        return $help;
    }
}
