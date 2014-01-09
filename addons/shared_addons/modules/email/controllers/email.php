<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 */
class Email extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('email_m');
	}

	public function get_mail()
	{
		$email = $this->current_user->email;
		$data = array(
   			'email' => $email
		);

		$test = $this->email_m->get_all();
		$count = 0;
		foreach ($test as $value) {
			if($value->email == $email)
			{
				$count = 1;
			}
		}
		if (filter_var($email, FILTER_VALIDATE_EMAIL) && $count == 0)
		{
			$this->email_m->insert($data);
			redirect(base_url());
		}
		else
		{
        	redirect(base_url());
		}
	}
}