<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct() {
		parent::__construct();		
		//load custom module
			$this->load->model('admin_testimonial');
			//load custom library
		$this->load->library('my_language');
	}
	
	public function index()
	{
		$this->data['testimonial_data'] = $this->admin_testimonial->get_details();
		
		//set SEO data
		$this->page_title = lang('seo_testimonial_tile');
		$this->data['meta_keys'] = lang('seo_testimonial_meta_key');
		$this->data['meta_desc'] = lang('seo_testimonial_meta_desc');
		
		$this->template
			->set_layout('general')
			->enable_parser(FALSE)
			->title($this->page_title)			
			->build('home_view', $this->data);
		
	}
		
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */