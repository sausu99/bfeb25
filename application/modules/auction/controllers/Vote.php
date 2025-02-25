<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vote extends CI_Controller {

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
                        $this->load->library('Netcoreemail_class');

		//load custom module
			$this->load->model('admin_auction');
			$this->load->model('language-settings/admin_language_settings');
			$this->load->model('product-categories/category_model');
		//load helper
		$this->load->helper('editor_helper');

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	public function index()
	{		
		// echo "ts";
		// exit;
		$this->data['catgory_data']=$this->general->get_all_categories_display(DEFAULT_LANG_ID);
		// print_r($this->data['catgory_data']);
		// exit;

		$status = 'Pending';
		//set pagination configuration			
		$config['base_url'] = site_url(ADMIN_DASHBOARD_PATH).'/auction/vote/index/'.$status;
		$config['total_rows'] = $this->admin_auction->get_toal_auctions($status,'vote');
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
		


		$this->data['result_data'] = $this->admin_auction->get_auction_details($status,$config['per_page'],$offset,'vote');
		
		//$this->data = '';
		// print_r($this->data['result_data']);
		// exit;
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Auctions View | '.SITE_NAME)
			->build('auction_view_vote', $this->data);	
		
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
		if($this->input->post('end_day')=='' && $this->input->post('end_hour')=='' && $this->input->post('end_minute') =='')
		{
			$this->form_validation->set_rules('end_duration', 'At least one Field', 'required|integer');
		}

		$this->form_validation->set_rules($this->admin_auction->validate_settings_vote);		
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
								
		if($this->form_validation->run()==TRUE && $upload_result == FALSE && $this->data['error'] == FALSE)
		//if($this->form_validation->run()==TRUE && $this->data['error'] == FALSE)
		{			
			//Insert Lang Settings
			$this->admin_auction->insert_record_vote();
			$this->session->set_flashdata('message','The New Vote Auction records are insert successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/auction/vote','refresh');
			exit;			
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Add New Vote Auction | '.SITE_NAME)
			->build('auction_add_vote', $this->data);	
	}
	
	
	public function edit_auction($id)
	{
		$this->auc_id=$id;
		$this->data['jobs'] = 'Edit';
		
		//check id, if it is not set then redirect to view page
		if(!isset($id))
		{	
			redirect(ADMIN_DASHBOARD_PATH.'/auction/vote','refresh');
			exit;
		}
		
		$this->data['data_auction'] = $this->admin_auction->get_auction_byid($id);
		
		$this->data['data_auction_details'] = $this->admin_auction->get_auction_details_byid($id);
		
		//check data, if it is not set then redirect to view page
		if($this->data['data_auction'] ==false || $this->data['data_auction_details']==false)
		{
			redirect(ADMIN_DASHBOARD_PATH.'auction/vote','refresh');
			exit;
		}
		
		
		$this->data['error'] = FALSE;

		//Set the validation rules
		$this->form_validation->set_rules($this->admin_auction->validate_settings_vote_edit);		
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
			$this->admin_auction->update_record_vote($id);
			$this->session->set_flashdata('message','The Vote Auction records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/auction/vote','refresh');			
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Edit Auction | '.SITE_NAME)
			->build('auction_edit_vote', $this->data);	
	}
	
	public function copy_auction($id)
	{
		// $this->auc_id=$id;
		$this->data['jobs'] = 'Copy';
		
		//check id, if it is not set then redirect to view page
		if(!isset($id))
		{	
			redirect(ADMIN_DASHBOARD_PATH.'auction/vote/','refresh');
			exit;
		}
		
		$this->data['data_auction'] = $this->admin_auction->get_auction_byid($id);
		
		$this->data['data_auction_details'] = $this->admin_auction->get_auction_details_byid($id);
		
		//check data, if it is not set then redirect to view page
		if($this->data['data_auction'] ==false || $this->data['data_auction_details']==false)
		{
			redirect(ADMIN_DASHBOARD_PATH.'/auction/vote','refresh');
			exit;
		}
		$this->data['error'] = FALSE;
		//Set the validation rules
		$this->form_validation->set_rules($this->admin_auction->validate_settings_vote);		
		
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
			$this->admin_auction->copy_record_vote();
			$this->session->set_flashdata('message','The Auction records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/auction/vote','refresh');			
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Edit Auction | '.SITE_NAME)
			->build('auction_edit_vote', $this->data);	
	}
	
	public function delete_auction($id)
	{
			$query = $this->db->get_where('auction', array('id' => $id));

			if($query->num_rows() > 0) 
			{
				$this->db->delete('auction', array('id' => $id));
				$this->db->delete('auction_details', array('auc_id' => $id));
				//delte record from XML file
				$auction = $query->row();
				$this->admin_auction->delete_auction_xml($auction->product_id);
				
				$this->session->set_flashdata('message','The auction record delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/auction/vote','refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','Sorry no record found.');
					redirect(ADMIN_DASHBOARD_PATH.'auction/vote','refresh');
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
	
	public function bids($id)
	{
		$this->data['auc_data'] = $this->data['data_auction'] = $this->admin_auction->get_auction_byid($id);
		$this->data['total_bids'] = $this->admin_auction->get_bid_palce_byid($this->data['auc_data']->product_id);
		
		$this->data['auc_name'] = $this->admin_auction->get_auction_name($id);
		
		//set pagination configuration			
		$config['base_url'] = site_url(ADMIN_DASHBOARD_PATH).'/auction/bids/'.$id;
		$config['total_rows'] = $this->data['total_bids'];
		$config['num_links'] = 10;
		$config['prev_link'] = 'Prev';
		$config['next_link'] = 'Next';
		$config['per_page'] = '50'; 
		$config['next_tag_open'] = '<span>';
		$config['next_tag_close'] = '</span>';
		$config['cur_tag_open'] = '<span>';
		$config['cur_tag_close'] = '</span>';
		$config['num_tag_open'] = '<span>';
		$config['num_tag_close'] = '</span>';
		
		$config['uri_segment'] = '5';
		$offset=$this->uri->segment(5,0);	
		$this->pagination->initialize($config); 

		
		$this->data['bids'] = $this->admin_auction->get_all_bid_palce_byid($this->data['auc_data']->product_id,$config['per_page'],$offset);
		
		//$this->data = '';
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Auctions View | '.SITE_NAME)
			->build('bids_view', $this->data);	
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


public function move_auction($id)
	{
		$data_auction = $this->admin_auction->get_auction_byid($id);
		
		$end_day=$data_auction->end_day;
		$end_hour=$data_auction->end_hour;
		$end_minute=$data_auction->end_minute;
		$end_duration=($end_day*86400)+($end_hour*3600)+($end_minute*60);
		$start_date=$this->general->get_gmt_time('time');
		$end_date= date('Y-m-d H:i:s', strtotime($start_date) + $end_duration);	
	

		// echo "<pre>";
		// print_r($data_auction);
		// exit;
		$query = $this->db->get_where('auction', array('id' => $id));

			if($query->num_rows() > 0) 
			{

				$this->db->update('auction', array('auc_type'=>'lub','status'=>'Live','start_date'=>$start_date,'end_date'=>$end_date),array('id' => $id));
				$this->session->set_flashdata('message','The auction record move to live successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/auction/index/','refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','Sorry no record found.');
					redirect(ADMIN_DASHBOARD_PATH.'/auction/index/','refresh');
					exit;
				}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */