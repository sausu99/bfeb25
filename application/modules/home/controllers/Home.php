<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

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
		$this->load->model('home_module');
		
		$this->load->helper('text');
	}
	
	public function index()
	{
		$this->data['active_menu'] = 'home';
		$this->data['show_recent_wiiners']='yes';
				
		//$this->data['banners'] = $this->home_module->get_banner();	
		//get user watchlist
		$this->data['user_watchlist'] = $this->general->get_user_watchlist($this->user_id);
		//print_r($this->data['user_watchlist']);exit;
		
		//get featured auctions
		$this->data['featured_auctions'] = $this->home_module->get_featured_auction(3);
		
		//get trending auctions (Most bidding auction)
		$this->data['trending_auctions'] = $this->home_module->get_trending_auction(3);
		
		//get auctions which is closeding within 24 hours
		$this->data['ending_soon_auctions'] = $this->home_module->get_ending_soon_auction(6);
		
		//Get home page category
		$this->data['home_cat_auctions'] = $this->get_home_categories();
		//var_dump($this->data['home_cat_auctions']);exit;
				
		//get buy now products
		$data_all = [];		
		/*$buy_auctions = $this->home_module->get_buy_auctions(2);
		$buy_auctions_count = count($buy_auctions);
		if($buy_auctions)
			array_push($data_all,$buy_auctions);
		$remaining_buy = 2-$buy_auctions_count;*/
		//get upcomming auction
		$upcomming_auctions = $this->home_module->get_upcomming_auctions(6);
		if($upcomming_auctions)
			array_push($data_all,$upcomming_auctions);
		//$upcomming_auctions_count = count($upcomming_auctions);		
		//$remaining_up = 2-$upcomming_auctions_count;
		
		//get live auction
		/*$live_auctions = $this->home_module->get_live_auction(2+$remaining_up);
		if($live_auctions)
			array_push($data_all,$live_auctions);*/
		
		$this->data['get_all_auctions'] = $data_all;
		//print_r($this->data['get_all_auctions']);exit;
		
		//Get Popular Auctions
		//Find the large bidding category from 3 months
		$large_bidding_category = $this->home_module->get_large_bidding_category(2);
		// And then get auction from thosse category only.
		if($large_bidding_category)
			$cats = array_column($large_bidding_category, 'cat_id');
		else
			$cats = array();
			
		$this->data['popular_auctions'] = $this->home_module->get_popular_auctions($cats, 2);
		
		//Product category
		$this->data['category'] = $this->general->get_all_categories_display(LANG_ID);
		
		
		$seo_data=$this->general->get_seo(LANG_ID, 1);
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
			->build('body', $this->data);
	}

	public function get_home_categories(){
		$data = array();
		//get home page category
		$home_category = $this->home_module->get_home_category();
		if($home_category){
			$i = 0;
			foreach($home_category as $category){
				//get category auction
				$auctions = $this->home_module->get_auction_cat($category->parent_id, 3);
				if($auctions){
					$data[$i]['cat_name'] = $category->name;
					$data[$i]['cat_desc'] = $category->short_desc;
					$data[$i]['cat_parent_id'] = $category->parent_id;
					foreach($auctions as $auc){
						$data[$i]['auction'][] = $auc;
						
					}
					$i++;
				}
			}
			if($data)
			 return $data;
		}
		return false;
	}
	public function add_watch_list()
    {
    	$user_id=$this->session->userdata(SESSION.'user_id');
    	if(!empty($user_id))
    	{
    		$status=$this->home_module->add_watch_user_list();
    
	    	if($status=='insert')
	    	{
	    		print_r(json_encode(array('status'=>'success','operation'=>'insert', 'message'=>lang('add_to_watchlist'))));
	    		exit;
	    	}
	    	if($status=='delete')
	    	{
	    		print_r(json_encode(array('status'=>'success','operation'=>'delete', 'message'=>lang('delete_from_watchlist'))));
	    		exit;
	    	}
    	
    		print_r(json_encode(array('status'=>'Error','operation'=>'fail', 'message'=>lang('fail_operation_to_watchlist'))));
    		exit;
    	}
    	else
    	{
    		print_r(json_encode(array('status'=>'Error','operation'=>'notlogin', 'message'=>lang('your_are_not_login'))));
    		exit;
    	}
    	 	
	}


	public function insert_vote_record()
	{
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			
		$user_id=$this->session->userdata(SESSION.'user_id');

		$same_vote = $this->home_module->check_same_vote($this->input->post('vote_type'));

		$vote_count=$this->home_module->check_pre_vote($this->input->post('vote_type'));

		$vote_countval=$this->home_module->auction_vote_count();
		$pos_vote=($vote_countval->positive_rating)?$vote_countval->positive_rating:0;
		$neg_vote=($vote_countval->negative_rating)?$vote_countval->negative_rating:0;
		$total_vote=$pos_vote+$neg_vote;

		$this->positive_rating=0;
		$this->negative_rating=0;

		$vote_type='';

		if($this->input->post('vote_type')=='positive')
		{
			$this->positive_rating=1;
			$this->negative_rating=0;
			$vote_type='positive';
		}
		if($this->input->post('vote_type')=='negative')
		{
			$this->positive_rating=0;
			$this->negative_rating=1;
			$vote_type='negative';
		}
		
		if(empty($same_vote))
	 	{
				$trans=$this->home_module->vote_record_insert($user_id,$vote_count);
				$vote_countval=$this->home_module->auction_vote_count();
				$pos_vote=($vote_countval->positive_rating)?$vote_countval->positive_rating:0;
				$neg_vote=($vote_countval->negative_rating)?$vote_countval->negative_rating:0;
				$total_vote=$pos_vote+$neg_vote;

				if($trans)
				{
				print_r(json_encode(array('status'=>'success','message'=>lang('vote_successfully'),'vote_positive'=>$pos_vote,'vote_negative'=>$neg_vote,'vote_type'=>$vote_type,'total_vote'=>$total_vote)));
				exit;
				}
				else
				{
				print_r(json_encode(array('status'=>'error','message'=>lang('vote_successfully'),'vote_positive'=>$pos_vote,'vote_negative'=>$neg_vote,'vote_type'=>$vote_type,'total_vote'=>$total_vote)));
				exit;
				}
			}else
				print_r(json_encode(array('status'=>'error','message'=>lang('you_have_already'),'vote_positive'=>$pos_vote,'vote_negative'=>$neg_vote,'vote_type'=>$vote_type,'total_vote'=>$total_vote)));
				exit;			{

			}
			
			
		}
		else
		{
			print_r(json_encode(array('status'=>'error','message'=>lang('couldnt_operation'))));
			exit;
		}

	}	

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */