<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Votes extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//load custom library
		$this->load->library('my_language');
		
		if(SITE_STATUS == 'offline')
		{
			redirect($this->general->lang_uri('/offline'));
			exit;
		}
		if(SITE_STATUS == 'maintanance')
		{
			if(!$this->session->userdata('MAINTAINANCE_KEY') OR $this->session->userdata('MAINTAINANCE_KEY')!='YES'){
				redirect($this->general->lang_uri('/maintanance'));exit;
			}			
		}
		
		//check banned IP address
		$this->general->check_banned_ip();
		
		//load module
		$this->load->model('votes_module');
		
		$this->load->helper('text');
		$this->load->library('pagination');
		//get language id from configure file
		$this->lang_abbr = $this->config->item('lang');

	}
	
	public function index()
	{
		$this->data['active_menu'] = 'vote';

		// $this->data['banner_view'] = FALSE;
		// //set pagination configuration			
		$config['base_url'] = $this->general->lang_uri('/auctions/vote');
		$config['total_rows'] = $this->votes_module->get_total_vote_auction();		
		$config['per_page'] = 15;
		$config['page_query_string'] = FALSE;
		$config["uri_segment"] = 4;
		
		$this->general->frontend_pagination_config($config);
		$this->pagination->initialize($config);
		$this->data['offset'] = $this->uri->segment(4,0);
		
		$this->data['vote_auc'] = $this->votes_module->get_vote_auctions($config['per_page'],$this->data['offset']);
		// echo "<pre>";
		// print_r($this->data['vote_auc']);
		// exit;
		$this->data["pagination_links"] = $this->pagination->create_links();
		
		$this->data['right_sidebar'] = 'common/sidebar_right';
		

		//getting seo data for home page
		$seo_data=$this->general->get_seo(LANG_ID, 10);
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
			->set_layout('body_2col')
			->enable_parser(FALSE)
			->title($this->page_title)			
			->build('vote_body', $this->data);
		
	}
	
	public function details($product_id=false)
	{
		// echo $product_id;

		//check integer value
		// if(isset($product_id) == FALSE)
		// {
		// 	redirect($this->general->lang_uri(''));exit;
		// }
			
		$this->data['auc_data'] = $this->votes_module->get_auction_byproductid($product_id);
		// echo "<pre>";
		// print_r($this->data['auc_data']);
		// exit;
		
		// //check the auction is in record, if not redirect to home page
		// if($this->data['auc_data'] == FALSE)
		// {	redirect($this->general->lang_uri(''));exit;}
			
		//set social media open graph description
		$this->data['facebook_opengraph'] = 'yes';
		$this->data['og_url'] = current_url();
		$this->data['og_title'] = $this->data['auc_data']->name;
		$this->data['og_description'] = $this->data['auc_data']->description;
		$this->data['og_image'] = $this->data['auc_data']->image1;
		
		//set SEO data
		$this->page_title = isset($this->data['auc_data']->page_title)? $this->data['auc_data']->page_title : SITE_NAME;
		$this->data['meta_keys'] = isset($this->data['auc_data']->meta_keys)? $this->data['auc_data']->meta_keys : SITE_NAME;
		$this->data['meta_desc'] = isset($this->data['auc_data']->meta_desc)? $this->data['auc_data']->meta_desc : SITE_NAME;
		
		
		$this->template
			->set_layout('body_full')
			->enable_parser(FALSE)
			->title($this->page_title)
			->build('vote_details_body', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */