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
		$this->load->model('front_help');
		$this->load->helper('text');
	}
	
	public function index()
	{		
		$this->data['banner_view'] = FALSE;
		$this->data['right_sidebar'] = 'common/sidebar_right';
		
		$this->data['help_cat'] = $this->front_help->get_help_category();
		
		
		//set SEO data
		//getting seo data for home page
		$seo_data=$this->general->get_seo(LANG_ID, 9);
		if($seo_data)
		{
			//set SEO data
			$this->page_title = $seo_data->page_title.' | '.SITE_NAME;
			$this->data['meta_keys']= $seo_data->meta_key;
			$this->data['meta_desc']= $seo_data->meta_description;
		}
		else
		{
			//set SEO data
		    $this->page_title = SITE_NAME;
		    $this->data['meta_keys']= SITE_NAME;
		    $this->data['meta_desc']= SITE_NAME;
		}
		
		$this->template
			->set_layout('body_full')
			->enable_parser(FALSE)
			->title($this->page_title)			
			->build('help_body', $this->data);	
		
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */