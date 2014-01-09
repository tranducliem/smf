<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Book module
 *
 * @author  Tranducliem
 * @company Framgia
 * @package Addons\Share\Modules\Book
 */
class Module_Definition extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'Definition',
            ),
            'description' => array(
                'en' => 'Keyword Definition/Description.',
            ),
            'frontend' => false,
            'backend' => true,
            'skip_xss' => true,
            'menu' => 'content',
            'roles' => array(),

            'sections' => array(
                'posts' => array(
                    'name' => 'definition:posts_title',
                    'uri' => 'admin/definition',
                    'shortcuts' => array(
                        array(
                            'name' => 'definition:create_title',
                            'uri' => 'admin/definition/create',
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
        $this->streams->utilities->remove_namespace('definitions');

        // Just in case.
        $this->dbforge->drop_table('definition');

        if ($this->db->table_exists('data_streams')) {
            $this->db->where('stream_namespace', 'definitions')->delete('data_streams');
        }

        $this->streams->streams->add_stream(
            'lang:definition:definition_title',
            'definition',
            'definitions',
            null,
            null
        );

        // Ad the rest of the blog fields the normal way.
        $definition_fields = array(
            'title' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => false, 'unique' => true),
            'slug' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => false, 'unique' => true),
            'description' => array('type' => 'TEXT', 'null' => true),
            'system' => array('type' => 'INT', 'constraint' => 1, 'null' => false)
        );
        if ($this->dbforge->add_column('definition', $definition_fields) AND
            is_dir($this->upload_path . 'definition') OR @mkdir($this->upload_path . 'definition', 0777, TRUE)
        ) {
            return TRUE;
        }
    }


    public function uninstall()
    {
        //if ($this->dbforge->drop_table('definition')) {
            return TRUE;
        //}
    }

    public function upgrade($old_version)
    {
        return true;
    }

    public function help()
    {
        $help = "<h3>Definition Module v1.0.2</h3>";
        $help .= "Definition Module is a back-end, front-end module for PyroCMS and it supports the latest version 2.1.x.<br />";
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
