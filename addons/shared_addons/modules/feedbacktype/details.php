<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Category module
 *
 * @author  laiminhdao
 * @company Framgia
 * @package Addons\Share\Modules\feedbacktype
 */
class Module_Feedbacktype extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'FeedbackType',
            ),
            'description' => array(
                'en' => 'Feedback type manager!',
            ),
            'frontend' => false,
            'backend' => true,
            'skip_xss' => true,
            'menu' => 'content',
            'roles' => array(),

            'sections' => array(
                'feedbacktypes' => array(
                    'name' => 'feedbacktype:types_title',
                    'uri' => 'admin/feedbacktype',
                    'shortcuts' => array(
                        array(
                            'name' => 'feedbacktype:create_title',
                            'uri' => 'admin/feedbacktype/create',
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
        $this->streams->utilities->remove_namespace('feedback_manager_types');

        // Just in case.
        $this->dbforge->drop_table('feedback_manager_type');

        if ($this->db->table_exists('data_streams')) {
            $this->db->where('stream_namespace', 'feedback_manager_types')->delete('data_streams');
        }

        $this->streams->streams->add_stream(
            'feedbacktype:types_title',
            'feedback_manager_type',
            'feedback_manager_types',
            null,
            null
        );

        // Ad the rest of the blog fields the normal way.
        $feedback_manager_type_fields = array(
            'title' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => false),
            'description' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true),
        );
        if ($this->dbforge->add_column('feedback_manager_type', $feedback_manager_type_fields) AND
            is_dir($this->upload_path . 'feedback_manager_type') OR @mkdir($this->upload_path . 'feedback_manager_type', 0777, TRUE)
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
        $help = "<h3>Feedback Type Module v1.0.0</h3>";
        $help .= "Feedback Type Module is a back-end module of PyroCMS and it supports the latest version 2.1.x.<br />";
        return $help;
    }
}
