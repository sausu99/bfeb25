<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_site_settings extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
		
	}
	
	public $validate_site_settings =  array(
		array('field' => 'site_name', 'label' => 'Website Name', 'rules' => 'required'),
	    array('field' => 'contact_email', 'label' => 'Contact Email', 'rules' => 'required|valid_email'),
		array('field' => 'contact_phone', 'label' => 'Contact Phone', 'rules' => 'trim'),
		array('field' => 'contact_address', 'label' => 'Contact Address', 'rules' => 'trim'),
	    array('field' => 'system_email', 'label' => 'System Email', 'rules' => 'required|valid_email'),
		array('field' => 'subscription_email', 'label' => 'Subscription Email', 'rules' => 'required|valid_email'),
		array('field' => 'default_timezone', 'label' => 'Default TimeZone', 'rules' => 'required'),
		array('field' => 'signup_bonus', 'label' => 'Signup Bonus', 'rules' => 'required|numeric'),
		array('field' => 'refer_bonus', 'label' => 'Refer Bonus', 'rules' => 'required|numeric'),
		array('field' => 'min_bid_4buy_now', 'label' => 'Buy Now Discount', 'rules' => 'required|numeric'),
		array('field' => 'buy_now_bid_reward_times', 'label' => 'Buy Now Bid Reward Times', 'rules' => 'required|numeric'),
		array('field' => 'buy_now_product', 'label' => 'Buy Now Discount Item', 'rules' => 'required|numeric'),
		//array('field' => 'mailchimp_api_key', 'label' => 'Mailchimp API Key', 'rules' => 'required'),
		//array('field' => 'mailchimp_list_id', 'label' => 'Mailchimp List ID', 'rules' => 'required'),
		//array('field' => 'checkmobi_sms_api_key', 'label' => 'Secret API Key', 'rules' => 'required'),
		array('field' => 'site_status', 'label' => 'Site Status', 'rules' => 'required|trim'),
		);
		
		
	public function get_site_setting()
	{		
		$query = $this->db->get('site_settings');

		if ($query->num_rows() > 0)
		{
		   return $query->row_array();
		} 

		return false;
	}
	
	public function update_site_settings()
	{
		if(!empty($this->image_name_path1))
		{
			@unlink(SITE_LOGO_PATH.$this->input->post('logo_old'));
		}

		$data = array(
               'site_name' => $this->input->post('site_name'),
               'site_logo' =>!empty($this->image_name_path1)?$this->image_name_path1:$this->input->post('logo_old'),
               'system_email' => $this->input->post('system_email'),
			   'subscription_email' => $this->input->post('subscription_email'),
			   'default_timezone' => $this->input->post('default_timezone'),
               'contact_email' => $this->input->post('contact_email'),
			   'contact_phone' => $this->input->post('contact_phone'),
			   'contact_address' => $this->input->post('contact_address'),
               'signup_bonus' => $this->input->post('signup_bonus'),
               'refer_bonus' => $this->input->post('refer_bonus'),
			   'min_bid_4buy_now' => $this->input->post('min_bid_4buy_now'),
			   'buy_now_bid_reward_times' => $this->input->post('buy_now_bid_reward_times'),
			   'buy_now_product' => $this->input->post('buy_now_product'),
               //'mailchimp_api_key' => $this->input->post('mailchimp_api_key'),
               //'mailchimp_list_id' => $this->input->post('mailchimp_list_id'),
			   //'checkmobi_sms_api_key' => $this->input->post('checkmobi_sms_api_key'),

			  // 'android_app' => $this->input->post('android_app'),
			  // 'ios_app' => $this->input->post('ios_app'),

			   'facebook_url' => $this->input->post('facebook_url'),
			   'facebook_app_id' => $this->input->post('facebook_app_id'),
			   'twitter_url' => $this->input->post('twitter_url'),
			   'linkedin_url' => $this->input->post('linkedin_url'),
			   'google_url' => $this->input->post('google_url'),
			   'maintainance_key'=>$this->input->post('maintainance_key',TRUE),
			   'instagram_url' => $this->input->post('instagram_url'),
			    'google_url' => $this->input->post('google_url'),
			   'site_status' => $this->input->post('site_status'),
			   'html_tracking_code' => $this->input->post('html_tracking_code'),
			   'google_analytical_code' => $this->input->post('google_analytical_code'),
			   'google_client_id'=>$this->input->post('google_client_id'),
			   'google_api_key'=>$this->input->post('google_api_key'),
			    //'twitter_app_key'=>$this->input->post('twitter_app_key'),
			   //'twitter_app_secret'=>$this->input->post('twitter_app_secret'),
			    //'node_server'=>$this->input->post('node_server'),
			     //'node_port'=>$this->input->post('node_port'),
			     'signup_credit'=>$this->input->post('signup_credit')
            );

		$this->db->update('site_settings', $data); 

	}


public function upload_auction_images($job)
	{
		$image_error = FALSE;
		$this->session->unset_userdata('error_img1');	
		
					
		// Upload image 1
		if( (!empty($_FILES['site_logo']['name']) && $job =='Edit'))
		{
			//make file settins and do upload it
			$image1_name = $this->file_settings_do_upload('site_logo');
			
            if (isset($image1_name['file_name']))
            {
				$this->image_name_path1 = $image1_name['file_name'];

				//resize image
				// $this->resize_image($this->image_name_path1,$image1_name['raw_name'].$image1_name['file_ext'],400,400);
				// $this->resize_image($this->image_name_path1,'thumb_'.$image1_name['raw_name'].$image1_name['file_ext'],240,240);
            }
            else
            {
			   $image_error = TRUE;
               $this->session->set_userdata('error_img1',$this->error_img);
            }
		}

		
		return $image_error;
		
	}

	public function file_settings_do_upload($file)
	{
				$config['upload_path'] = './'.SITE_LOGO_PATH;//define in constants
				$config['allowed_types'] = 'gif|jpg|png';
				$config['remove_spaces'] = TRUE;		
				$config['max_size'] = '5000';
				$config['max_width'] = '250';
				$config['max_height'] = '100';
				$this->upload->initialize($config);
				//print_r($_FILES);
			$this->upload->do_upload($file);
			if($this->upload->display_errors())
				{
					$this->error_img=$this->upload->display_errors();
					return false;
				}
			else
				{
				$data = $this->upload->data();
				return $data;
				}
	}


	public function resize_image($file_name,$thumb_name,$width,$height)
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = './'.SITE_LOGO_PATH.$file_name;
		//$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;
		$config['master_dim'] = 'width';
		$config['new_image'] = './'.SITE_LOGO_PATH.$thumb_name;
		
		$this->image_lib->initialize($config);
		
		$this->image_lib->resize();
		// $this->image_lib->clear(); 
		
	}

	
	

}
