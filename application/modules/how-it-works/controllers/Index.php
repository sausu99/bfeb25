<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//load custom library
		$this->load->library('my_language');
		
		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'));exit;
		}
		
		//load module
		$this->load->model('front_howitworks');
		
	}
	
	public function index()
	{		
		
		$this->data['hwitworks'] = $this->front_howitworks->get_how_it_works();
		
		//set SEO data
		$this->page_title = lang('seo_hwitworks_page_tile');
		$this->data['meta_keys'] = lang('seo_hwitworks_meta_key');
		$this->data['meta_desc'] = lang('seo_hwitworks_meta_desc');
		
		$this->template
			->set_layout('general')
			->enable_parser(FALSE)
			->title($this->page_title)			
			->build('hwitworks_body', $this->data);	
		
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */