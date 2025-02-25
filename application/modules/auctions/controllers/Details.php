<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Details extends CI_Controller {

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
		//get user id by session records
		$this->user_id = $this->session->userdata(SESSION . 'user_id');
		//Check profile empty befor start bidding.
		if($this->user_id && $this->general->check_mobile_blank_field() >=1){
			$this->session->set_flashdata('error_message', "We require your mobile and it's verification. Please enter your mobile and submit.");
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/mobile'),'refresh');
			exit;
		}
		else if($this->user_id && $this->general->check_profile_blank_field() >=1)
		{
			$this->session->set_flashdata('error_message', lang('profile_complete_personal_details'));
			redirect($this->general->lang_uri('/'.MY_ACCOUNT.'/user/edit'),'refresh');
			exit;
		}
		
		//check banned IP address
		$this->general->check_banned_ip();
		
		//load module
		$this->load->model('details_module');
		
		$this->load->helper('text');
		
		//get language id from configure file
		$this->lang_abbr = $this->config->item('lang');

	}
	
	public function index($product_id)
	{
		
		//check integer value
		if(isset($product_id) == FALSE)
		{
			redirect($this->general->lang_uri(''), 'refresh');exit;
		}
			
		$this->data['auc_data'] = $this->details_module->get_auction_byproductid($product_id);
		
        $this->data['p_id']=$product_id;
		
		
		//check the auction is in record, if not redirect to home page
		if($this->data['auc_data'] == FALSE)
		{	redirect($this->general->lang_uri(''), 'refresh');exit;}
			
		//print_r($this->data['auc_data']->page_title);exit;
		//set SEO data
		$this->page_title = ($this->data['auc_data']->page_title)? $this->data['auc_data']->page_title : $this->data['auc_data']->name;
		$this->data['meta_keys'] = ($this->data['auc_data']->meta_keys)? $this->data['auc_data']->meta_keys : $this->data['auc_data']->name;
		$this->data['meta_desc'] = ($this->data['auc_data']->meta_desc)? $this->data['auc_data']->meta_desc : $this->data['auc_data']->name;
		
		
		//Display Pagination only if user is logged in
		$this->data['total_records'] = "";
		$this->data['total_records_other'] = "";
		$this->data['per_page'] = 10;
		$this->data['user_id'] = $this->user_id;
		if($this->data['user_id']){
			//Now set pagination config for user bid history
			$this->data['pagination_link'] = '';
			$this->data['current_page'] = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
			//get total number of records from database for pagination
			$this->data['total_records'] = $this->details_module->count_users_bid_history_by_auction_id($this->data['auc_data']->product_id, $this->data['user_id']);
			$this->data['user_bid_history'] = $this->details_module->get_users_bid_history_by_auction_id($this->data['auc_data']->product_id, $this->user_id, $this->data['per_page'], $this->data['current_page']);
			
			//break records into pages
			$this->data['total_pages'] = ceil($this->data['total_records'] / $this->data['per_page']);
			$this->data['pagination_link'] = $this->general->paginate_function($this->data['per_page'], $this->data['current_page'] +1, $this->data['total_records'], $this->data['total_pages']);	
			
			//Other Bidders Bid History
			$this->data['per_page_other'] = 10;
			//Now set pagination config for user bid history
			$this->data['pagination_link_other'] = '';
			$this->data['current_page_other'] = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
			//get total number of records from database for pagination
			$this->data['total_records_other'] = $this->details_module->count_other_users_bid_history_by_auction_id($this->data['auc_data']->product_id, $this->data['user_id']);
			$this->data['other_bid_history'] = $this->details_module->get_other_users_bid_history_by_auction_id($this->data['auc_data']->product_id, $this->user_id, $this->data['per_page_other'], $this->data['current_page_other']);
			//break records into pages
			$this->data['total_pages_other'] = ceil($this->data['total_records_other'] / $this->data['per_page_other']);
			$this->data['pagination_link_other'] = $this->general->paginate_function($this->data['per_page_other'], $this->data['current_page_other'] +1, $this->data['total_records_other'], $this->data['total_pages_other']);	
		}
		
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		//Get auction faq data
		$this->data['auction_faq_data'] = $this->details_module->get_auction_faq_lists($lang_id, $this->data['auc_data']->id);

		
		//$this->data['winner_data'] = $this->general->get_winner($this->data['auc_data']->product_id);
		$this->data['total_bids'] = $this->details_module->count_users_bid_history_by_auction_id($this->data['auc_data']->product_id);
		$this->data['total_watchlist'] = $this->details_module->get_total_watchlist($product_id);				
		// set social media open graph description
		$this->data['facebook_opengraph'] = 'yes';
		$this->data['og_url'] = current_url();
		$this->data['og_title'] = $this->data['auc_data']->name;
		$this->data['og_description'] = $this->data['auc_data']->description;
		$this->data['og_image'] = $this->data['auc_data']->image1;
		
		
		//echo $this->page_title;exit;
		$this->template
			->set_layout('body_full')
			->enable_parser(FALSE)
			->title($this->page_title)
			->build('body', $this->data);
	}
	
	public function live_all()
	{	
		$this->load->library('pagination');
		$this->data['active_menu'] = 'live';
		$this->data['banner_view'] = FALSE;
		
		//get user watchlist
		$this->data['user_watchlist'] = $this->general->get_user_watchlist($this->user_id);
		
		$config['base_url'] = $this->general->lang_uri('/auctions/live');
		$config['total_rows'] = $this->details_module->count_all_live_auctions();
		$config['per_page'] = 15;		
		$config["uri_segment"] = 4;
		$config['enable_query_strings'] = FALSE;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->general->frontend_pagination_config($config);
		$this->pagination->initialize($config);
		$this->data['offset'] = $this->uri->segment(4,0);
		
		$this->data['live_auctions'] = $this->details_module->get_all_live_auctions($config["per_page"], $this->data['offset']);
		$this->data["pagination_links"] = $this->pagination->create_links();
		
		//set SEO data
		//getting seo data for home page
		$seo_data=$this->general->get_seo(LANG_ID, 6);
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
		
		//load module
		$this->load->model('home/home_module');
		//get featured auctions
		$this->data['featured_auctions'] = $this->home_module->get_featured_auction(3);
		
		$this->template
			->set_layout('body_full')
			->enable_parser(FALSE)
			->title($this->page_title)			
			->build('body_live_all', $this->data);
	}
	
	
	public function upcomming()
	{	
		$this->load->library('pagination');
		$this->data['active_menu'] = 'live';
		$this->data['banner_view'] = FALSE;
		
		//get user watchlist
		$this->data['user_watchlist'] = $this->general->get_user_watchlist($this->user_id);
		
		$config['base_url'] = $this->general->lang_uri('/auctions/live');
		$config['total_rows'] = $this->details_module->count_upcomming_auctions();
		$config['per_page'] = 15;
		$config['page_query_string'] = FALSE;
		$config["uri_segment"] = 4;
		
		$this->general->frontend_pagination_config($config);
		$this->pagination->initialize($config);
		$this->data['offset'] = $this->uri->segment(4,0);
		
		$this->data['upcomming_auctions'] = $this->details_module->get_upcomming_auctions($config["per_page"], $this->data['offset']);
		$this->data["pagination_links"] = $this->pagination->create_links();
		
		//set SEO data
		//getting seo data for home page
		$seo_data=$this->general->get_seo(LANG_ID, 6);
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
		
		//load module
		$this->load->model('home/home_module');
		//get featured auctions
		$this->data['featured_auctions'] = $this->home_module->get_featured_auction(3);
		
		$this->template
			->set_layout('body_full')
			->enable_parser(FALSE)
			->title($this->page_title)			
			->build('body_upcomming', $this->data);
	}
	
	public function upcomming_details($product_id)
	{
		
		//check integer value
		if(isset($product_id) == FALSE)
		{
			redirect($this->general->lang_uri(''), 'refresh');exit;
		}
			
		$this->data['auc_data'] = $this->details_module->get_upcomming_auction_byproductid($product_id);
		
        $this->data['p_id']=$product_id;
		
		
		//check the auction is in record, if not redirect to home page
		if($this->data['auc_data'] == FALSE)
		{	redirect($this->general->lang_uri(''), 'refresh');exit;}
			
		//print_r($this->data['auc_data']->page_title);exit;
		//set SEO data
		$this->page_title = ($this->data['auc_data']->page_title)? $this->data['auc_data']->page_title : $this->data['auc_data']->name;
		$this->data['meta_keys'] = ($this->data['auc_data']->meta_keys)? $this->data['auc_data']->meta_keys : $this->data['auc_data']->name;
		$this->data['meta_desc'] = ($this->data['auc_data']->meta_desc)? $this->data['auc_data']->meta_desc : $this->data['auc_data']->name;
		
		
		//get language id from configure file
		$lang_id = $this->config->item('current_language_id');
		
		//Get auction faq data
		$this->data['auction_faq_data'] = $this->details_module->get_auction_faq_lists($lang_id, $this->data['auc_data']->id);

		
		//$this->data['winner_data'] = $this->general->get_winner($this->data['auc_data']->product_id);
		$this->data['total_bids'] = $this->details_module->count_users_bid_history_by_auction_id($this->data['auc_data']->product_id);
		$this->data['total_watchlist'] = $this->details_module->get_total_watchlist($product_id);				
		// set social media open graph description
		$this->data['facebook_opengraph'] = 'yes';
		$this->data['og_url'] = current_url();
		$this->data['og_title'] = $this->data['auc_data']->name;
		$this->data['og_description'] = $this->data['auc_data']->description;
		$this->data['og_image'] = $this->data['auc_data']->image1;
		
		
		//echo $this->page_title;exit;
		$this->template
			->set_layout('body_full')
			->enable_parser(FALSE)
			->title($this->page_title)
			->build('body_upcomming_details', $this->data);
	}
	
	public function category_lists($slug,$cid)
	{
		$this->load->library('pagination');
		$this->data['active_menu'] = 'live';
		$this->data['banner_view'] = FALSE;
		
		
		//redirect if category id is empty
		if(!$cid)
			redirect($this->general->lang_uri('/auctions/live/'),'refresh');
			
		//get user watchlist
		$this->data['user_watchlist'] = $this->general->get_user_watchlist($this->user_id);
		//load module
		$this->load->model('home/home_module');
		//get featured auctions
		$this->data['featured_auctions'] = $this->home_module->get_featured_auction(3);
		
		$config['base_url'] = $this->general->lang_uri('/category/'.$slug.'-'.$cid);
		$config['total_rows'] = $this->details_module->count_all_live_auctions($cid);
		$config['per_page'] = 10;
		$config['page_query_string'] = FALSE;
		$config["uri_segment"] = 4;
		
		$this->general->frontend_pagination_config($config);
		$this->pagination->initialize($config);
		$this->data['offset'] = $this->uri->segment(4,0);
		
		$this->data['auctions'] = $this->details_module->get_all_live_auctions($config["per_page"], $this->data['offset'], $cid);
		//print_r($this->data['auctions']);exit;
		$this->data["pagination_links"] = $this->pagination->create_links();
		
		//get category name
		$this->data['cat_name'] = $this->details_module->get_category_name($cid);
		
		//set SEO data
		//getting seo data for home page
		$seo_data=$this->general->get_seo(LANG_ID, 8);
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
			->build('body_category_auctions', $this->data);
	}

	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */