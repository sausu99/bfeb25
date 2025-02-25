<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		// Check if User has logged in
		if (!$this->general->admin_logged_in())			
		{
			redirect(ADMIN_LOGIN_PATH, 'refresh');exit;
		}
			
		//load CI library
			$this->load->library('form_validation');
			$this->load->library('upload');
			$this->load->library('image_lib');
			$this->load->library('pagination');

		//load custom module
			$this->load->model('admin_auction');
			$this->load->model('language-settings/admin_language_settings');
			$this->load->model('product-categories/category_model');
		//load helper
		$this->load->helper('editor_helper');

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


     //push notification library
		$this->load->library('fcm');
                $this->load->library('Netcoreemail_class');
	}
	
	public function index()
	{		
		if($this->uri->segment(4)) $status = $this->uri->segment(4); else $status = 'Live';
		//set pagination configuration			
		$config['base_url'] = site_url(ADMIN_DASHBOARD_PATH).'/auction/index/'.$status;
		$config['total_rows'] = $this->admin_auction->get_toal_auctions($status,'lub');
		$config['num_links'] = 10;
		$config['prev_link'] = 'Prev';
		$config['next_link'] = 'Next';
		$config['per_page'] = '30'; 
		$config['next_tag_open'] = '<span>';
		$config['next_tag_close'] = '</span>';
		$config['cur_tag_open'] = '<span>';
		$config['cur_tag_close'] = '</span>';
		$config['num_tag_open'] = '<span>';
		$config['num_tag_close'] = '</span>';
		
		$config['uri_segment'] = '5';
		$offset=$this->uri->segment(5,0);	
		$this->pagination->initialize($config); 
		
		$this->data['result_data'] = $this->admin_auction->get_auction_details($this->uri->segment(4),$config['per_page'],$offset,'lub');
		
		//$this->data = '';
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Auctions View | '.SITE_NAME)
			->build('auction_view', $this->data);	
		
	}
	
	public function add_auction()
	{
		$this->data['jobs'] = 'Add';
		
		//$name = $this->input->post('name');
		//print_r($name[1]);//exit;
		$this->data['error'] = FALSE;
		
		//get all language
		$this->data['lang_details'] = $this->admin_language_settings->get_lang_details_for_others();

		// Set the validation rules
		$this->form_validation->set_rules($this->admin_auction->validate_settings);		
		//check and upload all image
		// $upload_result = $this->admin_auction->upload_auction_images($this->data['jobs']);
		
		//validate selected language fields
		//print_r($this->input->post('lang_id'));
		if($this->input->post('lang_id') != "")
		{
			$lang_id = '';
			
			for($i=0; $i<count($this->input->post('lang_id')); $i++)
			{
				$all_lang_id = $this->input->post('lang_id');
				$lang_id = $all_lang_id[$i];
				
				$name = $this->input->post('name');
				//print_r($name);
				$description = $this->input->post('description');
				//echo $name[$lang_id];exit;
				if($name[$lang_id] == "")
					{$this->data['error'] = TRUE; $this->session->set_userdata('name_'.$lang_id, 'The Auction Name field is required.');}
				else
					$this->session->unset_userdata('name_'.$lang_id);
					
				if($description[$lang_id] == "")
					{$this->data['error'] = TRUE;$this->session->set_userdata('description_'.$lang_id, 'The Acution Description field is required.');}
				else
					$this->session->unset_userdata('description_'.$lang_id);
			}
		}	
								
		if($this->form_validation->run()==TRUE )
		//if($this->form_validation->run()==TRUE && $this->data['error'] == FALSE)
		{			
			//Insert Lang Settings
			$this->admin_auction->insert_record();
			$this->session->set_flashdata('message','The New Auction records are insert successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/auction/index','refresh');
			exit;			
		}


		if($this->input->post()){
			$this->data['product_code'] = $this->input->post('pcodeimg', TRUE);
			$this->data['product_images'] = $this->admin_auction->get_producttemp_images($this->data['product_code']);
			// print_r($this->data['product_images']);

		} else {
			$this->data['product_code'] = strtotime('now').$this->session->userdata(SESSION.'user_id');
			$this->data['product_images'] = '';
		}	

		$this->data['random'] = $this->general->random_number(); 

		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Add New Auction | '.SITE_NAME)
			->build('auction_add', $this->data);	
	}
	
	
	public function edit_auction($id)
	{
		$this->auc_id=$id;
		$this->data['jobs'] = 'Edit';
		
		//check id, if it is not set then redirect to view page
		if(!isset($id))
		{	
			redirect(ADMIN_DASHBOARD_PATH.'/auction/index','refresh');
			exit;
		}
		
		$this->data['data_auction'] = $this->admin_auction->get_auction_byid($id);
		
		$this->data['data_auction_details'] = $this->admin_auction->get_auction_details_byid($id);
		
		//check data, if it is not set then redirect to view page
		if($this->data['data_auction'] ==false || $this->data['data_auction_details']==false)
		{
			redirect(ADMIN_DASHBOARD_PATH.'/auction/index','refresh');
			exit;
		}
		
		
		$this->data['error'] = FALSE;

		//Set the validation rules
		$this->form_validation->set_rules($this->admin_auction->validate_settings_edit);		
		//check and upload all image
		$upload_result = $this->admin_auction->upload_auction_images($this->data['jobs']);
		
		//validate selected language fields
		//print_r($this->input->post('lang_id'));
		
		if($this->input->post('lang_id') != "")
		{
			$lang_id = '';
			
			for($i=0; $i<count($this->input->post('lang_id')); $i++)
			{
				$all_lang_id = $this->input->post('lang_id');
				$lang_id = $all_lang_id[$i];
				
				$name = $this->input->post('name');
				
				$description = $this->input->post('description');
				//echo $name[$lang_id];exit;
				if($name[$lang_id] == "")
					{$this->data['error'] = TRUE; $this->session->set_userdata('name_'.$lang_id, 'The Auction Name field is required.');}
				else
					$this->session->unset_userdata('name_'.$lang_id);
					
				if($description[$lang_id] == "")
					{$this->data['error'] = TRUE;$this->session->set_userdata('description_'.$lang_id, 'The Acution Description field is required.');}
				else
					$this->session->unset_userdata('description_'.$lang_id);
			}
		}	
			
		if($this->form_validation->run()==TRUE && $upload_result == FALSE && $this->data['error'] == FALSE)
		{
			//Insert Lang Settings
			$this->admin_auction->update_record($id);
			$this->session->set_flashdata('message','The Auction records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/auction/index','refresh');			
			exit;
		}
			if($this->input->post()){
			$this->data['product_code'] = $this->input->post('pcodeimg', TRUE);
			$this->data['product_images'] = $this->admin_auction->get_product_images_by_product_id($id);
			$this->data['product_images_temp'] = $this->admin_auction->get_producttemp_images($this->data['product_code']);
			// $this->data['product_images'] = $this->admin_auction->get_producttemp_images($this->data['product_code']);

			// print_r($this->data['product_images']);

		} else {
			$this->data['product_code'] = strtotime('now').$this->session->userdata(SESSION.'user_id');
			$this->data['product_images'] = $this->admin_auction->get_product_images_by_product_id($id);
			$this->data['product_images_temp'] = array();

		}


		

		$this->data['images_quota'] = MAXIMUM_NUMBERS_OF_PRODUCT_IMAGES;

		$img_count = $this->admin_auction->count_valid_products($id);

		$post_image_count =  @sizeof($this->data['product_images_temp']);

		if($this->data['product_images']){

		$this->data['images_quota'] = (MAXIMUM_NUMBERS_OF_PRODUCT_IMAGES - $img_count);
	
		}


		if($this->data['product_images_temp']){

		$this->data['images_quota'] = (MAXIMUM_NUMBERS_OF_PRODUCT_IMAGES - ($post_image_count+$img_count));
	
		}

		$this->data['random'] = $this->general->random_number(); 
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Edit Auction | '.SITE_NAME)
			->build('auction_edit', $this->data);	
	}
	
	public function copy_auction($id)
	{
		// $this->auc_id=$id;
		$this->data['jobs'] = 'Copy';
		$this->product_original = $id;
		//check id, if it is not set then redirect to view page
		if(!isset($id))
		{	
			redirect(ADMIN_DASHBOARD_PATH.'/auction/index','refresh');
			exit;
		}
		
		$this->data['data_auction'] = $this->admin_auction->get_auction_byid($id);
		
		$this->data['data_auction_details'] = $this->admin_auction->get_auction_details_byid($id);
		
		//check data, if it is not set then redirect to view page
		if($this->data['data_auction'] ==false || $this->data['data_auction_details']==false)
		{
			redirect(ADMIN_DASHBOARD_PATH.'/auction/index','refresh');
			exit;
		}
		$this->data['error'] = FALSE;
		//Set the validation rules
		$this->form_validation->set_rules($this->admin_auction->validate_settings);		
		
		//check and upload all image
		$upload_result = $this->admin_auction->upload_auction_images($this->data['jobs']);
		
		//validate selected language fields
		//print_r($this->input->post('lang_id'));
		
		if($this->input->post('lang_id') != "")
		{
			$lang_id = '';
			
			for($i=0; $i<count($this->input->post('lang_id')); $i++)
			{
				$all_lang_id = $this->input->post('lang_id');
				$lang_id = $all_lang_id[$i];
				
				$name = $this->input->post('name');
				
				$description = $this->input->post('description');
				//echo $name[$lang_id];exit;
				if($name[$lang_id] == "")
					{$this->data['error'] = TRUE; $this->session->set_userdata('name_'.$lang_id, 'The Auction Name field is required.');}
				else
					$this->session->unset_userdata('name_'.$lang_id);
					
				if($description[$lang_id] == "")
					{$this->data['error'] = TRUE;$this->session->set_userdata('description_'.$lang_id, 'The Acution Description field is required.');}
				else
					$this->session->unset_userdata('description_'.$lang_id);
			}
		}	
			
		if($this->form_validation->run()==TRUE && $upload_result == FALSE && $this->data['error'] == FALSE)
		{
			//Insert Lang Settings
			$this->admin_auction->copy_record();
			$this->session->set_flashdata('message','The Auction records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/auction/index','refresh');			
			exit;
		}

			if($this->input->post()){
			$this->data['product_code'] = $this->input->post('pcodeimg', TRUE);
			$this->data['product_images'] = $this->admin_auction->get_product_images_by_product_id($id);
			$this->data['product_images_temp'] = $this->admin_auction->get_producttemp_images($this->data['product_code']);

			// print_r($this->data['product_images']);

		} else {
			$this->data['product_code'] = strtotime('now').$this->session->userdata(SESSION.'user_id');
			$this->data['product_images'] = $this->admin_auction->get_product_images_by_product_id($id);
			$this->data['product_images_temp'] = array();
		}

		$this->data['images_quota'] = MAXIMUM_NUMBERS_OF_PRODUCT_IMAGES;

		$img_count = $this->admin_auction->count_valid_products($id);
		$post_image_count =  sizeof($this->data['product_images_temp']);

		if($this->data['product_images']){

		$this->data['images_quota'] = (MAXIMUM_NUMBERS_OF_PRODUCT_IMAGES - $img_count);
	
		}

		if($this->data['product_images_temp']){

		$this->data['images_quota'] = (MAXIMUM_NUMBERS_OF_PRODUCT_IMAGES - ($post_image_count+$img_count));
	
		}

		

		$this->data['random'] = $this->general->random_number(); 
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Edit Auction | '.SITE_NAME)
			->build('auction_edit', $this->data);	
	}
	
	public function delete_auction($id)
	{
			$query = $this->db->get_where('auction', array('id' => $id));
                        

			if($query->num_rows() > 0) 
			{
                            $product_id=$query->row()->product_id;
				$this->db->delete('auction', array('id' => $id));
				$this->db->delete('auction_details', array('auc_id' => $id));
                                $this->db->delete('auction_winner', array('auc_id' => $product_id));
                                $this->db->delete('member_watch_lists', array('auction_id' => $product_id));
                                $this->db->delete('transaction', array('auc_id' => $product_id));
                                $this->db->delete('user_bids', array('auc_id' => $product_id));
                               
				//delte record from XML file
				$auction = $query->row();
				// $this->admin_auction->delete_auction_xml($auction->product_id);
				
				$this->session->set_flashdata('message','The auction record delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/auction/index','refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','Sorry no record found.');
					redirect(ADMIN_DASHBOARD_PATH.'/auction/index','refresh');
					exit;
				}
		
	}
	function delete_image($img,$id)
	{
		if($img == "img2")
			$this->db->update('auction', array("image2"=>""), "id = ".$id);
		else if($img == "img3")
			$this->db->update('auction', array("image3"=>""), "id = ".$id);
		else if($img == "img4")
			$this->db->update('auction', array("image4"=>""), "id = ".$id);
		
		redirect(ADMIN_DASHBOARD_PATH.'/auction/edit_auction/'.$id,'refresh');
		exit;
		
	}
	
	function check_start_date()
	{
		
		if(strtotime($this->input->post('start_date')) < strtotime($this->general->get_local_time('time')))
		{
			$this->form_validation->set_message('check_start_date',"This Start Date must be greater than or equal to current date and time.");
			return false;
		}
	}
	
	function check_end_date()
	{
		if(strtotime($this->input->post('end_date')) <= strtotime($this->general->get_local_time('time')))
		{
			$this->form_validation->set_message('check_end_date',"The %s must be greater than current date and time.");
			return false;
			
		}
		else if(strtotime($this->input->post('end_date')) <= strtotime($this->input->post('start_date')))
		{
			$this->form_validation->set_message('check_end_date',"The %s must be greater than start date.");
			return false;
		}
		
		return true;
	}
	
	public function bids($id=false)
	{
            
		$this->data['auc_data'] = $this->data['data_auction'] = $this->admin_auction->get_auction_byid($id);
        
		if($this->input->get('status')=="" && $this->session->userdata(SESSION . 'bid_hist_status')==""){    
            if($id==false || $this->data['auc_data']->status=='Live'){
                $this->session->set_flashdata('message','The auction has not been closed.');
            	redirect(ADMIN_DASHBOARD_PATH.'/auction/index','refresh');
				exit;                
            }
		}
		else{
			$this->session->set_userdata(SESSION . 'bid_hist_status','1');
		}
            
		$this->data['total_bids'] = $this->admin_auction->get_bid_palce_byid($this->data['auc_data']->product_id);		
		$this->data['auc_name'] = $this->admin_auction->get_auction_name($id);
		
		//set pagination configuration			
		$config['base_url'] = site_url(ADMIN_DASHBOARD_PATH).'/auction/bids/'.$id;
		$config['total_rows'] = $this->data['total_bids'];
		$config['num_links'] = 10;
		$config['prev_link'] = 'Prev';
		$config['next_link'] = 'Next';
		$config['per_page'] = '30'; 
		$config['next_tag_open'] = '<span>';
		$config['next_tag_close'] = '</span>';
		$config['cur_tag_open'] = '<span>';
		$config['cur_tag_close'] = '</span>';
		$config['num_tag_open'] = '<span>';
		$config['num_tag_close'] = '</span>';
		
		$config['uri_segment'] = '5';
		$offset = $this->uri->segment(5,0);	
		$this->pagination->initialize($config); 

		
		$this->data['bids'] = $this->admin_auction->get_all_bid_palce_byid($this->data['auc_data']->product_id,$config['per_page'],$offset);
		
		//$this->data = '';
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Auctions View | '.SITE_NAME)
			->build('bids_view', $this->data);	
	}
	
	public function cancel_auction($auc_id,$product_id)
	{
		if($this->admin_auction->check_live_auction($auc_id,$product_id) != 1)
		{
			redirect(ADMIN_DASHBOARD_PATH.'/auction/index','refresh');
			exit;
		}
		
		//update auction status as CANCEL
		$this->admin_auction->update_auction_status($auc_id,'Cancel');
		//update XML records
		// $this->admin_auction->update_XML_status($product_id);
				
		//Find all user who place bid in this auction to return bid fee
		$all_bid_placed_users = $this->admin_auction->get_users_used_fee($product_id);
		
		if($all_bid_placed_users != false)
		{
			foreach($all_bid_placed_users as $users)
			{
				$auc_id = $auc_id;
				$product_id = $product_id;
				$user_id = $users->user_id;
				$user_return_bid_fee = $users->balance;
				
				//$total_amount+=$user_return_bid_fee;				
				$item_name="Refunded  ".$user_return_bid_fee." on Auction ID:".$product_id;
				
				
				//refund user bid fee
				$this->admin_auction->update_user_balance($user_return_bid_fee,$user_id);
				//make refund transaction
				$this->admin_auction->update_refund_transaction($user_id,$product_id,$user_return_bid_fee,$item_name);
				//send auction cancel notification to user

				$enable_check = $this->general->check_notification_enable('auction_cancel_notification_user');

				if($enable_check->is_email_notification_send == '1')
				{						
				//Now Send Email to winner & Admin
				$this->admin_auction->send_auction_cancel_notification_user($product_id,$user_id,$user_return_bid_fee);
				}

				if($enable_check->is_sms_notification_send == '1')
				{
					$this->admin_auction->send_sms_notification($product_id,$user_id,$user_return_bid_fee);
				}

				if($enable_check->is_push_notification_send == '1')
				{
					$this->admin_auction->send_push_notification($product_id,$user_id,$user_return_bid_fee);
				}	

				
			}
		}
		
		$this->session->set_flashdata('message','Auction has been cancel.');
		redirect(ADMIN_DASHBOARD_PATH.'/auction/index/Cancel','refresh');
		exit;
	}

public function check_sms_code()
{
	 	$auc_id=$this->auc_id;
		$input_sms_code=$this->input->post('sms_code');
		$sms_code=$this->admin_auction->check_exit_sms_code($input_sms_code,$auc_id);
		if($sms_code)
		{
			$this->form_validation->set_message('check_sms_code', 'Alreay Exit SMS Code');
			return false;
		}
		else
		{
			return true;
		}

}

	//multiple image uploader
	function multiple_image_ajax_uploader()
	{
		if(!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
        }
		
		//add images 	
		if($_FILES)
		{
			$image_count=0;
			$image_data = array();
			$response_array = '';
			
			foreach($_FILES as $key=>$value){
				$image_name = $this->admin_auction->file_settings_do_upload_ajax($key, AUCTION_TEMP_PATH, 'encrypt');
				if ($image_name['file_name'])
				{
					$this->image_name_path = $image_name['file_name'];
					$image_count++;
					
				   //push image data into array
				   	array_push($image_data, array('product_code' => $this->input->post('pcodeimg', TRUE), 'image' => $this->image_name_path));
					//now store response in an array.
					$response_array = array('status'=>'success', 'name'=>$this->image_name_path);
					
					//stop uploading images if numbers of images exceeds the allowed images
					if($image_count >= MAXIMUM_NUMBERS_OF_PRODUCT_IMAGES){
						break; //will break if condition and foreach loop
					}
				}
				else
				{
					$response_array = array('status'=>'error','message'=>'invalid file');
				}   
			}

			//insert into database if response is success
			if($response_array['status']=='success'){

				//insert image into database in a batch
				$this->db->insert_batch('product_images_temp', $image_data);
			}
		}
		print_r(json_encode($response_array)); exit;
	}
	public function ajax_delete_product_temp_images()
	{
		if(!$this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
        }
		
		if($this->input->server('REQUEST_METHOD')=='POST'){
			//print_r(json_encode($_POST)); exit;
			
			$image_name = $this->input->post('name',TRUE);
			$product_code = $this->input->post('pcode',TRUE);
			// $product_id = $this->input->post('pid',TRUE);
			if($image_name && $image_name!='')
			{
				$query = $this->db->get_where('product_images_temp',array('image'=>$image_name));
				if ($query->num_rows() > 0)
				{
					$temp_image =  $query->result();
					@unlink(AUCTION_TEMP_PATH.''.$image_name);
					$query = $this->db->delete('product_images_temp',array('image'=>$image_name));
					if($query){
						$response['result'] = 'success';
						$response['image_quota'] = (MAXIMUM_NUMBERS_OF_PRODUCT_IMAGES - $this->admin_auction->count_total_temp_images_by_product_code($product_code));
						print_r(json_encode($response)); exit;
					}
				}
			}
		}
		print_r(json_encode(array('result'=>'error'))); exit;
	}

	public function ajax_delete_product_image()
	{
		if(!$this->input->is_ajax_request()){
			exit('No direct script access allowed');
        }

        $column = $this->input->post('column');
		//print_r($_POST);
		$response = array(); 
		if($this->input->server('REQUEST_METHOD')=='POST'){

		$product_id = intval($this->input->post('pid',TRUE));

		// echo $product_id;exit;
			$image = $this->input->post('name',TRUE);
			//print_r($_POST);
			if($product_id && $image){
				// $delete = $this->admin_auction->delete_product_image($product_id, $image);
				$update = $this->db->update('auction',array($column=>''),array('id'=>$product_id));

				@unlink(AUCTION_IMG_PATH.''.$image);
				@unlink(AUCTION_IMG_PATH.''.'thumb_'.$image);

				if($update){

					$response['image_quota'] = (MAXIMUM_NUMBERS_OF_PRODUCT_IMAGES - $this->admin_auction->count_valid_products($product_id));

					$response['result'] = 'success';

					print_r(json_encode($response));exit;
				}
			}
		}

		$response['result'] = 'Error';
		$response['image_quota'] = '';
		print_r(json_encode($response));exit;
	}





}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */