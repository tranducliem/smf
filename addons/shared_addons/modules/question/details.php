<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Question module
 *
 * @author  PhamXuanThanh
 * @company Framgia
 * @package Addons\Share\Modules\Question
 */
class Module_Question extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'Question',
            ),
            'description' => array(
                'en' => 'Post question entries.',
            ),
            'frontend' => true,
            'backend' => true,
            'skip_xss' => true,
            'menu' => 'content',
            'roles' => array(),

            'sections' => array(
                'posts' => array(
                    'name' => 'question:posts_title',
                    'uri' => 'admin/question',
                    'shortcuts' => array(
                        array(
                            'name' => 'question:create_title',
                            'uri' => 'admin/question/create',
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
        $this->streams->utilities->remove_namespace('questions');

        // Just in case.
        $this->dbforge->drop_table('question');

        if ($this->db->table_exists('data_streams')) {
            $this->db->where('stream_namespace', 'questions')->delete('data_streams');
        }

        $this->streams->streams->add_stream(
            'lang:question:question_title',
            'question',
            'questions',
            null,
            null
        );

        // Ad the rest of the blog fields the normal way.
        $question_fields = array(
            'title' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => false),
            'description' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true),
        );
        if ($this->dbforge->add_column('question', $question_fields) AND
            is_dir($this->upload_path . 'question') OR @mkdir($this->upload_path . 'question', 0777, TRUE)
        ) {
            return TRUE;
        }
    }


    public function uninstall()
    {
//        if ($this->dbforge->drop_table('question')) {
            return TRUE;
//        }
    }

    public function upgrade($old_version)
    {
        return true;
    }

    public function help()
    {
        $help = "<h3>Question Module v1.0.2</h3>";
        $help .= "Question Module is a back-end, front-end module for PyroCMS and it supports the latest version 2.1.x.<br />";
        $help .= "It helps members to start a discussion internally and collaborate provided the group must be give permissions.<br /><br />";
        $help .= "<strong>Features:</strong><br />";
        $help .= "1. Create / edit / delete question<br />";
        $help .= "2. View question<br /><br />";
        $help .= "<strong>Installation:</strong><br />";
        $help .= "1. Download the archive and upload via CP<br />";
        $help .= "2. Install the module<br /><br />";
        $help .= "Reach us for issues / feedback at <a href=\"mailto:hello@netpines.com\"><strong>NetPines Support</strong></a> or tweet us <a href=\"http://twitter.com/netpines\" target=\"_blank\"><strong>@netpines</strong></a><br /><br />";
        $help .= "Note: This is not forum based. A simple discussion panel which is nothing but a single thread in forum.<br />";
        return $help;
    }
}
