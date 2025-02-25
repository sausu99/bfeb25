<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ccavenue_ipn extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//load custom library
		$this->load->library('my_language');
		
		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'));exit;
		}
		
		$this->load->model('account_module');
		$this->load->model('paypal_module');
        $this->load->library('Netcoreemail_class');
		
	}
	
	public function index()
	{
		
		$post_string = '';    
			  foreach ($_POST as $field=>$value) { 
				 $this->ipn_data["$field"] = $value;
				 $post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
			  }
		
		$this->send_test_email('Validate IPN',$post_string);//test email
		
	}
	
	public function send_test_email($subject,$message)
	{
		$this->load->library('email');

		$this->email->from('demo@nepaimpressions.com', 'EmultitechSolution');
		$this->email->to('sujit.emts@gmail.com');		
		
		$this->email->subject($subject);
		$this->email->message($message); 
		
		$this->email->send();
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */