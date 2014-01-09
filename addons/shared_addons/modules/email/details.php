<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Email extends Module {

	public $version = '1.0.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Email'
			),
			'description' => array(
				'en' => 'Module Email'
			),
			'frontend' => TRUE,
			'menu' => 'content',

		    'shortcuts' => array(
				array(
			 	   'name' => 'email',
				   'uri' => 'admin/email/create',
				   'class' => 'add'
				),
			),
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('email');

		$email = "
			CREATE TABLE ".$this->db->dbprefix('email')." (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `email` varchar(255) NOT NULL,
			  PRIMARY KEY (`id`)
			)";

		if($this->db->query($email))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		if($this->dbforge->drop_table('email'))
		{
			return TRUE;
		}
	}


	public function upgrade($old_version)
	{
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "<p>The email module </p>";
	}
}
/* End of file details.php */