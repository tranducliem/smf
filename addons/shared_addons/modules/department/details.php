<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Department module
 *
 * @author  PhamXuanThanh
 * @company Framgia
 * @package Addons\Share\Modules\Department
 */
class Module_Department extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'Department',
            ),
            'description' => array(
                'en' => 'Post department entries.',
            ),
            'frontend' => true,
            'backend' => true,
            'skip_xss' => true,
            'menu' => 'content',
            'roles' => array(),

            'sections' => array(
                'posts' => array(
                    'name' => 'department:posts_title',
                    'uri' => 'admin/department',
                    'shortcuts' => array(
                        array(
                            'name' => 'department:create_title',
                            'uri' => 'admin/department/create',
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
        $this->streams->utilities->remove_namespace('departments');

        // Just in case.
        $this->dbforge->drop_table('department');

        if ($this->db->table_exists('data_streams')) {
            $this->db->where('stream_namespace', 'departments')->delete('data_streams');
        }

        $this->streams->streams->add_stream(
            'lang:department:department_title',
            'department',
            'departments',
            null,
            null
        );

        // Ad the rest of the blog fields the normal way.
        $department_fields = array(
            'title' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => false),
            'description' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true),
            'company_id' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
        );
        if ($this->dbforge->add_column('department', $department_fields) AND
            is_dir($this->upload_path . 'department') OR @mkdir($this->upload_path . 'department', 0777, TRUE)
        ) {
            return TRUE;
        }
    }


    public function uninstall()
    {
//        if ($this->dbforge->drop_table('department')) {
            return TRUE;
//        }
    }

    public function upgrade($old_version)
    {
        return true;
    }

    public function help()
    {
        $help = "<h3>Department Module v1.0.2</h3>";
        $help .= "Department Module is a back-end, front-end module for PyroCMS and it supports the latest version 2.1.x.<br />";
        $help .= "It helps members to start a discussion internally and collaborate provided the group must be give permissions.<br /><br />";
        $help .= "<strong>Features:</strong><br />";
        $help .= "1. Create / edit / delete department<br />";
        $help .= "2. View department<br /><br />";
        $help .= "<strong>Installation:</strong><br />";
        $help .= "1. Download the archive and upload via CP<br />";
        $help .= "2. Install the module<br /><br />";
        $help .= "Reach us for issues / feedback at <a href=\"mailto:hello@netpines.com\"><strong>NetPines Support</strong></a> or tweet us <a href=\"http://twitter.com/netpines\" target=\"_blank\"><strong>@netpines</strong></a><br /><br />";
        $help .= "Note: This is not forum based. A simple discussion panel which is nothing but a single thread in forum.<br />";
        return $help;
    }
}
