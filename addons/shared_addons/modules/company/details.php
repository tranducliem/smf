<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Category module
 *
 * @author  laiminhdao
 * @company Framgia
 * @package Addons\Share\Modules\feedbacktype
 */
class Module_Company extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'Company',
            ),
            'description' => array(
                'en' => 'Companies manager!',
            ),
            'frontend' => true,
            'backend' => true,
            'skip_xss' => true,
            'menu' => 'content',
            'roles' => array(),

            'sections' => array(
                'companies' => array(
                    'name' => 'company:types_title',
                    'uri' => 'admin/company',
                    'shortcuts' => array(
                        array(
                            'name' => 'company:create_title',
                            'uri' => 'admin/company/create',
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
        $this->streams->utilities->remove_namespace('companies');

        // Just in case.
        $this->dbforge->drop_table('company');

        if ($this->db->table_exists('data_streams')) {
            $this->db->where('stream_namespace', 'companies')->delete('data_streams');
        }

        $this->streams->streams->add_stream(
            'company:types_title',
            'company',
            'companies',
            null,
            null
        );

        // Ad the rest of the blog fields the normal way.
        $feedback_manager_type_fields = array(
            'title' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => false),
            'description' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true),
            'address' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true),
        );
        if ($this->dbforge->add_column('company', $feedback_manager_type_fields) AND
            is_dir($this->upload_path . 'company') OR @mkdir($this->upload_path . 'company', 0777, TRUE)
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
        $help = "<h3>Companies Manager Module v1.0.0</h3>";
        $help .= "Companies Manage Module is a back-end module of PyroCMS and it supports the latest version 2.1.x.<br />";
        return $help;
    }
}
