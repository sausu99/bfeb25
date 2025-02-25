<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		$this->load->library('my_language');
		
		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'));exit;
		}
		
	}
	
	public function index()
	{
		redirect(site_url(''));exit;
	}
	
	public function e404()
	{		
		//set SEO data
		$this->page_title = "404 Page Not Found";
		$this->data['meta_keys'] = "404 Page Not Found";
		$this->data['meta_desc'] = "404 Page Not Found";
		$this->data['heading'] = "404 Page Not Found";
		$this->data['body'] = "The page you requested was not found.";
		
		$this->template
			->set_layout('general')
			->enable_parser(FALSE)
			->title($this->page_title)			
			->build('body', $this->data);
	}
	
	public function e301()
	{		
		//set SEO data
		$this->page_title = "301 Page Not Found";
		$this->data['meta_keys'] = "301 Page Not Found";
		$this->data['meta_desc'] = "301 Page Not Found";
		$this->data['heading'] = "301 Page Not Found";
		$this->data['body'] = "The page you requested was not found.";
		
		$this->template
			->set_layout('general')
			->enable_parser(FALSE)
			->title($this->page_title)			
			->build('body', $this->data);
	}
	
	public function e500()
	{		
		//set SEO data
		$this->page_title = "500 Page Not Found";
		$this->data['meta_keys'] = "500 Page Not Found";
		$this->data['meta_desc'] = "500 Page Not Found";
		$this->data['heading'] = "500 Page Not Found";
		$this->data['body'] = "The page you requested was not found.";
		
		$this->template
			->set_layout('general')
			->enable_parser(FALSE)
			->title($this->page_title)			
			->build('body', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */