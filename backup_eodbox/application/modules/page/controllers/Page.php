<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//load custom library
		$this->load->library('my_language');
		
		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'));exit;
		}
		
		if(SITE_STATUS == 'maintanance')
		{
			if(!$this->session->userdata('MAINTAINANCE_KEY') OR $this->session->userdata('MAINTAINANCE_KEY')!='YES'){
				redirect($this->general->lang_uri('/maintanance'));exit;
			}			
		}
		
		//load module
		$this->load->model('page_module');
		$this->load->helper('text');

	}
	
	public function index($cms_slug)
	{
		$this->data['banner_view'] = FALSE;

		$this->data['cms'] = $this->page_module->get_cms($cms_slug);

		if(!isset($this->data['cms']->id))
			redirect($this->general->lang_uri(),'refresh');

		//$this->data['right_sidebar'] = 'common/sidebar_right';
		
		//set SEO data
		$this->page_title = isset($this->data['cms']->page_title)? $this->data['cms']->page_title : SITE_NAME;
		$this->data['meta_keys'] = isset($this->data['cms']->meta_keys)? $this->data['cms']->meta_keys : SITE_NAME;
		$this->data['meta_desc'] = isset($this->data['cms']->meta_desc)? $this->data['cms']->meta_desc : SITE_NAME;
		
		$this->template
			->set_layout('body_full')
			->enable_parser(FALSE)
			->title($this->page_title)			
			->build('body', $this->data);
	}


	public function contact_us()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$this->data['right_sidebar'] = 'common/sidebar_right';	

		$this->form_validation->set_rules($this->page_module->validate_contact_us);
	  
	 	
		if($this->form_validation->run()==true)
		{
	        $this->page_module->send_email();
		    $this->session->set_flashdata('message',lang('message_sent_success'));
			redirect($this->general->lang_uri('/page/contact-us'),'refresh');
		}
		  $this->page_title = SITE_NAME.'| '.lang('contact_us');
		    $this->data['meta_keys']= SITE_NAME.'| '.lang('contact_us');
		    $this->data['meta_desc']= SITE_NAME.'| '.lang('contact_us');

		
		$this->template
			->set_layout('body_2col')
			->enable_parser(FALSE)
			->title($this->page_title)			
			->build('body_contact', $this->data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */