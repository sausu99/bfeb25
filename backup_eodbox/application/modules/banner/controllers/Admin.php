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
			
		//load custom module
			$this->load->model('admin_banner');
			$this->load->model('language-settings/admin_language_settings');
		
		//load helper
		$this->load->helper('file');

		$this->load->library('image_lib');

		//Changing the Error Delimiters
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		//get all language
		$this->data['lang_details'] = $this->admin_language_settings->get_lang_details_for_others();
	}
	
	public function index()
	{
				
		if($this->uri->segment(4))
		{$this->data['lang_id'] = $this->uri->segment(4);}
		else
			{$this->data['lang_id'] = $this->data['lang_details'][0]->id;}
			
		$this->data['result_data'] = $this->admin_banner->get_banner_lists($this->data['lang_id']);
		//$this->data = '';
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Banner View | '.SITE_NAME)
			->build('banner_view', $this->data);	
		
	}
	
	public function add_banner()
	{
		$this->data['jobs'] = 'Add';
		
		// Set the validation rules
		$this->form_validation->set_rules($this->admin_banner->validate_settings);		
		
		//check and upload all image
		$upload_result = $this->admin_banner->upload_auction_images($this->data['jobs']);
		
		//make file settins and do upload it					
		if($this->form_validation->run()==TRUE && $upload_result == FALSE)
		{			
			//Insert Lang Settings
			$this->admin_banner->insert_record();
			$this->session->set_flashdata('message','The Banner records are insert successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/banner/index/'.$this->input->post('lang', TRUE),'refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Add Banner | '.SITE_NAME)
			->build('banner_add', $this->data);	
	}
	
	
	public function edit_banner($id='')
	{
		$this->data['jobs'] = 'Edit';
		
		//check lang id, if it is not set then redirect to view page
		if(!isset($id)){redirect(ADMIN_DASHBOARD_PATH.'/banner/index','refresh');exit;}
		
		$this->data['result_data'] = $this->admin_banner->get_banner_details_by_id($id);
		
		//check lang data, if it is not set then redirect to view page
		if($this->data['result_data'] == false){redirect(ADMIN_DASHBOARD_PATH.'/banner/index','refresh');exit;}
		
			//print_r($_FILES);

		// Set the validation rules
		$this->form_validation->set_rules($this->admin_banner->validate_settings);		
		
		//make file settins and do upload it
		if(isset($_FILES['banner']['tmp_name']) && $_FILES['banner']['tmp_name']!='')
		{
			$this->admin_banner->file_settings_do_upload();
			$image_data = $this->upload->data();			
			$flag_full_path = $image_data['file_name'];
			$this->admin_banner->resize_image('./'.BANNER_PATH,$image_data['file_name'],'thumb_'.$image_data['file_name'],960,420);
			$file_error = $this->upload->display_errors();
		}
		else
			{
				$file_error = '';
				$flag_full_path = '';
			}
			
		
		if($this->form_validation->run()==TRUE && !$file_error)
		{	
			//Insert Lang Settings
			$this->admin_banner->update_record($id,$flag_full_path);
			$this->session->set_flashdata('message','The Banner records are update successful.');
			redirect(ADMIN_DASHBOARD_PATH.'/banner/index/'.$this->input->post('lang', TRUE),'refresh');
			exit;
		}
		
		$this->template
			->set_layout('dashboard')
			->enable_parser(FALSE)
			->title('Edit Banner | '.SITE_NAME)
			->build('banner_edit', $this->data);	
	}
	
	public function delete_banner($id)
	{
		
			$query = $this->db->get_where('banner', array('id' => $id));

			if($query->num_rows() > 0) 
			{
							
				$this->db->delete('banner', array('id' => $id));
				$row_data = $query->row();	
				@unlink('./'.BANNER_PATH.$row_data->banner);
				$this->session->set_flashdata('message','The banner record delete successful.');
				redirect(ADMIN_DASHBOARD_PATH.'/banner/index','refresh');
				exit;
			}
			else
				{
					$this->session->set_flashdata('message','The banner id='.$id.' is not available in our database.');
					redirect(ADMIN_DASHBOARD_PATH.'/banner/index','refresh');
					exit;
				}
	}
		
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */