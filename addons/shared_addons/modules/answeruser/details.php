<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Category module
 *
 * @author  laiminhdao
 * @company Framgia
 * @package Addons\Share\Modules\feedbackuser
 */
class Module_Answeruser extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'AnswerUser',
            ),
            'description' => array(
                'en' => 'Answer user.',
            ),
            'frontend'  => true,
            'backend'   => true,
            'skip_xss'  => true,
            'menu'      => 'content',
            'roles'     => array(),

            'sections' => array(
                'answerusers' => array(
                    'name'      => 'answeruser:types_title',
                    'uri'       => 'admin/answeruser',
                    'shortcuts' => array(
                        array(
                            'name'  => 'answeruser:create_title',
                            'uri'   => 'admin/answeruser/create',
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
        $this->streams->utilities->remove_namespace('answer_users');

        // Just in case.
        $this->dbforge->drop_table('answer_user');

        if ($this->db->table_exists('data_streams')) {
            $this->db->where('stream_namespace', 'answer_users')->delete('data_streams');
        }

        $this->streams->streams->add_stream(
            'answeruser:types_title',
            'answer_user',
            'answer_users',
            null,
            null
        );

        // Ad the rest of the blog fields the normal way.
        $answer_user_fields = array(
            'answer_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
            'user_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
        );
        if ($this->dbforge->add_column('answer_user', $answer_user_fields) AND
            is_dir($this->upload_path . 'answer_user') OR @mkdir($this->upload_path . 'answer_user', 0777, TRUE)
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
        $help = "<h3>Answer User Module v1.0.0</h3>";
        $help .= "Answer User is a back-end module of PyroCMS and it supports the latest version 2.1.x.<br />";
        return $help;
    }
}
