<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Team module
 *
 * @author  PhamXuanThanh
 * @company Framgia
 * @package Addons\Share\Modules\Team
 */
class Module_Team extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'Team',
            ),
            'description' => array(
                'en' => 'Post team entries.',
            ),
            'frontend' => true,
            'backend' => true,
            'skip_xss' => true,
            'menu' => 'content',
            'roles' => array(),

            'sections' => array(
                'posts' => array(
                    'name' => 'team:posts_title',
                    'uri' => 'admin/team',
                    'shortcuts' => array(
                        array(
                            'name' => 'team:create_title',
                            'uri' => 'admin/team/create',
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
        $this->streams->utilities->remove_namespace('teams');

        // Just in case.
        $this->dbforge->drop_table('team');

        if ($this->db->table_exists('data_streams')) {
            $this->db->where('stream_namespace', 'teams')->delete('data_streams');
        }

        $this->streams->streams->add_stream(
            'lang:team:team_title',
            'team',
            'teams',
            null,
            null
        );

        // Ad the rest of the blog fields the normal way.
        $team_fields = array(
            'title' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => false),
            'description' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true),
            'company_id' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
        );
        if ($this->dbforge->add_column('team', $team_fields) AND
            is_dir($this->upload_path . 'team') OR @mkdir($this->upload_path . 'team', 0777, TRUE)
        ) {
            return TRUE;
        }
    }


    public function uninstall()
    {
//        if ($this->dbforge->drop_table('team')) {
            return TRUE;
//        }
    }

    public function upgrade($old_version)
    {
        return true;
    }

    public function help()
    {
        $help = "<h3>Team Module v1.0.2</h3>";
        $help .= "Team Module is a back-end, front-end module for PyroCMS and it supports the latest version 2.1.x.<br />";
        $help .= "It helps members to start a discussion internally and collaborate provided the group must be give permissions.<br /><br />";
        $help .= "<strong>Features:</strong><br />";
        $help .= "1. Create / edit / delete team<br />";
        $help .= "2. View team<br /><br />";
        $help .= "<strong>Installation:</strong><br />";
        $help .= "1. Download the archive and upload via CP<br />";
        $help .= "2. Install the module<br /><br />";
        $help .= "Reach us for issues / feedback at <a href=\"mailto:hello@netpines.com\"><strong>NetPines Support</strong></a> or tweet us <a href=\"http://twitter.com/netpines\" target=\"_blank\"><strong>@netpines</strong></a><br /><br />";
        $help .= "Note: This is not forum based. A simple discussion panel which is nothing but a single thread in forum.<br />";
        return $help;
    }
}
